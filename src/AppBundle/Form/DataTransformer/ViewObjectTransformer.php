<?php

namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\ValidatedObject;

/**
 * Transforms Object to array for view
 *
 * @author Charles J. C. Elling <charles@denumeris.com>
 */
class ViewObjectTransformer implements DataTransformerInterface {
	
	/**
	 * 
	 * @var EntityRepository $repository
	 */
	protected $repository;
	
	/**
	 * 
	 * @param EntityRepository $repository
	 * @param string $dataClass
	 */
	public function __construct( EntityRepository $repository) {
		$this->repository = $repository;
	}
	public function transform($object) {
		if($object === null) {
			return null;
		}else if($object instanceof ValidatedObject) {
			$viewData = array(
					'id'=> $object->getId(),
					'property1'=> $object->getProperty1(),
					'property2'=> $object->getProperty2(),
					'childObjects'=> $object->getChildObjects()
			);
			return $viewData;
		}else {
			if(is_object($object)) {
				$type = get_class($object);
			}else {
				$type = gettype($object);
			}
			throw new TransformationFailedException("Expected an {$this->dataClass} got {$type}");
		}
	}
	public function reverseTransform($viewData) {
		if ($viewData === null) {
			$viewData = array();
		}
		if(is_array($viewData) || $viewData instanceof \Traversable) {
			$id = null;
			if(isset($viewData['id'])){
				$id = $viewData['id'];
				unset($viewData['id']);
			}
			if(empty($id)){
				$object = new ValidatedObject();
			}else{
				$object = $this->repository->find($id);
				if(!($object instanceof ValidatedObject)){
					throw new TransformationFailedException("Entity not found");
				}
			}
			foreach ($viewData as $name => $value) {
				switch ($name){
					case 'property1':
						$object->setProperty1($value);
						break;
					case 'property2':
						$object->setProperty2($value);
						break;
					case 'childObjects':
						foreach ($value as $child){
							$object->addChildObject($child);
						}
						break;
					default:
						break;
				}
			}
			return $object;
		} else {
			if (is_object($viewData)) {
				$type = get_class($viewData);
			} else {
				$type = gettype($viewData);
			}
			throw new TransformationFailedException("Expected an array got {$type}");
		}
	}
}