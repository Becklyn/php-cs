PHP Code Style Checker
======================


Installation
------------

```bash
composer require --dev becklyn/php-cs 
```

Now run your tools like this:


Fix CI
------

There is a (simple) automated tool, that runs all the tasks your CI will run as well:

```bash
vendor/bin/fix-ci
```

Pass the `--check` argument, to also run the tasks that are only checks (like tests, audits) and not just fixers:

```bash
vendor/bin/fix-ci --check
```


Usage
-----

### PHPStan

```bash
php vendor/bin/phpstan analyse -l 4 --memory-limit 4G -c vendor/becklyn/php-cs/phpstan.neon .
```


### PHP CS Fixer

```bash
php vendor/bin/php-cs-fixer fix --dry-run --diff --config vendor/becklyn/php-cs/.php_cs.dist
```

Composer normalize needs the [composer `normalize` plugin](https://packagist.org/packages/localheinz/composer-normalize).
