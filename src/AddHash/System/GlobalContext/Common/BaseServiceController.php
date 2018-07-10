<?php

namespace App\AddHash\System\GlobalContext\Common;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseServiceController
{
	/** @var SerializerInterface */
	protected $serializer;

	/** @var ValidatorInterface */
	private $validator;

	private $validationErrors = [];

	/**
	 * @var array
	 *
	 * Keys are action names (method names)
	 * Value can be one of:
	 * - PERMISSION::XXX Constant
	 * - Array of PERMISSION::XXX Constants. Meaning OR (At least one of listed permission)
	 * - Callback method name (must be implemented in controller). The only argument $permissions: array of current user permission
	 * - '@' means 'Any logged user' (Permission::ANY_LOGGED_USER constant)
	 */
	protected $permissions = [];

	/**
	 * @param ValidatorInterface $validator
	 */
	public function setValidator(ValidatorInterface $validator)
	{
		$this->validator = $validator;
	}

	/**
	 * @param SerializerInterface $serializer
	 */
	public function setSerializer(SerializerInterface $serializer)
	{
		var_dump(123123);
		$this->serializer = $serializer;
	}


	public function json($data, $status = 200, $headers = [], $context = [])
	{
		if ($this->serializer) {
			$json = $this->serializer->serialize($data, 'json', array_merge([
				'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
			], $context));

			return new JsonResponse($json, $status, $headers, true);
		}

		echo 'NIXUYA';

		return new JsonResponse($data, $status, $headers);
	}

	/**
	 * @param $command
	 * @return bool
	 */
	protected function commandIsValid($command): bool
	{
		$errors = $this->validator->validate($command);

		if (count($errors) > 0) {
			$this->validationErrors = $errors;
			return false;
		}

		return true;
	}

	/**
	 * @return array
	 */
	public function getLastValidationErrors(): array
	{
		return $this->validationErrors;
	}
}