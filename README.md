PHP Code Style Checker
======================


Installation
------------

```bash
composer require --dev becklyn/php-cs 
```

Now run your tools like this:


Usage
-----

### PHPStan

```bash
phpstan -l 4 --memory_limit 4G -c vendor/becklyn/php-cs/phpstan.neon
```


### PHP CS Fixer

```bash
wget https://cs.sensiolabs.org/download/php-cs-fixer-v2.phar -O php-cs-fixer.phar
php php-cs-fixer.phar fix --dry-run --diff
```
