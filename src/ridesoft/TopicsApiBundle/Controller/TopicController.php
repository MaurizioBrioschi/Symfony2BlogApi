<?php

namespace ridesoft\TopicsApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Util\Codes;
use ridesoft\TopicsApiBundle\Form\TopicType;

use ridesoft\TopicsApiBundle\Entity\Topic;

/**
 * Topic controller.
 *
 */
class TopicController extends FOSRestController
{

    /**
     * Lists all articles entities.
     * @return array
     */
    public function getTopicsAction()
    {
        try{
            $em = $this->getDoctrine()->getManager();
            $topic = new Topic($em->getConnection());

            return array("topics"=>$topic->getAll());
        }catch (DBALException $e){
            return Codes::HTTP_SERVICE_UNAVAILABLE;
        }

    }

    /**
     *
     * @param $id
     * @return array
     * @internal param $id
     */
    public function getTopicAction($id)   {
        try{
            $em = $this->getDoctrine()->getManager();
            $topic = new Topic($em->getConnection());

            return array('topic'=>$topic->get($id));
        }catch (DBALException $e){
            return Codes::HTTP_SERVICE_UNAVAILABLE;
        }
    }

    public function getTopicsArticlesAction($id)    {
        try{
            $em = $this->getDoctrine()->getManager();
            $topic = new Topic($em->getConnection());

            return array('topic'=>$topic->getTopicWithArticles($id));
        }catch (DBALException $e){
            return Codes::HTTP_SERVICE_UNAVAILABLE;
        }
    }
    /**
     * @param Request $request
     */
    public function postTopicsAction(Request $request){
        try{
            $em = $this->getDoctrine()->getManager();

            $topic = new Topic($em->getConnection());
            $form = $this->createForm(new TopicType(), $topic, array(
                'method' => 'POST',
                'csrf_protection' => false
            ));

            $parameters  = $request->request->all();
            $form->submit($parameters);
            if($form->isValid())    {
                $topic = $form->getData();
                $topic->insert();
                $routeOptions = array(
                    'id' => $topic->getId(),
                    '_format' => 'json'
                );

                return $this->routeRedirectView('get_topic', $routeOptions, Codes::HTTP_CREATED);
            }else{
                return Codes::HTTP_BAD_REQUEST;
            }
        }catch (DBALException $e){
            return Codes::HTTP_SERVICE_UNAVAILABLE;
        }
    }

    /**
     * @param $id
     */
    public function deleteTopicsAction($id)   {
        try{
            $em = $this->getDoctrine()->getManager();

            $topic = new Topic($em->getConnection());
            $topic->delete($id);
            return Codes::HTTP_ACCEPTED;
        }catch (DBALException $e){
            return Codes::HTTP_SERVICE_UNAVAILABLE;
        }
    }
}
