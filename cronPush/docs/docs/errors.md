# Exceptions

## Server-side
You can throw validationException in your models to send client errors (status code 4xx).
```PHP
namespace Appsolute\Api\Models\Validation;

use Appsolute\Api\Models\Validation\ValidationException;

throw new ValidationException( 'You have to provide an email.', 10, 400 );
```

## Server-side
If you can throw classException in your class to send client errors (status code 4xx).
```PHP
namespace Appsolute\Api\Classes\Logs;

use Appsolute\Api\Classes\Exceptions\ClassException;

throw new ClassException( 'You have to provide an email.', 10, 400 );
```

## Server-side
You can use plain exceptions in order to throw server-side errors. The status code will be automatically set to 500.
But server-side error should never happens.

You should never throw exceptions in your controller or in the routes. If it happens it means something is wrong with the logic you applied.