<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Testing;

use Becklyn\PhpCs\Translations\Diff\CatalogueDiffer;
use Becklyn\PhpCs\Translations\Loader\TranslationsLoader;
use Becklyn\PhpCs\Translations\TranslationExtractor;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

/**
 * @internal
 */
final class NoMissingTranslationsTest extends TestCase
{
    /**
     * Tests that there are no missing translations
     */
    public function testNoMissingTranslations () : void
    {
        $locales = $this->getLocales();

        $extractor = new TranslationExtractor($this);
        $required = $extractor->extract($this->getDirectoriesWithUsages());

        $existingTranslationLoader = new TranslationsLoader();
        $existing = $existingTranslationLoader->loadTranslations($this->getDirectoriesWithTranslations(), $locales);

        $differ = new CatalogueDiffer();
        $missing = $differ->diff(
            $locales,
            $required,
            $existing,
            $this->getIgnoredKeys()
        );

        $log = \json_encode($missing, \JSON_PRETTY_PRINT);
        self::assertEmpty($missing, "Ensure that there are no missing translations, missing are:\n{$log}");
    }


    /**
     * Returns the locales to check for.
     */
    protected function getLocales () : array
    {
        return ["en", "de"];
    }


    /**
     * Returns the directories from where the translations should be extracted.
     */
    protected function getDirectoriesWithUsages () : array
    {
        $root = \rtrim($this->getRootDir(), "/");

        return [
            "{$root}/src",
        ];
    }


    /**
     * Returns the list of directories containing translation files.
     */
    protected function getDirectoriesWithTranslations () : array
    {
        $root = \rtrim($this->getRootDir(), "/");

        return [
            "{$root}/src/Resources/translations",
        ];
    }


    /**
     * Returns the path to the root dir.
     */
    protected function getRootDir () : string
    {
        $r = new \ReflectionClass($this);
        return \dirname($r->getFileName(), 2);
    }


    /**
     * Ignore these keys. These will not be reported as missing, even if they are used.
     *
     * Return type: array
     *
     *  -> key: regular expression matching the key(s)
     *  -> value: true     -> ignore in every domain
     *            string[] -> ignore in these specific domains
     */
    protected function getIgnoredKeys () : array
    {
        return [];
    }


    /**
     * Registers all twig extensions
     */
    public function registerTwigExtensions (Environment $twig) : void
    {

    }
}
