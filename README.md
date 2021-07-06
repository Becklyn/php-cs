PHP Code Style Checker
======================


Installation
------------

Installation should be done via the [composer-bin-plugin](https://github.com/bamarni/composer-bin-plugin).

```bash
composer bin test require --dev becklyn/php-cs 
```

Now run your tools like this:

Usage
-----

### PHP CS Fixer

```bash
./vendor/bin/php-cs-fixer fix --dry-run --diff --config vendor-bin/test/vendor/becklyn/php-cs/.php_cs.dist.php
./vendor/bin/phpstan -c vendor-bin/test/vendor/becklyn/php-cs/phpstan/lib.neon
```

### Composer Normalize

Composer normalize needs the [composer `normalize` plugin](https://packagist.org/packages/localheinz/composer-normalize).


Versioning
==========

This package doesn't use semantic versioning, but it always releases a major version.


Main Branch Naming
==================

Normally, in Becklyn's repositories, we use versioned branches (`1.x`, `2.x`), but here it doesn't make sense
as every update implies a new major version.

So the main branch is just called `release`, as that's where all releases are coming from.
