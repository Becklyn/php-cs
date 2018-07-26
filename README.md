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
php vendor/bin/phpstan -l 4 --memory_limit 4G -c vendor/becklyn/php-cs/phpstan.neon
```


### PHP CS Fixer

```bash
php vendor/bin/php-cs-fixer fix --dry-run --diff
```
