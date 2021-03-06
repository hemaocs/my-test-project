# Overview
This framework use namespaces in order to work. It helps for autoloading files and makes the developer life easier. The prefix name is \Appsolute\Api\ and after that it just follow the directories structure.

# Namespace used
All my example contains namespace, so you can use it the same way. Here's the list of namespace to use for the different folders.
```JSON
{
	"psr-4": {
		"Appsolute\\Api\\": "api",
		"Appsolute\\Api\\Classes\\": "api/Classes",
		"Appsolute\\Api\\Controllers\\": "api/Controllers",
		"Appsolute\\Api\\Middlewares\\": "api/Middlewares",
		"Appsolute\\Api\\Models\\": "api/Models",
		"Appsolute\\Api\\Routes\\": "api/Routes",
		"Appsolute\\Api\\Views\\": "api/Views"
	}
}
```
You can also find the delcaration of the namespace in the composer.json file. The autoloading process follows the [psr-4 standards](http://www.php-fig.org/psr/psr-4/).

# Documentation
You can import namespace in your file for ease of use.

Here's some link to help you get started with namespacing :
* http://php.net/manual/en/language.namespaces.rationale.php
* http://php.net/manual/en/language.namespaces.definition.php
* http://php.net/manual/en/language.namespaces.importing.php