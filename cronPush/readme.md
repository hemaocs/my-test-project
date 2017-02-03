Slim sample project
=====

A sample project that show how to use Slim Framework with RedBeanPHP.
For mor information, follow these guides :

[Documentation](https://docs.appsolute-preprod.fr/slim-api) in progress

[Slim Framework documentation](http://docs.slimframework.com)

[RedbeanPHP documentation](http://www.redbeanphp.com)

[How to use Slim Framework](https://drive.google.com/open?id=1vhmIE2YeDDWw8SCqZdrV1zLmN4NxZPaWjLJ1UN4i5Zo&authuser=0)

[How to use RedBeanPHP](https://drive.google.com/open?id=1cz0ua8ws0v8eZi1vUhUMn0P02NOJx4zuO7Ik5qXrpog&authuser=0)


## Installation

1) Download or clone the project somewhere in your server(www, public_html or whatever)
```git clone -b v2-dev http://appsolute-git.fr/code-samples-web/slim-sample-project.git```

2) Run ```composer install``` in your terminal.

3) Create a new MySQL database and import the appsolute-backend.sql in it.

4) Change the config/config.php to your need. (path and database setup)

5) You're done. Try making a POST request.


## Requests

1 . **POST**
```POST http://yourdomain/slim-api/api/users```
```JSON
{
    "firstname": "User",
    "lastname": "Test",
    "email": "user.test@app.fr",
    "password": "abc123"
}
```

2 . **GET**
```GET http://yourdomain/slim-api/api/users/{id}```

3 . **UPDATE**
```PUT http://yourdomain/slim-api/api/users/{id}```
```JSON
{
    "firstname": "aaa",
    "lastname": "aaa",
    "email": "bbb@app.fr",
    "password": "test"
}
```

4 . **DELETE**
```DELETE http://yourdomain/slim-api/api/users/{id}```

5 . **GET ALL**
```GET http://yourdomain/slim-api/api/users/```

6 . **GET ALL (with pagination)**
```GET http://yourdomain/slim-api/api/users/page/1/limit/1```