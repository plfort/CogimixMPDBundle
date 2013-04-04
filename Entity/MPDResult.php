<?php
namespace Cogipix\CogimixMPDBundle\Entity;

use Cogipix\CogimixCommonBundle\Entity\TrackResult;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;
/**
  * @JMSSerializer\AccessType("public_method")
 * @author plfort - Cogipix
 */
class MPDResult extends TrackResult
{

    public function __construct(){
        parent::__construct();
        // $this->pluginProperties=array('test'=>array('url'=>'','test'=>'hello'));
    }

    public function setHash($hash){
        $this->pluginProperties['hash'] =$hash;
    }

    public function setServerAlias($alias){
        $this->pluginProperties['alias'] =$alias;
    }

    public function setUrl($url)
    {
        $this->pluginProperties['url'] =$url;
    }

    public function getEntryId(){
        return $this->getId();
    }

}
