<?php
namespace app\modules\parser\models;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 13.07.15
 * Time: 15:32
 */
//include 'simple_html_dom.php';
class Parser {
    public $protocol = '';
    public $host = '';
    public $path = '';
    public $cacheurl = [];
    public $result = [];
    public $_allcount = 10;
    function __construct($params){
        if(isset($params['url'])){
            $this->parse($params['url']);
        }
    }
    function parse($url){
        $url = $this->readUrl($url);
var_dump($url);die;
        if( !$url or $this->cacheurl[$url] or $this->cacheurl[preg_replace('#/$#','',$url)] )
        {return false;}
var_dump('111');die;
        $this->_allcount--;

        if( $this->_allcount<=0 )
        {return false;}

        $this->cacheurl[$url] = true;
        $item = array();

        $data = str_get_html(request($url));
        $item['url'] = $url;
        $item['title'] = count($data->find('title'))?$data->find('title',0)->plaintext:'';
        $item['text'] = $data->plaintext;
        $this->result[] = $item;

        if(count($data->find('a'))){
            foreach($data->find('a') as $a){
                $this->parse($a->href);
            }
        }
        $data->clear();
        unset($data);
    }
    function readUrl($url){
        var_dump($url);die;
        $urldata = parse_url($url);
        if( isset($urldata['host']) ){
            if($this->host and $this->host!=$urldata['host'])
                return false;

            $this->protocol = $urldata['scheme'];
            $this->host = $urldata['host'];
            $this->path = $urldata['path'];
            return $url;
        }

        if( preg_match('#^/#',$url) ){
            $this->path = $urldata['path'];
            return $this->protocol.'://'.$this->host.$url;
        }else{
            if(preg_match('#/$#',$this->path))
                return $this->protocol.'://'.$this->host.$this->path.$url;
            else{
                if( strrpos($this->path,'/')!==false ){
                    return $this->protocol.'://'.$this->host.substr($this->path,0,strrpos($this->path,'/')+1).$url;
                }else
                    return $this->protocol.'://'.$this->host.'/'.$url;
            }
        }
    }

}