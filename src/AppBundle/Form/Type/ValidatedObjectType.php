<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use AppBundle\Form\DataTransformer\ViewObjectTransformer;
use AppBundle\Entity\ValidatedObject;

/**
 * Test form validation type
 * 
 * @author Charles J. C. Elling <charles@denumeris.com>
 *
 */
class ValidatedObjectType extends AbstractType {
	/**
	 * 
	 * @var RegistryInterface $doctrine
	 */
	protected $doctrine;
	
	public function __construct(RegistryInterface $doctrine){
		$this->doctrine = $doctrine;
	}
	
	public function getName() {
		return 'validated_object';
	}
	
	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults(array(
				'data_class' => null,
				'use_data_transformer' => true,
				'error_bubbling' => false,
				'deep'=> 1
		));
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options){
		if($options['use_data_transformer']){
			$builder->addViewTransformer(new ViewObjectTransformer($this->doctrine->getRepository('AppBundle\Entity\ValidatedObject')));
			$builder->add(
					'id',
					'hidden',
					array(
							'required' => false
			
					));
		}
		
		$builder->add(
				'property1', 
				'text', 
				array(
						'required' => false
						
				));
		$builder->add(
				'property2', 
				'text', 
				array(
						'required' => false
						
				));
		$deep = $options['deep'];
		if($deep > 0){
			$deep--;
			$builder->add(
					'childObjects', 
					'collection', 
					array(
							'type' => $this->getName(),
							'options' => array(
									'use_data_transformer'=>true,
									'data_class' => null,
									'deep'=>$deep,
									'label'=>false
							),
							'required'  => false,
							'allow_add' => true,
							'allow_delete' => true,
							'by_reference' => false,
							'error_bubbling'=> false,
							'prototype_name'=>"__child_{$deep}__"
					));
		}
		
		
		
	}
}