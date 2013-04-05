<?php
namespace Cogipix\CogimixMPDBundle\Services;

use Cogipix\CogimixMPDBundle\Entity\MPDResult;

class ResultBuilder
{

    private $filenameHasher;
    private $mpdServerInfo;
    public function __construct($filenameHasher)
    {
        $this->filenameHasher = $filenameHasher;
    }

    public function parseItem($item,$key)
    {
        $track = new MPDResult();
        $track->setTag('mpd');
        $track->setEntryId($key);
        $track->setId($key);
        $track->setArtist($item['Artist']);
        $track->setTitle($item['Title']);
        $track->setDuration($item['Time']);
        $track->setHash($this->filenameHasher->crypt($item['file']));
        $track->setIcon('bundles/cogimixmpd/images/mpd-logo.png');
        $track->setThumbnails('bundles/cogimixmpd/images/mpd-logo.png');
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

}
