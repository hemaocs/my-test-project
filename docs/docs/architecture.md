# Overview
This framework use a separation of concern pattern (it's somewhat the same as MVC). Each part of your process to go through a request is divided into different folder. All these directories are located in the api folder. 

# External Libraries
Most of the dependences should be integrated to composer with the [require attribute](https://getcomposer.org/doc/04-schema.md#require). It will handle everything for you and you won't have to think about requiring files. The libraries will be installed in the vendor directory.

If you ever need to install external library then you will have to put it in the **/libs** folder. And if you wish to use it then you have to require the library in composer.json and then run a ```composer dump-autoload```
If it's a single file, you can put it in the [files attributes](https://getcomposer.org/doc/04-schema.md#files) as I did in the sample for RedbeanPHP. Otherwise, you can use the [classmap attributes](https://getcomposer.org/doc/04-schema.md#classmap).

# Logs
If your application ever run into exceptions, the framwork will handle it for you and write it in a log file inside the **logs directory**. If you ever encounter an issue, you should first check this folder.

# Your classes
During the developpement, you will probably to have to make some utility classes. They should be placed in the **Classes** folder. There is already the logging classes inside it and you will have to use the same structure for your classes. 
You have to divide all your different concerns into folders and keep things organized.

# Controllers
The controllers classes contains all the logic of your application. It will be the link between **the routes**, **the models** and **your classes**.
You have to use **ONE** public method by route. So for example, if you want to get the user with the id 1 from the application. In your route you only have to call the getSingleUser method. 

You will never have other methods in the controller except the one used in the routes, anything else are simple classes.

# Returning data from the controller
The controller classes have to extend the base controller. And for sending your data you just have to use the data property.
```PHP
$this->data['users'] = $users->getAll();
```

A meta property and a message property also exist. You can also specify the responde code to use with
```PHP 
$this->meta['next_page'] = 2;
$this->message = "Successfully retrieved the users list.";
$this->statusCode = 200;
```

# Accessing arguments from routes
You can pass arguments to the function in your routes. And for getting them back in your controller you can use the args array.
```PHP
$this->args['page']
```

# Slim

### Slim app
You can access the slim application variable from your controller with 
```PHP
$this->app
```
For example to get the body of the request you will use 
```PHP
$this->app->request->getBody()
```

### Slim configuration
If you ever need to add some script at the start of your application, you can put it in the constructor of the **Api.php** file.

### Middlewares
Middlewares are implemented the same way as with slim and are located in the **Middlewares** folder, so I redirect you to the Slim documentation to know how to use this. 
[http://docs.slimframework.com/middleware/overview/](http://docs.slimframework.com/middleware/overview/)

You can declare them in Api.php in the loadMiddleware method.

# Models
Models contain all the classes for accessing the DB. In the sample example, I used RedbeanPHP as ORM. 
[http://www.redbeanphp.com/](http://www.redbeanphp.com/)

You can simply create a file at the root of the folder and create a new class containing your method for each request. This class must extends the Database class located at the same place.

```PHP
namespace Appsolute\Api\Models;

use R;
use Appsolute\Api\Models\Resource\ResourceArray;
use Appsolute\Api\Models\Validation\ValidationException;

Class Users extends Database {

	public function findOneByEmail( $email ) {
		$user = R::findOne( 'users', 'email = ?', [ $email ] );
		if(!empty($user)){
			return $user->toArray();
		} else {
			return FALSE;
		}
    }
}
```

## Models Validation
You can use the [Redbean FUSE](http://www.redbeanphp.com/models) system to validate your data before sending it to the database. For example, you can check in the validation class if a user has provided all the field required.
If you wish to return an error you can use the ValidationException. By providing a message, an error code and a status code and it will automatically return a JSON error to the client.
```PHP
namespace Appsolute\Api\Models\Validation;

use R;
use Appsolute\Api\Models\Resource\Resource;

class Users extends Resource {

	public function update() {
		$email = trim($this->bean->email);

		if(isset($this->bean->email)){
			$email = trim($this->bean->email);
			if(empty($email)) {
				throw new ValidationException( 'You have to provide an email.', 10, 400 );
			}
		}
	}
}
```
will return
```JSON
{
	error_code: 10
	message: "You have to provide an email"
}
```

## Models Transformer
In order to properly send the data and to keep your resource consistent between all the request, you can use the transformers.
It use the League\Fractal library [http://fractal.thephpleague.com](http://fractal.thephpleague.com/).

You just have to pass your bean object or array to the transform method and return an array of your data with the good type.
```PHP
namespace Appsolute\Api\Models\Transformer;

use RedbeanPHP\OODBBean;
use League\Fractal;

class UsersTransformer extends Fractal\TransformerAbstract {

	public function transform(OODBBean $user) {
	    return [
	        'id'      	=> (int) $user->id,
	        'firstname' => $user->firstname,
	        'lastname'  => $user->lastname,
	        'email'		=> $user->email,
	        'created_at' => $user->created_at,
	        'updated_at' => $user->updated_at
	    ];
	}

}
```

If your object is a bean then when calling the toArray function, it will directly return the correct data.
If you pass an array, you will have to call manually the resource class with 
```PHP
new Resource($data,'Users');
```
The first argument is your actual array and the second is the name of your model class. Then you can use the toArray method with the true argument(it means it's an array collection).
```PHP
public function getAll() {
	$data = R::findAll( 'users' ); //returns an array so we need to call the Resource Array
	$users = new ResourcE($data,'Users');
	return $users->toArray(1);
}

public function findOneById( $id ) {
	$user = R::load( 'users', $id ); //return a bean
	if(!$user->isEmpty()){
		return $user->toArray(); //automatically call the transformer on the method
	} else {
		throw new ValidationException("This user doesn't exist", 10, 400);
	}
}
```

# Routes
All your routes should be located in the Routes folder.
The main file is Routes.php. You should separate your different resources in different files and require it inside this class.
The main file also contains the not found method and the error method. But they are already configured to work with the framework, so you shouldn't have to change it.

The route should always call the coresponding controller and pass the arguments to it. It doesn't need to do anything else.
```PHP
$this->app->get('/:id', function ($id) {
    $data = new Users\GetController( 'getSingleUser', ['id' => $id] );
    $data->send();
});
```