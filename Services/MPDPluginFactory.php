<?php
namespace Cogipix\CogimixMPDBundle\Services;


use Cogipix\CogimixMPDBundle\Entity\MPDServerInfo;

use Symfony\Component\DependencyInjection\ContainerInterface;

class MPDPluginFactory{

    private $container;

    public function __construct(ContainerInterface $container){

        $this->container=$container;
    }

    public function createMPDPlugin(MPDServerInfo $mpdServerInfo){
        $resultBuilder = $this->container->get('cogimix_mpd.result_builder');
        $resultBuilder->setMpdServerInfo($mpdServerInfo);
        $MPDPlugin = new MPDMusicSearch($resultBuilder);
        $MPDPlugin->setLogger($this->container->get('logger'));
        $MPDPlugin->setMpdServerInfo($mpdServerInfo);

       return $MPDPlugin;
    }
}