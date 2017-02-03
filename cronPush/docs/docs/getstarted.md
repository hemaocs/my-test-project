# Overview
This project is made on top of slim framework. It just adds a MVC pattern to the framework. So if you know how to use Slim you can easily use this project to create your web-srevices.

The project is shipped with a sample project. You can create/retrieve/update/delete users from the database. 

# Installation
1) Download or clone the project somewhere in your server(www, public_html or whatever)
```git clone -b v2-dev http://appsolute-git.fr/code-samples-web/slim-sample-project.git```

2) Run ```composer install``` in your terminal.

3) Create a new MySQL database and import the appsolute-backend.sql.

4) Change the config/config.php to your need. (paths and database setup)

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