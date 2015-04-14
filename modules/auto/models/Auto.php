<?php
namespace app\modules\auto\models;
use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 13.04.15
 * Time: 11:12
 */
    class Auto extends Model
    {
        public function buildTree($sModelID,$aTreelList){
            $dom = new DOMDocument();
            $ul = $dom->appendChild(new DOMElement("ul"));
            $ul->setAttribute("class", "my_tree");
            $ul->setAttribute("id", "l1");
            $aObj = new stdClass();
            $aObj->{0} = $ul;
            foreach($aTreelList as $f){
                $ul = $aObj->{$f->parent_id};
                $li = $ul->appendChild(new DOMElement("li"));
                $li->setAttribute("class", "close");
                $a = $li->appendChild(new DOMElement("a"));
                $a->setAttribute("title",$f->tree_name);
                if($f->childs != 0){
                    $b = $a->appendChild(new DOMElement("b"));
                    $b->appendChild(new DOMText($f->tree_name));
                    $a->setAttribute("href", "javascript:;");
                    $ul = $li->appendChild(new DOMElement("ul"));
                    $ul->setAttribute("class", "close");
                    $aObj->{$f->id} = $ul;
                }else{
                    $a->appendChild(new DOMText($f->tree_name));
                    $a->setAttribute("href", 'map.php?modelID='.$sModelID.'&treeID='.$f->id);
                    $a->setAttribute("id", '_'.$f->id);
                }
            }
            return $dom->saveHTML();
        }
    }