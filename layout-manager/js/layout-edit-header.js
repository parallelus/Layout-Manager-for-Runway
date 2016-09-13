(function ($) {

    $('.inside').css({'display': ''});

    $('#save-button').click(function () {

        var prefix = layout_header_data.prefix;
        var $headerTitle = $('#header-title');
        var headerTitle = $headerTitle.val().trim();

        if (headerTitle == '') {
            $headerTitle.css('border-color', 'Red');
        } else {
            var header = $('#header-alias').val();

            if (header == '') {
                $.ajax({
                    url: ajaxurl,
                    async: false,
                    data: {
                        action: 'sanitize_title',
                        string: headerTitle
                    }
                }).done(function (response) {
                    header = response;
                    $('#header-alias').val(header);
                });
            }

            console.log(prefix + header);
            save_custom_options(header, prefix + header, 'header');
            $('#header-add-edit').submit();
        }
    });

})(jQuery);
