# Config file
You will have to update the config.php to your need.

# Environnement
You should always keep the debug configuration to false. Otherwise the errors response won't be printed as JSON.

```PHP
$this->app = new Slim(array(
	'debug' => true
));
```

# Permission
Make sure your logs, tmp, upload directories are writtable.
```BASH
chmod 755 -R logs tmp upload
```