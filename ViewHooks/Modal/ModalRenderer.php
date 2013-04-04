<?php
namespace Cogipix\CogimixMPDBundle\ViewHooks\Modal;

use Cogipix\CogimixCommonBundle\ViewHooks\Modal\ModalItemInterface;
/**
 *
 * @author plfort - Cogipix
 *
 */
class ModalRenderer implements ModalItemInterface
{

    public function getModalTemplate()
    {
        return 'CogimixMPDBundle:Modal:modals.html.twig';

    }

}
