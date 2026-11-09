<?php
/**
 * Form Builder view - Fluent Forms-style 3-panel drag-and-drop editor
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

$config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );
?>

<div class="altitude-form-builder-wrap">
	<!-- Top Toolbar -->
	<div class="builder-top-toolbar">
		<div class="builder-logo">
			<h1><?php esc_html_e( 'Form Builder', 'altitude-accountability-audit' ); ?></h1>
		</div>
		<div class="builder-actions">
			<button type="button" class="button" id="save-builder-btn">
				<span class="dashicons dashicons-yes"></span>
				<?php esc_html_e( 'Save Changes', 'altitude-accountability-audit' ); ?>
			</button>
			<button type="button" class="button button-primary" id="publish-builder-btn">
				<span class="dashicons dashicons-saved"></span>
				<?php esc_html_e( 'Publish', 'altitude-accountability-audit' ); ?>
			</button>
		</div>
	</div>

	<!-- 3-Panel Layout -->
	<div class="builder-main-container">
		<!-- LEFT PANEL: Elements Library -->
		<div class="builder-left-panel">
			<div class="panel-header">
				<h3><?php esc_html_e( 'Form Elements', 'altitude-accountability-audit' ); ?></h3>
			</div>
			<div class="elements-library">
				<div class="element-group">
					<h4><?php esc_html_e( 'Quiz Elements', 'altitude-accountability-audit' ); ?></h4>
					<div class="element-item" draggable="true" data-element-type="category">
						<span class="element-icon">📂</span>
						<div class="element-info">
							<strong><?php esc_html_e( 'Category Section', 'altitude-accountability-audit' ); ?></strong>
							<small><?php esc_html_e( 'Group of questions', 'altitude-accountability-audit' ); ?></small>
						</div>
					</div>
					<div class="element-item" draggable="true" data-element-type="question">
						<span class="element-icon">❓</span>
						<div class="element-info">
							<strong><?php esc_html_e( 'Quiz Question', 'altitude-accountability-audit' ); ?></strong>
							<small><?php esc_html_e( '4-point scale question', 'altitude-accountability-audit' ); ?></small>
						</div>
					</div>
				</div>

				<div class="element-group">
					<h4><?php esc_html_e( 'Form Fields', 'altitude-accountability-audit' ); ?></h4>
					<div class="element-item disabled" title="<?php esc_attr_e( 'Name and Email fields are always included', 'altitude-accountability-audit' ); ?>">
						<span class="element-icon">👤</span>
						<div class="element-info">
							<strong><?php esc_html_e( 'Personal Info', 'altitude-accountability-audit' ); ?></strong>
							<small><?php esc_html_e( 'Name & Email (built-in)', 'altitude-accountability-audit' ); ?></small>
						</div>
					</div>
				</div>

				<!-- Drag-and-drop reordering (restored) -->
				<div class="element-group altitude-structure-group">
					<h4><?php esc_html_e( 'Form Structure', 'altitude-accountability-audit' ); ?></h4>
					<p class="altitude-structure-hint"><?php esc_html_e( 'Drag to reorder categories and questions. The new order is saved automatically.', 'altitude-accountability-audit' ); ?></p>
					<div class="altitude-structure-status" id="altitude-structure-status" role="status" aria-live="polite"></div>
					<ul id="altitude-structure-list" class="altitude-structure-list"></ul>
				</div>
			</div>
		</div>

		<!-- CENTER PANEL: Live Preview -->
		<div class="builder-center-panel">
			<div class="panel-header">
				<h3><?php esc_html_e( 'Form Preview', 'altitude-accountability-audit' ); ?></h3>
				<div class="preview-actions">
					<button type="button" class="button button-small" id="refresh-preview-btn">
						<span class="dashicons dashicons-update"></span>
						<?php esc_html_e( 'Refresh', 'altitude-accountability-audit' ); ?>
					</button>
				</div>
			</div>
			<div class="form-preview-container" id="form-preview-area">
				<!-- Live form preview will be rendered here -->
				<div class="form-preview-inner">
					<?php echo Altitude_Audit_Form_Builder::render_form(); ?>
				</div>
			</div>
		</div>

		<!-- RIGHT PANEL: Field Settings -->
		<div class="builder-right-panel">
			<div class="panel-header">
				<h3><?php esc_html_e( 'Field Settings', 'altitude-accountability-audit' ); ?></h3>
			</div>
			<div class="settings-panel-content" id="settings-panel">
				<div class="no-selection-message">
					<span class="dashicons dashicons-admin-settings"></span>
					<p><?php esc_html_e( 'Click on a category or question to edit its settings', 'altitude-accountability-audit' ); ?></p>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Category Settings Template -->
<script type="text/template" id="category-settings-template">
	<div class="settings-section">
		<h4><?php esc_html_e( 'Category Settings', 'altitude-accountability-audit' ); ?></h4>
		<input type="hidden" id="edit-category-key" value="{{key}}">

		<div class="setting-field">
			<label for="edit-category-label"><?php esc_html_e( 'Category Name', 'altitude-accountability-audit' ); ?></label>
			<input type="text" id="edit-category-label" class="regular-text" value="{{label}}" placeholder="<?php esc_attr_e( 'e.g., Distraction', 'altitude-accountability-audit' ); ?>">
		</div>

		<div class="setting-field">
			<label for="edit-category-icon"><?php esc_html_e( 'Icon (Emoji)', 'altitude-accountability-audit' ); ?></label>
			<input type="text" id="edit-category-icon" class="small-text" value="{{icon}}" maxlength="2" placeholder="📱">
			<p class="description"><?php esc_html_e( 'Single emoji character', 'altitude-accountability-audit' ); ?></p>
		</div>

		<div class="setting-field">
			<label for="edit-category-description"><?php esc_html_e( 'Description', 'altitude-accountability-audit' ); ?></label>
			<textarea id="edit-category-description" rows="3" class="large-text" placeholder="<?php esc_attr_e( 'Describe this category...', 'altitude-accountability-audit' ); ?>">{{description}}</textarea>
		</div>

		<div class="setting-actions">
			<button type="button" class="button button-primary button-large" id="update-category-btn">
				<span class="dashicons dashicons-yes"></span>
				<?php esc_html_e( 'Update Category', 'altitude-accountability-audit' ); ?>
			</button>
			<button type="button" class="button button-link-delete" id="delete-category-btn">
				<span class="dashicons dashicons-trash"></span>
				<?php esc_html_e( 'Delete Category', 'altitude-accountability-audit' ); ?>
			</button>
		</div>

		<hr>

		<div class="category-questions-section">
			<h4><?php esc_html_e( 'Questions in this Category', 'altitude-accountability-audit' ); ?></h4>
			<button type="button" class="button button-secondary button-large" id="add-question-to-category-btn">
				<span class="dashicons dashicons-plus"></span>
				<?php esc_html_e( 'Add Question', 'altitude-accountability-audit' ); ?>
			</button>
			<div class="questions-list-sidebar">
				{{questions_html}}
			</div>
		</div>
	</div>
</script>

<!-- Question Settings Template -->
<script type="text/template" id="question-settings-template">
	<div class="settings-section">
		<h4><?php esc_html_e( 'Question Settings', 'altitude-accountability-audit' ); ?></h4>
		<input type="hidden" id="edit-question-category" value="{{category}}">
		<input type="hidden" id="edit-question-index" value="{{index}}">

		<div class="setting-field">
			<label for="edit-question-label"><?php esc_html_e( 'Question Text', 'altitude-accountability-audit' ); ?></label>
			<textarea id="edit-question-label" rows="4" class="large-text" placeholder="<?php esc_attr_e( 'Enter your question here...', 'altitude-accountability-audit' ); ?>">{{label}}</textarea>
		</div>

		<div class="setting-field">
			<label for="edit-question-help"><?php esc_html_e( 'Help Text (Optional)', 'altitude-accountability-audit' ); ?></label>
			<input type="text" id="edit-question-help" class="large-text" value="{{help_text}}" placeholder="<?php esc_attr_e( 'Additional guidance...', 'altitude-accountability-audit' ); ?>">
			<p class="description"><?php esc_html_e( 'This text appears below the question', 'altitude-accountability-audit' ); ?></p>
		</div>

		<div class="setting-field">
			<label><?php esc_html_e( 'Answer Options', 'altitude-accountability-audit' ); ?></label>
			<div class="answer-options-preview">
				<div class="option-preview">0 points - Not true</div>
				<div class="option-preview">1 point - Sometimes true</div>
				<div class="option-preview">2 points - Often true</div>
				<div class="option-preview">3 points - This is my pattern</div>
			</div>
			<p class="description"><?php esc_html_e( 'Standard 4-point scale for all questions', 'altitude-accountability-audit' ); ?></p>
		</div>

		<div class="setting-actions">
			<button type="button" class="button button-primary button-large" id="update-question-btn">
				<span class="dashicons dashicons-yes"></span>
				<?php esc_html_e( 'Update Question', 'altitude-accountability-audit' ); ?>
			</button>
			<button type="button" class="button button-link-delete" id="delete-question-btn">
				<span class="dashicons dashicons-trash"></span>
				<?php esc_html_e( 'Delete Question', 'altitude-accountability-audit' ); ?>
			</button>
		</div>
	</div>
</script>

<!-- Add Category Modal (Quick Add) -->
<div id="add-category-modal" class="altitude-modal" style="display:none;">
	<div class="altitude-modal-overlay"></div>
	<div class="altitude-modal-content">
		<div class="altitude-modal-header">
			<h2><?php esc_html_e( 'Add New Category', 'altitude-accountability-audit' ); ?></h2>
			<button type="button" class="altitude-modal-close">&times;</button>
		</div>
		<div class="altitude-modal-body">
			<form id="add-category-form">
				<p>
					<label for="new-category-label"><?php esc_html_e( 'Category Name', 'altitude-accountability-audit' ); ?></label>
					<input type="text" id="new-category-label" class="regular-text" required placeholder="<?php esc_attr_e( 'e.g., Distraction', 'altitude-accountability-audit' ); ?>">
				</p>
				<p>
					<label for="new-category-icon"><?php esc_html_e( 'Icon (Emoji)', 'altitude-accountability-audit' ); ?></label>
					<input type="text" id="new-category-icon" class="small-text" maxlength="2" placeholder="📱">
				</p>
			</form>
		</div>
		<div class="altitude-modal-footer">
			<button type="button" class="button button-primary" id="create-category-btn"><?php esc_html_e( 'Create Category', 'altitude-accountability-audit' ); ?></button>
			<button type="button" class="button altitude-modal-close"><?php esc_html_e( 'Cancel', 'altitude-accountability-audit' ); ?></button>
		</div>
	</div>
</div>

<!-- Add Question Modal (Quick Add) -->
<div id="add-question-modal" class="altitude-modal" style="display:none;">
	<div class="altitude-modal-overlay"></div>
	<div class="altitude-modal-content">
		<div class="altitude-modal-header">
			<h2><?php esc_html_e( 'Add New Question', 'altitude-accountability-audit' ); ?></h2>
			<button type="button" class="altitude-modal-close">&times;</button>
		</div>
		<div class="altitude-modal-body">
			<form id="add-question-form">
				<input type="hidden" id="new-question-category">
				<p>
					<label for="new-question-label"><?php esc_html_e( 'Question Text', 'altitude-accountability-audit' ); ?></label>
					<textarea id="new-question-label" rows="4" class="large-text" required placeholder="<?php esc_attr_e( 'Enter your question here...', 'altitude-accountability-audit' ); ?>"></textarea>
				</p>
			</form>
		</div>
		<div class="altitude-modal-footer">
			<button type="button" class="button button-primary" id="create-question-btn"><?php esc_html_e( 'Create Question', 'altitude-accountability-audit' ); ?></button>
			<button type="button" class="button altitude-modal-close"><?php esc_html_e( 'Cancel', 'altitude-accountability-audit' ); ?></button>
		</div>
	</div>
</div>

<!-- Edit Category Modal (Comprehensive) -->
<div id="edit-category-modal" class="altitude-modal altitude-modal-large" style="display:none;">
	<div class="altitude-modal-overlay"></div>
	<div class="altitude-modal-content">
		<div class="altitude-modal-header">
			<h2><?php esc_html_e( 'Edit Category', 'altitude-accountability-audit' ); ?></h2>
			<button type="button" class="altitude-modal-close">&times;</button>
		</div>
		<div class="altitude-modal-body" id="edit-category-modal-body">
			<!-- Content will be dynamically generated -->
		</div>
		<div class="altitude-modal-footer">
			<button type="button" class="button button-primary button-large" id="save-category-changes-btn">
				<span class="dashicons dashicons-yes"></span>
				<?php esc_html_e( 'Save All Changes', 'altitude-accountability-audit' ); ?>
			</button>
			<button type="button" class="button altitude-modal-close"><?php esc_html_e( 'Close', 'altitude-accountability-audit' ); ?></button>
		</div>
	</div>
</div>

<style>
/* =======================
   BUILDER WRAPPER
   ======================= */
