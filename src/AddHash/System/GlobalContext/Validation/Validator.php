<?php

namespace App\AddHash\System\GlobalContext\Validation;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\MetadataInterface;
use Symfony\Component\Validator\Validator\ContextualValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\NoSuchMetadataException;
use Symfony\Component\Validator\Validation;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Validator
 * @package Orangear\System\GlobalContext\Validator
 */
class Validator implements ValidatorInterface
{
	/**
	 * @var \Symfony\Component\Validator\Validator\ValidatorInterface
	 */
	private $validator;


	public function __construct()
	{
		$this->validator = Validation::createValidatorBuilder()
			->enableAnnotationMapping()
			//->setTranslator($container->get('translator'))
			->getValidator()
		;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getMetadataFor($value)
	{
		return $this->validator->getMetadataFor($value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasMetadataFor($value)
	{
		return $this->validator->hasMetadataFor($value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function validate($value, $constraints = null, $groups = null)
	{
		/** @var \Symfony\Component\Validator\ConstraintViolation[] $violations*/
		$violations =  $this->validator->validate($value, $constraints, $groups);
		$list = [];
		foreach ($violations as $key => $violation) {
			$list[$violation->getPropertyPath()][] = $violation->getMessage();
		}

		return $list;
	}

	/**
	 * {@inheritdoc}
	 */
	public function validateProperty($object, $propertyName, $groups = null)
	{
		return $this->validator->validateProperty($object, $propertyName, $groups);
	}

	/**
	 * {@inheritdoc}
	 */
	public function validatePropertyValue($objectOrClass, $propertyName, $value, $groups = null)
	{
		return $this->validator->validatePropertyValue($objectOrClass, $propertyName, $value, $groups);
	}

	/**
	 * {@inheritdoc}
	 */
	public function startContext()
	{
		return $this->validator->startContext();
	}

	/**
	 * {@inheritdoc}
	 */
	public function inContext(ExecutionContextInterface $context)
	{
		return $this->validator->inContext($context);
	}

}