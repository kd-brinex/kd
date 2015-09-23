<table class="dataTable">
    <thead>
    <tr>
        <th class="center">Модель</th>
        <th class="center">Комплектация</th>
        <th class="center">Производсьтво</th>
        <th class="center">Двигатель</th>
        <th class="center">Кузов</th>
        <th class="center">Класс</th>
        <th class="center">КПП</th>
        <th class="center">Другое</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach( $aRezult AS $v ){?>
        <tr>
            <td><a href="<?=$url?>&code=<?=$v->compl?>"><?=$v->modelName?></a></td>
            <td><a href="<?=$url?>&code=<?=$v->compl?>"><?=$v->modelCode?></a></td>
            <td><a href="<?=$url?>&code=<?=$v->compl?>"><?=$v->prod?></a></td>
            <td><a href="<?=$url?>&code=<?=$v->compl?>"><?=$v->engine?></a></td>
            <td><a href="<?=$url?>&code=<?=$v->compl?>"><?=$v->body?></a></td>
            <td><a href="<?=$url?>&code=<?=$v->compl?>"><?=$v->grade?></a></td>
            <td><a href="<?=$url?>&code=<?=$v->compl?>"><?=$v->kpp?></a></td>
            <td><a href="<?=$url?>&code=<?=$v->compl?>"><?=$v->other?></a></td>
        </tr>
    <?php }?>
    </tbody>
</table>

<div class="explanation">
    <span class="cBlue">Расшифровка сокращений</span>
    <div class="expTable">
        <?php foreach( $aList AS $n=>$s ){?>
            <div class="eTableHead"><?=$n?></div>
            <div class="eTableBody">
                <?php foreach( $s AS $a ){?>
                    <span class="sign"><?=$a['sign']?></span> = <span class="desc"><?=$a['desc']?></span><br/>
                <?php }?>
            </div>
        <?php }?>
    </div>
</div>