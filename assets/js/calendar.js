/**
 *	Custom jQuery Scripts
 *	
 *	Developed by: Lisa DeBona
 */

jQuery(document).ready(function ($) {

  if( $('.tribe-events-calendar-month .tribe-events-calendar-month__day').length ) {
    var EventListing=[]
    $('.tribe-events-calendar-month__day').each(function(k){
      var cell = $(this);
      var parent = $(this).parents('.tribe-events-calendar-month');
      var target = $(this).find('.tribe-events-calendar-month__day-cell');
      if( $(this).find('.tribe-events-calendar-month__events article').length ) {
        var items = $(this).find('.tribe-events-calendar-month__events article');
        items.each(function(a){
          var article = $(this);
              article.attr('data-index', a);
          var prevArticle = article.prev();
          if( $(this).find('h3[data-slug]').length ) {
            var eventName = $(this).find('h3[data-slug]');
            var eventSlug = eventName.attr('data-slug');
            if( cell.prev().find('[data-slug="'+eventSlug+'"]').length ) {
              article.addClass('seriesEvent');
              if(prevArticle.hasClass('tribe_events')) {
                article.insertBefore(prevArticle);
              }
            } else {
              if( cell.next().find('[data-slug="'+eventSlug+'"]').length ) {
                var countEvents = cell.next().find('[data-slug="'+eventSlug+'"]').length;
                article.addClass('seriesParentEvent');
              }
            }
          }
        });
      }
    });

    $('.tribe-events-calendar-month__day').each(function(k){
      var cellWidth = $(this).width();
      var items = $(this).find('article.tribe_events');
      //console.log(cellWidth);
      items.each(function(a){
        var target = $(this);
        if( $(this).attr('data-child-count')!=undefined ) {
          //var thisWidth = $(this).outerWidth();
          var count = $(this).attr('data-child-count');
              count = parseInt(count);
          if(count>1) {
            var ncount = count+1;
            var newWidth = cellWidth * ncount;
            //target.css('width',newWidth+'px');
          }
        }  
      });
    });
  }


});// END #####################################    END