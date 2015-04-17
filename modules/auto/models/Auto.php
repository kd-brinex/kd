<?php
namespace app\modules\auto\models;

use yii\base\Model;
use yii\bootstrap\Collapse;
use yii\helpers\Html;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 13.04.15
 * Time: 11:12
 */
class Auto extends Model
{
    public $sTypeID;
    public $sTypeName;  /// Имя группы
    public $sMarkID;    /// Идентификатор марки
    public $sMarkName;  /// Имя марки
    public $sModelName; /// Имя модели
    public $aTreelList;   /// список деталей
    public $sModelID;
    public $tree;

    public function __construct($oA2D, $modelid)
    {
        parent::__construct();
//        var_dump($oA2D);die;
        $bMultiArray=false;
        $oAdcpi = \Yii::$app->adcpi;
        $oTreelList=$oAdcpi->getTreeList($modelid,$bMultiArray);
        $this->sTypeID = $oA2D->property($oTreelList, 'typeID');    /// Идентификатор группы
        $this->sTypeName = $oA2D->property($oTreelList, 'typeName');  /// Имя группы
        $this->sMarkID = $oA2D->property($oTreelList, 'markID');    /// Идентификатор марки
        $this->sMarkName = $oA2D->property($oTreelList, 'markName');  /// Имя марки
        $this->sModelName = $oA2D->property($oTreelList, 'modelName'); /// Имя модели
        $this->aTreelList = $oA2D->property($oTreelList, 'details');   /// список деталей
        $this->sModelID    = $modelid;
    }


    public function addItem($f)
    {
        return \yii\helpers\Html::a($f->tree_name, '/auto/auto/map?modelID=' . $this->sModelID . '&treeID=' . $f->id);
    }

    public function buildTree()

    {foreach($this->aTreelList as $tr) {
        if ($tr->parent_id==0) {
//            $tr = $this->aTreelList[0];
            $this->tree .= $this->getChild($tr);
        }
    }
    }

    public function getChild($parent)
    {
        $a='';
        if ($parent->childs > 0) {
            foreach ($this->aTreelList as $child) {
                if ($child->parent_id == $parent->id) {
                    if ($child->childs > 0) {
//                        $a['list'][$child->id] = $this->getChild($child);
                        $a.=$this->getChild($child);
                    } else {
//                        $a['list'][] = $child;
//                        $a.=$child->tree_name.'<br>';
                        $a.=Html::a( $child->tree_name,'/auto/auto/map?modelID='.$this->sModelID.'&treeID='.$child->id,['id'=>'_'.$child->id]).'<br>';
                    }
                }
            }
        }
        return Collapse::widget ( [
            'items' => [

                [
                    'label' => $parent->tree_name,
                    'content' =>$a,
                    'contentOptions' => [ ],
                    'options' => [ ]
                ],

            ]
        ] );

    }


}