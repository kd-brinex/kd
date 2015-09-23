<table id="dataTable" class="dataTable">
    <thead>
    <tr>
        <?php foreach( $aFields AS $sField ){?>
            <th><?=$sField?></th>
        <?php }?>
    </tr>
    </thead>
    <tbody>
    <?php foreach( $aRezult AS $_v ){?>
        <tr>
            <?php foreach( $_v AS $v ){?>
                <td><a href="<?=$nextUrl?>"><?=$v?></a></td>
            <?php }?>
        </tr>
    <?php }?>
    </tbody>
</table>