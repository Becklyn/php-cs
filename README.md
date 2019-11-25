PHP Code Style Checker
======================


Installation
------------

```bash
composer require --dev becklyn/php-cs 
```

You also need to install PHP-CS-Fixer via Phive:

```bash
phive install php-cs-fixer
```

Now run your tools like this:


Usage
-----

### PHP CS Fixer

```bash
php tools/php-cs-fixer fix --dry-run --diff --config vendor/becklyn/php-cs/.php_cs.dist
```

### Composer Normalize

Composer normalize needs the [composer `normalize` plugin](https://packagist.org/packages/localheinz/composer-normalize).
