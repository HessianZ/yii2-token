RESTful Token for Yii 2
=======================
RESTful Token for Yii 2

[![Latest Stable Version](https://poser.pugx.org/hessian/yii2-token/v/stable)](https://packagist.org/packages/hessian/yii2-token) 
[![Total Downloads](https://poser.pugx.org/hessian/yii2-token/downloads)](https://packagist.org/packages/hessian/yii2-token) 
[![Latest Unstable Version](https://poser.pugx.org/hessian/yii2-token/v/unstable)](https://packagist.org/packages/hessian/yii2-token) 
[![License](https://poser.pugx.org/hessian/yii2-token/license)](https://packagist.org/packages/hessian/yii2-token)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist HessianZ/yii2-token "*"
```

or add

```
"HessianZ/yii2-token": "*"
```

to the require section of your `composer.json` file.


Migrations
-----------

Run the following command

```shell
php yii migrate --migrationPath=@hessian/yii/token/migrations/

```

Usage
-----

Once the extension is installed, simply modify your application configuration as follows:

```php
return [
    'modules' => [
        'token' => [
            'class' => 'hessian\yii\token\Module',
        ],
    ],
];
```

[More detail](/src/models/Token.php)
