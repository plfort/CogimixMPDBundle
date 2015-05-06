<?php
namespace Cogipix\CogimixMPDBundle\Entity;

use Cogipix\CogimixCommonBundle\Entity\Song;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;
/**
  * @JMSSerializer\AccessType("public_method")
 * @ORM\MappedSuperclass()
 * @author plfort - Cogipix
 */
class MPDResult extends Song
{

    protected $shareable=false;


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

    public function setDuration($duration)
    {
        $this->duration = $duration;
        $this->pluginProperties['duration'] =$duration;
    }

    public function getEntryId(){
        return $this->getId();
    }

}
