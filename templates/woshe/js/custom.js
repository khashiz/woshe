jQuery(document).ready(function () {
    jQuery('#shipping_date').datepicker({
        dateFormat: 'DD ، d MM yy',
        changeMonth: false,
        changeYear: false,
        minDate: '1d',
        maxDate: '7d'
    });

    jQuery("#codenum").keyup(function () {
        if (jQuery(this).val().length == jQuery(this).attr("maxlength") ) {
            jQuery('button.formSubmit').addClass('add_in_progress').trigger('click');
        }
    });
});