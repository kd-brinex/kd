$( document ).ready(function() {

    $('#table00').html($('#table0').html());
    $('#table11').html($('#table1').html());
    $('#table22').html($('#table2').html());

    var mas = [], j = 0;
    var tr1 =$('#table0 tr');
    var tr2 =$('#table1 tr');
    var tr3 =$('#table2 tr');



    table_sort(tr1,0,'','#table0 tbody');
    table_sort(tr2,0,'','#table1 tbody');
    table_sort(tr3,0,'','#table2 tbody');




    $('th').click(function()
    {
        var tr;
        if ($(this).parent().parent().parent().attr('id')=='table0')
        {
            trbody = '#table0 tbody';
            tr = tr1;
        }
        else if ($(this).parent().parent().parent().attr('id')=='table1')
        {
            trbody = '#table1 tbody';
            tr = tr2;
        }
        else
        {
            trbody = '#table2 tbody';
            tr = tr3;
        }


        var column = $(this).parent().children().index($(this));
        $( this ).toggleClass( "ASC" );
        if ($(this).hasClass('ASC'))
        {
            table_sort(tr,column,'ASC',trbody);
        }
        else
        {
            table_sort(tr,column,'DESC',trbody);
        }


    });











    function table_sort(tr,column,sort,trbody)
    {

        for (i=1;i<tr.length;i++){
            mas[i] = new Array();
            $(tr[i]).children().each(function(j){
                mas[i][j] = $(this).html();
                j++;
            });
            j=0;

        }

        var mas_for_ob=[];
        for (i=0;i<tr.length;i++){
            mas_for_ob[i] = new Array();
            $(tr[i]).children().each(function(j){
                mas_for_ob[i][j] = $(this).html();
                j++;
            });
            j=0;

        }
        var temp='',temp_str, i, j,sort_arr=[];
        var arr=mas;
        for (i=1;i<arr.length;i++)
        {
            if ($.isNumeric(arr[i][column]))
            {
                sort_arr[i] = Number(arr[i][column]);

            }
            else
            {
                if (column==5 || column==6)
                    sort_arr[i] = Number ($(arr[i][column]).text());
                else
                    sort_arr[i] = arr[i][column];
            }


        }

        if (sort=='ASC') {

            for (i=1; i<sort_arr.length; i++) {
                for (j=i; j<sort_arr.length;j++) {
                    if (sort_arr[j]<sort_arr[i]) {
                        temp = sort_arr[i];
                        temp_str =  $(tr[i]).html();
                        sort_arr[i] = sort_arr[j];
                        $(tr[i]).html($(tr[j]).html());
                        sort_arr[j] = temp;
                        $(tr[j]).html(temp_str);
                    }
                }
            }

        }
        else if (sort=='DESC'){
            for (i = 1; i < sort_arr.length; i++) {
                for (j = i; j < sort_arr.length; j++) {
                    if (sort_arr[j]>sort_arr[i]) {
                        temp = sort_arr[i];
                        temp_str =  $(tr[i]).html();
                        sort_arr[i] = sort_arr[j];
                        $(tr[i]).html($(tr[j]).html());
                        sort_arr[j] = temp;
                        $(tr[j]).html(temp_str);

                    }
                }
            }
        }
        for (i=1;i<tr.length;i++){
            mas[i] = new Array();
            $(tr[i]).children().each(function(j){
                mas[i][j] = $(this).html();
                j++;
            });
            j=0;

        }

        var mas_for_ob=[];
        for (i=0;i<tr.length;i++){
            mas_for_ob[i] = new Array();
            $(tr[i]).children().each(function(j){
                mas_for_ob[i][j] = $(this).html();
                j++;
            });
            j=0;

        }
        var temp='',temp_str, i, j,sort_arr=[];
        var arr=mas;
        for (i=1;i<arr.length;i++)
        {
            if ($.isNumeric(arr[i][column]))
            {
                sort_arr[i] = Number(arr[i][column]);
            }
            else
            {
                sort_arr[i] = arr[i][column];
            }


        }


        //ТУТ НАЧИНАЕТСЯ ОБЪЕДИНЕНИЕ
        var k=1,u= 1,rowcount=1;
        var table1='<table border="1" id="table"><thead>';
        for (i=0;i<mas_for_ob.length;i++)
        {

            table1=table1+'<tr>';
            for (j=0;j<mas_for_ob[i].length;j++)
            {
                if (i==0)
                {
                    table1=table1+'<th>'+mas_for_ob[i][j]+'</th>';
                    if (j==(mas_for_ob[i].length-1))
                    {
                        table1=table1+'</tr></thead><tbody>';
                    }
                }

                else
                {

                    if (mas_for_ob[i][j]!='deleted')
                    {

                        if (i==(mas_for_ob.length-1))
                        {

                            table1=table1+'<td>'+mas_for_ob[i][j]+'</td>';
                        }
                        else
                        {

                            if (mas_for_ob[i][j]==mas_for_ob[k][j])
                            {

                                if (mas_for_ob[0][j]=='Количество' || mas_for_ob[0][j]=='Доставка'  || mas_for_ob[0][j]=='' || mas_for_ob[0][j]=='Заказ от(шт.)' || mas_for_ob[0][j]=='Баллы')
                                {
                                    table1=table1+'<td>'+mas_for_ob[i][j]+'</td>';
                                }
                                else{


                                    while (mas_for_ob[i][j]==mas_for_ob[u][j])
                                    {

                                        mas_for_ob[u][j]='deleted';
                                        u=u+1;
                                        rowcount=rowcount+1;
                                        if (u==mas_for_ob.length) {break;}

                                    }
                                    table1=table1+'<td rowspan="'+rowcount+'">'+mas_for_ob[i][j]+'</td>';
                                    rowcount=1;
                                    u=k;

                                }

                            }
                            else
                            {
                                if (mas[i][j]==''){
                                    table1=table1+'<td> - </td>';
                                }
                                else
                                {
                                    table1=table1+'<td>'+mas[i][j]+'</td>';
                                }

                            }
                        }

                    }
                    else
                    {
                        table1=table1;
                    }


                }
                if (j==mas_for_ob[i].length-1 && i!=0) { table1 = table1 + '</tr>'; }
            }

            k=k+1;
            u=k;



        }

        table1=table1+'</tbody></table>';



        $(trbody).html($(table1).find('tbody').html());

    }







});