<?php
namespace Cogipix\CogimixMPDBundle\ViewHooks\Playlist;
use Cogipix\CogimixMPDBundle\Model\PlaylistMPD;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Cogipix\CogimixMPDBundle\lib\mpd;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

use Cogipix\CogimixCommonBundle\Utils\TokenStorageAwareInterface;

use Doctrine\Common\Persistence\ObjectManager;

use Cogipix\CogimixCommonBundle\ViewHooks\Playlist\PlaylistRendererInterface;
/**
 *
 * @author plfort - Cogipix
 *
 */
class PlaylistRenderer implements PlaylistRendererInterface,
        TokenStorageAwareInterface
{

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var ObjectManager
     */
    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }
    public function getListTemplate()
    {
        return 'CogimixMPDBundle:Playlist:list.html.twig';

    }

    public function getPlaylists()
    {
        $playlists = array();
        $user = $this->getCurrentUser();
        if($user !==null){
            $mpdServerInfos=$this->om->getRepository('CogimixMPDBundle:MPDServerInfo')->findByUser($user);
            if(!empty($mpdServerInfos)){
                foreach($mpdServerInfos as $mpdServerInfo){
                    $currentServerAlias = $mpdServerInfo->getAlias();
                    $playlists[$mpdServerInfo->getName()]=array();
                    try{
                    $mpd = new mpd($mpdServerInfo->getHost(), $mpdServerInfo->getPort(),$mpdServerInfo->getPassword());

                    $currentPlaylists=$mpd->getAvailablePlaylists();
                    if($currentPlaylists){
                        foreach($currentPlaylists as $key=>$name){
                            $pl = new PlaylistMPD($currentServerAlias,$name);
                            $playlists[$mpdServerInfo->getName()][]=$pl;
                        }
                    }
                    }catch(\Exception $ex){

                    }
                   // $playlists[$mpdServerInfo->getName()]=$currentPlaylists;
                }

            }
        }

        return $playlists;
    }
    public function setTokenStorage(
            TokenStorageInterface $tokenStorage)
    {
        // TODO: Auto-generated method stub
        $this->tokenStorage=$tokenStorage;
    }

    protected function getCurrentUser() {
        $user = $this->tokenStorage->getToken()->getUser();
        if ($user instanceof AdvancedUserInterface){
            return $user;
        }

        return null;
    }

    public function getTag(){
        return 'mpd';
    }

    public function getRenderPlaylistsParameters()
    {
        return array('playlists'=>$this->getPlaylists());
    }

}
