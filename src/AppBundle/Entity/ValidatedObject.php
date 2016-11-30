<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Test object for validation
 * @author Charles J. C. Elling <charles@denumeris.com>
 * 
 * @ORM\Entity()
 * @ORM\Table(
 * 	name="validated_object"
 * )
 **/
class ValidatedObject {
	
	/**
	 * Id
	 *
	 * @var integer
	 *
	 * @ORM\Column(type="integer", nullable=false, unique=true)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	protected $id;
	
	/**
	 * @var string $property1
	 * @ORM\Column(
	 * 	type="string",
	 *  nullable=true
	 * )
	 * @Assert\NotBlank()
	 **/
	protected $property1;
	
	/**
	 * @var string $property2
	 * @ORM\Column(
	 * 	type="string",
	 *  nullable=true
	 * )
	 * @Assert\NotBlank()
	 **/
	protected $property2;
	
	/**
	 * @var ValidatedObject[]|Collection
	 * @ORM\OneToMany(
	 * 	targetEntity="ValidatedObject",
	 *  mappedBy="parentObject",
	 *  cascade={"all"},
	 *  orphanRemoval=true
	 * )
	 * @Assert\Valid()
	 **/
	protected $childObjects;
	
	/**
	 * 
	 * @var ValidatedObject
	 * @ORM\ManyToOne(
	 * 	targetEntity="ValidatedObject",
	 * 	inversedBy="childObjects"
	 * )
	 * @ORM\JoinColumn(
	 * 	name="parent_object_id",
	 *  referencedColumnName="id",
	 * 	nullable=true,
	 * 	onDelete="CASCADE"
	 * )
	 */
	protected $parentObject;
	
	/**
	 * 
	 * @param number $deep
	 */
	public function __construct($deep=0){
		$this->childObjects = new ArrayCollection();
		if($deep > 0){
			$deep--;
			$this->childObjects->add(new ValidatedObject($deep));
			$this->childObjects->add(new ValidatedObject($deep));
		}
	}
	
	/**
	 * 
	 * @return number
	 */
	public function getId() {
		return $this->id;
	}
	/**
	 * 
	 * @return string
	 */
	public function getProperty1() {
		return $this->property1;
	}
	/**
	 * 
	 * @param string $property1
	 * @return ValidatedObject
	 */
	public function setProperty1($property1) {
		$this->property1 = $property1;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function getProperty2() {
		return $this->property2;
	}
	/**
	 * 
	 * @param string $property2
	 * @return ValidatedObject
	 */
	public function setProperty2($property2) {
		$this->property2 = $property2;
		return $this;
	}
	/**
	 * 
	 * @return ValidatedObject[]|Collection
	 */
	public function getChildObjects() {
		return $this->childObjects;
	}
	
	/**
	 * 
	 * @param ValidatedObject $childObject
	 * @return ValidatedObject
	 */
	public function addChildObject(ValidatedObject $childObject) {
		if(!$this->childObjects->contains($childObject)){
			$childObject->setParentObject($this);
			$this->childObjects->add($childObject);
		}
		return $this;
	}
	
	/**
	 *
	 * @param ValidatedObject $childObject
	 * @return ValidatedObject
	 */
	public function removeChildObject(ValidatedObject $childObject) {
		if($this->childObjects->contains($childObject)){
			$this->childObjects->removeElement($childObject);
		}
		return $this;
	}
	/**
	 * 
	 * @return ValidatedObject
	 */
	public function getParentObject() {
		return $this->parentObject;
	}
	/**
	 * 
	 * @param ValidatedObject $parentObject
	 * @return ValidatedObject
	 */
	public function setParentObject(ValidatedObject $parentObject) {
		$this->parentObject = $parentObject;
		return $this;
	}
}