(function ($) {
    $(document).ready(function () {
        setStates();
    });

    $('body').on('change', '#grid-structure', function () {
        setStates();
    });

    function setStates() {
        var gridStruct = $('#grid-structure').val();
        var $columns = $('#columns');
        var $columnClassFormat = $('#column-class-format');

        switch (gridStruct) {
            case 'bootstrap':
                $columns.val(12);
                //$('#columns').attr('disabled', true);
                //$('#row-class').val('row-fluid');
                $columnClassFormat.val('span#');
                //$('#column-class-format').attr('disabled', true);

                break;

            case '960':
                $columnClassFormat.val('grid_#');
                //$('#column-class-format').attr('disabled', true);
                $columns.attr('disabled', false);

                break;

            case 'unsemantic':
                $columnClassFormat.val('grid-%');
                //$('#column-class-format').attr('disabled', true);
                $columns.attr('disabled', false);

                break;

            case 'custom':
                //$('#column-class-format').attr('disabled', false);
                $columns.attr('disabled', false);

                break;

            default:
                // Nothing to do
                break;
        }
    }

})(jQuery);
