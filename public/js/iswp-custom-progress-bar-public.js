(function ($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
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
    $(window).load(function () {

        var bar_holder = document.getElementById("iswpcpb-holder");

        // Bar starts hidden to hide this repositioning

        // Relocate progress bar
        var profile_info = document.querySelector(".profile_info");
        profile_info.parentNode.insertBefore(
            bar_holder,
            profile_info.nextSibling
        )

        // Re-show bar
        $(bar_holder).show("fast");

    });

})(jQuery);
