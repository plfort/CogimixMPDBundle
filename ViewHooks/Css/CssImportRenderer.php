<?php
namespace Cogipix\CogimixMPDBundle\ViewHooks\Css;
use Cogipix\CogimixCommonBundle\ViewHooks\Css\CssImportInterface;


/**
 *
 * @author plfort - Cogipix
 *
 */
class CssImportRenderer implements CssImportInterface
{

    public function getCssImportTemplate()
    {
        return 'CogimixMPDBundle::css.html.twig';
    }

}
