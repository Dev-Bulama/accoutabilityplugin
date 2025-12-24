/**
 * Public JavaScript
 *
 * @package Altitude_Audit
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Animate score bars on scroll
        function animateScoreBars() {
            $('.score-bar').each(function() {
                var $bar = $(this);
                var targetWidth = $bar.css('width');

                if (!$bar.hasClass('animated')) {
                    var scrollTop = $(window).scrollTop();
                    var elementOffset = $bar.offset().top;
                    var distance = elementOffset - scrollTop;
                    var windowHeight = $(window).height();

                    if (distance < windowHeight * 0.75) {
                        $bar.css('width', '0');
                        setTimeout(function() {
                            $bar.css('width', targetWidth);
                        }, 100);
                        $bar.addClass('animated');
                    }
                }
            });
        }

        // Run on page load
        animateScoreBars();

        // Run on scroll
        $(window).on('scroll', animateScoreBars);

        // Client-side scoring (optional enhancement)
        // This calculates scores in real-time as user answers questions
        if (typeof altitudeAuditConfig !== 'undefined') {
            var categories = altitudeAuditConfig.categories;

            // Listen for radio button changes
            $('.altitude-audit-question input[type="radio"]').on('change', function() {
                calculateScores();
            });

            function calculateScores() {
                var categoryScores = {};
                var totalScore = 0;

                // Initialize category scores
                categories.forEach(function(category) {
                    categoryScores[category] = 0;
                });

                // Calculate scores for each category
                categories.forEach(function(category) {
                    var categoryScore = 0;

                    $('input[name^="' + category + '_q"]:checked').each(function() {
                        var value = parseInt($(this).val()) || 0;
                        categoryScore += value;
                    });

                    categoryScores[category] = categoryScore;
                    totalScore += categoryScore;

                    // Update hidden field
                    $('input[name="' + category + '_score"]').val(categoryScore);
                });

                // Update total score
                $('input[name="total_score"]').val(totalScore);

                // Determine accountability type (highest score)
                var highestCategory = '';
                var highestScore = -1;

                categories.forEach(function(category) {
                    if (categoryScores[category] > highestScore) {
                        highestScore = categoryScores[category];
                        highestCategory = category;
                    }
                });

                // Update accountability type hidden field
                $('input[name="accountability_type"]').val(highestCategory);

                // Optional: Show progress indicator
                updateProgressIndicator(categoryScores, totalScore);
            }

            function updateProgressIndicator(categoryScores, totalScore) {
                // Check if progress indicator exists
                if ($('.altitude-audit-progress').length === 0) {
                    return;
                }

                // Update progress display
                $('.altitude-audit-progress-total').text(totalScore + '/54');

                // Update category progress bars
                for (var category in categoryScores) {
                    var score = categoryScores[category];
                    var percentage = (score / 9) * 100;

                    $('.altitude-audit-progress-' + category + ' .progress-bar')
                        .css('width', percentage + '%')
                        .text(score + '/9');
                }
            }
        }

        // Form validation enhancement
        $('.altitude-audit-submit').closest('form').on('submit', function(e) {
            var allQuestionsAnswered = true;

            $('.altitude-audit-question').each(function() {
                var $question = $(this);
                var name = $question.attr('name');
                var $checked = $('input[name="' + name + '"]:checked');

                if ($checked.length === 0) {
                    allQuestionsAnswered = false;
                }
            });

            if (!allQuestionsAnswered) {
                // Fluent Forms has its own validation, but we can add a custom message
                // This is just a backup
                console.log('Please answer all questions');
            }
        });

        // Smooth scroll to first error
        $(document).on('fluentform_validation_error', function(e, data) {
            if (data.errors && data.errors.length > 0) {
                var $firstError = $('.ff-el-is-error').first();
                if ($firstError.length) {
                    $('html, body').animate({
                        scrollTop: $firstError.offset().top - 100
                    }, 500);
                }
            }
        });

        // Add keyboard navigation for radio buttons
        $('.altitude-audit-question input[type="radio"]').on('keydown', function(e) {
            if (e.key === 'ArrowDown' || e.key === 'ArrowRight') {
                e.preventDefault();
                $(this).parent().next().find('input[type="radio"]').focus().click();
            } else if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
                e.preventDefault();
                $(this).parent().prev().find('input[type="radio"]').focus().click();
            }
        });

        // Print results functionality
        $('.print-results-btn').on('click', function(e) {
            e.preventDefault();
            window.print();
        });

        // Share results functionality (if social sharing is added in the future)
        $('.share-results-btn').on('click', function(e) {
            e.preventDefault();
            var url = window.location.href;
            var text = 'I just completed my Personal Accountability Audit!';

            if (navigator.share) {
                navigator.share({
                    title: 'My Accountability Audit Results',
                    text: text,
                    url: url
                });
            } else {
                // Fallback: copy to clipboard
                copyToClipboard(url);
                alert('Link copied to clipboard!');
            }
        });

        function copyToClipboard(text) {
            var $temp = $('<input>');
            $('body').append($temp);
            $temp.val(text).select();
            document.execCommand('copy');
            $temp.remove();
        }

        // Add aria-live region for dynamic updates
        if ($('.altitude-audit-results').length > 0) {
            $('<div role="status" aria-live="polite" class="sr-only"></div>')
                .appendTo('body');
        }
    });

})(jQuery);
