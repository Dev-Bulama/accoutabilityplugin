/**
 * Admin JavaScript
 *
 * @package Altitude_Audit
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Auto-save indication
        var $form = $('.altitude-audit-settings form, .altitude-audit-email form');
        var originalData = $form.serialize();

        $form.on('change', function() {
            var currentData = $form.serialize();
            if (currentData !== originalData) {
                $('.wrap h1').append(' <span class="unsaved-changes" style="color: #f0ad4e; font-size: 14px;">(Unsaved Changes)</span>');
            } else {
                $('.unsaved-changes').remove();
            }
        });

        // Confirm navigation away with unsaved changes
        $(window).on('beforeunload', function() {
            if ($('.unsaved-changes').length > 0) {
                return 'You have unsaved changes. Are you sure you want to leave?';
            }
        });

        // Remove unsaved warning on form submit
        $form.on('submit', function() {
            $(window).off('beforeunload');
        });

        // Insert merge tag at cursor
        $('.merge-tag-item code').on('click', function() {
            var tag = $(this).text();
            var $textarea = $('#email-template-editor');

            if ($textarea.length) {
                var cursorPos = $textarea.prop('selectionStart');
                var textBefore = $textarea.val().substring(0, cursorPos);
                var textAfter = $textarea.val().substring(cursorPos);

                $textarea.val(textBefore + tag + textAfter);
                $textarea.focus();

                // Set cursor position after inserted tag
                var newPos = cursorPos + tag.length;
                $textarea[0].setSelectionRange(newPos, newPos);
            }
        });

        // Add tooltips to help icons (if any are added in the future)
        $('.help-icon').on('mouseenter', function() {
            var tooltip = $(this).data('tooltip');
            $(this).append('<div class="tooltip">' + tooltip + '</div>');
        }).on('mouseleave', function() {
            $(this).find('.tooltip').remove();
        });
    });

})(jQuery);
