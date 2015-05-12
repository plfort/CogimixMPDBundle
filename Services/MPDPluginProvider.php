<?php
namespace Cogipix\CogimixMPDBundle\Services;


use Cogipix\CogimixCommonBundle\Plugin\PluginProviderInterface;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MPDPluginProvider implements PluginProviderInterface{

    private $om;
    private $torkenStorage;
    protected $plugins = array();
    protected $pluginProviders;

    private $pluginFactory;

    public function __construct(ObjectManager $om,TokenStorageInterface $tokenStorageInterface,MPDPluginFactory $factory){
        $this->om=$om;
        $this->torkenStorage=$tokenStorageInterface;
        $this->pluginFactory=$factory;

    }

    public function getAvailablePlugins(){
     $user = $this->getCurrentUser();
     if($user!=null){
        $MPDServerInfos=$this->om->getRepository('CogimixMPDBundle:MPDServerInfo')->findByUser($user);
        if(!empty($MPDServerInfos)){
            foreach($MPDServerInfos as $MPDServerInfo){
                $this->plugins[$MPDServerInfo->getAlias()]= $this->pluginFactory->createMPDPlugin($MPDServerInfo);
            }
        }
     }
        return $this->plugins;
    }


    public function getPluginChoiceList()
    {
        $choices = array();
        if(!empty($this->plugins)){
            foreach($this->plugins as $alias=>$plugin){
                $choices[$alias] = $plugin->getName();
            }
        }
        return $choices;
    }


    protected function getCurrentUser() {
        $token = $this->torkenStorage->getToken();
        if($token != null){
            $user = $token->getUser();
            if ($user instanceof \FOS\UserBundle\Model\UserInterface){
                return $user;
            }
                
        }
       
        return null;
    }

    public function getAlias(){
        return 'mpdpluginprovider';
    }
}