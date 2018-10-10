<?php

namespace App\AddHash\System\Lib\Captcha\ReCaptcha;

class Captcha implements CaptchaInterface
{
    private const URL = 'https://www.google.com/recaptcha/api/siteverify';

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