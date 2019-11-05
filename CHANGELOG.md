1.7.0
=====

*   Correctly skip schema validation test, if there are no entities.


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
