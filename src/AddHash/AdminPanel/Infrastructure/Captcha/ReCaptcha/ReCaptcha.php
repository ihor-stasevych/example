<?php

namespace App\AddHash\AdminPanel\Infrastructure\Captcha\ReCaptcha;

use App\AddHash\AdminPanel\Domain\Captcha\ReCaptcha\ReCaptchaInterface;

class ReCaptcha implements ReCaptchaInterface
{
    private const URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * @param null|string $response
     * @return bool
     */
    public function isVerify(?string $response): bool
    {
        if (true === empty($response)) {
            return false;
        }

        $secretKey = getenv('RE_CAPTCHA_SECRET_KEY');

        if (false === $secretKey) {
            return false;
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
            return false;
        }

        $response = json_decode($response);

        if (true === empty($response->success) || false == $response->success) {
            return false;
        }

        return true;
    }
}