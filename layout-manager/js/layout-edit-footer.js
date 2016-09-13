(function ($) {

    $('.inside').css({'display': ''});

    $('#save-button').click(function () {

        var $footerTitle = $('#footer-title');
        var footerTitle = $footerTitle.val().trim();
        var prefix = layout_footer_data.prefix;

        if (footerTitle == '') {
            $footerTitle.css('border-color', 'Red');
        } else {
            var footer = $('#footer-alias').val();

            if (footer == '') {
                $.ajax({
                    url: ajaxurl,
                    async: false,
                    data: {
                        action: 'sanitize_title',
                        string: footerTitle
                    }
                }).done(function (response) {
                    footer = response;
                    $('#footer-alias').val(footer);
                });
            }

            save_custom_options(footer, prefix + footer, 'footer');
            $('#footer-add-edit').submit();
        }
    });

})(jQuery);
