<?php

namespace ridesoft\TopicsApiBundle\Controller;

use Doctrine\DBAL\DBALException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Util\Codes;
use ridesoft\TopicsApiBundle\Entity\Article;
use ridesoft\TopicsApiBundle\Form\ArticleType;

/**
 * Article controller.
 */
class ArticleController extends FOSRestController
{

    /**
     * Lists all articles.
     * @return array
     */
    public function getArticlesAction()
    {
        try{
            $em = $this->getDoctrine()->getManager();
            $article = new Article($em->getConnection());

            return array("articles"=>$article->getAll());
        }catch (DBALException $e){
            return Codes::HTTP_SERVICE_UNAVAILABLE;
        }

    }

    /**
     * List the article with $id
     * @param $id
     * @return array
     * @internal param Article $article
     */
    public function getArticleAction($id)   {
        try{
            $em = $this->getDoctrine()->getManager();
            $article = new Article($em->getConnection());

            return array('article'=>$article->get($id));
        }catch (DBALException $e){
            return Codes::HTTP_SERVICE_UNAVAILABLE;
        }
    }

    /**
     * Post an article
     * @param Request $request
     */
    public function postArticleAction(Request $request){
        try{
            $em = $this->getDoctrine()->getManager();

            $article = new Article($em->getConnection());
            $form = $this->createForm(new ArticleType(), $article, array(
                'method' => 'POST',
                'csrf_protection' => false
            ));

            $parameters  = $request->request->all();
            $form->submit($parameters);
            if($form->isValid())    {
                $article = $form->getData();
                $article->insert();
                $routeOptions = array(
                    'id' => $article->getId(),
                    '_format' => 'json'
                );

                return $this->routeRedirectView('get_article', $routeOptions, Codes::HTTP_CREATED);
            }else{
                return Codes::HTTP_BAD_REQUEST;
            }
        }catch (DBALException $e){
            return Codes::HTTP_SERVICE_UNAVAILABLE;
        }
    }

    /**
     * Delete the article with $id
     * @param Article $article
     */
    public function deleteArticleAction($id)   {
        try{
            $em = $this->getDoctrine()->getManager();

            $article = new Article($em->getConnection());
            $article->delete($id);
            return Codes::HTTP_ACCEPTED;
        }catch (DBALException $e){
            return Codes::HTTP_SERVICE_UNAVAILABLE;
        }
    }


}
