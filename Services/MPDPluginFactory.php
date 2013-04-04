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

        $MPDPlugin = new MPDMusicSearch();
        $MPDPlugin->setLogger($this->container->get('logger'));
        $MPDPlugin->setMpdServerInfo($mpdServerInfo);
        $MPDPlugin->setSerializer($this->container->get('jms_serializer'));
        $MPDPlugin->setFilenameHasher($this->container->get('cogimix_mpd.filename_hasher'));
       return $MPDPlugin;
    }
}