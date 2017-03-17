Moloquent Logger
===============

A Laravel 5 package to log changes of your Mongodb Models.

### Dependencies
- `jenssegers/mongodb`

### Installation

Install using composer:

```bash
composer require rummykhan/moloquent-logger
```

### Add Service Provider

Add service provider in `config/app.php`:

```php
RummyKhan\MoloquentLogger\MoloquentLoggerServiceProvider::class,,
```

### Publish Configuration

Publish the configuration using command:

```bash
php artisan vendor:publish
```
### Configure your application logging behavior
In `config/moloquent-logger.php` there are certain options which you can use to control the logging behavior of you application.

| Variable               | Description                                                       | Default                 |
|:---------------------- |:------------------------------------------------------------------|:------------------------| 
| `connection`           |  Database Connection for logs (`string`).                         | `env('DB_CONNECTION')`  |
| `collection`           | Collection for moloquent logs. (`string`)                         |   `moloquent_logs`      |
| `ignore_environments`  | Environment for which you don't want to perform logging. (`array`)|    `['test']`           |

### Add MoloquentLogger Trait
Any Model for which you want to track changes add `MoloquentLogger` Trait to that Model.

e.g.
```php
<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use RummyKhan\MoloquentLogger\Logger\MoloquentLogger;

class Post extends Model{
    
    use MoloquentLogger;
    
}

```

### Log Collection Document Structure

```bson
{
    "_id" : ObjectId("58bf371ddd73170408004836"),
    "request" : [],
    "before" : {
        "name" : "eod nhoj",
        "type" : "admin"
    },
    "after" : {
        "name" : "john doe",
        "type" : "user"
    },
    "before_model" : {
        "_id" : "58bf3388dd7317040800482e",
        "name" : "eod nhoj",
        "email" : "john@doe.ae",
        "updated_at" : "2017-03-07 22:41:20",
        "created_at" : "2017-03-07 22:26:16",
        "type" : "admin"
    },
    "after_model" : {
        "_id" : "58bf3388dd7317040800482e",
        "name" : "john doe",
        "email" : "john@doe.ae",
        "updated_at" : "2017-03-07 22:41:20",
        "created_at" : "2017-03-07 22:26:16",
        "type" : "user"
    },
    "scope" : {
        "file" : "/home/user/laravel/routes/web.php",
        "line" : 29,
        "function" : "save",
        "class" : "Illuminate\\Database\\Eloquent\\Model",
        "object" : {},
        "type" : "->",
        "args" : []
    },
    "user_id" : null,
    "action" : "update",
    "moloquent_type" : "App\\User",
    "moloquent_id" : "58bf3388dd7317040800482e",
    "updated_at" : ISODate("2017-03-07T22:41:33.359Z"),
    "created_at" : ISODate("2017-03-07T22:41:33.359Z")
}
```
### Document Description

| Attribute               | Description                                                       |
|:----------------------  |:------------------------------------------------------------------| 
| `_id`                   |  Primary Key for the logs table.                         |
| `request`               | Current Request dump. `Request::all()`. (Adding for debugging only.)                         |
| `before`                | (Dirty) Attibutes before change.|
| `after`                 | (Dirty) Attibutes after change.|
| `before_model`          | Complete model attributes before modification.|
| `after_model`           | Complete model attributes after modification.|
| `scope`                 | (Public) Scope of model event, it describe at what line of code this update was issued.|
| `user_id`               | If a logged in user updated this model.|
| `action`                | What action was being performed on model.|
| `moloquent_type`        | Model being modified. [Morph Many](https://laravel.com/docs/5.3/eloquent-relationships#polymorphic-relations)|
| `moloquent_id`          | Id of Model being modified. [Morph Many](https://laravel.com/docs/5.3/eloquent-relationships#polymorphic-relations)|

### Access Logs
To access logs for a record
```php

$post = Post::find(1);

dd($post->logs);

```

### Get State On
Get state on specific date, using string date format understandable by PHP `Date`.
```php

$post = Post::find(1);

dd($post->stateOn('2017-03-17'));

```