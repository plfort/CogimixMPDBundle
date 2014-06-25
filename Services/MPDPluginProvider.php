<?php
namespace Cogipix\CogimixMPDBundle\Services;


use Cogipix\CogimixCommonBundle\Plugin\PluginProviderInterface;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Security\Core\SecurityContextInterface;

class MPDPluginProvider implements PluginProviderInterface{

    private $om;
    private $securityContext;
    protected $plugins = array();
    protected $pluginProviders;

    private $pluginFactory;

    public function __construct(ObjectManager $om,SecurityContextInterface $securityContext,MPDPluginFactory $factory){
        $this->om=$om;
        $this->securityContext=$securityContext;
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
        $token = $this->securityContext->getToken();
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