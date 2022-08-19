<?php

namespace bestyii\encrypter;

use yii\base\Event;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;

/**
 * This Behavior is used to encrypt data before storing it on the database
 * and to decrypt it upon retrieval.
 *
 * To attach this behavior to an ActiveRecord add the following code
 * ```php
 *
 * public function behaviors()
 *  {
 *      return [
 *          'encryption' => [
 *              'class' => '\bestyii\encrypter\EncrypterBehavior',
 *              'attributes' => [
 *                  'attribute1',
 *                  'attribute2',
 *              ],
 *          ],
 *      ];
 *  }
 * ```
 *
 * @author ezsky <ez@bestyii.com>
 * @version 1.0
 */
class EncrypterBehavior extends Behavior
{
    public $attributes = [];

    /**
     * Adds to the behavior the listeners for the following events:
     * AFTER_FIND
     * BEFORE_INSERT
     * BEFORE_UPDATE
     * AFTER_INSERT
     * AFTER_UPDATE
     *
     * @return array
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_AFTER_FIND => 'decryptAllAttributes',
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'encryptAllAttributes',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'encryptAllAttributes',
            BaseActiveRecord::EVENT_AFTER_INSERT => 'decryptAllAttributes',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'decryptAllAttributes',
        ];
    }

    /**
     * Decrypts all the listed attributes by the ActiveRecord in the behavior
     * configuration.
     *
     * @param Event $event
     */
    public function decryptAllAttributes(Event $event)
    {
        foreach ($this->attributes as $attribute) {
            $this->decryptValue($attribute);
        }
    }

    /**
     * Encrypts all the listed attributes by the ActiveRecord in the behavior
     * configuration.
     *
     * @param Event $event
     */
    public function encryptAllAttributes(Event $event)
    {
        foreach ($this->attributes as $attribute) {
            $this->encryptValue($attribute);
        }
    }

    /**
     * Decrypts the value of the given attribute.
     *
     * @param string $attribute the attribute name
     */
    private function decryptValue($attribute)
    {

        $this->owner->$attribute = $this->getEncrypter()->decrypt($this->owner->$attribute);

    }

    /**
     * Encrypts the value of the given attribute.
     *
     * @param string $attribute the attribute name
     */
    private function encryptValue($attribute)
    {
        $this->owner->$attribute = $this->getEncrypter()->encrypt($this->owner->$attribute);
    }

    /**
     * Returns the Encrypter component used by the behavior.
     *
     * @return Encrypter
     * @throws InvalidConfigException
     */
    private function getEncrypter()
    {
        try {
            return \Yii::$app->encrypter;
        } catch (\Exception $exc) {
            throw new InvalidConfigException('Encrypter component not enabled.');
        }
    }
}
