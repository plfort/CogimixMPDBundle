<?php
namespace Cogipix\CogimixMPDBundle\Services;
use Cogipix\CogimixCommonBundle\ResultBuilder\ResultBuilderInterface;

use Cogipix\CogimixMPDBundle\Entity\MPDResult;

class ResultBuilder implements ResultBuilderInterface
{

    private $filenameHasher;
    private $mpdServerInfo;

    public function __construct($filenameHasher)
    {
        $this->filenameHasher = $filenameHasher;
    }

    public function parseItem($item, $key)
    {
        $track = new MPDResult();
        $track->setTag($this->getResultTag());
        $track->setEntryId($key);
        $track->setId($key);
        $track->setArtist($item['Artist']);
        $track->setTitle($item['Title']);
        $track->setDuration($item['Time']);
        $track->setHash($this->filenameHasher->crypt($item['file']));
        $track->setIcon($this->getDefaultIcon());
        $track->setThumbnails($this->getDefaultIcon());
        $track->setServerAlias($this->mpdServerInfo->getAlias());
        return $track;
    }

    public function getMpdServerInfo()
    {
        return $this->mpdServerInfo;
    }

    public function setMpdServerInfo($mpdServerInfo)
    {
        $this->mpdServerInfo = $mpdServerInfo;
    }

    public function getResultTag()
    {
        return 'mpd';

    }
    public function getDefaultIcon()
    {
        return '/bundles/cogimixmpd/images/mpd-logo.png';

    }

}
