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
     * Lists all topics
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
     * Get the topic with $id
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

    /**
     * List the topic with $id with all  its articles related
     * @param $id
     * @return array|int
     * @throws \Exception
     * @throws \ridesoft\TopicsApiBundle\Entity\DBALException
     */
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
     * Post a new Topic
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
     * Delete the topic with $id
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
