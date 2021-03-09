(function ($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */
    $(function() {

        // Activate datepicker
        $('.jqueryui-datepicker').datepicker({
            dateFormat : 'yy-mm-dd',
            changeYear: true,
            changeMonth: true,
        });

        // Function that sets the correct state for the date
        let setDatepickerState = function () {
            let is_checked = $("#_wsp_payment_received").is(":checked");
            let datepicker = $("#_wsp_payment_date");

            if (is_checked) {
                $(datepicker).datepicker("option", "disabled", false);
            } else {
                $(datepicker).datepicker("option", "disabled", true);
            }
        }
        // Run once, on load
        setDatepickerState();
        // Run on every click
        $("#_wsp_payment_received").click(setDatepickerState);
    });

})(jQuery);
