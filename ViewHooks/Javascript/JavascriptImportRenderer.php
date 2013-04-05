<?php
namespace Cogipix\CogimixMPDBundle\ViewHooks\Javascript;
use Cogipix\CogimixCommonBundle\ViewHooks\Javascript\JavascriptImportInterface;

/**
 *
 * @author plfort - Cogipix
 *
 */
class JavascriptImportRenderer implements JavascriptImportInterface
{

    public function getJavascriptImportTemplate()
    {
        return 'CogimixMPDBundle::js.html.twig';
    }

}
