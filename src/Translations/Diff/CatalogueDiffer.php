<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Translations\Diff;

/**
 * Diffs the existing keys with the required translations
 */
class CatalogueDiffer
{
    /**
     * @param array $locales
     * @param array $requiredKeys
     * @param array $existingTranslations
     *
     * @return array
     */
    public function diff (
        array $locales,
        array $requiredKeys,
        array $existingTranslations,
        array $ignoredKeys
    )
    {
        $missing = [];

        foreach ($locales as $locale)
        {
            foreach ($requiredKeys as $domain => $keys)
            {
                foreach ($keys as $key)
                {
                    if ($this->isIgnored($ignoredKeys, $domain, $key))
                    {
                        continue;
                    }

                    if (!isset($existingTranslations[$locale][$domain][$key]))
                    {
                        $missing[$locale][$domain][] = $key;
                    }
                }

                if (isset($missing[$locale][$domain]))
                {
                    \usort($missing[$locale][$domain], "strnatcasecmp");
                }
            }

            if (isset($missing[$locale]))
            {
                \uksort($missing[$locale], "strnatcasecmp");
            }
        }

        \uksort($missing, "strnatcasecmp");
        return $missing;
    }


    /**
     * @param array  $ignores
     * @param string $key
     *
     * @return bool
     */
    private function isIgnored (array $ignores, string $domain, string $key) : bool
    {
        foreach ($ignores as $pattern => $ignoredDomains)
        {
            if (\preg_match($pattern, $key) && (true === $ignoredDomains || \in_array($domain, $ignoredDomains, true)))
            {
                return true;
            }
        }

        return false;
    }
}
