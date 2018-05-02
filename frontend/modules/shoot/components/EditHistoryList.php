<?php

/**
 * 列表式显示任务编辑记录
 */

namespace frontend\modules\shoot\components;

use Yii;
use yii\base\Arrayable;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\i18n\Formatter;

/**
 * Description of EditHistoryList
 *
 * @author Administrator
 */
class EditHistoryList extends Widget {
    
    /** 
     * 数据模型集合，为列表提供显示
     * @var $dataProvider array [Model,Model]
     */
    public $dataProvider;
    /**
     * @var array a list of attributes to be displayed in the detail view. Each array element
     * represents the specification for displaying one particular attribute.
     *
     * An attribute can be specified as a string in the format of "attribute", "attribute:format" or "attribute:format:label",
     * where "attribute" refers to the attribute name, and "format" represents the format of the attribute. The "format"
     * is passed to the [[Formatter::format()]] method to format an attribute value into a displayable text.
     * Please refer to [[Formatter]] for the supported types. Both "format" and "label" are optional.
     * They will take default values if absent.
     *
     * An attribute can also be specified in terms of an array with the following elements:
     *
     * - attribute: the attribute name. This is required if either "label" or "value" is not specified.
     * - label: the label associated with the attribute. If this is not specified, it will be generated from the attribute name.
     * - value: the value to be displayed. If this is not specified, it will be retrieved from [[model]] using the attribute name
     *   by calling [[ArrayHelper::getValue()]]. Note that this value will be formatted into a displayable text
     *   according to the "format" option.
     * - format: the type of the value that determines how the value would be formatted into a displayable text.
     *   Please refer to [[Formatter]] for supported types.
     * - visible: whether the attribute is visible. If set to `false`, the attribute will NOT be displayed.
     */
    public $attributes;
    
    /**
     * 所有数据，由设置的$attributes生成
     * @var array  [attributes,attributes]
     */
    protected $itemAttributes;
    
    /**
     * 数据为空时显示子字
     * @var string 
     */
    protected $emptyText;
    /**
     * @var string|callable the template used to render a single attribute. If a string, the token `{label}`
     * and `{value}` will be replaced with the label and the value of the corresponding attribute.
     * If a callback (e.g. an anonymous function), the signature must be as follows:
     *
     * ~~~
     * function ($attribute, $index, $widget)
     * ~~~
     *
     * where `$attribute` refer to the specification of the attribute being rendered, `$index` is the zero-based
     * index of the attribute in the [[attributes]] array, and `$widget` refers to this widget instance.
     */
    public $template = "<tr><th>{label}</th><td>{value}</td></tr>";
    /**
     * @var array the HTML attributes for the container tag of this widget. The "tag" option specifies
     * what container tag should be used. It defaults to "table" if not set.
     * @see Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = ['class' => 'list-group'];
    
    /** table属性 */
    public $tableOptions = ['class' => 'list-group-item'];
    
    /** 条纹颜色 */
    public $stripColors = ['#eeeeee','#ffffff']; 
    /**
     * @var array|Formatter the formatter used to format model attribute values into displayable texts.
     * This can be either an instance of [[Formatter]] or an configuration array for creating the [[Formatter]]
     * instance. If this property is not set, the "formatter" application component will be used.
     */
    public $formatter;
    
    /**
     * Initializes the detail view.
     * This method will initialize required property values.
     */
    public function init()
    {
        if($this->dataProvider === null)
            throw new InvalidConfigException('"dataProvider"不能为空!');
        if ($this->formatter == null) {
            $this->formatter = Yii::$app->getFormatter();
        } elseif (is_array($this->formatter)) {
            $this->formatter = Yii::createObject($this->formatter);
        }
        if (!$this->formatter instanceof Formatter) {
            throw new InvalidConfigException('The "formatter" property must be either a Format object or a configuration array.');
        }
        $this->normalizeAttributes();
    }

