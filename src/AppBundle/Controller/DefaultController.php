<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration AS CONFIG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ValidatedObject;

class DefaultController extends Controller
{
    /**
     * @CONFIG\Route("/", name="homepage")
     */
    public function indexAction(Request $request){
    	
    	$repository = $this->getDoctrine()->getRepository('AppBundle\Entity\ValidatedObject');
    	$qb = $repository->createQueryBuilder('o');
    	$qb->select('o')
    	   ->leftJoin('o.parentObject', 'p')
    	   ->addSelect("COALESCE(p.id,o.id) AS HIDDEN myOrderByParent")
    	   ->orderBy('myOrderByParent')
    	   ->addGroupBy('o.id');
    	$query = $qb->getQuery();
    	$objects = $query->getResult();
    	
        // replace this example code with whatever you need
        $parameters = array(
        		'title' => 'Example list',
        		'objects' => $objects
        );
        return $this->render('AppBundle:Default:index.html.twig',$parameters);
    }
    
    /**
     * @CONFIG\Route("/edit/{id}", name="edit")
     */
    public function editAction($id,Request $request){
    	$doctrine = $this->getDoctrine();
    	$new = null;
    	$object = null;
    	if(empty($id)){
    		$object = new ValidatedObject(2);
    		$new = true;
    	}else{
    		$repository = $this->getDoctrine()->getRepository('AppBundle\Entity\ValidatedObject');
    		$object = $repository->find($id);
    		if($object instanceof ValidatedObject){
    			$new = false;
    		}else{
    			throw $this->createNotFoundException();
    		}
    	}
    	$form = $this->createForm(
    			'validated_object',
    			$object,
    			array(
    					'use_data_transformer' => false,
    					'data_class' => 'AppBundle\Entity\ValidatedObject',
    					'deep'=> 2
    			));
    	$form->add('save','submit');
    	$form->handleRequest($request);
    	if($form->isSubmitted()){
    		if($form->isValid()){
    			$mangager = $doctrine->getManager();
    			$mangager->persist($object);
    			$mangager->flush();
    			$this->addFlash('info', 'Object saved');
    			return $this->redirectToRoute('homepage');
    		}
    	}
    	$parameters = array(
    			'title' => $new ? 'Example add':'Example edit',
    			'form' => $form->createView()
    	);
    	return $this->render('AppBundle:Default:edit.html.twig',$parameters);
    }
    
    /**
     * @CONFIG\Route("/add", name="add")
     */
    public function addAction(Request $request){
    	return $this->editAction(null, $request);
    }
}
