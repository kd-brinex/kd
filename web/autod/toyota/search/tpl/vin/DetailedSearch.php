<div id="ToyModifs">

    <table class="dataTable">
        <thead>
            <tr>
                <th>Рынок</th>
                <th>Модель</th>
                <th>Комплектация</th>
                <th>Выпуск</th>
                <th>Производство</th>
                <th>Двигатель</th>
                <th>Кузов</th>
                <th>Класс</th>
                <th>КПП</th>
                <th>Другое</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach( $aRezult AS $_v ){ ?>
            <tr onclick="window.location.href ='<?=$url?>'">
                <td><a href="<?=$url?>"><?=$_v->marketRU?></a></td>
                <td><?=$_v->modelName?></td>
                <td><?=$_v->modelCode?></td>
                <td><?=$_v->prod?></td>
                <td><?=$_v->date?></td>
                <td><?=$_v->engine?></td>
                <td><?=$_v->body?></td>
                <td><?=$_v->grade?></td>
                <td><?=$_v->kpp?></td>
                <td><?=$_v->other?></td>
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

</div>