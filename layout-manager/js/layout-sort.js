(function($){

   $( ".layouts-list-sortable tbody" ).sortable();
   $( ".layouts-list-sortable tbody" ).disableSelection();

   var layoutsList = $('.layouts-list-sortable tbody');
   var old_rank, new_rank;
    
    layoutsList.sortable({
        items: 'tr',
        stop: function(event, ui) {
                var list = $('.layouts-list-sortable tbody tr');
                list.removeClass('alt');
                list.each(function(i, el){
                if( ! (i % 2) ) {
                    $(this).addClass('alt');
                }
                $('.save-layouts-sort').show();
            });
        }
    });

    $('.save-layouts-sort').click(function(e){
        var list = $('.layouts-list-sortable tbody tr');
        var ranks = [];

        list.each(function(i){
            ranks.push($(this).attr('data-sort-alias'));
        }); 

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            async:false,
            data: {
                action: 'save_layouts_sort',
                ranks: ranks
            },
        }).done(function(response){ console.log(response);
            document.location = '?page=layout-manager';
        });                       

    });
})(jQuery);    