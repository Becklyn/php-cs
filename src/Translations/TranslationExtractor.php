<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Translations;


use Becklyn\PhpCs\Translations\Integration\NameResolverIntegration;
use Becklyn\PhpCs\Translations\Visitor\BackendTranslatorVisitor;
use Becklyn\PhpCs\Translations\Visitor\ClassValidationVisitor;
use Becklyn\PhpCs\Translations\Visitor\PropertyValidationVisitor;
use Symfony\Component\Finder\Finder;
use Translation\Extractor\Extractor;
use Translation\Extractor\FileExtractor\FileExtractor;
use Translation\Extractor\FileExtractor\PHPFileExtractor;
use Translation\Extractor\FileExtractor\TwigFileExtractor;
use Translation\Extractor\Model\SourceCollection;
use Translation\Extractor\Model\SourceLocation;
use Translation\Extractor\Visitor\Php\Symfony\ContainerAwareTrans;
use Translation\Extractor\Visitor\Php\Symfony\ContainerAwareTransChoice;
use Translation\Extractor\Visitor\Php\Symfony\FlashMessage;
use Translation\Extractor\Visitor\Php\Symfony\FormTypeChoices;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TranslationExtractor
{
    /**
     * @param array $dirs
     *
     * @return string
     */
    public function extract (array $dirs) : array
    {
        if (empty($dirs))
        {
            return new SourceCollection();
        }

        $extractor = new Extractor();
        $extractor->addFileExtractor($this->createPhpExtractor());
        $extractor->addFileExtractor($this->createTwigExtractor($dirs));

        $messages = [];
        /** @var SourceLocation $location */
        foreach ($extractor->extract($this->createFinder($dirs)) as $location)
        {
            $domain = $location->getContext()["domain"] ?? "messages";
            $messages[$domain][] = $location->getMessage();
        }

        return $this->dedupeMessages($messages);
    }


    /**
     * Dedupes the messages
     *
     * @param array $domains
     *
     * @return array
     */
    private function dedupeMessages (array $domains): array
    {
        $result = [];

        foreach ($domains as $group => $messages)
        {
            $filtered = [];

            foreach ($messages as $message)
            {
                // filter null / empty messages
                if (!$message)
                {
                    continue;
                }

                $filtered[$message] = true;
            }

            if (!empty($filtered))
            {
                $result[$group] = \array_keys($filtered);
            }
        }

        return $result;
    }


    /**
     * @param array $dirs
     *
     * @return FileExtractor
     */
    private function createPhpExtractor () : FileExtractor
    {
        $fileExtractor = new PHPFileExtractor();

        // add FQCN first
        $fileExtractor->addVisitor(new NameResolverIntegration());

        // add remaining visitors
        $fileExtractor->addVisitor(new BackendTranslatorVisitor());
        $fileExtractor->addVisitor(new ClassValidationVisitor());
        $fileExtractor->addVisitor(new ContainerAwareTrans());
        $fileExtractor->addVisitor(new ContainerAwareTransChoice());
        $fileExtractor->addVisitor(new FlashMessage());
        $fileExtractor->addVisitor(new FormTypeChoices());
        $fileExtractor->addVisitor(new PropertyValidationVisitor());

        return $fileExtractor;
    }


    /**
     * @param array $dirs
     *
     * @return SourceCollection
     */
    private function createTwigExtractor (array $dirs) : FileExtractor
    {
        $loader = new FilesystemLoader($dirs);
        $twig = new Environment($loader);
        return new TwigFileExtractor($twig);
    }


    /**
     * @param array $dirs
     *
     * @return Finder
     */
    private function createFinder (array $dirs) : Finder
    {
        $finder = new Finder();
        $finder
            ->name("*.{php,twig}")
            ->in($dirs);

        return $finder;
    }
}
