<?php

namespace Cogipix\CogimixMPDBundle\Controller;




use Cogipix\CogimixMPDBundle\Form\MPDServerInfoEditFormType;

use Cogipix\CogimixMPDBundle\Form\MPDServerInfoFormType;

use Cogipix\CogimixMPDBundle\Entity\MPDServerInfo;

use Cogipix\CogimixCommonBundle\Controller\AbstractController;

use Cogipix\CogimixCommonBundle\Utils\AjaxResult;

use Cogipix\CogimixMPDBundle\lib\mpd;

use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/mpd")
 * @author plfort - Cogipix
 *
 */
class DefaultController extends AbstractController
{
    /**
     * @Secure(roles="ROLE_USER")
     * @Route("/play/{serverAlias}/{hash}",name="_cogimix_mpd_play",options={"expose"=true})
     * @Template()
     */
    public function playAction(Request $request,$serverAlias, $hash)
    {
       $response = new AjaxResult();
      $em = $this->getDoctrine()->getEntityManager();
      $mpdServerInfo=$em->getRepository('CogimixMPDBundle:MPDServerInfo')->findOneByAlias($serverAlias);
      if($mpdServerInfo!==null){
          $user= $this->getCurrentUser();
          if($mpdServerInfo->getUser()==$user){
              //echo $hash."\n";

              $filename= $this->get('cogimix_mpd.filename_hasher')->decrypt(  urldecode($hash));

              //echo $filename;die();
              $mpd=new mpd($mpdServerInfo->getHost(), $mpdServerInfo->getPort(),$mpdServerInfo->getPassword());
              $mpd->PLClear();
              $mpd->PLAdd($filename);
              $mpd->Play();

              $response->setSuccess(true);
              $response->addData('streamUrl', $mpdServerInfo->getStreamUrl());
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
            $mpd=new mpd($mpdServerInfo->getHost(), $mpdServerInfo->getPort(),$mpdServerInfo->getPassword());
            $mpd->Stop();

            $response->setSuccess(true);
            }
        }
        return $response->createResponse();
    }

    /**
     * @Secure(roles="ROLE_USER")
     * @Route("/seekTo/{serverAlias}/{value}",name="_cogimix_mpd_seekTo",options={"expose"=true})
     * @Template()
     */
    public function seekToAction(Request $request,$serverAlias,$value)
    {
        $response = new AjaxResult();
        $em = $this->getDoctrine()->getEntityManager();
        $mpdServerInfo=$em->getRepository('CogimixMPDBundle:MPDServerInfo')->findOneByAlias($serverAlias);
        if($mpdServerInfo!==null){
            $user= $this->getCurrentUser();
            if($mpdServerInfo->getUser()==$user){
                $mpd=new mpd($mpdServerInfo->getHost(), $mpdServerInfo->getPort(),$mpdServerInfo->getPassword());
                $mpd->SeekTo(floor($value/1000));

                $response->setSuccess(true);
            }
        }
        return $response->createResponse();
    }

    /**
     * @Secure(roles="ROLE_USER")
     * @Route("/paus/{serverAlias}",name="_cogimix_mpd_pause",options={"expose"=true})
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
                $mpd=new mpd($mpdServerInfo->getHost(), $mpdServerInfo->getPort(),$mpdServerInfo->getPassword());
                $mpd->Pause();

                $response->setSuccess(true);
            }

        }
        return $response->createResponse();
    }

    /**
     *  @Secure(roles="ROLE_USER")
     *  @Route("/manageModal",name="_mpd_manage_modal",options={"expose"=true})
     */
    public function getManageModalAction(Request $request){
        $response = new AjaxResult();
        $user = $this->getCurrentUser();
        $em = $this->getDoctrine()->getEntityManager();
        $mpdServerInfos=$em->getRepository('CogimixMPDBundle:MPDServerInfo')->findByUser($user);
        $response->setSuccess(true);
        $response->addData('modalContent', $this->renderView('CogimixMPDBundle:MPDServerInfo:modalContent.html.twig',array('mpdServerInfos'=>$mpdServerInfos)));
        return $response->createResponse();
    }

