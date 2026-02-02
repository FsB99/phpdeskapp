# PHPDeskApp
PHPDeskApp is a proof of concept php desktop web app launcher in binary, it used existing user browser and running a basic PHP tcp server.

# Get Started
Modify the index.php as you like, to build it into phar file, run this command on console:

```php
php builder.php -a phar
```

Then to build the phar to into exe files, run cli 

```php
php builder.php -a exe
```

the exe files located on build/{os}/