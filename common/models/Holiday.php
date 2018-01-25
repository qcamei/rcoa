<?php

namespace common\models;

use common\utils\Lunar;
use Yii;
use yii\caching\Cache;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%holiday}}".
 *
 * @property string $id                 ID
 * @property string $name               节假日名称
 * @property integer $type              类型：1节假日，2公众节日，3自定义节日
 * @property string $year               所属年份
 * @property string $date               日期
 * @property string $des                描述
 * @property integer $is_publish        是否发布：0否1是
 * @property integer $is_lunar          是否是阴历：0否1是
 * @property string $created_at
 * @property string $updated_at
 */
class Holiday extends ActiveRecord {

    const cacheKey = "holiday";
    const TYPE_MAP = [
        1 => "法定节假日",
        2 => "公众节日",
        3 => "自定义假日",
    ];
    
    const TYPE_NAME_MAP = [
        1 => "假",
        2 => "节",
        3 => "补",
    ];

    /* @var $cache Cache */

    private static $cache;

    /**
     * 所有节日
     * @var array ([id,name,type,date,is_hunar,year,des])
     */
    private static $holidays;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%holiday}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['type', 'is_publish', 'is_lunar', 'created_at', 'updated_at'], 'integer'],
            [['year'], 'safe'],
            [['name', 'date'], 'string', 'max' => 50],
            [['des'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'year' => Yii::t('app', 'Holiday Year'),
            'date' => Yii::t('app', 'Holiday Date'),
            'des' => Yii::t('app', 'Des'),
            'is_publish' => Yii::t('app', 'Is Publish'),
            'is_lunar' => Yii::t('app', 'Is Lunar'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    /**
     * 初始化缓存
     */
    static private function initializeCache() {
        if (!self::$cache || !self::$holidays) {
            self::$cache = Instance::ensure([
                        'class' => 'yii\caching\FileCache',
                        'cachePath' => Yii::getAlias('@frontend') . '/runtime/cache'
                            ], Cache::className());
            $data = self::$cache->get(self::cacheKey);

            if ($data != null) {
                self::$holidays = $data;
            } else {
                self::createCache();
            }
        }
    }

    /**
     * 创建缓存
     */
    static private function createCache() {
        $results = (new Query())
                ->from(Holiday::tableName())
                ->select(['id', 'name', 'type', 'year', 'date', 'des', 'is_lunar'])
                ->where(['is_publish' => 1])
                ->all();
        $holidays = [];
        foreach ($results as $holiday) {
            if ($holiday['type'] == 2) {
                $holidays [] = $holiday;
            } else {
                $holidays = array_merge($holidays, self::getRangeDate($holiday));
            }
        }
        self::$holidays = $holidays;
        self::$cache->set(self::cacheKey, $holidays);
    }

    /**
     * 分析时间段假日，生成每天的假日
     * @param array $holiday
     * @return array
     */
    static private function getRangeDate($holiday) {
        $days = explode(' - ', $holiday['date']);
        $days = Lunar::getDaysBetweenSolar2($days[0], $days[1], "");
        $result = [];
        foreach ($days as $day) {
            $newHoliday = $holiday;
            $newHoliday['date'] = $day;
            $result [] = $newHoliday;
        }
        return $result;
    }

    /**
     * 清除缓存
     */
    static public function invalidateCache() {
        if (!self::$cache) {
            self::initializeCache();
        }
        self::$cache->delete(self::cacheKey);
        self::$holidays = null;
    }

    /**
     * 获取两个日期之间所有假日 <br/>
     * 
     * 法定假日: <br/>
     *      指国家规则的假日,每年放假日期示情况而定，必须带年份<br/>
     *      type = 1,   日期格式 = 20170102 - 20170105  <br/>
     * 公众假日：<br/>
     *      指公众记念日，大多数以公历假日（不带年份），小部分每年不同如‘母亲节’、‘父亲节’、‘感因节’等这些在每年的几月第几周的周末，所以需要带年份<br/>
     *      type = 2,   日期格式 = 0102 <br/>
     * 自定久假日：<br/>
     *      特殊情况，需要自行添加的假日，如法定假日时的‘补班’，或者公司‘晚会’等    <br/>
     *      type = 3,   日期格式 = 20170102 - 20170105  <br/>
     * 
     * 
     * @param string|integer $start     起始日期 20120111或者2012-01-11或者时间戳
     * @param string|integer $end       结束日期 20120113或者2012-01-13或者时间戳
     * @return array 
     * 
     */
    static public function getHolidayBetween($start, $end) {
        self::initializeCache();

        $dt_start   = is_int($start) ? $start : strtotime($start);
        $dt_end     = is_int($end) ? $end : strtotime($end);

        $start_year =   date('Y', $dt_start);
        $end_year   =   date('Y', $dt_end);
        //转换起始年份为农历年份
        $start_lunar_year = Lunar::convertSolarToLunar($start_year, date('m', $dt_start), date('d', $dt_start))[0];
        $end_lunar_year   = Lunar::convertSolarToLunar($end_year, date('m', $dt_end), date('d', $dt_end))[0];
        
        $result           = [];
        $newHolidays      = [];
        foreach (self::$holidays as $holiday) {
            if ($holiday['is_lunar'] != 1) {
                if (((integer) $holiday['year']) > 0) {
                    //已设置年份的假日直接匹配
                    //法定假日和自定义假日都为公历，只要在时间内即为匹配
                    if($holiday['type'] == 2){
                        //有年份并且重复的假日只有月份和日期，必须加上年份
                        $holiday['date'] = $holiday['year'].$holiday['date'];
                    }
                    $newHolidays [] = $holiday;
                } else {
                    //重复的节日，需要加上年份再匹配
                    $newHolidays [] = self::covertRepeatHoliday($holiday, $start_year);
                    //起始与结束年份不同，需要分开匹配
                    if ($start_year != $end_year) {
                        $newHolidays [] = self::covertRepeatHoliday($holiday, $end_year);
                    }
                }
            } else {
                //农历假日可能存在润年情况，需要特殊处理
                //转换农历假期为$start年的公历
                $newHolidays = array_merge($newHolidays, self::convertLunarToSolar($holiday, $start_lunar_year));
                if ($start_lunar_year != $end_lunar_year) {
                    //转换农历假期为$end年的公历
                    $newHolidays = array_merge($newHolidays, self::convertLunarToSolar($holiday, $end_lunar_year));
                }
            }
        }
        //配置所有假日，时间在范围内的返回
        foreach ($newHolidays as $holiday) {
            if ($dt_start <= strtotime($holiday["date"]) && strtotime($holiday["date"]) <= $dt_end) {
                unset($holiday['id']);
                $result [] = $holiday;
            }
        }
        ArrayHelper::multisort($result, 'date');
        return $result;
    }

    /**
     * 转换重复（没有年份）假日
     * @param array $holiday
     * @param int $year
     */
    static private function covertRepeatHoliday($holiday, $year) {
        $holiday['date'] = $year . $holiday['date'];
        return $holiday;
    }

    /**
     * 农历假期转公历
     * @param array $holidays       农历假日集合
     * @param int $year             农历年份
     */
    static private function convertLunarToSolar($holiday, $year) {
        //润月 0无润月，1~12 润月
        $leapMonth = Lunar::getLeapMonth($year);
        $month = (integer) substr($holiday["date"], 0, 2);
        $day = (integer) substr($holiday["date"], 2, 2);

        if ($month < $leapMonth) {
            //农历转公历
            $date = Lunar::convertLunarToSolar($year, $month, $day);
            $holiday['date'] = $date[0] . $date[1] . $date[2];
            return [$holiday];
        } else if ($month == $leapMonth) {
            //润月，需要添加两个假日，分别为本月和润月的
            //本月农历转公历
            $date = Lunar::convertLunarToSolar($year, $month, $day);
            $holiday['date'] = $date[0] . $date[1] . $date[2];

            //润月农历转公历
            $date = Lunar::convertLunarToSolar($year, $month + 1, $day);
            $leapHoliday = $holiday;
            $leapHoliday['date'] = $date[0] . $date[1] . $date[2];
            return [$holiday, $leapHoliday];
        } else {
            //比润月大的月份
            //农历转公历
            $date = Lunar::convertLunarToSolar($year, $month + 1, $day);
            $holiday['date'] = $date[0] . $date[1] . $date[2];
            return [$holiday];
        }
    }

}
