<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "library_books".
 *
 * @property integer $id
 * @property string $author
 * @property string $title
 * @property integer $borrowed
 * @property integer $reserved

 */
class Books extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'library_books';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['borrowed', 'reserved'], 'integer'],
            [['title', 'author'], 'string', 'max' => 130],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Titul',
            'author' => 'Autor',
            'borrowed' => 'Půjčeno',
            'reserved' => 'Rezervováno',
        ];
    }
}
