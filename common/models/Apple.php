<?php

namespace common\models;

use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "apple".
 *
 * @property integer $id
 * @property string $color
 * @property integer $created_at
 * @property integer $falled_at
 * @property int $status
 * @property decimal $size
 */
class Apple extends \yii\db\ActiveRecord
{
    const STATUS_ON_TREE = 1;
    const STATUS_FALLED_TO_GROUND = 2;
    const STATUS_SPOILED = 3;

    static public $statuses = [
        self::STATUS_ON_TREE => 'On Tree',
        self::STATUS_FALLED_TO_GROUND => 'Falled to ground',
        self::STATUS_SPOILED => 'Spoiled',


    ];

    public function __construct($color = null)
    {
        $this->status = static::STATUS_ON_TREE;
        $this->size = 1.0;
        $this->created_at = mt_rand(time() - 12*3600, time());
        $this->color = $color ?: sprintf('#%06X', mt_rand(0, 0xFFFFFF));

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%apple}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'falled_at'], 'integer'],
            [['status'], 'in','range'=>array_keys(static::$statuses)],
            [['size'], 'number'],
            [['color'], 'string', 'max' => 50],
        ];
    }

    public function afterFind()
    {
        if ($this->status == static::STATUS_FALLED_TO_GROUND && time() - $this->falled_at > 5*3600){
            $this->status = static::STATUS_SPOILED;
        }
        parent::afterFind();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color' => 'Color',
            'created_at' => 'Created time',
            'fall_at' => 'Falled time',
            'status' => 'Status',
            'size' => 'Size',
        ];
    }

    /**
     * eat
     *
     * @param int $percent
     *
     * @return float
     * @throws Exception
     */
    public function eat(int $percent)
    {
        if (($res = $this->canEat()) !== true){
            throw new Exception($res);
        }

        if ($percent < 1 || $percent > 100){
            throw new Exception('Bad percent');
        }


        $this->size -= $percent/100;
        $this->size = max(0, $this->size);

        return $this->size;
    }

    /**
     * Check about eating apple
     *
     * @return string|bool
     */
    function canEat()
    {
        if ($this->status == static::STATUS_ON_TREE){
            return 'Apple on tree. Can`t be eated';
        }

        return true;
    }

    /**
     * fallToGround
     *
     * @return void
     * @throws Exception
     */
    public function fallToGround()
    {
        if ($this->status != static::STATUS_ON_TREE){
            throw new Exception('Status error');
        }

        $this->falled_at = time();
        $this->status = self::STATUS_FALLED_TO_GROUND;
    }
}