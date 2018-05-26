<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Class VerificationCode.
 */
class VerificationCode
{
    const KEY_TEMPLATE = 'verify_code_of_%s';

    /**
     * 创建并存储验证码
     *
     * @param string $phone
     *
     * @return int
     */
    public function create($phone)
    {
        $code = mt_rand(1000, 9999);

        \Log::debug("验证码:{$phone}:{$code}");

        Cache::put(sprintf(self::KEY_TEMPLATE, $phone), $code, 10);

        if (app()->environment('production')) {
            app('sms')->send($phone, [
                    'content' => "您的验证码是：{$code}，10 分钟内有效，如非本人操作，请忽略本短信",
                    'code' => $code,
                ]);
        }

        return $code;
    }

    /**
     * 检查手机号与验证码是否匹配.
     *
     * @param string $phone
     * @param int    $code
     *
     * @return bool
     */
    public function validate($phone, $code)
    {
        if (empty($phone) || empty($code)) {
            return false;
        }

        $key = sprintf(self::KEY_TEMPLATE, $phone);

        $cachedCode = Cache::get($key);

        \Log::debug('cached verify code', ['key' => $key, 'cached' => $cachedCode, 'input' => $code]);

        return strval($cachedCode) === strval($code);
    }
}
