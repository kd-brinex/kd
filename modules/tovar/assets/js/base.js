$( document ).ready(function() {

    //вешаем события на заголовки таблицы у которых присутствует класс thd
    $(".table").each(function(index){

        //сохраняем ссылку на объект tbody содержащий ячейки таблицы
        var tbody = $(this).children("tbody");

        //присваеиваем
        tbody.data("table",tbody.html());

        var thead = $(this).children("thead");

        //вешаем событие на заголовки
        thead.find(".thd").each(function(index){
            //вешаем событие клика
            $(this).on('click',function(){ sorttable(this);});

            //вызываем функцию сортировки для первого столбца
            if(index == 0)
            {
                sorttable(this);
            }
        });

    });

});

/**
 * функция сортировки таблицы
 * входной параметр:
 * el - указатель на заголовок таблицы
 **/

function sorttable(el)
{

    //название заголовка столбца по которому нажали
    var col_sort=$(el).html();

    //список заголовков таблицы
    var tr = $(el).parent("tr");

    //данные таблицы
    var table = tr.parent("thead").parent("table").children("tbody");

    //будет храниться index сортируемого столбца
    var col_sort_num;

    //заменяем значения таблицы на исходные (не отсортированные) значения
    table.html(table.data('table'));

    //перебираем заголовки таблицы
    tr.find("th").each(function(index){
        //находим заголовок по которому кликнули
        if($(this).html() == col_sort)
        {
            //запоминаем ииндекс сортируемого столбца
            col_sort_num = index;

            //проверяем отсортирован столбец или нет
            if($(this).data("prevsort") == "y")
            {
                //если столбец отсортирован изменяем маркер направления сортировки
                // 0- по возрастания
                // 1- по убыванию
                el.up = Number(!el.up);
            }
            else
            {
                //если столбец сортируется первый раз
                //добавляем переменную prevsort = y к ячейке заголовка, служит маркером того что столбец отсортирован
                $(this).data("prevsort", "y");

                //добавлени тэга span для вывода указателя направления сортировки
                //$(this).append("<span></span>");

                //если столбец сортируется первый раз то присваиваем переменной, которая отвечает за направление сортировки
                // значение 0 - направление сортировки по возрастания (1- по убыванию)
                // так же отвечает зха направление сортировки по умолчанию, при первом нажатии по заголовку
                el.up = 0;
            }

            //вывод указателя направления сортировки
            //$(this).children("span").text(el.up?"↑ ":"↓ ");
        }
        else
        {
            //если столбец был ранее отсортирован, то изменяем маркер сортировки
            if ($(this).data("prevsort") == "y")
            {
                $(this).data("prevsort", "n");

                //удалеям тэг с указателем направления сортировки
                //$(this).children("span").remove();
            }
        }
    });

    //массив в который записываются данные из строки таблицы
    var a = new Array();

    //проверяем наличие класса dual у заголовка, класс используется для столбцов где данные в формате 12-13 (два числа через тире)
    if($(el).hasClass("dual"))
    {
        table.find("tr").each(function(index){
            //определояем массив
            a[index] = new Array();

            //сохраняем значение текущей ячейки
            a[index][2]=$(this).find("td").eq(col_sort_num).text();

            //отсекаем значения после тире и применяем костыль чтобы сортировал как числа а не строки
            a[index][0]=parseFloat(a[index][2]) / 1000000;

            //сохраняем значение текущей строки
            a[index][1]=$(this);

        });
    }
    else
    {
        table.find("tr").each(function(index){
            //определояем массив
            a[index] = new Array();

            //сохраняем значение текущей ячейки
            a[index][2]=$(this).find("td").eq(col_sort_num).text();

            //проверяем на число и применяем костыль к числам чтобы сортировал их как числа а не строки
            if( $.isNumeric(a[index][2]))
            {
                a[index][0]=a[index][2] / 1000000;
            }
            else
            {
                a[index][0]=a[index][2];
            }

            //сохраняем значение текущей строки
            a[index][1]=$(this);
        });
    }

    //сортируем массив
    a.sort();

    //изменяем направление сортировки если el.up = 1
    if(el.up) a.reverse();

    //выполняем дополнитлеьную сортировку по значениям после тире для столбца в заголовке которого есть класс dual
    if($(el).hasClass("dual"))
    {
        //переменная для хранения подмассива для сортировки
        var a_temp = new Array();

        //значение индекса с которого начинается подмассив a_temp в массивае a
        var t_start = 0;
        //значение индекса на котором заканчивается подмассив a_temp в массивае a
        var t_end = 0;
        //ругелярное выражение для проверки на формат 12-12
        var regexp_str = new RegExp(/^\d+-\d+$/);

        a_temp.push(a[0]);

        for(i=1; i<a.length; i++)
        {

            if(a[i][0] == a[i-1][0])
            {
                a_temp.push(a[i]);
                t_end = i;
            }
            else
            {
                if(a_temp.length > 1)
                {
                    //убираем значения до тире чтобы отсортировать по значения после тире
                    for(var k = 0; k < a_temp.length; k++)
                    {
                        if(regexp_str.test(a_temp[k][2]) == true)
                        {
                            a_temp[k][0] = parseInt(a_temp[k][2].replace(/^\d+-/,''));

                            if( typeof a_temp[k][0] == "number" )
                            {
                                a_temp[k][0] = a_temp[k][0]/1000000;
                            }
                        }
                        else
                        {
                            a_temp[k][0] = 0;
                        }
                    }

                    //сортируем массив
                    a_temp.sort();

                    //определяем направление сортировки, если по убыванию то разворачиваем массив
                    if(el.up) a_temp.reverse();

                    //заменяем диапазон на отсортированный в основном массиве
                    for(var j=t_start; j<=t_end; j++)
                    {
                        a[j] = a_temp.shift();
                    }
                }
                delete a_temp;

                var a_temp = new Array();
                a_temp.push(a[i]);

                t_start = i;


            }
        }
    }

    for(i=0; i < a.length; i++)
    {
        table.append(a[i][1]);
    }


    var str=table.children("tr");
    var str_length = str.length;
    //var grouping_array=new Array("code","manufacture","name");
    var grouping_array=new Array(0,1,2);
    var grouping_length = grouping_array.length;


    //вызываем функцию группировки
    groupingnew(str, 0, str_length, grouping_length, 0, grouping_array);
}

