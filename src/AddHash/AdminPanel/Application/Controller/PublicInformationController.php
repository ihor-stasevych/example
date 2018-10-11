<?php

namespace App\AddHash\AdminPanel\Application\Controller;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\AddHash\System\GlobalContext\Common\BaseServiceController;

class PublicInformationController extends BaseServiceController
{
	/**
	 * @SWG\Response(
	 *     response=200,
	 *     description="ReCaptcha public key"
	 * )
	 *
	 * @SWG\Tag(name="Information")
	 * @return JsonResponse
	 */
	public function getPublicKeyReCaptcha()
	{
	    $key = getenv('RE_CAPTCHA_PUBLIC_KEY');
		$key = (false !== $key) ? $key : '';

        return $this->json(['key' => $key]);
	}
}