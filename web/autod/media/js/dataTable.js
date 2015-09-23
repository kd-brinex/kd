(function(){

// Search function
    $.fn.dataTable.Api.register( 'alphabetSearch()', function ( searchTerm ) {
        this.iterator( 'table', function ( context ) {
            context.alphabetSearch = searchTerm;
        } );

        return this;
    } );

// Recalculate the alphabet display for updated data
    $.fn.dataTable.Api.register( 'alphabetSearch.recalc()', function ( searchTerm ) {
        this.iterator( 'table', function ( context ) {
            draw(
                new $.fn.dataTable.Api( context ),
                $('div.alphabet', this.table().container())
            );
        } );

        return this;
    } );


// Search plug-in
    $.fn.dataTable.ext.search.push( function ( context, searchData ) {
        // Ensure that there is a search applied to this table before running it
        if ( ! context.alphabetSearch ) {
            return true;
        }

        if ( searchData[0].charAt(0) === context.alphabetSearch ) {
            return true;
        }

        return false;
    } );


// Private support methods
    function bin( data ){
        //console.log(data);
        var letter, bins = {};
        for ( var i=0, ien=data.length ; i<ien ; i++ ) {

            //console.log( data[i] );
            var xmlString = data[i]
                , parser = new DOMParser()
                , doc = parser.parseFromString(xmlString, "text/xml")
                , model = doc.firstChild.innerHTML
            ;
            //console.log( model );
            //doc.firstChild // => <div id="foo">...
            //doc.firstChild.firstChild // => <a href="#">...

            letter = model.charAt(0).toUpperCase();

            if ( bins[letter] ) {
                bins[letter]++;
            }
            else {
                bins[letter] = 1;
            }
        }
        //console.log(bins);
        return bins;
    }

    function draw ( table, alphabet ){
        alphabet.empty();
        //alphabet.append( 'Search: ' );

        //console.log( table.column(0).data() );
        var columnData = table.column(0).data();
        var bins = bin( columnData );

        $('<span class="clear active"/>')
            .data( 'letter', '' )
            .data( 'match-count', columnData.length )
            .html( 'Все' )
            .appendTo( alphabet );

        /// 4 \\\
        var letter = String.fromCharCode( 52 );
        $('<span/>')
            .data( 'letter', letter )
            .data( 'match-count', bins[letter] || 0 )
            .addClass( ! bins[letter] ? 'empty' : '' )
            .html( letter )
            .appendTo( alphabet )
        ;

        /// A-Z \\\
        for ( var i=0 ; i<26 ; i++ ) {
            var letter = String.fromCharCode( 65 + i );

            $('<span/>')
                .data( 'letter', letter )
                .data( 'match-count', bins[letter] || 0 )
                .addClass( ! bins[letter] ? 'empty' : '' )
                .html( letter )
                .appendTo( alphabet );
        }

        $('<div class="alphabetInfo"></div>')
            .appendTo( alphabet );
    }


    $.fn.dataTable.AlphabetSearch = function ( context ) {
        var table = new $.fn.dataTable.Api( context );
        var alphabet = $('<div class="alphabet"/>');

        draw( table, alphabet );

        // Trigger a search
        alphabet.on( 'click', 'span', function () {
            alphabet.find( '.active' ).removeClass( 'active' );
            $(this).addClass( 'active' );

            table
                .alphabetSearch( $(this).data('letter') )
                .draw();
        } );

        // Mouse events to show helper information
        alphabet
            .on( 'mouseenter', 'span', function () {
                alphabet
                    .find('div.alphabetInfo')
                    .css( {
                        opacity: 1,
                        left: $(this).position().left,
                        width: $(this).width()
                    } )
                    .html( $(this).data('match-count') )
            } )
            .on( 'mouseleave', 'span', function () {
                alphabet
                    .find('div.alphabetInfo')
                    .css('opacity', 0);
            } );

        // API method to get the alphabet container node
        this.node = function () {
            return alphabet;
        };
    };

    $.fn.DataTable.AlphabetSearch = $.fn.dataTable.AlphabetSearch;


// Register a search plug-in
    $.fn.dataTable.ext.feature.push( {
        fnInit: function ( settings ) {
            var search = new $.fn.dataTable.AlphabetSearch( settings );
            return search.node();
        },
        cFeature: 'A'
    } );

}());


$(document).ready(function() {
    var table = $('#dataTable').DataTable( {
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        bPaginate: false,
        //dom: 'Alfrtip',//Tabs
        //dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
        order: [[ 0, "asc" ]],
        jQueryUI:       true,
        language: {
            "sProcessing":   "Подождите...",
            "sLengthMenu":   "Показать _MENU_ записей",
            "sZeroRecords":  "Попробуйте другой запрос",
            //"sInfo":         "Записи с _START_ до _END_ из _TOTAL_ записей",
            "sInfo":         "В таблице: _TOTAL_ запись(ей)",
            "sInfoEmpty":    "По запросу ничего не найдено",
            "sInfoFiltered": "(отфильтровано из _MAX_ записей)",
            "sInfoPostFix":  "",
            "sSearch":       "Поиск по таблице:",
            "sUrl":          "",
            "oPaginate": {
                "sFirst": "Первая",
                "sPrevious": "Предыдущая",
                "sNext": "Следующая",
                "sLast": "Последняя"
            },
            "oAria": {
                "sSortAscending":  ": активировать для сортировки столбца по возрастанию",
                "sSortDescending": ": активировать для сортировки столбцов по убыванию"
            }
        }
    } );
} );