<?php declare(strict_types=1);

namespace Tests\Becklyn\PhpCs\fixtures\BackendTranslatorVisitor;

class BackendTranslatorFixture
{
    public function someMethod ()
    {
        $this->get("")->t("getter");
        $this->backendTranslator->trans("backendTranslator.trans.property");
        $backendTranslator->trans("backendTranslator.trans.var");

        $this->backendTranslator->t("backendTranslator.t.property");
        $backendTranslator->t("backendTranslator.t.var");

        $this->translator->t("translator.t.property");
        $translator->t("translator.t.var");

        // these should not be included
        $this->translator->trans("missing1");
        $translator->trans("missing2");
    }
}
