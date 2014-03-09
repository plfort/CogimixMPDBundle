<?php
namespace Cogipix\CogimixMPDBundle\ViewHooks\Menu;
use Cogipix\CogimixCommonBundle\ViewHooks\Menu\MenuItemInterface;
use Cogipix\CogimixCommonBundle\ViewHooks\Menu\AbstractMenuItem;

/**
 *
 * @author plfort - Cogipix
 *
 */
class MenuItem  extends AbstractMenuItem{

    public function getMenuItemTemplate()
    {
          return 'CogimixMPDBundle:Menu:menu.html.twig';

    }

    public function getName(){
    	return 'mpd';
    }
}