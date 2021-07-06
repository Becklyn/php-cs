11 to 12
========

*   The previous released version `12` included a BC where the `.php_cs.dist` file has been renamed to `.php_cs.dist.php`.
    You'll need to update your scripts to point to the new file, which currently looks something like this:
    `./vendor/bin/php-cs-fixer fix --diff --config vendor-bin/test/vendor/becklyn/php-cs/.php_cs.dist --dry-run --no-interaction`
    
    You'll just need to update the `--config` path to look like this:

    `./vendor/bin/php-cs-fixer fix --diff --config vendor-bin/test/vendor/becklyn/php-cs/.php_cs.dist.php --dry-run --no-interaction`.

*   With PhpCsFixer v3.0.0, the cache file has been renamed to `.php-cs-fixer.cache`. Please update your `.gitignore` files accordingly.


1.x to 2.0
==========

*   The PhpStan config files were moved from `phpstan_*` to `phpstan/*`.
*   The `phpstan.neon` was removed (it was used for Symfony 3.x projects). Copy it from an old release, if you still need it.
