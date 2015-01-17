<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 16.01.15
 * Time: 14:19
 */
$template=(isset($attribute['template']))?$attribute['template']:'<td>{label}</td><td>{value}</td>';
echo strtr($template, [
    '{label}' => $attribute['label'],
    '{class}' => (isset($attribute['class']))?'class="'.$attribute['class'].'"':'',
    '{value}' => $widget->formatter->format($attribute['value'], $attribute['format']),
]);
