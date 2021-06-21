(function($) {
  $('.tab-content').each( function() {
    var year = $(this).attr('id');
    $('#'+year+':first').append($('.'+year)).nextAll('.'+year).remove();
    $('.'+year).slice(0, 5).show();
    var visibleElement = $('.'+year+':not([style*="display: none"])').length;
    var numberOfElement = $('.'+year).length;
    if (visibleElement != numberOfElement) {
      $('#'+year).append('<center><button type="button" class="view-more-btn" id="view-more-'+year+'">View More</button></center>');
    }
    $('#view-more-'+year).click( function() {
      var currentVisibleElement = $('.'+year+':not([style*="display: none"])').length;
      $('.'+year).slice(currentVisibleElement, currentVisibleElement+3).show();
      if (currentVisibleElement == numberOfElement) {
        $('#view-more-'+year).addClass('btn-disable');
      }
    });
  });
  $('.tabs .tab-link').first().addClass('current');
  $('.container .tab-content').first().addClass('current');
  $('ul.tabs li').click(function(){
    var tab_id = $(this).attr('data-tab');
    $('ul.tabs li').removeClass('current');
    $('.tab-content').removeClass('current');
    $(this).addClass('current');
    $("#"+tab_id).addClass('current');
  });
  $('.select-year').click( function() {
    var responsiveNavi = document.getElementById('top-navigation');
    if (responsiveNavi.className === 'topnav') {
      responsiveNavi.className += ' responsive';
    } else {
      responsiveNavi.className = 'topnav';
    }
  });
})(jQuery);
