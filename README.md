# YII2 Encrypter

兼容 `mcrypt_encrypt`及 `openssl_encrypt`

[![Latest Stable Version](https://poser.pugx.org/bestyii/yii2-encrypter/v/stable)](https://packagist.org/packages/bestyii/yii2-encrypter)
[![Total Downloads](https://poser.pugx.org/bestyii/yii2-encrypter/downloads)](https://packagist.org/packages/bestyii/yii2-encrypter)
[![License](https://poser.pugx.org/bestyii/yii2-encrypter/license)](https://packagist.org/packages/bestyii/yii2-encrypter)

## 安装

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist bestyii/yii2-encrypter "*"
```

or add

```
"bestyii/yii2-encrypter": "*"
```

to the require section of your `composer.json` file.

### 配置

配置文件中加入

```php
return [
    //...
    'components' => [
        //...
        'encrypter' => [
            'class' => 'bestyii\encrypter\Encrypter',
            'key' => '32bit string',
            'iv' => '32bit string',
        ],
    ],
];
```

## 如何使用

### 手动

You can now use the encrypter manually in any part of the application to either encrypt a string

```php
\Yii::$app->encrypter->encrypt('string to encrypt');
```

or decrypt and encrypted string

```php
\Yii::$app->encrypter->decrypt('string to decrypt');
```

### 使用Behavior自动加密/解密

The extension also comes with a behavior that you can easily attach to any ActiveRecord Model.

Use the following syntax to attach the behavior.

```php
public function behaviors()
{
    return [
        'encryption' => [
            'class' => '\bestyii\encrypter\EncrypterBehavior',
            'attributes' => [
                'attributeName',
                'otherAttributeName',
            ],
        ],
    ];
}
```

The behavior will automatically encrypt all the data before saving it on the database and decrypt it after the retrieve.

Keep in mind that the behavior will use the current configuration of the extension for the encryption.