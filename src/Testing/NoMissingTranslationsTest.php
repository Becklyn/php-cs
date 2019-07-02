<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Testing;

use Becklyn\PhpCs\Translations\Diff\CatalogueDiffer;
use Becklyn\PhpCs\Translations\Loader\TranslationsLoader;
use Becklyn\PhpCs\Translations\TranslationExtractor;
use PHPUnit\Framework\TestCase;

class NoMissingTranslationsTest extends TestCase
{
    /**
     * Tests that there are no missing translations
     */
    final public function testNoMissingTranslations ()
    {
        $locales = $this->getLocales();

        $extractor = new TranslationExtractor();
        $required = $extractor->extract($this->getDirectoriesToExtract());

        $existingTranslationLoader = new TranslationsLoader();
        $existing = $existingTranslationLoader->loadTranslations($this->getDirectoriesWithTranslations(), $locales);

        $differ = new CatalogueDiffer();
        $missing = $differ->diff(
            $locales,
            $required,
            $existing,
            $this->getIgnoredKeys()
        );

        self::assertEquals([], $missing, "Ensure that there are no missing translations");
    }


    /**
     * Returns the locales to check for.
     *
     * @return array
     */
    protected function getLocales () : array
    {
        return ["en", "de"];
    }


    /**
     * Returns the directories from where the translations should be extracted.
     *
     * @return array
     */
    protected function getDirectoriesToExtract () : array
    {
        $root = \rtrim($this->getRootDir(), "/");

        return [
            "{$root}/src",
        ];
    }


    /**
     * Returns the path to the root dir.
     *
     * @return string
     */
    protected function getRootDir () : string
    {
        $r = new \ReflectionClass($this);
        return \dirname($r->getFileName(), 2);
    }


    /**
     * Returns the list of directories containing translation files.
     *
     * @return array
     */
    protected function getDirectoriesWithTranslations () : array
    {
        $root = \rtrim($this->getRootDir(), "/");

        return [
            "{$root}/src/Resources/translations",
        ];
    }


    /**
     * Ignore these keys. These will not be reported as missing, even if they are used.
     *
     * Return type: array
     *
     *  -> key: regular expression matching the key(s)
     *  -> value: true     -> ignore in every domain
     *            string[] -> ignore in these specific domains
     *
     * @return array
     */
    protected function getIgnoredKeys () : array
    {
        return [
        ];
    }
}
