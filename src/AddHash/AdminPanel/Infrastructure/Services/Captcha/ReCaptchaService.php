<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Captcha;

use App\AddHash\AdminPanel\Domain\Captcha\Services\ReCaptchaServiceInterface;
use App\AddHash\AdminPanel\Domain\Captcha\Commands\ReCaptchaCommandInterface;
use App\AddHash\AdminPanel\Domain\Captcha\Services\ShowCaptchaServiceInterface;
use App\AddHash\AdminPanel\Domain\Captcha\Exceptions\ReCaptchaCheckFailedException;
use App\AddHash\AdminPanel\Domain\Captcha\Exceptions\ReCaptchaNoEnvPrivateKeyErrorException;
use App\AddHash\AdminPanel\Domain\Captcha\Exceptions\ReCaptchaInvalidInputParamErrorException;

class ReCaptchaService implements ReCaptchaServiceInterface
{
    private const URL = 'https://www.google.com/recaptcha/api/siteverify';


    private $showCaptchaService;

    public function __construct(ShowCaptchaServiceInterface $showCaptchaService)
    {
        $this->showCaptchaService = $showCaptchaService;
    }

    /**
     * @param ReCaptchaCommandInterface $command
     * @return bool
     * @throws ReCaptchaCheckFailedException
     * @throws ReCaptchaInvalidInputParamErrorException
     * @throws ReCaptchaNoEnvPrivateKeyErrorException
     */
    public function execute(ReCaptchaCommandInterface $command): bool
    {
        $isShow = $this->showCaptchaService->execute(
            $command->getIp(),
            $command->getUserAgent()
        );

        if (false === $isShow) {
            return true;
        }

        $response = $command->getResponse();

        if (true === empty($response)) {
            throw new ReCaptchaInvalidInputParamErrorException('Invalid input param');
        }

        $secretKey = getenv('RE_CAPTCHA_SECRET_KEY');

        if (false === $secretKey) {
            throw new ReCaptchaNoEnvPrivateKeyErrorException('No private key');
        }

        $data = [
            'secret'   => $secretKey,
            'response' => $response,
        ];

        $ch = curl_init(static::URL);
        $postString = http_build_query($data, '', '&');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        if (null === $response || !$response) {
            throw new ReCaptchaCheckFailedException('Invalid response');
        }

        $response = json_decode($response);

        if (true === empty($response->success) || false == $response->success) {
            throw new ReCaptchaCheckFailedException('Check status is failed');
        }

        return true;
    }
}