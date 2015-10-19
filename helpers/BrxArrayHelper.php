<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 19.08.15
 * @time: 9:30
 */
namespace app\helpers;

class BrxArrayHelper{

    /**
     * Функция работает также как и встроенная PHP функция array_change_key_case, только делает это рекурсивно
     * @param array $array массив для обработки
     * @param $case int константы PHP: CASE_UPPER, CASE_LOWER
     * @return array результирующий массив
     */
    public static function array_change_key_case_recursive(array $array, $case = 0){
        $changeCase = (int)$case ? 'mb_strtoupper' : 'mb_strtolower';

        $resultArray = [];
        foreach($array as $key => $value){
            $key = $changeCase($key);

            if(is_array($value))
                $value = self::array_change_key_case_recursive($value, $case);

            $resultArray[$key] = $value;
        }
        return $resultArray;
    }

    /**
     * Функция работает аналогично PHP функции array_replace_recursive, только делает это (NCS - NON CASE SENSITIVE) не обращая внимания на регистр ключей.(ВНИМАНИЕ! Ключи самого первого массива остаются в исходном регистре)
     * @param $array array исходный массив. В него все записывается
     * @param $arrays array остальные массивы через запятую  (array_replace_recursive_ncs(array $array, $array, $array, ... , $array))
     * @return mixed возвращается склеенный массив
     */
    public static function array_replace_recursive_ncs(array $array, $arrays){
        $args = self::array_change_key_case_recursive(func_get_args(), CASE_LOWER);
        for ($i = 1; $i < count($args); $i++){
            if (is_array($args[$i])){
                $array = self::recurse($array, $args[$i]);
            }
        }
        return $array;
    }

    /**
     * Функция для работы рекурсивного пробега по массивам (помощник для функции array_replace_recursive_ncs())
     * @param $array
     * @param $arrays
     * @return array
     */
    private function recurse($array, $arrays){
        foreach($array as $key => $value){
            $cs_key = strtolower($key);
            if(isset($arrays[$cs_key]) && !is_array($arrays[$cs_key]))
                $array[$key] = $arrays[$cs_key];
            if(isset($arrays[$cs_key]) && is_array($arrays[$cs_key]))
                $array[$key] = self::recurse($array[$key], $arrays[$cs_key]);

            unset($arrays[$cs_key]);
        }
        $array = array_merge($array, $arrays);

        return $array;
    }

    /**
     * Функция аналогична PHP функции array_search только работает рекурсивно
     * @param $needle mixed значение для поиска
     * @param $haystack array|object массив|объект поиска
     * @param bool|false $strict
     * @param array $path вспомогательная переменная для записи индексов
     * @return array|bool массив ключей
     */
    public static function array_search_recursive($needle, $haystack, $strict = false, $path = []){
        $haystack = is_object($haystack) ? (array)$haystack : $haystack;
        foreach($haystack as $key => $val){
            $val = is_object($val) ? (array)$val : $val;
            if(is_array($val) && $subPath = self::array_search_recursive($needle, $val, $strict, $path)) {
                $path = array_merge($path, array($key), $subPath);
                return $path;
            } else if((!$strict && $val == $needle) || ($strict && $val === $needle)) {
                $path[] = $key;
                return $path;
            }
        }
        return false;
    }

    /**
     * Функция ищет значение из массива по массиву ключей полученному функцией array_search_recursive
     * @param $keys array|object массив|объект ключей полученный функцией array_search_recursive
     * @param $haystack array целевой массив
     * @return mixed результат
     */
    public static function array_search_recursive_value($keys, $haystack){
        $haystack = is_object($haystack) ? (array)$haystack : $haystack;
        $key = $keys[0];
        if(isset($haystack[$key]) && (is_object($haystack[$key]) || is_array($haystack[$key]))){
            array_shift($keys);
            return self::array_search_recursive_value($keys, $haystack[$key]);
        }

        if(isset($haystack[$key]) && (!is_array($haystack[$key]) || !is_object(!is_array($haystack[$key]))))
            return $haystack[$key];

    }
    /**
     * Функция аналогична PHP функции array_flip только работате рекурсивно
     * @param array $array массив для обработки
     * @return array обработанный массив
     */
    public static function array_flip_recursive(array $array) {
        foreach($array as $k => $v) {
            if(!is_array($v)){
                $array[$v] = $k;
                unset($array[$k]);
            }
            if(is_array($v))
                $array[$k] = self::array_flip_recursive($v);
        }
        return $array;
    }

    /**
     * Функция для рекурсивного поиска значения по ключу.
     * @param $key string|int ключ
     * @param $haystack array|object целевой массив или объект
     * @param $removeItem boolean если true то возвращенные элементы удаляются из исходного массива. По умолчанию false
     * @return array массив значений. Если в массиве несколько одинаковых ключей, будут возвразщены все в одном массиве.
     */
    public static function array_search_values_recursive($key, &$haystack, $removeItem = false){
        $haystack = is_object($haystack) ? (array)$haystack : $haystack;
        static $result = [];
        $result = [];
        foreach ($haystack as &$v) {
            $v = is_object($v) ? (array)$v : $v;
            if(is_array($v)){
                if(array_key_exists($key, $v)){
                    $result[] = $v[$key];
                    if($removeItem)
                        unset($v[$key]);
                } else
                    self::array_search_values_recursive($key, $v, $removeItem);
            }
        }
        return $result;
    }


}