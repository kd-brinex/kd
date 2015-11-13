/**
 * Created by marat on 02.07.15.
 */
$(document).ready(function () {
    //h=$('.page_image').height()
    //console.log(h)
    //$('.page-scroll').height(h)



    $('.panel-label').mouseenter(function () {
        $('.panel-label').removeClass('part-active')
        $(this).addClass('part-active')
        $('.page_label').removeClass('part-active')
        $('.page-label, #' + this.id).addClass('part-active')

        //console.log(this.id);
    });

    $('.page_label').mouseenter(function () {
        $('.panel-label').removeClass('part-active')
        $('.page_label').removeClass('part-active')
        $(this).addClass('part-active')
        e = $('#' + this.id)
        e.addClass('part-active')
        //scroll(e)
        //$('.page-label, #'+this.id).click()
    });

    $('.page_label').click(function () {
        $('.panel-collapse').removeClass('in')
        a = $('#' + this.id).children().find('a')
        a.click();
        scroll(a)
    });

    $('#find_model').on('input',function(){
        ftext = this.value.toUpperCase();
        //console.log(ftext);
        $('[type="button"]').addClass('model-hide')

        find_model(ftext);
    })


});

function find_model(ftext) {
    //console.log($("button:contains(ftext)"));
    $('[type="button"]:contains('+ftext+')').toggleClass('model-hide');
    //$('[type="button"]:contains('+ftext+')').hide();
}

function scroll(e) {
    h = $('page-scroll').height();
    $('.page-scroll').scrollTop(100);
    $('.page-scroll').animate({
        scrollTop: e.offset().top
    }, 500);
}