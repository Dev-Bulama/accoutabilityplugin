<?php
/**
 * Form Builder view - Visual drag-and-drop form editor
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

$config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );
?>

<div class="wrap altitude-audit-admin altitude-form-builder">
    <h1><?php esc_html_e( 'Form Builder', 'altitude-accountability-audit' ); ?></h1>
    <p class="description"><?php esc_html_e( 'Drag and drop to reorder categories and questions. Click to edit or delete.', 'altitude-accountability-audit' ); ?></p>

    <div class="builder-container">
        <!-- Builder Toolbar -->
        <div class="builder-toolbar">
            <button type="button" class="button button-primary" id="add-category-btn">
                <span class="dashicons dashicons-plus"></span>
                <?php esc_html_e( 'Add Category', 'altitude-accountability-audit' ); ?>
            </button>

            <button type="button" class="button button-secondary" id="save-builder-btn">
                <span class="dashicons dashicons-yes"></span>
                <?php esc_html_e( 'Save Changes', 'altitude-accountability-audit' ); ?>
            </button>

            <button type="button" class="button" id="preview-form-btn">
                <span class="dashicons dashicons-visibility"></span>
                <?php esc_html_e( 'Preview Form', 'altitude-accountability-audit' ); ?>
            </button>
        </div>

        <!-- Categories List -->
        <div id="categories-list" class="categories-list">
            <?php foreach ( $config['categories'] as $category_key => $category ) : ?>
                <div class="category-item" data-key="<?php echo esc_attr( $category_key ); ?>" draggable="true">
                    <div class="category-header">
                        <span class="drag-handle" title="<?php esc_attr_e( 'Drag to reorder', 'altitude-accountability-audit' ); ?>">
                            <span class="dashicons dashicons-menu"></span>
                        </span>

                        <span class="category-icon"><?php echo esc_html( $category['icon'] ); ?></span>

                        <h3 class="category-title"><?php echo esc_html( $category['label'] ); ?></h3>

                        <div class="category-actions">
                            <button type="button" class="button button-small edit-category-btn" title="<?php esc_attr_e( 'Edit category', 'altitude-accountability-audit' ); ?>">
                                <span class="dashicons dashicons-edit"></span>
                            </button>

                            <button type="button" class="button button-small add-question-btn" title="<?php esc_attr_e( 'Add question', 'altitude-accountability-audit' ); ?>">
                                <span class="dashicons dashicons-plus-alt"></span>
                            </button>

                            <button type="button" class="button button-small button-link-delete delete-category-btn" title="<?php esc_attr_e( 'Delete category', 'altitude-accountability-audit' ); ?>">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </div>
                    </div>

                    <?php if ( ! empty( $category['description'] ) ) : ?>
                        <div class="category-description"><?php echo esc_html( $category['description'] ); ?></div>
                    <?php endif; ?>

                    <!-- Questions List -->
                    <div class="questions-list" data-category="<?php echo esc_attr( $category_key ); ?>">
                        <?php if ( ! empty( $category['questions'] ) ) : ?>
                            <?php foreach ( $category['questions'] as $index => $question ) : ?>
                                <div class="question-item" data-index="<?php echo esc_attr( $index ); ?>" draggable="true">
                                    <span class="drag-handle" title="<?php esc_attr_e( 'Drag to reorder', 'altitude-accountability-audit' ); ?>">
                                        <span class="dashicons dashicons-menu"></span>
                                    </span>

                                    <div class="question-content">
                                        <div class="question-label"><?php echo esc_html( $question['label'] ); ?></div>
                                        <?php if ( ! empty( $question['help_text'] ) ) : ?>
                                            <div class="question-help"><?php echo esc_html( $question['help_text'] ); ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="question-actions">
                                        <button type="button" class="button button-small edit-question-btn" title="<?php esc_attr_e( 'Edit question', 'altitude-accountability-audit' ); ?>">
                                            <span class="dashicons dashicons-edit"></span>
                                        </button>

                                        <button type="button" class="button button-small button-link-delete delete-question-btn" title="<?php esc_attr_e( 'Delete question', 'altitude-accountability-audit' ); ?>">
                                            <span class="dashicons dashicons-trash"></span>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="no-questions">
                                <p><?php esc_html_e( 'No questions yet. Click "Add Question" to get started.', 'altitude-accountability-audit' ); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ( empty( $config['categories'] ) ) : ?>
            <div class="no-categories">
                <p><?php esc_html_e( 'No categories yet. Click "Add Category" to get started.', 'altitude-accountability-audit' ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Category Edit Modal -->
<div id="category-modal" class="altitude-modal" style="display:none;">
    <div class="altitude-modal-overlay"></div>
    <div class="altitude-modal-content">
        <div class="altitude-modal-header">
            <h2 id="category-modal-title"><?php esc_html_e( 'Edit Category', 'altitude-accountability-audit' ); ?></h2>
            <button type="button" class="altitude-modal-close">&times;</button>
        </div>
        <div class="altitude-modal-body">
            <form id="category-form">
                <input type="hidden" id="category-key" name="key">

                <p>
                    <label for="category-label"><?php esc_html_e( 'Category Name', 'altitude-accountability-audit' ); ?></label>
                    <input type="text" id="category-label" name="label" class="regular-text" required>
                </p>

                <p>
                    <label for="category-icon"><?php esc_html_e( 'Icon (emoji)', 'altitude-accountability-audit' ); ?></label>
                    <input type="text" id="category-icon" name="icon" class="small-text" maxlength="2">
                    <span class="description"><?php esc_html_e( 'Single emoji character', 'altitude-accountability-audit' ); ?></span>
                </p>

                <p>
                    <label for="category-description"><?php esc_html_e( 'Description', 'altitude-accountability-audit' ); ?></label>
                    <textarea id="category-description" name="description" rows="3" class="large-text"></textarea>
                </p>
            </form>
        </div>
        <div class="altitude-modal-footer">
            <button type="button" class="button button-primary" id="save-category-btn"><?php esc_html_e( 'Save Category', 'altitude-accountability-audit' ); ?></button>
            <button type="button" class="button altitude-modal-close"><?php esc_html_e( 'Cancel', 'altitude-accountability-audit' ); ?></button>
        </div>
    </div>
</div>

<!-- Question Edit Modal -->
<div id="question-modal" class="altitude-modal" style="display:none;">
    <div class="altitude-modal-overlay"></div>
    <div class="altitude-modal-content">
        <div class="altitude-modal-header">
            <h2 id="question-modal-title"><?php esc_html_e( 'Edit Question', 'altitude-accountability-audit' ); ?></h2>
            <button type="button" class="altitude-modal-close">&times;</button>
        </div>
        <div class="altitude-modal-body">
            <form id="question-form">
                <input type="hidden" id="question-category" name="category">
                <input type="hidden" id="question-index" name="index">

                <p>
                    <label for="question-label"><?php esc_html_e( 'Question Text', 'altitude-accountability-audit' ); ?></label>
                    <textarea id="question-label" name="label" rows="3" class="large-text" required></textarea>
                </p>

                <p>
                    <label for="question-help"><?php esc_html_e( 'Help Text (optional)', 'altitude-accountability-audit' ); ?></label>
                    <input type="text" id="question-help" name="help_text" class="large-text">
                </p>
            </form>
        </div>
        <div class="altitude-modal-footer">
            <button type="button" class="button button-primary" id="save-question-btn"><?php esc_html_e( 'Save Question', 'altitude-accountability-audit' ); ?></button>
            <button type="button" class="button altitude-modal-close"><?php esc_html_e( 'Cancel', 'altitude-accountability-audit' ); ?></button>
        </div>
    </div>
</div>

<!-- Now load the JavaScript -->
<script>
jQuery(document).ready(function($) {
    let draggedElement = null;
    let draggedType = null; // 'category' or 'question'
    let isDirty = false;

    // === DRAG AND DROP ===

    // Category drag events
    $('#categories-list').on('dragstart', '.category-item', function(e) {
        draggedElement = this;
        draggedType = 'category';
        $(this).addClass('dragging');
        e.originalEvent.dataTransfer.effectAllowed = 'move';
    });

    $('#categories-list').on('dragend', '.category-item', function() {
        $(this).removeClass('dragging');
        draggedElement = null;
        draggedType = null;
        markDirty();
    });

    $('#categories-list').on('dragover', '.category-item', function(e) {
        if (draggedType !== 'category') return;
        e.preventDefault();

        const afterElement = getDragAfterElement($('#categories-list')[0], e.originalEvent.clientY);
        if (afterElement == null) {
            $('#categories-list').append(draggedElement);
        } else {
            $('#categories-list')[0].insertBefore(draggedElement, afterElement);
        }
    });

    // Question drag events
    $('.questions-list').on('dragstart', '.question-item', function(e) {
        draggedElement = this;
        draggedType = 'question';
        $(this).addClass('dragging');
        e.originalEvent.dataTransfer.effectAllowed = 'move';
    });

    $('.questions-list').on('dragend', '.question-item', function() {
        $(this).removeClass('dragging');
        // Renumber questions
        renumberQuestions($(this).closest('.questions-list'));
        draggedElement = null;
        draggedType = null;
        markDirty();
    });

    $('.questions-list').on('dragover', '.question-item', function(e) {
        if (draggedType !== 'question') return;
        e.preventDefault();

        const container = $(this).closest('.questions-list')[0];
        const afterElement = getDragAfterElement(container, e.originalEvent.clientY);

        if (afterElement == null) {
            container.appendChild(draggedElement);
        } else {
            container.insertBefore(draggedElement, afterElement);
        }
    });

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.category-item:not(.dragging), .question-item:not(.dragging)')];

        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;

            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    function renumberQuestions($list) {
        $list.find('.question-item').each(function(index) {
            $(this).attr('data-index', index);
        });
    }

    function markDirty() {
        isDirty = true;
        $('#save-builder-btn').addClass('button-primary').removeClass('button-secondary');
    }

    // === ADD CATEGORY ===

    $('#add-category-btn').on('click', function() {
        $('#category-modal-title').text('<?php esc_html_e( 'Add New Category', 'altitude-accountability-audit' ); ?>');
        $('#category-form')[0].reset();
        $('#category-key').val(''); // Empty = new
        $('#category-modal').fadeIn();
    });

    // === EDIT CATEGORY ===

    $(document).on('click', '.edit-category-btn', function() {
        const $category = $(this).closest('.category-item');
        const key = $category.data('key');
        const title = $category.find('.category-title').text();
        const icon = $category.find('.category-icon').text();
        const description = $category.find('.category-description').text();

        $('#category-modal-title').text('<?php esc_html_e( 'Edit Category', 'altitude-accountability-audit' ); ?>');
        $('#category-key').val(key);
        $('#category-label').val(title);
        $('#category-icon').val(icon);
        $('#category-description').val(description);
        $('#category-modal').fadeIn();
    });

    // === SAVE CATEGORY ===

    $('#save-category-btn').on('click', function() {
        const key = $('#category-key').val();
        const data = {
            label: $('#category-label').val(),
            icon: $('#category-icon').val(),
            description: $('#category-description').val()
        };

        if (!data.label) {
            alert('<?php esc_html_e( 'Please enter a category name', 'altitude-accountability-audit' ); ?>');
            return;
        }

        if (key) {
            // Update existing
            $.post(ajaxurl, {
                action: 'altitude_audit_update_category',
                nonce: altitudeAuditAdmin.nonce,
                key: key,
                data: JSON.stringify(data)
            }, function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.data.message);
                }
            });
        } else {
            // Add new
            $.post(ajaxurl, {
                action: 'altitude_audit_add_category',
                nonce: altitudeAuditAdmin.nonce,
                label: data.label,
                icon: data.icon
            }, function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.data.message);
                }
            });
        }
    });

    // === DELETE CATEGORY ===

    $(document).on('click', '.delete-category-btn', function() {
        if (!confirm('<?php esc_html_e( 'Are you sure you want to delete this category and all its questions?', 'altitude-accountability-audit' ); ?>')) {
            return;
        }

        const key = $(this).closest('.category-item').data('key');

        $.post(ajaxurl, {
            action: 'altitude_audit_delete_category',
            nonce: altitudeAuditAdmin.nonce,
            key: key
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.data.message);
            }
        });
    });

    // === ADD QUESTION ===

    $(document).on('click', '.add-question-btn', function() {
        const $category = $(this).closest('.category-item');
        const categoryKey = $category.data('key');

        $('#question-modal-title').text('<?php esc_html_e( 'Add New Question', 'altitude-accountability-audit' ); ?>');
        $('#question-form')[0].reset();
        $('#question-category').val(categoryKey);
        $('#question-index').val(''); // Empty = new
        $('#question-modal').fadeIn();
    });

    // === EDIT QUESTION ===

    $(document).on('click', '.edit-question-btn', function() {
        const $question = $(this).closest('.question-item');
        const $category = $question.closest('.category-item');
        const categoryKey = $category.data('key');
        const index = $question.data('index');
        const label = $question.find('.question-label').text();
        const help = $question.find('.question-help').text();

        $('#question-modal-title').text('<?php esc_html_e( 'Edit Question', 'altitude-accountability-audit' ); ?>');
        $('#question-category').val(categoryKey);
        $('#question-index').val(index);
        $('#question-label').val(label);
        $('#question-help').val(help);
        $('#question-modal').fadeIn();
    });

    // === SAVE QUESTION ===

    $('#save-question-btn').on('click', function() {
        const category = $('#question-category').val();
        const index = $('#question-index').val();
        const data = {
            label: $('#question-label').val(),
            help_text: $('#question-help').val()
        };

        if (!data.label) {
            alert('<?php esc_html_e( 'Please enter a question', 'altitude-accountability-audit' ); ?>');
            return;
        }

        if (index !== '') {
            // Update existing
            $.post(ajaxurl, {
                action: 'altitude_audit_update_question',
                nonce: altitudeAuditAdmin.nonce,
                category: category,
                index: index,
                data: JSON.stringify(data)
            }, function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.data.message);
                }
            });
        } else {
            // Add new
            $.post(ajaxurl, {
                action: 'altitude_audit_add_question',
                nonce: altitudeAuditAdmin.nonce,
                category: category,
                label: data.label
            }, function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.data.message);
                }
            });
        }
    });

    // === DELETE QUESTION ===

    $(document).on('click', '.delete-question-btn', function() {
        if (!confirm('<?php esc_html_e( 'Are you sure you want to delete this question?', 'altitude-accountability-audit' ); ?>')) {
            return;
        }

        const $question = $(this).closest('.question-item');
        const $category = $question.closest('.category-item');
        const categoryKey = $category.data('key');
        const index = $question.data('index');

        $.post(ajaxurl, {
            action: 'altitude_audit_delete_question',
            nonce: altitudeAuditAdmin.nonce,
            category: categoryKey,
            index: index
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.data.message);
            }
        });
    });

    // === SAVE BUILDER (Reorder) ===

    $('#save-builder-btn').on('click', function() {
        const categories = {};

        $('#categories-list .category-item').each(function() {
            const key = $(this).data('key');
            const $category = $(this);

            const questions = [];
            $category.find('.question-item').each(function(index) {
                const $q = $(this);
                questions.push({
                    label: $q.find('.question-label').text(),
                    name: key + '_q' + (index + 1),
                    help_text: $q.find('.question-help').text()
                });
            });

            categories[key] = {
                label: $category.find('.category-title').text(),
                description: $category.find('.category-description').text(),
                icon: $category.find('.category-icon').text(),
                questions: questions
            };
        });

        $.post(ajaxurl, {
            action: 'altitude_audit_save_builder',
            nonce: altitudeAuditAdmin.nonce,
            categories: JSON.stringify(categories)
        }, function(response) {
            if (response.success) {
                isDirty = false;
                $('#save-builder-btn').removeClass('button-primary').addClass('button-secondary');
                alert(response.data.message);
            } else {
                alert(response.data.message);
            }
        });
    });

    // === MODAL CONTROLS ===

    $('.altitude-modal-close, .altitude-modal-overlay').on('click', function() {
        $(this).closest('.altitude-modal').fadeOut();
    });

    // === WARN ON EXIT ===

    $(window).on('beforeunload', function() {
        if (isDirty) {
            return '<?php esc_html_e( 'You have unsaved changes. Are you sure you want to leave?', 'altitude-accountability-audit' ); ?>';
        }
    });

    // === PREVIEW FORM ===

    $('#preview-form-btn').on('click', function() {
        window.open('<?php echo esc_url( home_url( '/?preview_altitude_audit=1' ) ); ?>', '_blank');
    });
});
</script>
