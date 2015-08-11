<?php
namespace app\modules\autocatalog\models;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 05.08.15
 * Time: 16:49
 */
use yii\db\Query;

class Hyundai extends CCar
{
    /**
     * @return Query
     */

//private $model_name;    // - название модели
//private $prod_start;    // - начало производства
//private $prod_end;      // - окончание производства
//private $models_codes;  // - код модели
//private $catalog_code;  // - код каталога
//private $catalog_name;  // - название каталога
//private $region ;       // - код региона


    public function getModelList($prm)
    {
//        $prm['where']= (isset($params['region']))?['data_regions LIKE %:region%',[':region'=>$params['region']]]:'';
        $query = new Query();
//        var_dump($prm['where']);die;
        $query->select(
            'c.family model_name,
            c.data_regions region,
            c.cat_name catalog_name,
           c.catalogue_code catalog_code'
        )

            ->distinct()
            ->from('catalog c')
//            ->orderBy('family')
        ->where('data_regions LIKE :region',[':region'=>'%'.$prm['region'].'%'])
        ;
        return $query;
    }
    public function getRegionList()
    {
        return [
            'GEN' => 'Общие',
            'CIS' => 'СНГ',
            'EUR' => 'Европа',
            'HMA' => 'США',
            'MES' => 'Middle East',
            'AUS' => 'Австралия',
            'HAC' => 'Канада',
            'HMI' => 'Индия',
        ];
    }
    public function getVehicle($prm)
    {
//        $this->lang = 'RU'; // по умолчанию - Английский
//        $this->catalog_code = (isset($prm["catalog_code"]) ? $prm["catalog_code"] : $this->catalog_code);
//        $this->prod_start = (isset($prm["prod_start"]) ? $prm["prod_start"] : $this->prod_start);
//        $this->region = (isset($prm["region"]) ? $prm["region"] : $this->region);

        $this->catalog_code = $this->getCatalogList($prm);
//        $catalog = $catalog[0];
//        $cat_production_years = $MC_API->cat_catalog_production_years_array($catalog['year_from'], $catalog['year_to']);  // года выпуска
//        $catalog_regions = $MC_API->catalog_regions_array(array(array('data_regions'=>$catalog['data_regions'])));  // регионы
    }

    public function getCatalogList($prm)
    {
        $this->lang = 'RU'; // по умолчанию - Английский
        $this->catalog_code = (isset($prm["catalog_code"]) ? $prm["catalog_code"] : $this->catalog_code);
        $this->catalog_name = (isset($prm["catalog_name"]) ? $prm["catalog_name"] : $this->catalog_name);
        $this->prod_start = (isset($prm["prod_start"]) ? $prm["prod_start"] : $this->prod_start);
        $this->region = (isset($prm["region"]) ? $prm["region"] : $this->region);
        $query = new Query();
//var_dump($this->catalog_name);die;

        $query->select("c.*,
  c0.production_from c0_production_from,
  c0.production_to c0_production_to,
  c0.vehicle_type_code,
  c0.vehicle_type,
  c0.year_from,
  c0.year_to,
  c.catalogue_code catalog_code,
  c.family model_name,
  c.cat_name catalog_name,
  c.data_regions region,
  c.cat_folder image,
  c.production_from date_start,
  c.production_to date_end"
        )

        ->from('catalog c')
        ->join('JOIN','cats0_catalog c0','c.catalogue_code = c0.catalogue_code')
        ->where('c.catalogue_code=:catalogue_code',[':catalogue_code'=>$this->catalog_code])
        ->andWhere('c.cat_name = :catalog',[':catalog'=>$this->catalog_name]);
        return $query;
    }
    public function getTranslate($lang_code,$lex_desc)
    {
        $query= new Query();
        $query->select('$lang_code.lex_desc')
            ->from([$lang_code=>'mc_lexicon'])
            ->leftJoin(['EN'=>'mc_lexicon'],'EN.lex_code=$lang_code.lex_code')
            ->where('EN.lex_desc=:lex_desc and $lang_code.lang_code=:lang_code',[':lex_desc'=>$lex_desc,':lang_code'=>$lang_code]);
        return $query;
    }
}