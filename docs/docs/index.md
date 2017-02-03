# Welcome

If you find a bug or if you have any problems with this project don't hesitate to create a [new issue](http://appsolute-git.fr/code-samples-web/slim-sample-project/issues) on our git or to send me a email at [kendryck@appsolute](mailto:kendryck@appsolute).

## Requirements
I highly recommend to run this project at least on **PHP5.5**. I can't guarantee that it will work properly on inferior version.

In order to use this project you will have to install several dependencies with **[Composer](https://getcomposer.org/)**. So make sure it's properly installed on your server.

## Project layout
```
├───api
│   ├───Classes				# All your utility classes in separates folders
│   ├───Controllers			# All your controllers classes in different folders (one for each resource)
│   ├───Middlewares			# Middleware classes
│   ├───Models				# All your models class should be at the root of this directory
│   │   ├───Resource		# Folder containing the classes used to call the resources
│   │   ├───Transformer		# You can define all your transformers (resources) here
│   │   └───Validation		# All the models validation files
│   └───Routes				# All your routes here
├───config					# Your config files
├───docs					# Where is located this doc
├───libs					# Your external libraries
├───logs					# Log files directory
│   └───api
├───tmp						# Temporary files directory
├───upload					# Upload files directory
└───vendor					# Composer dependencies
```

## Depenencies

By default, this project use 4 external library.

* Slim Framework : his project is build on top of it and use most of these methods in order to work.
* RedbeanPHP : an ORM used to easily make request to the database.
* Monolog :used for generating the log files.
* Fractal : to create an manage resources for the API.

I highly recommend you to read their documentation to better understand how this framwork work.

* [Slim Framework documentation](http://docs.slimframework.com)
* [RedbeanPHP documentation](http://www.redbeanphp.com)
* [Fractal documentation](http://fractal.thephpleague.com/)
* [Monolog documentation](https://github.com/Seldaek/monolog)