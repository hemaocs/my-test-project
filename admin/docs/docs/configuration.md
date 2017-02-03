# Config file
You will have to update the config.php to your need.

All the URL and path should have an ending trailing slash. Though, the FOLDER_NAME doesn't contain slash at the begining or at the end.

# Config manager
A configuration manager has been created to make it easier to pass the different type of variables through the application.
You can create a new class that implements the ``ConfigManagerInterface`` and you can add attributes or methods to this class for retrieving your data.

In the application, you should add a config arguments to the constructor of the class where you need the config data. For example, it has been implemented that way for the database models files.
```
use Appsolute\Config\ConfigManagerInterface;

Abstract Class Database {

	protected $config;

	public function __construct(ConfigManagerInterface $config = null) {
		$this->config = $config;
		...	
	}

}
```

By default, you can instanciate any configuration files from the controllers. You just have to call the following methods with the name of the class as only argument :
```
$config = $this->configManager('Database');
```
The ``$config`` variable will now contains a new instance of the database config file. If the file you are asking through this method doesn't exit, it will return an exception.

# Htaccess
Don't forget to change the .htaccess inside the **public folder**. By default, it removes the index.php from the URL after the /slim-backend/ part. So if you're in another folder or if you are at the root of your server you need to change this value.

# Environnement
### Developpement
When you're in developpement you have to set the debug option to **FALSE** in order to print the error message.
```PHP
$this->app = new Slim(array(
	'debug' => true
));
```

### Production
When deploying your application to production never forget to set this option to **TRUE**. We don't want the client to see the error message, instead it will print the custom error page.

```PHP
$this->app = new Slim(array(
	'debug' => false
));
```

# Permission
Make sure your logs, tmp, upload directories are writtable.
```BASH
chmod 755 -R logs tmp upload
```

# Sessions
The application use Slim session. So you must never call the session_start function because it might cause some issue to function using the sessions/cookies.
If you ever want to manually set session, then you make sure to disable the SessionCookie middleware. (located in App.php)