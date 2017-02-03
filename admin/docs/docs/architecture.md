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
The controllers classes contains all the logic of your application. It will be the link between **the routes**, **the models**, **the views** and **your classes**.
You have to use **ONE** public method by route. So for example, if you want to get the user with the id 1 from the application. In your route you only have to call the getSingleUser method. 

You will never have other methods in the controller except the one used in the routes, anything else should be simple classes.

# Returning data from the controller
The controller classes have to extend the base controller. And for sending your data you just have to use the data property.
```PHP
$this->data['users'] = $user->getAll();
```

You can also specify the responde code to use with
```PHP 
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
For example to get the POST request values you will use 
```PHP
$this->app->request->post()
```

### Slim configuration
If you ever need to add some script at the start of your application, you can put it in the constructor of the **App.php** file.

### Middlewares
Middlewares are implemented the same way as with slim and are located in the **Middlewares** folder, so I redirect you to the Slim documentation to know how to use this. 
[http://docs.slimframework.com/middleware/overview/](http://docs.slimframework.com/middleware/overview/)

You can declare them in App.php in the loadMiddleware method.

# Models
Models contain all the classes for accessing the DB. In the sample example, I used RedbeanPHP as ORM. 
[http://www.redbeanphp.com/](http://www.redbeanphp.com/)

You can simply create a file at the root of the folder and create a new class containing your method for each request. This class must extends the Database class located at the same place.

```PHP
namespace Appsolute\Backend\Models;

use R;
use Appsolute\Backend\Models\Validation\ValidationException;

Class Users extends Database {

	public function getAll() {
		try {
			$users = R::findAll( 'Users' );
			return R::exportAll($users);
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }
}
```

## Models Validation
You can use the [Redbean FUSE](http://www.redbeanphp.com/models) system to validate your data before sending it to the database. For example, you can check in the validation class if a user has provided all the field required.
If you wish to return an error you can use the ValidationException. You only need to pass the message as argument.

```PHP
namespace Appsolute\Api\Models\Validation;

use R;
use Appsolute\Api\Models\Resource\Resource;

class Users extends Resource {

	public function update() {

		if(isset($this->bean->firstname)){
			$firstname = trim($this->bean->firstname);
			if(empty($firstname)) {
				throw new ValidationException( 'You have to provide a firstname.' );
			}
		}
	}
}
```
Make sure to catch the ValidationException in your model otherwise it will throw an error.

# Routes
All your routes should be located in the Routes folder.
The main file is Routes.php. You should separate your different resources in different files and require it inside this class.
The main file also contains the not found method and the error method. But they are already configured to work with the framework, so you shouldn't have to change it. 

The route should always call the coresponding controller and pass the arguments to it. Once done, you can call the send method with the path to your view inside the Views folder.
```PHP
$this->app->map('/', function () {
	$data = new Users\GetController( 'getList' );
	$data->send("Users/list.template.html");
})->via('GET');
```

# Views
The views use the [Twig template engine](http://twig.sensiolabs.org/documentation#reference). It implements feature and template to easily call the variables or function without doing any PHP. I highly recommend to read the twig reference documentation.

For example, the following code will loop through an array and display the value of each users in a table.
```HTML
{% for user in users %}
  <tr>
    <td>{{ user.id }}</td>
    <td>{{ user.firstname }}</td>
    <td>{{ user.lastname }}</td>
    <td>{{ user.email }}</td>
  </tr>
{% endfor %}
```

By default, you can access :

* the **SESSION** values with the ``session`` variable.
* the slim **flashMessage** values with the ``flash`` variable.
* the **FOLDER_NAME** constant with the ``dirName`` variable. (can be used for generating link if the application is not at the root of your server)

You can also access the data set in your controller by directly calling the key you want.
For example, if you set this
```
$this->data['user']['id'] = 1;
```
You can retrieve it with
```
<span>{{user.id}}</span>
```

Make sure to thoroughly check the examples shipped with the project.

# Assets
You can store all your different public files (css, js, picture) in the **public/assets** folder.