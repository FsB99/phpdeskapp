# PHPDeskApp
PHPDeskApp is a proof of concept php desktop web app launcher in binary, it used existing user browser and running a basic PHP tcp server.

# Get Started
1. Clone this repo:
``` git
git clone https://github.com/FsB99/phpdeskapp
```

2. Run Composer:
``` console
composer install
```

3. Modify the index.php as you like.

4. Check Phar ext:
``` php
php --ini
```

5. Enable Phar ext:
``` php
// if ext is disabled with comment like this
;extension=phar

// enable it by uncomment it
extension=phar
```

6. If You need to use some php-ext for the compiler, here's the link to documentation:
[phpacker](https://phpacker.dev/docs/installation/)

7. To build it into phar file, run this command on console:
``` php
php builder.php -a phar
```

8. Then to build the phar to into exe files, run cli 

``` php
php builder.php -a exe
```

9. Congrat, now You got desktop application with PHP :)
- The exe files located on build/{os}/