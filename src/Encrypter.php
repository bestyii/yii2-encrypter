<?php

namespace bestyii\encrypter;


use yii\base\Component;
use yii\base\InvalidConfigException;

class Encrypter extends Component
{

    public $key;
    public $iv;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if ($this->key === null) {
            throw new InvalidConfigException('The "key" property must be set.');
        }
        if ($this->iv === null) {
            throw new InvalidConfigException('The "iv" property must be set.');
        }

        if (strlen($this->key) !== 32) {
            throw new InvalidConfigException('The "key" property must be 32 bit.');
        }

        if (strlen($this->iv) !== 32) {
            throw new InvalidConfigException('The "iv" property must be 32 bit.');
        }

        parent::init();
    }

    /**
     * 加密
     * @param string $str 需要加密的字符串
     * @return string  加密好的字符串
     */
    public function encrypt($str)
    {

        $key = pack("H*", $this->key);
        $iv = pack("H*", $this->iv);

        if (function_exists("mcrypt_encrypt")) {
            $str = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $str, MCRYPT_MODE_CBC, $iv);
        } else {
            if (strlen($str) % 16) {
                $str = str_pad($str, strlen($str) + 16 - strlen($str) % 16, "\0");
            }

            $str = openssl_encrypt($str, 'AES-128-CBC', $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, $iv);
        }

        return base64_encode($str);
    }

    /**
     * 解密
     * @param string $str 需要解密的字符串
     * @return string 解密后的字符串
     */
    public function decrypt($str)
    {
        $key = pack("H*", $this->key);
        $iv = pack("H*", $this->iv);
        $str = base64_decode($str);

        if (function_exists("mcrypt_decrypt")) {
            $str = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $str, MCRYPT_MODE_CBC, $iv);
        } else {
            $str = openssl_decrypt($str, 'AES-128-CBC', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
        }

        return rtrim($str);
    }
}
