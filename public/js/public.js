/**
 * Public JavaScript - Standalone Form
 *
 * @package Altitude_Audit
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        var $form = $('#altitude-audit-form');

        if ($form.length === 0) {
            // Form not on this page
            return;
        }

        // Total number of questions
        var totalQuestions = $('input[type="radio"]').length / 4; // 4 options per question

        // Track answered questions
        function updateProgress() {
            var answeredQuestions = 0;

            // Count how many questions have been answered
            $('input[type="radio"]').each(function() {
                var name = $(this).attr('name');
                if ($('input[name="' + name + '"]:checked').length > 0) {
                    answeredQuestions++;
                    // Remove duplicates (we're counting per name)
                }
            });

            // Get unique question names
            var uniqueQuestions = {};
            $('input[type="radio"]').each(function() {
                uniqueQuestions[$(this).attr('name')] = true;
            });

            var answeredCount = 0;
            for (var name in uniqueQuestions) {
                if ($('input[name="' + name + '"]:checked').length > 0) {
                    answeredCount++;
                }
            }

            // Update progress display
            $('#answered-count').text(answeredCount);

            var percentage = (answeredCount / totalQuestions) * 100;
            $('.altitude-progress-fill').css('width', percentage + '%');

            return answeredCount;
        }

        // Calculate scores in real-time
        function calculateScores() {
            var categoryScores = {
                'distraction': 0,
                'comfort': 0,
                'ego': 0,
                'emotion': 0,
                'boundaries': 0,
                'spiritual': 0
            };

            var totalScore = 0;

            // Calculate each category score
            for (var category in categoryScores) {
                var score = 0;

                $('input[data-category="' + category + '"]:checked').each(function() {
                    score += parseInt($(this).val()) || 0;
                });

                categoryScores[category] = score;
                totalScore += score;

                // Update hidden field
                $('#' + category + '_score').val(score);
            }

            // Update total score
            $('#total_score').val(totalScore);

            // Determine highest category
            var highestCategory = '';
            var highestScore = -1;

            for (var cat in categoryScores) {
                if (categoryScores[cat] > highestScore) {
                    highestScore = categoryScores[cat];
                    highestCategory = cat;
                }
            }

            // Update accountability type
            $('#accountability_type').val(highestCategory);
        }

        // Listen for radio button changes
        $('input[type="radio"]').on('change', function() {
            updateProgress();
            calculateScores();

            // Visual feedback for answered question
            $(this).closest('.altitude-question-wrapper').addClass('answered');
        });

        // Auto-fill button (testing mode)
        $('#altitude-autofill-btn').on('click', function(e) {
            e.preventDefault();

            // Fill in name and email
            $('#first_name').val('John Tester');
            $('#email').val('test@example.com');

            // Randomly select answers for each question
            $('input[type="radio"]').each(function() {
                var name = $(this).attr('name');

                // Skip if already filled (to avoid filling same question multiple times)
                if ($('input[name="' + name + '"]:checked').length > 0) {
                    return;
                }

                // Select a random option (0-3)
                var randomValue = Math.floor(Math.random() * 4);
                $('input[name="' + name + '"][value="' + randomValue + '"]').prop('checked', true).trigger('change');
            });

            // Scroll to submit button
            $('html, body').animate({
                scrollTop: $('.altitude-form-submit').offset().top - 100
            }, 500);

            alert('✓ Form auto-filled with random test data!');
        });

        // Form validation before submit
        $form.on('submit', function(e) {
            var isValid = true;
            var errors = [];

            // Check name
            if ($('#first_name').val().trim() === '') {
                errors.push('Please enter your first name');
                isValid = false;
            }

            // Check email
            if ($('#email').val().trim() === '') {
                errors.push('Please enter your email address');
                isValid = false;
            }

            // Check all questions are answered
            var uniqueQuestions = {};
            $('input[type="radio"]').each(function() {
                uniqueQuestions[$(this).attr('name')] = true;
            });

            for (var name in uniqueQuestions) {
                if ($('input[name="' + name + '"]:checked').length === 0) {
                    errors.push('Please answer all questions');
                    isValid = false;
                    break;
                }
            }

            if (!isValid) {
                e.preventDefault();

                alert('Please complete the form:\n\n' + errors.join('\n'));

                // Scroll to first unanswered question
                var $firstUnanswered = $('.altitude-question-wrapper').not('.answered').first();
                if ($firstUnanswered.length) {
                    $('html, body').animate({
                        scrollTop: $firstUnanswered.offset().top - 100
                    }, 500);
                }

                return false;
            }

            // Show loading state
            var $submitBtn = $form.find('button[type="submit"]');
            $submitBtn.prop('disabled', true).text('Processing...');
        });

        // Keyboard navigation
        $('input[type="radio"]').on('keydown', function(e) {
            var $current = $(this);
            var name = $current.attr('name');
            var $allOptions = $('input[name="' + name + '"]');
            var currentIndex = $allOptions.index($current);

            if (e.key === 'ArrowDown' || e.key === 'ArrowRight') {
                e.preventDefault();
                var nextIndex = (currentIndex + 1) % $allOptions.length;
                $allOptions.eq(nextIndex).focus();
            } else if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
                e.preventDefault();
                var prevIndex = (currentIndex - 1 + $allOptions.length) % $allOptions.length;
                $allOptions.eq(prevIndex).focus();
            } else if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $current.prop('checked', true).trigger('change');
            }
        });

        // Animate score bars on results page
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

        // Run animation if on results page
        if ($('.altitude-audit-results').length > 0) {
            animateScoreBars();
            $(window).on('scroll', animateScoreBars);
        }

        // Print results functionality
        $('.print-results-btn').on('click', function(e) {
            e.preventDefault();
            window.print();
        });

        // Initialize progress
        updateProgress();
    });

})(jQuery);
