# Exceptions

## Client-side
You can throw validationException in your models to send client errors (status code 4xx).
```PHP
namespace Appsolute\Api\Models\Validation;

use Appsolute\Api\Models\Validation\ValidationException;

throw new ValidationException( 'You have to provide an email.', 10, 400 );
```

But make sur to catch the exception in your models and to return the error as HTML (with flashMessage for example), otherwise an error 500 might occur.

```
public function getAll() {
	try {
		$users = R::findAll( 'Users' );
		return R::exportAll($users);
	} catch(ValidationException $e) {
		$this->errors[] = $e->getMessage();
		return FALSE;
	}
}
```

## Server-side
You can use plain exceptions in order to throw server-side errors. The status code will be automatically set to 500 and it returns the error page defined in the route.

You should never throw exceptions in your controller or in the routes. If it happens it means something is wrong with the logic you applied.