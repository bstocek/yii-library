<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "library_reservations".
 *
 * @property integer $id
 * @property integer $book_id
 * @property integer $user_id

 */
class Reserve extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'library_reservations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['id', 'book_id', 'user_id'], 'required'],
            [['id', 'book_id', 'user_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_id' => 'Book ID',
            'user_id' => 'User ID',
        ];
    }
}
