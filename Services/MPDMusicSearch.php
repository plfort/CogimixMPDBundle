<?php
namespace Cogipix\CogimixMPDBundle\Services;
use Cogipix\CogimixMPDBundle\Entity\MPDResult;

use Cogipix\CogimixMPDBundle\lib\mpd;

use Cogipix\CogimixCommonBundle\MusicSearch\AbstractMusicSearch;

class MPDMusicSearch extends AbstractMusicSearch
{

    private $mpdServerInfo;
    private $mpdClass;
    private $resultBuilder;

    public function __construct(ResultBuilder $resultBuilder){
        $this->resultBuilder=$resultBuilder;
    }
    protected function parseResponse($results)
    {
        $return = array();
        if(isset($results['files'])){
            foreach ($results['files'] as $key=>$result) {
                $item =  $this->resultBuilder->parseItem($result,$key);
                if($item !== null){
                    $return[] = $item;
                }
            }
        }
        return $return;
    }

    protected function buildQuery()
    {
        if($this->mpdServerInfo !== null ){
            $this->mpdClass = new mpd($this->mpdServerInfo->getHost(), $this->mpdServerInfo->getPort(),$this->mpdServerInfo->getPassword());
        }
    }

    protected function executeQuery()
    {
      $results=array();
      if($this->mpdClass!==null){
          $results= $this->mpdClass->Search(MPD_SEARCH_ANY, $this->searchQuery->getSongQuery());
          $this->mpdClass->Disconnect();
      }
      return $this->parseResponse($results);
    }

    public function getName()
    {
        return $this->mpdServerInfo->getName();
    }

    public function getAlias()
    {
        return $this->mpdServerInfo->getAlias();

    }

    public function getResultTag()
    {
        return 'mpd';
    }

    public function getDefaultIcon()
    {
        return '/bundles/cogimixcustomprovider/images/cogimix.png';
    }

    public function getMpdServerInfo()
    {
        return $this->mpdServerInfo;
    }

    public function setMpdServerInfo($mpdServerInfo)
    {

        $this->mpdServerInfo = $mpdServerInfo;


    }

}

?>