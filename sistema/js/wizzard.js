$(document).ready(function () {
    //Initialize tooltips
    $('.nav-tabs > li a[title]').tooltip();
    //Wizard
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

        var $target = $(e.target);


        if ($target.parent().hasClass('disabled')) {
            return false;
        }else{
            $target.parent().nextAll().removeClass('active');
            $target.parent().prevAll().removeClass('active');
            $target.parent().addClass('active');

        }
    });

    $(".btn.btn-primary.next-step").click(function (e) {
        var $active = $('.wizard .nav-tabs li.active');
        $active.removeClass('active');
        $active.next().removeClass('disabled');
        $active.next().addClass('active');
        nextTab($active);

    });

    $(".prev-step").click(function (e) {
        var $active = $('.wizard .nav-tabs li.active');
        $active.removeClass('active');
        prevTab($active);
    });


    $(".final-step").click(function (e) {
      var $active = $('.wizard .nav-tabs li.active');
      $active.removeClass('active');
      $active.addClass('disabled');
      $active.prevAll().addClass('disabled');
      var $list = $('.wizard .nav-tabs li');
      $list.first().removeClass('disabled');
      $list.first().addClass('active');
      var $tabpane = $('.tab-content .tab-pane');
      $tabpane.last().removeClass("active");
      $tabpane.first().addClass("active");
      firstTab($active);
      var $form = $(".wizard form");
      $form[0].reset();
    });

});

function firstTab(elem) {
  $(elem).first().find('a[data-toggle="tab"]').click();
  $(".wizard-form .container").scrollTop(0);
}

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
    $('.wizard-form .container').animate({
                   scrollTop: $(".upreference").offset().top
               }, 300);
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
    $('.wizard-form .container').animate({
                   scrollTop: $(".upreference").offset().top
               }, 300);
}