.altitude-form-builder-wrap {
	margin: -20px -20px -12px -2px;
	background: #f8f9fa;
	min-height: calc(100vh - 32px);
	display: flex;
	flex-direction: column;
}

/* =======================
   TOP TOOLBAR
   ======================= */
.builder-top-toolbar {
	background: white;
	border-bottom: 1px solid #ddd;
	padding: 15px 30px;
	display: flex;
	justify-content: space-between;
	align-items: center;
	box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.builder-logo h1 {
	margin: 0;
	font-size: 20px;
	color: #667eea;
}

.builder-actions {
	display: flex;
	gap: 10px;
}

/* =======================
   3-PANEL LAYOUT
   ======================= */
.builder-main-container {
	display: grid;
	grid-template-columns: 280px 1fr 320px;
	gap: 0;
	flex: 1;
	overflow: hidden;
}

/* =======================
   LEFT PANEL
   ======================= */
.builder-left-panel {
	background: white;
	border-right: 1px solid #ddd;
	overflow-y: auto;
	display: flex;
	flex-direction: column;
}

.panel-header {
	padding: 20px;
	border-bottom: 1px solid #e5e5e5;
	display: flex;
	justify-content: space-between;
	align-items: center;
}

.panel-header h3 {
	margin: 0;
	font-size: 16px;
	font-weight: 600;
	color: #333;
}

.elements-library {
	padding: 15px;
}

.element-group {
	margin-bottom: 25px;
}

.element-group h4 {
	font-size: 12px;
	text-transform: uppercase;
	color: #666;
	margin: 0 0 12px 0;
	font-weight: 600;
	letter-spacing: 0.5px;
}

.element-item {
	background: #f7f9fc;
	border: 2px solid #e5e5e5;
	border-radius: 8px;
	padding: 12px;
	margin-bottom: 10px;
	cursor: grab;
	transition: all 0.2s;
	display: flex;
	align-items: center;
	gap: 12px;
}

.element-item:hover {
	border-color: #667eea;
	background: #f0f3ff;
	transform: translateX(5px);
}

.element-item.disabled {
	opacity: 0.5;
	cursor: not-allowed;
}

.element-item.disabled:hover {
	transform: none;
	border-color: #e5e5e5;
	background: #f7f9fc;
}

.element-item:active {
	cursor: grabbing;
}

.element-icon {
	font-size: 24px;
	flex-shrink: 0;
}

.element-info {
	flex: 1;
}

.element-info strong {
	display: block;
	font-size: 14px;
	color: #333;
	margin-bottom: 3px;
}

.element-info small {
	font-size: 12px;
	color: #666;
}

/* =======================
   CENTER PANEL
   ======================= */
.builder-center-panel {
	background: #f8f9fa;
	overflow-y: auto;
	display: flex;
	flex-direction: column;
}

.preview-actions {
	display: flex;
	gap: 8px;
}

.form-preview-container {
	flex: 1;
	padding: 30px;
	overflow-y: auto;
}

.form-preview-inner {
	max-width: 900px;
	margin: 0 auto;
	background: white;
	padding: 40px;
	border-radius: 12px;
	box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Highlight elements on hover in preview */
.form-preview-inner .altitude-category-section {
	position: relative;
	padding: 10px;
	margin: -10px -10px 20px -10px;
	border-radius: 8px;
	cursor: pointer;
	transition: all 0.2s;
}

.form-preview-inner .altitude-category-section:hover {
	background: rgba(102, 126, 234, 0.05);
	outline: 2px solid #667eea;
}

.form-preview-inner .altitude-question-wrapper {
	cursor: pointer;
	position: relative;
}

.form-preview-inner .altitude-question-wrapper:hover {
	outline: 2px solid #667eea;
}

/* =======================
   RIGHT PANEL
   ======================= */
.builder-right-panel {
	background: white;
	border-left: 1px solid #ddd;
	overflow-y: auto;
	display: flex;
	flex-direction: column;
}

.settings-panel-content {
	flex: 1;
	padding: 20px;
}

.no-selection-message {
	text-align: center;
	color: #999;
	padding: 60px 20px;
}

.no-selection-message .dashicons {
	font-size: 64px;
	width: 64px;
	height: 64px;
	margin-bottom: 15px;
	opacity: 0.3;
}

.no-selection-message p {
	margin: 0;
	font-size: 14px;
}

/* Settings Fields */
.settings-section h4 {
	margin: 0 0 20px 0;
	padding-bottom: 10px;
	border-bottom: 2px solid #667eea;
	color: #333;
	font-size: 15px;
}

.setting-field {
	margin-bottom: 20px;
}

.setting-field label {
	display: block;
	font-weight: 600;
	margin-bottom: 8px;
	color: #333;
	font-size: 13px;
}

.setting-field input[type="text"],
.setting-field textarea {
	width: 100%;
	padding: 8px 12px;
	border: 1px solid #ddd;
	border-radius: 4px;
	font-size: 13px;
}

.setting-field input[type="text"]:focus,
.setting-field textarea:focus {
	border-color: #667eea;
	outline: none;
	box-shadow: 0 0 0 1px #667eea;
}

.setting-field .description {
	margin: 5px 0 0 0;
	font-size: 12px;
	color: #666;
	font-style: italic;
}

.answer-options-preview {
	background: #f7f9fc;
	padding: 12px;
	border-radius: 6px;
	border: 1px solid #e5e5e5;
}

.option-preview {
	padding: 8px;
	margin-bottom: 6px;
	background: white;
	border-radius: 4px;
	font-size: 12px;
	color: #333;
}

.option-preview:last-child {
	margin-bottom: 0;
}

.setting-actions {
	margin-top: 30px;
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.setting-actions .button {
	width: 100%;
	justify-content: center;
	height: auto;
	padding: 10px 20px;
}

.questions-list-sidebar {
	margin-top: 15px;
	display: grid;
	gap: 8px;
}

.question-sidebar-item {
	background: #f7f9fc;
	padding: 10px;
	border-radius: 4px;
	font-size: 13px;
	border: 1px solid #e5e5e5;
	cursor: pointer;
	transition: all 0.2s;
}

.question-sidebar-item:hover {
	border-color: #667eea;
	background: #f0f3ff;
}

/* =======================
   MODAL STYLES
   ======================= */
.altitude-modal {
	position: fixed;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	z-index: 100000;
}

.altitude-modal-overlay {
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background: rgba(0, 0, 0, 0.7);
}

.altitude-modal-content {
	position: relative;
	background: white;
	max-width: 600px;
	margin: 50px auto;
	border-radius: 8px;
	box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
	z-index: 100001;
	max-height: calc(100vh - 100px);
	display: flex;
	flex-direction: column;
}

.altitude-modal-large .altitude-modal-content {
	max-width: 900px;
}

.altitude-modal-header {
	padding: 20px 30px;
	border-bottom: 1px solid #e5e5e5;
	display: flex;
	justify-content: space-between;
	align-items: center;
}

.altitude-modal-header h2 {
	margin: 0;
	font-size: 20px;
	color: #667eea;
}

.altitude-modal-close {
	background: none;
	border: none;
	font-size: 32px;
	line-height: 1;
	color: #999;
	cursor: pointer;
	padding: 0;
	width: 32px;
	height: 32px;
}

.altitude-modal-close:hover {
	color: #333;
}

.altitude-modal-body {
	padding: 30px;
	overflow-y: auto;
	flex: 1;
}

.altitude-modal-footer {
	padding: 20px 30px;
	border-top: 1px solid #e5e5e5;
	display: flex;
	gap: 10px;
	justify-content: flex-end;
}

.edit-category-section {
	background: #f7f9fc;
	padding: 20px;
	border-radius: 8px;
	margin-bottom: 20px;
	border: 1px solid #e5e5e5;
}

.edit-category-section h3 {
	margin: 0 0 15px 0;
	color: #667eea;
	font-size: 16px;
}

.edit-category-section .form-field {
	margin-bottom: 15px;
}

.edit-category-section .form-field:last-child {
	margin-bottom: 0;
}

.edit-category-section label {
	display: block;
	font-weight: 600;
	margin-bottom: 5px;
	color: #333;
	font-size: 13px;
}

.edit-category-section input[type="text"],
.edit-category-section textarea {
	width: 100%;
	padding: 8px 12px;
	border: 1px solid #ddd;
	border-radius: 4px;
	font-size: 13px;
}

.edit-questions-list {
	margin-top: 20px;
}

.edit-question-item {
	background: white;
	border: 1px solid #e5e5e5;
	border-radius: 8px;
	padding: 15px;
	margin-bottom: 15px;
}

.edit-question-item-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 15px;
}

.edit-question-item-header h4 {
	margin: 0;
	color: #667eea;
	font-size: 14px;
}

.edit-question-item-header .delete-question-btn-inline {
	color: #dc3545;
	text-decoration: none;
	font-size: 12px;
	cursor: pointer;
}

.edit-question-item-header .delete-question-btn-inline:hover {
	text-decoration: underline;
}

.add-question-btn-inline {
	width: 100%;
	padding: 12px;
	text-align: center;
	border: 2px dashed #667eea;
	background: #f0f3ff;
	color: #667eea;
	border-radius: 8px;
	cursor: pointer;
	font-weight: 600;
	margin-top: 15px;
}

.add-question-btn-inline:hover {
	background: #667eea;
	color: white;
}

/* =======================
   RESPONSIVE
   ======================= */
@media (max-width: 1400px) {
	.builder-main-container {
		grid-template-columns: 260px 1fr 300px;
	}
}

@media (max-width: 1200px) {
	.builder-main-container {
		grid-template-columns: 240px 1fr 280px;
	}
}

@media (max-width: 900px) {
	.builder-main-container {
		grid-template-columns: 1fr;
	}

	.builder-left-panel,
	.builder-right-panel {
		display: none;
	}
}

/* =======================
   DRAG-AND-DROP (restored)
   ======================= */
.altitude-structure-hint {
	margin: 0 0 10px 0;
	font-size: 12px;
	color: #666;
}

.altitude-structure-status {
	min-height: 18px;
	margin-bottom: 8px;
	font-size: 12px;
	font-weight: 600;
}

.altitude-structure-status.is-saving { color: #666; }
.altitude-structure-status.is-success { color: #1e7e34; }
.altitude-structure-status.is-error { color: #dc3545; }

.altitude-structure-list,
.altitude-structure-questions {
	list-style: none;
	margin: 0;
	padding: 0;
}

.altitude-structure-list.is-saving {
	opacity: 0.6;
	pointer-events: none;
}

.altitude-structure-category {
	background: #fff;
	border: 2px solid #e5e5e5;
	border-radius: 8px;
	margin: 0 0 10px 0;
	overflow: hidden;
}

.altitude-structure-category-header {
	display: flex;
	align-items: center;
	gap: 8px;
	padding: 8px 10px;
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	color: #fff;
	font-weight: 600;
	font-size: 13px;
	cursor: grab;
}

.altitude-structure-category-header .altitude-structure-count {
	margin-left: auto;
	font-weight: normal;
	opacity: 0.85;
	font-size: 11px;
}

.altitude-structure-questions {
	padding: 6px;
	min-height: 10px;
}

.altitude-structure-question {
	display: flex;
	align-items: center;
	gap: 6px;
	padding: 6px 8px;
	margin: 0 0 4px 0;
	background: #f7f9fc;
	border: 1px solid #e5e5e5;
	border-radius: 4px;
	font-size: 12px;
	color: #333;
	cursor: grab;
}

.altitude-structure-question:hover {
	border-color: #667eea;
	background: #f0f3ff;
}

.altitude-structure-question:last-child {
	margin-bottom: 0;
}

.altitude-structure-label {
	flex: 1;
	min-width: 0;
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
}

.altitude-structure-list .altitude-drag-handle {
	display: inline-flex;
	align-items: center;
	flex-shrink: 0;
	cursor: grab;
	opacity: 0.7;
	touch-action: none; /* lets touch devices drag via the handle without scrolling */
}

.altitude-structure-list .altitude-drag-handle:hover {
	opacity: 1;
}

.altitude-structure-list .dragging {
	opacity: 0.5;
	cursor: grabbing;
}

.form-preview-container.altitude-drop-target {
	outline: 3px dashed #667eea;
	outline-offset: -10px;
}

.form-preview-inner .altitude-category-section.altitude-drop-hover {
	background: rgba(102, 126, 234, 0.12);
	outline: 2px dashed #667eea;
}

@media (max-width: 900px) {
	/* Keep the drag-and-drop panel available on tablets and phones (stacked above the preview). */
	.builder-left-panel {
		display: flex;
		border-right: none;
		border-bottom: 1px solid #ddd;
	}

	.altitude-structure-list .altitude-drag-handle .dashicons {
		font-size: 24px;
		width: 24px;
		height: 24px;
	}

	.altitude-structure-question {
		padding: 10px 8px;
		font-size: 13px;
	}
}
</style>

<script>
jQuery(document).ready(function($) {
	let selectedElement = null;
	let selectedType = null; // 'category' or 'question'
	let selectedData = null;
	let config = <?php echo json_encode( $config ); ?>;
	let isDirty = false;
	const ajaxurl = altitudeAuditAdmin.ajaxUrl; // WordPress ajaxurl for this plugin

	// === CLICK TO SELECT ELEMENTS IN PREVIEW ===

	// Select category - open beautiful edit modal
	$(document).on('click', '.form-preview-inner .altitude-category-section', function(e) {
		e.stopPropagation();
		const $section = $(this);
		const categoryKey = $section.data('category-key');

		if (!categoryKey) return;

		// Find category in config
		const category = config.categories[categoryKey];
		if (!category) return;

		openCategoryEditModal(categoryKey, category);
	});

	// Select question
	$(document).on('click', '.form-preview-inner .altitude-question-wrapper', function(e) {
		e.stopPropagation();
		const $question = $(this);
		const categoryKey = $question.closest('.altitude-category-section').data('category-key');
		const questionIndex = $question.data('question-index');

		if (!categoryKey || questionIndex === undefined) return;

		const category = config.categories[categoryKey];
		if (!category) return;

		// Ensure questions array exists
		if (!category.questions || !Array.isArray(category.questions)) {
			category.questions = [];
			alert('This category has no questions. Please add a question first.');
			return;
		}

		if (!category.questions[questionIndex]) {
			alert('Question not found.');
			return;
		}

		selectQuestion(categoryKey, questionIndex, category.questions[questionIndex]);
	});

	// Open comprehensive category edit modal with all questions
	function openCategoryEditModal(key, category) {
		// Ensure questions array exists
		if (!category.questions || !Array.isArray(category.questions)) {
			category.questions = [];
			config.categories[key].questions = [];
		}

		// Build modal HTML
		let modalHTML = `
			<div class="edit-category-section">
				<h3>Category Settings</h3>
				<div class="form-field">
					<label>Category Name</label>
					<input type="text" class="modal-category-label" value="${escapeHtml(category.label || '')}" placeholder="e.g., Distraction">
				</div>
				<div class="form-field">
					<label>Icon (Emoji)</label>
					<input type="text" class="modal-category-icon" value="${escapeHtml(category.icon || '')}" maxlength="2" placeholder="📱">
				</div>
				<div class="form-field">
					<label>Description</label>
					<textarea class="modal-category-description" rows="3" placeholder="Describe this category...">${escapeHtml(category.description || '')}</textarea>
				</div>
			</div>

			<div class="edit-category-section">
				<h3>Questions (${category.questions.length})</h3>
				<div class="edit-questions-list">`;

		// Add each question
		category.questions.forEach((q, index) => {
			modalHTML += `
				<div class="edit-question-item" data-question-index="${index}">
					<div class="edit-question-item-header">
						<h4>Question ${index + 1}</h4>
						<a href="#" class="delete-question-btn-inline" data-index="${index}">Delete</a>
					</div>
					<div class="form-field">
						<label>Question Text</label>
						<textarea class="modal-question-label" rows="3" placeholder="Enter your question here...">${escapeHtml(q.label || '')}</textarea>
					</div>
					<div class="form-field">
						<label>Help Text (Optional)</label>
						<input type="text" class="modal-question-help" value="${escapeHtml(q.help_text || '')}" placeholder="Additional guidance...">
					</div>
				</div>`;
		});

		modalHTML += `
				</div>
				<div class="add-question-btn-inline">
					<span class="dashicons dashicons-plus"></span> Add New Question
				</div>
			</div>`;

		// Set modal content
		$('#edit-category-modal-body').html(modalHTML);
		$('#edit-category-modal').data('category-key', key).fadeIn();
	}

	// Save all changes from category edit modal
	$(document).on('click', '#save-category-changes-btn', function() {
		const categoryKey = $('#edit-category-modal').data('category-key');
		const $modal = $('#edit-category-modal-body');

		// Get category data
		const categoryData = {
			label: $modal.find('.modal-category-label').val(),
			icon: $modal.find('.modal-category-icon').val(),
			description: $modal.find('.modal-category-description').val()
		};

		// Get all questions data - use actual index in DOM, not data attribute
		const questionsData = [];
		$modal.find('.edit-question-item').each(function(actualIndex) {
			const $item = $(this);
			const questionText = $item.find('.modal-question-label').val().trim();

			// Only add if question text is not empty
			if (questionText) {
				questionsData.push({
					label: questionText,
					help_text: $item.find('.modal-question-help').val(),
					name: categoryKey + '_q' + (actualIndex + 1)
				});
			}
		});

		if (questionsData.length === 0) {
			alert('Please add at least one question before saving.');
			return;
		}

		// Save entire category with questions in one call
		const categoryToSave = {
			label: categoryData.label,
			icon: categoryData.icon,
			description: categoryData.description,
			questions: questionsData
		};

		console.log('Saving category:', categoryKey, categoryToSave);

		$.post(ajaxurl, {
			action: 'altitude_audit_save_category_with_questions',
			nonce: altitudeAuditAdmin.nonce,
			key: categoryKey,
			category: JSON.stringify(categoryToSave)
		}, function(response) {
			console.log('Save response:', response);
			if (response.success) {
				$('#edit-category-modal').fadeOut();
				alert('Category and all questions saved successfully!');
				location.reload();
			} else {
				alert('Error: ' + (response.data.message || 'Failed to save. Please try again.'));
			}
		}).fail(function(xhr, status, error) {
			console.error('AJAX Error:', status, error);
			alert('Network error. Please check your connection and try again.');
		});
	});

	// Add new question inline in modal
	$(document).on('click', '.add-question-btn-inline', function() {
		const $list = $(this).prev('.edit-questions-list');
		const questionCount = $list.find('.edit-question-item').length;
		const newIndex = questionCount;

		const questionHTML = `
			<div class="edit-question-item" data-question-index="${newIndex}">
				<div class="edit-question-item-header">
					<h4>Question ${newIndex + 1}</h4>
					<a href="#" class="delete-question-btn-inline" data-index="${newIndex}">Delete</a>
				</div>
				<div class="form-field">
					<label>Question Text</label>
					<textarea class="modal-question-label" rows="3" placeholder="Enter your question here..."></textarea>
				</div>
				<div class="form-field">
					<label>Help Text (Optional)</label>
					<input type="text" class="modal-question-help" value="" placeholder="Additional guidance...">
				</div>
			</div>`;

		$list.append(questionHTML);

		// Update question count
		$list.closest('.edit-category-section').find('h3').text(`Questions (${newIndex + 1})`);
	});

	// Delete question inline in modal
	$(document).on('click', '.delete-question-btn-inline', function(e) {
		e.preventDefault();
		if (!confirm('Are you sure you want to delete this question?')) {
			return;
		}

		const $item = $(this).closest('.edit-question-item');
		$item.remove();

		// Re-index remaining questions
		const $list = $('.edit-questions-list');
		$list.find('.edit-question-item').each(function(index) {
			$(this).data('question-index', index);
			$(this).find('h4').text('Question ' + (index + 1));
			$(this).find('.delete-question-btn-inline').data('index', index);
		});

		// Update question count
		const count = $list.find('.edit-question-item').length;
		$list.closest('.edit-category-section').find('h3').text(`Questions (${count})`);
	});

	function selectCategory(key, category) {
		selectedElement = key;
		selectedType = 'category';
		selectedData = category;

		// Ensure questions array exists
		if (!category.questions || !Array.isArray(category.questions)) {
			category.questions = [];
			// Update the config reference
			config.categories[key].questions = [];
		}

		// Build questions HTML
		let questionsHtml = '';
		if (category.questions && category.questions.length > 0) {
			category.questions.forEach((q, index) => {
				questionsHtml += `<div class="question-sidebar-item" data-category="${key}" data-index="${index}">
					${index + 1}. ${escapeHtml(q.label || 'Untitled Question')}
				</div>`;
			});
		} else {
			questionsHtml = '<p style="color:#999;font-size:12px;text-align:center;padding:20px 0;"><?php esc_html_e( 'No questions yet', 'altitude-accountability-audit' ); ?></p>';
		}

		// Render settings
		let template = $('#category-settings-template').html();
		template = template.replace(/{{key}}/g, key);
		template = template.replace(/{{label}}/g, escapeHtml(category.label || ''));
		template = template.replace(/{{icon}}/g, escapeHtml(category.icon || ''));
		template = template.replace(/{{description}}/g, escapeHtml(category.description || ''));
		template = template.replace(/{{questions_html}}/g, questionsHtml);

		$('#settings-panel').html(template);
	}

	function selectQuestion(categoryKey, index, question) {
		selectedElement = { category: categoryKey, index: index };
		selectedType = 'question';
		selectedData = question;

		// Render settings
		let template = $('#question-settings-template').html();
		template = template.replace(/{{category}}/g, categoryKey);
		template = template.replace(/{{index}}/g, index);
		template = template.replace(/{{label}}/g, escapeHtml(question.label || ''));
		template = template.replace(/{{help_text}}/g, escapeHtml(question.help_text || ''));

		$('#settings-panel').html(template);
	}

	function escapeHtml(text) {
		const div = document.createElement('div');
		div.textContent = text;
		return div.innerHTML;
	}

	// === UPDATE CATEGORY ===

	$(document).on('click', '#update-category-btn', function() {
		const key = $('#edit-category-key').val();
		const data = {
			label: $('#edit-category-label').val(),
			icon: $('#edit-category-icon').val(),
			description: $('#edit-category-description').val()
		};

		if (!data.label) {
			alert('<?php esc_html_e( 'Please enter a category name', 'altitude-accountability-audit' ); ?>');
			return;
		}

		$.post(ajaxurl, {
			action: 'altitude_audit_update_category',
			nonce: altitudeAuditAdmin.nonce,
			key: key,
			data: JSON.stringify(data)
		}, function(response) {
			if (response.success) {
				config.categories[key] = $.extend(config.categories[key], data);
				refreshPreview();
				selectCategory(key, config.categories[key]);
				markDirty();
			} else {
				alert(response.data.message);
			}
		});
	});

	// === DELETE CATEGORY ===

	$(document).on('click', '#delete-category-btn', function() {
		if (!confirm('<?php esc_html_e( 'Are you sure you want to delete this category and all its questions?', 'altitude-accountability-audit' ); ?>')) {
			return;
		}

		const key = $('#edit-category-key').val();

		$.post(ajaxurl, {
			action: 'altitude_audit_delete_category',
			nonce: altitudeAuditAdmin.nonce,
			key: key
		}, function(response) {
			if (response.success) {
				delete config.categories[key];
				refreshPreview();
				$('#settings-panel').html('<div class="no-selection-message"><span class="dashicons dashicons-admin-settings"></span><p><?php esc_html_e( 'Category deleted', 'altitude-accountability-audit' ); ?></p></div>');
				markDirty();
			} else {
				alert(response.data.message);
			}
		});
	});

	// === ADD QUESTION TO CATEGORY ===

	$(document).on('click', '#add-question-to-category-btn', function() {
		const categoryKey = $('#edit-category-key').val();
		$('#new-question-category').val(categoryKey);
		$('#add-question-modal').fadeIn();
	});

	// === UPDATE QUESTION ===

	$(document).on('click', '#update-question-btn', function() {
		const category = $('#edit-question-category').val();
		const index = parseInt($('#edit-question-index').val());
		const data = {
			label: $('#edit-question-label').val(),
			help_text: $('#edit-question-help').val()
		};

		if (!data.label) {
			alert('<?php esc_html_e( 'Please enter a question', 'altitude-accountability-audit' ); ?>');
			return;
		}

		$.post(ajaxurl, {
			action: 'altitude_audit_update_question',
			nonce: altitudeAuditAdmin.nonce,
			category: category,
			index: index,
			data: JSON.stringify(data)
		}, function(response) {
			if (response.success) {
				config.categories[category].questions[index] = $.extend(config.categories[category].questions[index], data);
				refreshPreview();
				selectQuestion(category, index, config.categories[category].questions[index]);
				markDirty();
			} else {
				alert(response.data.message);
			}
		});
	});

	// === DELETE QUESTION ===

	$(document).on('click', '#delete-question-btn', function() {
		if (!confirm('<?php esc_html_e( 'Are you sure you want to delete this question?', 'altitude-accountability-audit' ); ?>')) {
			return;
		}

		const category = $('#edit-question-category').val();
		const index = parseInt($('#edit-question-index').val());

		$.post(ajaxurl, {
			action: 'altitude_audit_delete_question',
			nonce: altitudeAuditAdmin.nonce,
			category: category,
			index: index
		}, function(response) {
			if (response.success) {
				config.categories[category].questions.splice(index, 1);
				refreshPreview();
				selectCategory(category, config.categories[category]);
				markDirty();
			} else {
				alert(response.data.message);
			}
		});
	});

	// === CREATE CATEGORY ===

	$(document).on('click', '.element-item[data-element-type="category"]', function() {
		$('#add-category-modal').fadeIn();
	});

	$('#create-category-btn').on('click', function() {
		const label = $('#new-category-label').val();
		const icon = $('#new-category-icon').val();

		if (!label) {
			alert('<?php esc_html_e( 'Please enter a category name', 'altitude-accountability-audit' ); ?>');
			return;
		}

		$.post(ajaxurl, {
			action: 'altitude_audit_add_category',
			nonce: altitudeAuditAdmin.nonce,
			label: label,
			icon: icon
		}, function(response) {
			if (response.success) {
				location.reload();
			} else {
				alert(response.data.message);
			}
		});
	});

	// === CREATE QUESTION ===

	$('#create-question-btn').on('click', function() {
		const category = $('#new-question-category').val();
		const label = $('#new-question-label').val();

		if (!label) {
			alert('<?php esc_html_e( 'Please enter a question', 'altitude-accountability-audit' ); ?>');
			return;
		}

		$.post(ajaxurl, {
			action: 'altitude_audit_add_question',
			nonce: altitudeAuditAdmin.nonce,
			category: category,
			label: label
		}, function(response) {
			if (response.success) {
				location.reload();
			} else {
				alert(response.data.message);
			}
		});
	});

	// === REFRESH PREVIEW ===

	function refreshPreview() {
		// Re-render form preview with updated config
		$.post(ajaxurl, {
			action: 'altitude_audit_render_preview',
			nonce: altitudeAuditAdmin.nonce
		}, function(response) {
			if (response.success) {
				$('.form-preview-inner').html(response.data.html);
				addCategoryKeys();
				renderStructure();
			}
		});
	}

	$('#refresh-preview-btn').on('click', function() {
		location.reload();
	});

	// Add data attributes to categories and questions for selection
	function addCategoryKeys() {
		$('.form-preview-inner .altitude-category-section').each(function(index) {
			const keys = Object.keys(config.categories);
			if (keys[index]) {
				$(this).attr('data-category-key', keys[index]);
			}
		});

		$('.form-preview-inner .altitude-category-section').each(function() {
			const categoryKey = $(this).data('category-key');
			if (categoryKey) {
				$(this).find('.altitude-question-wrapper').each(function(qIndex) {
					$(this).attr('data-question-index', qIndex);
				});
			}
		});
	}

	// Initialize on load
	addCategoryKeys();

	// === SELECT QUESTION FROM SIDEBAR ===

	$(document).on('click', '.question-sidebar-item', function() {
		const categoryKey = $(this).data('category');
		const index = $(this).data('index');

		// Validate category and questions exist
		if (!config.categories[categoryKey]) {
			alert('Category not found.');
			return;
		}

		if (!config.categories[categoryKey].questions || !Array.isArray(config.categories[categoryKey].questions)) {
			alert('This category has no questions array.');
			return;
		}

		const question = config.categories[categoryKey].questions[index];
		if (!question) {
			alert('Question not found.');
			return;
		}

		selectQuestion(categoryKey, index, question);
	});

	// === DRAG AND DROP (restored) ===
	//
	// The original builder (v2) reordered categories/questions with native HTML5
	// drag-and-drop and saved via the `altitude_audit_save_builder` AJAX action.
	// The 3-panel redesign removed that list and its handlers; this restores them
	// inside the current layout and saves the new order immediately, like every
	// other edit in this builder.

	const $structureList = $('#altitude-structure-list');
	const $structureStatus = $('#altitude-structure-status');
	const structureL10n = {
		saving: '<?php echo esc_js( __( 'Saving new order…', 'altitude-accountability-audit' ) ); ?>',
		saved: '<?php echo esc_js( __( 'Order saved.', 'altitude-accountability-audit' ) ); ?>',
		failed: '<?php echo esc_js( __( 'Could not save the new order. Your previous order has been restored.', 'altitude-accountability-audit' ) ); ?>',
		dragHandle: '<?php echo esc_js( __( 'Drag to reorder', 'altitude-accountability-audit' ) ); ?>',
		untitled: '<?php echo esc_js( __( 'Untitled Question', 'altitude-accountability-audit' ) ); ?>',
		questions: '<?php echo esc_js( __( 'questions', 'altitude-accountability-audit' ) ); ?>',
		noQuestions: '<?php echo esc_js( __( 'No questions yet', 'altitude-accountability-audit' ) ); ?>',
		dropOnCategory: '<?php echo esc_js( __( 'Drop the question onto a category in the preview.', 'altitude-accountability-audit' ) ); ?>'
	};
	let dragState = null; // { el, type: 'category'|'question', container, startOrder }
	let isSavingOrder = false;

	function getQuestionsArray(category) {
		if (!category || !category.questions) return [];
		return Array.isArray(category.questions) ? category.questions : Object.values(category.questions);
	}

	function escapeAttr(text) {
		return escapeHtml(text == null ? '' : String(text)).replace(/"/g, '&quot;').replace(/'/g, '&#39;');
	}

	function setStructureStatus(message, state) {
		$structureStatus.removeClass('is-saving is-success is-error').text(message || '');
		if (state) $structureStatus.addClass('is-' + state);
	}

	function renderStructure() {
		if (!$structureList.length) return;
		$structureList.empty();

		const categories = (config && config.categories) ? config.categories : {};
		Object.keys(categories).forEach(function(key) {
			const category = categories[key] || {};
			const questions = getQuestionsArray(category);
			let questionsHtml = '';

			questions.forEach(function(q, index) {
				const label = (q && q.label) ? q.label : structureL10n.untitled;
				questionsHtml += '<li class="altitude-structure-question" draggable="true" data-index="' + index + '">' +
					'<span class="altitude-drag-handle" title="' + escapeAttr(structureL10n.dragHandle) + '"><span class="dashicons dashicons-menu" aria-hidden="true"></span></span>' +
					'<span class="altitude-structure-label" title="' + escapeAttr(label) + '">' + escapeHtml(label) + '</span>' +
					'</li>';
			});

			if (!questions.length) {
				questionsHtml = '<li class="altitude-structure-empty" style="color:#999;font-size:12px;padding:4px;">' + escapeHtml(structureL10n.noQuestions) + '</li>';
			}

			$structureList.append(
				'<li class="altitude-structure-category" draggable="true" data-key="' + escapeAttr(key) + '">' +
					'<div class="altitude-structure-category-header">' +
						'<span class="altitude-drag-handle" title="' + escapeAttr(structureL10n.dragHandle) + '"><span class="dashicons dashicons-menu" aria-hidden="true"></span></span>' +
						'<span class="altitude-structure-label">' + escapeHtml((category.icon ? category.icon + ' ' : '') + (category.label || key)) + '</span>' +
						'<span class="altitude-structure-count">' + questions.length + ' ' + escapeHtml(structureL10n.questions) + '</span>' +
					'</div>' +
					'<ul class="altitude-structure-questions">' + questionsHtml + '</ul>' +
				'</li>'
			);
		});
	}

	// Snapshot of the current DOM order: [{ key, questions: [originalIndex, ...] }, ...]
	function readStructureOrder() {
		const order = [];
		$structureList.children('.altitude-structure-category').each(function() {
			const questions = [];
			$(this).children('.altitude-structure-questions').children('.altitude-structure-question').each(function() {
				questions.push(parseInt($(this).attr('data-index'), 10));
			});
			order.push({ key: String($(this).attr('data-key')), questions: questions });
		});
		return order;
	}

	// From the original builder: find the sibling the dragged item should be placed before.
	function getDragAfterElement(container, selector, y) {
		const draggableElements = Array.prototype.filter.call(container.children, function(child) {
			return child.matches(selector) && !child.classList.contains('dragging');
		});

		return draggableElements.reduce(function(closest, child) {
			const box = child.getBoundingClientRect();
			const offset = y - box.top - box.height / 2;

			if (offset < 0 && offset > closest.offset) {
				return { offset: offset, element: child };
			}
			return closest;
		}, { offset: Number.NEGATIVE_INFINITY }).element;
	}

	function startStructureDrag(el, type) {
		dragState = {
			el: el,
			type: type,
			container: el.parentNode,
			startOrder: JSON.stringify(readStructureOrder())
		};
		el.classList.add('dragging');
	}

	function moveStructureDrag(clientY) {
		if (!dragState) return;
		const selector = dragState.type === 'category' ? '.altitude-structure-category' : '.altitude-structure-question';
		// Questions are only reordered within their own category (as in the original builder).
		const container = dragState.container;
		const afterElement = getDragAfterElement(container, selector, clientY);

		if (afterElement == null) {
			container.appendChild(dragState.el);
		} else if (afterElement !== dragState.el) {
			container.insertBefore(dragState.el, afterElement);
		}
	}

	function endStructureDrag() {
		if (!dragState) return;
		const state = dragState;
		dragState = null;
		state.el.classList.remove('dragging');

		if (JSON.stringify(readStructureOrder()) !== state.startOrder) {
			saveStructureOrder();
		}
	}

	function saveStructureOrder() {
		if (isSavingOrder) return;
		isSavingOrder = true;
		$structureList.addClass('is-saving');
		setStructureStatus(structureL10n.saving, 'saving');

		$.post(ajaxurl, {
			action: 'altitude_audit_save_builder',
			nonce: altitudeAuditAdmin.nonce,
			order: JSON.stringify(readStructureOrder())
		}).done(function(response) {
			if (response && response.success && response.data && response.data.categories) {
				config.categories = response.data.categories;
				setStructureStatus(structureL10n.saved, 'success');
				renderStructure();
				refreshPreview();
			} else {
				renderStructure(); // restore last saved order
				setStructureStatus((response && response.data && response.data.message) ? response.data.message : structureL10n.failed, 'error');
			}
		}).fail(function() {
			renderStructure(); // restore last saved order
			setStructureStatus(structureL10n.failed, 'error');
		}).always(function() {
			isSavingOrder = false;
			$structureList.removeClass('is-saving');
		});
	}

	// Mouse / pen: native HTML5 drag-and-drop.
	$structureList.on('dragstart', '.altitude-structure-question, .altitude-structure-category', function(e) {
		// Nested items: only the innermost draggable handles the event.
		if (e.target !== this || isSavingOrder) {
			if (isSavingOrder) e.preventDefault();
			return;
		}
		e.stopPropagation();
		const type = $(this).hasClass('altitude-structure-category') ? 'category' : 'question';
		startStructureDrag(this, type);

		const dt = e.originalEvent && e.originalEvent.dataTransfer;
		if (dt) {
			dt.effectAllowed = 'move';
			try { dt.setData('text/plain', type); } catch (err) {} // Firefox needs data to start a drag
		}
	});

	$structureList.on('dragover', function(e) {
		if (!dragState) return;
		e.preventDefault();
		if (e.originalEvent.dataTransfer) e.originalEvent.dataTransfer.dropEffect = 'move';
		moveStructureDrag(e.originalEvent.clientY);
	});

	$structureList.on('drop', function(e) {
		if (dragState) e.preventDefault();
	});

	$structureList.on('dragend', '.altitude-structure-question, .altitude-structure-category', function(e) {
		if (dragState && dragState.el === this) {
			e.stopPropagation();
			endStructureDrag();
		}
	});

	// Touch devices (phones/tablets): HTML5 drag events are not fired for touch,
	// so the same reorder logic is driven from touch events on the drag handle.
	if ($structureList.length) {
		const listEl = $structureList[0];

		listEl.addEventListener('touchstart', function(e) {
			const handle = e.target.closest('.altitude-drag-handle');
			if (!handle || isSavingOrder || e.touches.length !== 1) return;
			const item = handle.closest('.altitude-structure-question, .altitude-structure-category');
			if (!item) return;
			e.preventDefault();
			startStructureDrag(item, item.classList.contains('altitude-structure-category') ? 'category' : 'question');
		}, { passive: false });

		listEl.addEventListener('touchmove', function(e) {
			if (!dragState) return;
			e.preventDefault();
			moveStructureDrag(e.touches[0].clientY);
		}, { passive: false });

		listEl.addEventListener('touchend', endStructureDrag);
		listEl.addEventListener('touchcancel', endStructureDrag);
	}

	// === DRAG ELEMENTS FROM THE LIBRARY INTO THE PREVIEW ===
	// The left-panel elements were marked draggable but had no handlers.

	let libraryDragType = null;
	const $previewArea = $('#form-preview-area');

	$(document).on('dragstart', '.elements-library .element-item[draggable="true"]', function(e) {
		if ($(this).hasClass('disabled')) {
			e.preventDefault();
			return;
		}
		libraryDragType = $(this).attr('data-element-type');
		const dt = e.originalEvent && e.originalEvent.dataTransfer;
		if (dt) {
			dt.effectAllowed = 'copy';
			try { dt.setData('text/plain', libraryDragType); } catch (err) {}
		}
		$previewArea.addClass('altitude-drop-target');
	});

	$(document).on('dragend', '.elements-library .element-item[draggable="true"]', function() {
		libraryDragType = null;
		$previewArea.removeClass('altitude-drop-target');
		$('.form-preview-inner .altitude-drop-hover').removeClass('altitude-drop-hover');
	});

	$previewArea.on('dragover', function(e) {
		if (!libraryDragType) return;
		e.preventDefault();
		if (e.originalEvent.dataTransfer) e.originalEvent.dataTransfer.dropEffect = 'copy';

		if (libraryDragType === 'question') {
			const $section = $(e.target).closest('.altitude-category-section');
			$('.form-preview-inner .altitude-drop-hover').not($section).removeClass('altitude-drop-hover');
			$section.addClass('altitude-drop-hover');
		}
	});

	$previewArea.on('drop', function(e) {
		if (!libraryDragType) {
			if (dragState) e.preventDefault(); // a structure item dropped here must not insert text into preview inputs
			return;
		}
		e.preventDefault(); // never drop text into the preview's inputs
		const type = libraryDragType;
		const $section = $(e.target).closest('.altitude-category-section');

		libraryDragType = null;
		$previewArea.removeClass('altitude-drop-target');
		$('.form-preview-inner .altitude-drop-hover').removeClass('altitude-drop-hover');

		if (type === 'category') {
			$('#add-category-modal').fadeIn();
		} else if (type === 'question') {
			const categoryKey = $section.attr('data-category-key');
			if (!categoryKey) {
				alert(structureL10n.dropOnCategory);
				return;
			}
			$('#new-question-category').val(categoryKey);
			$('#add-question-modal').fadeIn();
		}
	});

	renderStructure();

	// === MODAL CONTROLS ===

	$('.altitude-modal-close, .altitude-modal-overlay').on('click', function() {
		$(this).closest('.altitude-modal').fadeOut();
	});

	// === SAVE/PUBLISH ===

	function markDirty() {
		isDirty = true;
		$('#save-builder-btn, #publish-builder-btn').addClass('button-primary');
	}

	$('#save-builder-btn, #publish-builder-btn').on('click', function() {
		location.reload();
	});

	// === WARN ON EXIT ===

	$(window).on('beforeunload', function() {
		if (isDirty) {
			return '<?php esc_html_e( 'You have unsaved changes. Are you sure you want to leave?', 'altitude-accountability-audit' ); ?>';
		}
	});
});
</script>
