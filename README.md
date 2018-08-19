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
php vendor/bin/phpstan analyse -l 4 --memory-limit 4G -c vendor/becklyn/php-cs/phpstan.neon .
```


### PHP CS Fixer

```bash
php vendor/bin/php-cs-fixer fix --dry-run --diff
```


Complete Gitlab Test Script
---------------------------

Just add the file `.gitlab-ci.yml` in the root of your project:

```yaml
before_script:
    - composer install --no-interaction --no-progress --ansi

composer_normalize:
    script:
        - composer normalize --indent-size 4 --indent-style space --dry-run

package_json:
    script:
        - npx prettier-package-json --list-different --tab-width 4

kaba:
    script:
        # fixes possible `node-sass` installation issues
        - export npm_config_unsafe_perm=true
        - yarn
        - npx kaba --analyze

php_cs_fixer:
    script:
        - php vendor/bin/php-cs-fixer fix --dry-run --diff --config vendor/becklyn/php-cs/.php_cs.dist --no-interaction --ansi

phpstan:
    script:
        - php vendor/bin/phpstan analyse -l 4 --memory-limit 4G -c vendor/becklyn/php-cs/phpstan.neon .
        # or
        # - php vendor/bin/phpstan analyse -l 4 --memory-limit 4G -c vendor/becklyn/php-cs/phpstan_lib.neon .

phpunit:
    script:
        - ./vendor/bin/simple-phpunit -c phpunit.xml --colors=always
```

Composer normalize needs the [composer `normalize` plugin](https://packagist.org/packages/localheinz/composer-normalize).
