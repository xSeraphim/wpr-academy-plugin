(function($) {
    function delay(callback, ms) {
        var timer = 0;
        return function() {
          var context = this, args = arguments;
          clearTimeout(timer);
          timer = setTimeout(function () {
            callback.apply(context, args);
          }, ms || 0);
        };
      }
    $('#wpr-filter select').on('change',doAjax);
    
    $('#wpr-filter input').keyup(delay(doAjax, 500));
    function doAjax(){
        var role = $('#wpr-filter select').val();
        var search_term = $('#wpr-filter input').val();
        data = {
            action: 'search',
            role: role,
            search_term: search_term,
        }
        $.ajax({  
            url: WPR.ajax_url, 
            type: 'GET', 
            data: data,
            success: function(response){
                $('#archive-engineers').empty();
                if (response && response.length) {
                    for (var i = 0; i < response.length; i++) {
                    var html = '<section  class="content"><div class="column-1"><img src='+ response[i]['thumbnail_url'] + ' class="image"></div><div class="column-2"><h2 class="heading"><a href='+ response[i]['url'] + '>'+ response[i]['title'] +'</a></h2></div><div class="column-3"></div></section>';
                     $('#archive-engineers').append(html);
                    }
                 }
            }
        })  
    }
} ) (jQuery); 