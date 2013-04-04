<?php
namespace Cogipix\CogimixMPDBundle\ViewHooks\Menu;
use Cogipix\CogimixCommonBundle\ViewHooks\Menu\MenuItemInterface;

/**
 *
 * @author plfort - Cogipix
 *
 */
class MenuRenderer implements MenuItemInterface{

    public function getMenuItemTemplate()
    {
          return 'CogimixMPDBundle:Menu:menu.html.twig';

    }
}