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



Testing Helpers
---------------

The bundle contains various base tests, that can be used in bundles / libraries.


### Schema Validation Test

This test receives a list of directories and tests that there are no mapping errors in doctrine annotations.

#### Usage

Extend it in your `tests` directory:

`tests/ValidateEntitySchemaTest`:

```php
use Becklyn\PhpCs\Testing\SchemaValidationTest;

class ValidateEntitySchemaTest extends SchemaValidationTest
{
}
```

There are two extension points:

*   `getRootDir(): string` must return the absolute path to the root of your app. By default it assumes that your test is directly
    in the tests directory top level. Change it, if that isn't the case.

*   `getEntityDirs(): string[]` must return the list of absolute paths, where files with mapping info are stored.
    By default set to `getRootDir()/src/Entity`.



### No Missing Translations Test

This tests checks that there are no translations used, that are missing in the library.

#### Usage

Extend it in your `tests` directory:

`tests/ValidateEntitySchemaTest`:

```php
use Becklyn\PhpCs\Testing\NoMissingTranslationsTest;

class ValidateTranslationsTest extends NoMissingTranslationsTest
{
}
```

There are several extension points:

*   `getLocales(): string[]` returns the list of locales to validate. By default set to `["de", "en"]`.
*   `getDirectoriesWithUsages(): string[]` returns the absolute paths to where there is code that is using translations 
    (like forms, entities, templates, etc..). 
    By default set to `["getRootDir()/src"]`.
*   `getDirectoriesWithTranslations(): string[]`: returns the absolute paths to where the translations are stored.
    The translations files are expected to be in the `yaml` format.
    By default set to `["getRootDir()/src/Resources/translations"]`.
*   `getIgnoredKeys[]: (complex)`: returns the list of keys that should be ignored when validating for missing keys.
    See below for details.
    By default set to `[]`.
*   `getRootDir(): string[]`: must return the absolute path to the root of your app. By default it assumes that your 
    test is directly in the tests directory top level. Change it, if that isn't the case.


##### Defining Ignored Keys

The ignored keys are an indexed array, where

*   *the key* is a valid RegEx pattern. *Be sure to make it as specific as possible (e.g. by using `^` and `$`).
*   *the value* is the list of domains (`string[]`) where this ignore should apply. Instead of an array you can pass
    `true` to ignore it in any domain.
    
```php
return [
    "~^example\\..*$~" => ["form", "validators"], // ignore example.* in "form" and "validators" domain only 
                                                  // (i.e. will still be reported for "messages" for example)
                                                  
    "~^second\\..*$~" => true,                    // ignore second.* in every domain
];
```
