<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Translations\Loader;

use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\Reader\TranslationReader;

class TranslationsLoader
{
    /**
     * Loads all translations
     *
     * @param array $dirs
     * @param array $locales
     *
     * @return array
     */
    public function loadTranslations (array $dirs, array $locales) : array
    {
        $reader = new TranslationReader();
        $reader->addLoader('yaml', new YamlFileLoader());
        $result = [];

        foreach ($locales as $locale)
        {
            $catalogue = new MessageCatalogue($locale);

            foreach ($dirs as $dir)
            {
                $reader->read($dir, $catalogue);
            }

            $result[$locale] = $catalogue->all();
        }

        return $result;
    }
}
