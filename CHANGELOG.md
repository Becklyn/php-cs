3.0.6
=====

*   (improvement) Drop default PHPStan level to `5`, otherwise we have a huge list of questionable results.


3.0.5
=====

*   (improvement) Bump default PHPStan level to `6`.


3.0.4
=====

*   (improvement) Bump to newest `PHPStan` version.


3.0.3
=====

*   Also ignore `vendor-bin` in PHPStan.


3.0.2
=====

*   Readded PHPStan + PHP-CS-Fixer.


3.0.1 (= 3.0.0)
===============

*   Remove bundled code.


2.0.3
=====

*   Disable `mb_str_functions` fixer.


2.0.2
=====

*   Disable the `method_chaining_indentation` fixer (as it "fixes" some unintended cases)


2.0.1
=====

*   Fixed testing classes to be `abstract` and not `final` anymore (thanks PHP-CS-Fixer...)


2.0.0
=====

*   Bumped dependencies.
*   Updated PHP-CS-Fixer with a lot of new rules.
*   Moved the config files to new directories
*   Correctly skip schema validation test, if there are no entities.
*   Simplified and removed PhpStan config.


1.6.2
=====

*   Adapted `BackendTranslatorVisitor` for the new `BackendTranslator::t()`.
*   Extract correct argument for `BackendTranslator`.


1.6.1
=====

*   Fix domain of extract translations from `LabeledEntityRemovalBlockedException` (it has to be `backend`).


1.6.0
=====

*   Improved supported symfony twig features by default, to easier test integration bundles.
*   Add way to register custom twig extensions in `NoMissingTranslationsTest`.
*   Fix variable access bug. 


1.5.0
=====

*   Added translation extractor for default messages in custom validation constraints.
*   Added translation extractor for custom object constructor call parameters.


1.4.0
=====

*   Add `NoMissingTranslationsTest`.
*   The `SchemaValidationTest` will now print the violations in PHPUnit to ease debugability.


1.3.0
=====

*   Added `SchemaValidationTest`: a test helper to check that there are no mapping errors in doctrine annotations.


1.2.0
=====

*   Added a new `fix-ci` binary, to run all the scripts from the CI tools.


1.1.0
=====

*   Ignore `tests` directory.
*   Lint `src` for regular libraries.
