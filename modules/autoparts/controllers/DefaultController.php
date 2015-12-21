<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 13.08.15
 * @time: 11:59
 */

namespace app\modules\autoparts\controllers;

class DefaultController extends ProviderController
{
    public function actionIndex()
    {
        $arr = [
          'a' => 'aaa',
          'b' => [
              'gg' => 'gggg',
              'hh' => 'hhhh',
              'ii' => 'iiii',
              'jj' => [
                  'kkk' => 'kkkkk',
                  'lll' => 'lllll',
                  'mmm' => 'mmmmm'
              ],
          ],
          'c' => 'ccc',
          'd' => 'ddd',
          'e' => 'eee',
          'f' => [
              'gg' => 'gggg',
              'hh' => 'hhhh',
              'ii' => 'iiii',
              'jj' => [
                  'kkk' => 'kkkkk',
                  'lll' => 'lllll',
                  'mmm' => 'mmmmm'
              ]
          ]
        ];

        $iterator = new \RecursiveArrayIterator($arr);

        foreach($it = new \RecursiveIteratorIterator($iterator) as $key => $val){
            echo $key.' : '.$val.'<br>';
            var_dump($it->getInnerIterator(), $it->getMaxDepth());
        }

    }

}