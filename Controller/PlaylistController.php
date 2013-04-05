<?php

namespace Cogipix\CogimixMPDBundle\Controller;




use Cogipix\CogimixMPDBundle\Entity\MPDResult;

use Cogipix\CogimixCommonBundle\Controller\AbstractController;

use Cogipix\CogimixCommonBundle\Utils\AjaxResult;

use Cogipix\CogimixMPDBundle\lib\mpd;

use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/mpd/playlist")
 * @author plfort - Cogipix
 *
 */
class PlaylistController extends AbstractController
{
    /**
     * @Secure(roles="ROLE_USER")
     * @Route("/get/{serverAlias}/{name}",name="_cogimix_mpd_playlist_songs",options={"expose"=true})
     * @Template()
     */
    public function getPlaylistSongsAction(Request $request,$serverAlias, $name)
    {
       $response = new AjaxResult();
      $em = $this->getDoctrine()->getEntityManager();
      $mpdServerInfo=$em->getRepository('CogimixMPDBundle:MPDServerInfo')->findOneByAlias($serverAlias);
      if($mpdServerInfo!==null){
          $user= $this->getCurrentUser();
          if($mpdServerInfo->getUser()==$user){
                      //echo $filename;die();
              $mpd=new mpd($mpdServerInfo->getHost(), $mpdServerInfo->getPort(),$mpdServerInfo->getPassword());
              $data=$mpd->getPlaylistInfo($name);

              if(isset($data['files'])){
                  $return = array();
                  $resultBuilder = $this->get('cogimix_mpd.result_builder');
                  $resultBuilder->setMpdServerInfo($mpdServerInfo);
                  foreach($data['files'] as $key=>$song){
                      $item=$resultBuilder->parseItem($song,$key);

                      $return[]=$item;
                  }
                  $response->addData('tracks', $return);
                  $response->setSuccess(true);
              }

              //$response->addData('streamUrl', $mpdServerInfo->getStreamUrl());
          }
      }
      return $response->createResponse();
    }

    /**
     * @Secure(roles="ROLE_USER")
     * @Route("/stop/{serverAlias}",name="_cogimix_mpd_stop",options={"expose"=true})
     * @Template()
     */
    public function stopAction(Request $request,$serverAlias)
    {
        $response = new AjaxResult();
        $em = $this->getDoctrine()->getEntityManager();
        $mpdServerInfo=$em->getRepository('CogimixMPDBundle:MPDServerInfo')->findOneByAlias($serverAlias);
        if($mpdServerInfo!==null){
            $user= $this->getCurrentUser();
            if($mpdServerInfo->getUser()==$user){
            $mpd=new mpd($mpdServerInfo->getHost(), $mpdServerInfo->getPort());
            $mpd->Stop();

            $response->setSuccess(true);
            }
        }
        return $response->createResponse();
    }

    /**
     * @Secure(roles="ROLE_USER")
     * @Route("/paus/{serverAlias}",name="_cogimix_mpd_paus",options={"expose"=true})
     * @Template()
     */
    public function pauseAction(Request $request,$serverAlias)
    {
        $response = new AjaxResult();
        $em = $this->getDoctrine()->getEntityManager();
        $mpdServerInfo=$em->getRepository('CogimixMPDBundle:MPDServerInfo')->findOneByAlias($serverAlias);
        if($mpdServerInfo!==null){
            $user= $this->getCurrentUser();
                if($mpdServerInfo->getUser()==$user){
                $mpd=new mpd($mpdServerInfo->getHost(), $mpdServerInfo->getPort());
                $mpd->Stop();

                $response->setSuccess(true);
            }

        }
        return $response->createResponse();
    }
}