//функция группировки
//входные параметры
// str - ссылка на строки таблицы
// str_start - начальный индекс с которого начинается перебор строк таблицы (в начале 0 передается)
// str_length - конечный индекс строк таблицы на котором заканчивается перебор строк
// grouping_length - длинна массива содержащий поля для группировки
// grouping_i - текущий индекс группируемого столбца
function groupingnew(str, str_start, str_length, grouping_length, grouping_i, grouping_array)
{


    var s =  str.eq(str_start).children("td").eq(grouping_array[grouping_i]).text();
    var t = str_start;


    for(var i = str_start+1; i < str_length; i++)
    {
        if(s == str.eq(i).children("td").eq(grouping_array[grouping_i]).text())
        {
            //если дошли до конца столбца и небыло изменений то рекурсивно вызываем этуже функцию с переходом на следующий столбец
            //проверяем чтобы не дошли до конца массива со списком группируемых столбцов
            if(i == str_length - 1 && grouping_i < grouping_length)
            {
                //вызываем функцию группировки
                groupingnew(str, t, i + 1 , grouping_length, grouping_i + 1, grouping_array);

                //удаляем ячейки с повторениями
                for(var j=t+1; j <= i; j++)
                {
                    str.eq(j).children("td").eq(grouping_array[grouping_i]).remove();
                }

                //добавляем параметр rowspan = кол-во удаленных ячеек
                var rw = str.eq(t).children("td").eq(grouping_array[grouping_i]);
                rw.attr("rowspan",i + 1 - t);
            }
        }
        else
        {
            //если изменилось значение в ячейке столбца то рекурсивно вызываем этуже функцию с переходом на следующий столбец
            //проверяем чтобы не дошли до конца массива со списком группируемых столбцов
            if(grouping_i < grouping_length)
            {
                groupingnew(str, t, i , grouping_length, grouping_i + 1, grouping_array);
            }

            //удаляем ячейки с повторениями
            for(var j = t+1; j < i; j++)
                str.eq(j).children("td").eq(grouping_array[grouping_i]).remove();

            //добавляем параметр rowspan = кол-во удаленных ячеек
            var rw = str.eq(t).children("td").eq(grouping_array[grouping_i]);
            rw.attr("rowspan",i - t);

            //присваиваем временной переменной значение текущей чяейки
            s =  str.eq(i).children("td").eq(grouping_array[grouping_i]).text();

            //запоминаем значение индекса текущей ячейки
            t = i;
        }
    }

}
