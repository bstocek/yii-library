<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "library_books".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $author
 * @property int|null $borrowed
 * @property int|null $reserved
 */
class BooksCrud extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'library_books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['borrowed', 'reserved'], 'integer'],
            [['title', 'author'], 'string', 'max' => 130],
        ];
    }

    /**
     * {@inheritdoc}
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