    /**
     * Renders the detail view.
     * This is the main entry of the whole detail view rendering.
     */
    public function run()
    {
        if(count($this->dataProvider)==0)
            echo (isset($this->emptyText) ? $this->emptyText : '无');
        else
        {
            $rows = [];
            foreach($this->itemAttributes as $attributes)
                $this->renderItem($attributes,$rows);
            $options = $this->options;
            $tag = ArrayHelper::remove($options, 'tag', 'div');
            echo Html::tag($tag, implode("\n", $rows), $options);
        }
    }
    
    /**
     * 对应 item 数据
     * @param array $attributes
     */
    protected function renderItem($attributes,&$rows)
    {
        $trs = [];
        $i = 0;
        foreach ($attributes as $attribute) {
            $trs[] = $this->renderAttribute($attribute, $i++);
        }
        $options = $this->tableOptions;
        Html::addCssStyle($options,['background-color' => $this->stripColors[count($rows)%2]]);
        $tag = ArrayHelper::remove($options, 'tag', 'table');
        $rows[] = Html::tag($tag, implode("\n", $trs), $options);;
    }

    /**
     * Renders a single attribute.
     * @param array $attribute the specification of the attribute to be rendered.
     * @param integer $index the zero-based index of the attribute in the [[attributes]] array
     * @return string the rendering result
     */
    protected function renderAttribute($attribute, $index)
    {
        if (is_string($this->template)) {
            return strtr($this->template, [
                '{label}' => $attribute['label'],
                '{value}' => $this->formatter->format($attribute['value'], $attribute['format']),
            ]);
        } else {
            return call_user_func($this->template, $attribute, $index, $this);
        }
    }

    /**
     * Normalizes the attribute specifications.
     * @throws InvalidConfigException
     */
    protected function normalizeAttributes()
    {
        if(count($this->dataProvider)>0)
            $model = $this->dataProvider[0];
        else 
            return;
        if ($this->attributes === null) {
            if ($model instanceof Model) {
                $this->attributes = $model->attributes();
            } elseif (is_object($model)) {
                $this->attributes = $model instanceof Arrayable ? $model->toArray() : array_keys(get_object_vars($model));
            } elseif (is_array($model)) {
                $this->attributes = array_keys($model);
            } else {
                throw new InvalidConfigException('The "model" property must be either an array or an object.');
            }
            sort($this->attributes);
        }
        
        /** 格式修正 **/
        foreach ($this->attributes as $i => $attribute) {
            if (is_string($attribute)) {
                if (!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/', $attribute, $matches)) {
                    throw new InvalidConfigException('The attribute must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
                }
                
                $attribute = [
                    'attribute' => $matches[1],
                    'format' => isset($matches[3]) ? $matches[3] : 'text',
                    'label' => isset($matches[5]) ? $matches[5] : null,
                ];
            }

            if (!is_array($attribute)) {
                throw new InvalidConfigException('The attribute configuration must be an array.');
            }

            if (isset($attribute['visible']) && !$attribute['visible']) {
                unset($this->attributes[$i]);
                continue;
            }

            if (!isset($attribute['format'])) {
                $attribute['format'] = 'text';
            }
            $this->attributes[$i] = $attribute;
        }
        
        /**
         * 合成所有数据
         */
        $this->itemAttributes = [];
        $attributes = [];
        foreach ($this->dataProvider as $model)
        {
            foreach ($this->attributes as $i => $attribute)
            {
                if (isset($attribute['attribute'])) {
                    $attributeName = $attribute['attribute'];
                    if (!isset($attribute['label'])) {
                        $attribute['label'] = $model instanceof Model ? $model->getAttributeLabel($attributeName) : Inflector::camel2words($attributeName, true);
                    }
                    if (!array_key_exists('value', $attribute)) {
                        $attribute['value'] = ArrayHelper::getValue($model, $attributeName);
                    }else if($attribute['value'] instanceof \Closure)
                    {
                        $attribute['value'] = call_user_func($attribute['value'],$model);
                    }
                } elseif (!isset($attribute['label']) || !array_key_exists('value', $attribute)) {
                    throw new InvalidConfigException('The attribute configuration requires the "attribute" element to determine the value and display label.');
                }

                $attributes[$i] = $attribute;
            }
            $this->itemAttributes[] = $attributes;
        }
    }
}
