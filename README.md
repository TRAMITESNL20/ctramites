# NL Ciudadano/Notario APP | Lumen PHP Framework

## Install Composer
#### Composer - [Download](https://getcomposer.org/download/).

To quickly install Composer in the current directory, run the following script in your terminal. To automate the installation, use the [guide on installing Composer programmatically](https://getcomposer.org/doc/faqs/how-to-install-composer-programmatically.md).

```php
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'e5325b19b381bfd88ce90a5ddb7823406b2a38cff6bb704b0acc289a09c8128d4a8ce2bbafcd1fcbdc38666422fe2806') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

To use composer for anywhere use this command.
```
chmod a+x composer.phar
sudo mv composer.phar /usr/local/bin/composer.phar
```

## Install Dependencies
Run this command line to install all Project Dependecies
```
composer install
```

## Create Environment Variable
Create a file named `.env`. Inside it add the environment variables that will be used. You can use the `.env.example` to guide you.

**NOTE: Ask your Leader Team for the variables and secret keys.**


Example:
```
PORT=8000
APP_NAME=Example
APP_DEBUG=true
...
```

### Databases
To create database connection you only need to add a environment variable with this nomenclature:
```
DB_CONNECTION_<DB_NAME>
```
where **DB_CONNECTION_** will be the prefix that indicates that this variable is a database connection. And **\<DB_NAME\>** will be the name that will be assigned to the connection, this will be used to indicate within the models which connection is going to be used by adding the **db_** prefix.

**NOTE:** It is important to say that the first connection found within the environment variables, that is, the one that is highest, will be the one that will be taken as the main connection, so if a connection is not indicated in the models will use this.

Example:

**.ENV File**
``` dotenv
DB_CONNECTION_FIRST=mysql://username:pa$$w0rd@localhost/database_name
DB_CONNECTION_SECOND=mysql://username:pa$$w0rd@localhost/other_database
```

**TestModel.php**
```php
<?php
class TestModel extends Model {
    /**
      * The database table used by the model.
      *
      * @var string
      */
    //here i should call the external database table
    protected $connection = 'db_second';
}
```

## Inicialize Project
To Inicialize this Project run this command line:
```
composer start
```
This command is equivalent to:
```
php -S localhost:8000 -t public
```

## Stylesheets with SASS Lang
To work the style sheets in this project we must do it with **SASS Lang** so, in order to start with the styles, we must first install the **SASS Lang CLI** globally using the following command:
``` cmd
## Para Windows
npm install -g sass

## Para Mac (homebrew)
brew install sass/sass/sass
```

### Initialize SASS CLI
Once the SASS CLI has been installed globally, and before starting to edit the stylesheets, the sass watcher must be run. We do this through the following command:
``` cmd
composer sass-dev
```

This command is equivalent to:

``` cmd
sass --watch resources/sass:public/css --style compressed
```

### Work with
When executing the previous command, the SASS watcher will be initialized, which will always be looking inside the `resources/sass` folder waiting for a change to be made. So each new file with `.scss` extension or each modified file with `.scss` extension will be compiled by the ssas watcher and will create a compressed version with `.css` extension in the `public/css` folder.

# Troubleshooting
## Call undefined function...
If you receive an error indicating that one of these functions is not found, what you should do is run this command `composer dump-autoload`
- to_object()
- set_layout_arg()
- layout_view()

## Composer timeout off 300
If you recive an error indicating that any composer process exceeded the timeout of 300 seconds. You need to run this command on your terminal:
```
export COMPOSER_PROCESS_TIMEOUT=0   ( defaults to 300 )
```
<!--
[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework) 
[![Total Downloads](https://poser.pugx.org/laravel/lumen-framework/d/total.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/lumen-framework/v/stable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://poser.pugx.org/laravel/lumen-framework/license.svg)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Contributing

Thank you for considering contributing to Lumen! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
-->
