<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 13.07.15
 * Time: 15:33
 */
echo "Универсальный парсер";
?>
<p>
        <?= $this->render('_parse_form.php', ['params'=>$params]) ?>
</p>
<?php
    foreach($result as $item){
        echo '<h2>'.$item['title'].' - <small>'.$item['url'].'</small></h2>';
        echo '<p style="margin:20px 0px;background:#eee; padding:20px;">'.$item['text'].'</p>';

//    exit();
}