    /**
     *  @Secure(roles="ROLE_USER")
     *  @Route("/create",name="_mpd_create",options={"expose"=true})
     */
    public function createMPDServerInfoAction(Request $request){
        $response = new AjaxResult();
        $actionUrl = $this->generateUrl('_mpd_create');
        $user = $this->getCurrentUser();
        $em = $this->getDoctrine()->getEntityManager();
        $mpdServerInfo = new MPDServerInfo();

        $mpdServerInfo->setUser($user);
        $action = 'create';
        $response->addData('formType', $action);
        $form = $this->createForm(new MPDServerInfoFormType(),$mpdServerInfo);
        if($request->getMethod()==='POST'){
            $form->bind($request);
            if($form->isValid()){
                $em->persist($mpdServerInfo);
                $em->flush();
                $response->setSuccess(true);
                $response->addData('newItem', $this->renderView('CogimixMPDBundle:MPDServerInfo:listItem.html.twig',array('mpdServerInfo'=>$mpdServerInfo)));
            }else{
                $response->setSuccess(false);
                $response->addData('formHtml', $this->renderView('CogimixMPDBundle:MPDServerInfo:formContent.html.twig',array('action'=>$action, 'actionUrl'=>$actionUrl, 'mpdServerInfo'=>$mpdServerInfo,'form'=>$form->createView())));
            }
        }else{
            $response->setSuccess(true);
            $response->addData('formHtml', $this->renderView('CogimixMPDBundle:MPDServerInfo:formContent.html.twig',array('action'=>$action,'actionUrl'=>$actionUrl,'mpdServerInfo'=>$mpdServerInfo,'form'=>$form->createView())));
        }



        return $response->createResponse();
    }

    /**
     *  @Secure(roles="ROLE_USER")
     *  @Route("/edit/{id}",name="_mpd_edit",options={"expose"=true})
     */
    public function editMPDServerInfoAction(Request $request,$id){
        $response = new AjaxResult();

        $user = $this->getCurrentUser();
        $em = $this->getDoctrine()->getEntityManager();
        $mpdServerInfo=$em->getRepository('CogimixMPDBundle:MPDServerInfo')->findOneById($id);
        if($mpdServerInfo!==null){
            $action = 'edit';
            $actionUrl = $this->generateUrl('_mpd_edit',array('id'=>$id));

            $response->addData('formType', $action);
            $form = $this->createForm(new MPDServerInfoEditFormType(),$mpdServerInfo);
            if($request->getMethod()==='POST'){
                $form->bind($request);
                if($form->isValid()){
                    $em->flush();
                    $response->setSuccess(true);
                    //return $response->createResponse();
                }else{
                    $response->setSuccess(false);
                    $response->addData('formHtml', $this->renderView('CogimixMPDBundle:MPDServerInfo:formContent.html.twig',array('action'=>$action,'actionUrl'=>$actionUrl,'mpdServerInfo'=>$mpdServerInfo,'form'=>$form->createView())));
                }
            }else{
                $response->setSuccess(true);
                $response->addData('formHtml', $this->renderView('CogimixMPDBundle:MPDServerInfo:formContent.html.twig',array('action'=>$action,'actionUrl'=>$actionUrl,'mpdServerInfo'=>$mpdServerInfo,'form'=>$form->createView())));
            }
        }


        return $response->createResponse();
    }

    /**
     *  @Secure(roles="ROLE_USER")
     *  @Route("/remove/{id}",name="_mpd_remove",options={"expose"=true})
     */
    public function removeMPDServerInfoAction(Request $request, $id){
        $response = new AjaxResult();

        $user = $this->getCurrentUser();
        $em = $this->getDoctrine()->getEntityManager();
        $mpdServerInfo=$em->getRepository('CogimixMPDBundle:MPDServerInfo')->findOneById($id);
        if($mpdServerInfo!==null && $mpdServerInfo->getUser()==$user){
            $em->remove($mpdServerInfo);
            $em->flush();
            $response->setSuccess(true);
            $response->addData('id', $id);
        }

        return $response->createResponse();
    }
}
