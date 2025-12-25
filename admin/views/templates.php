<?php
/**
 * Templates page - Browse and apply form templates
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'config/templates.php';
$templates = altitude_audit_get_templates();
?>

<div class="wrap altitude-audit-admin">
	<h1><?php esc_html_e( 'Form Templates', 'altitude-accountability-audit' ); ?></h1>
	<p class="description"><?php esc_html_e( 'Choose from professional form templates to quickly create different types of forms', 'altitude-accountability-audit' ); ?></p>

	<div class="altitude-templates-grid" style="margin-top: 30px;">
		<?php foreach ( $templates as $template_id => $template ) : ?>
			<div class="template-card">
				<div class="template-icon"><?php echo esc_html( $template['icon'] ); ?></div>

				<div class="template-header">
					<h3><?php echo esc_html( $template['name'] ); ?></h3>
					<span class="template-category"><?php echo esc_html( $template['category'] ); ?></span>
				</div>

				<p class="template-description"><?php echo esc_html( $template['description'] ); ?></p>

				<div class="template-stats">
					<span class="stat-item">
						<strong><?php echo count( $template['categories'] ); ?></strong>
						<?php esc_html_e( 'Categories', 'altitude-accountability-audit' ); ?>
					</span>
					<span class="stat-item">
						<strong>
							<?php
							$total_questions = 0;
							foreach ( $template['categories'] as $cat ) {
								$total_questions += count( $cat['questions'] );
							}
							echo esc_html( $total_questions );
							?>
						</strong>
						<?php esc_html_e( 'Questions', 'altitude-accountability-audit' ); ?>
					</span>
				</div>

				<div class="template-categories-preview">
					<strong><?php esc_html_e( 'Includes:', 'altitude-accountability-audit' ); ?></strong>
					<ul>
						<?php foreach ( array_slice( $template['categories'], 0, 4 ) as $category ) : ?>
							<li><?php echo esc_html( $category['icon'] ); ?> <?php echo esc_html( $category['label'] ); ?></li>
						<?php endforeach; ?>
						<?php if ( count( $template['categories'] ) > 4 ) : ?>
							<li style="opacity: 0.6;">+ <?php echo esc_html( count( $template['categories'] ) - 4 ); ?> <?php esc_html_e( 'more', 'altitude-accountability-audit' ); ?></li>
						<?php endif; ?>
					</ul>
				</div>

				<div class="template-actions">
					<button type="button" class="button button-primary button-large apply-template-btn" data-template="<?php echo esc_attr( $template_id ); ?>">
						<?php esc_html_e( 'Apply Template', 'altitude-accountability-audit' ); ?>
					</button>
					<button type="button" class="button preview-template-btn" data-template="<?php echo esc_attr( $template_id ); ?>">
						<?php esc_html_e( 'Preview', 'altitude-accountability-audit' ); ?>
					</button>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<!-- Custom Template Card -->
	<div class="altitude-templates-grid" style="margin-top: 30px;">
		<div class="template-card custom-template">
			<div class="template-icon">🎨</div>

			<div class="template-header">
				<h3><?php esc_html_e( 'Start from Scratch', 'altitude-accountability-audit' ); ?></h3>
				<span class="template-category"><?php esc_html_e( 'Custom', 'altitude-accountability-audit' ); ?></span>
			</div>

			<p class="template-description"><?php esc_html_e( 'Build your own custom form from the ground up with complete control over categories and questions.', 'altitude-accountability-audit' ); ?></p>

			<div class="template-actions">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=altitude-audit-builder' ) ); ?>" class="button button-secondary button-large">
					<?php esc_html_e( 'Go to Form Builder', 'altitude-accountability-audit' ); ?>
				</a>
			</div>
		</div>
	</div>
</div>

<!-- Template Preview Modal -->
<div id="template-preview-modal" class="altitude-modal" style="display:none;">
	<div class="altitude-modal-overlay"></div>
	<div class="altitude-modal-content" style="max-width: 900px;">
		<div class="altitude-modal-header">
			<h2 id="preview-template-name"><?php esc_html_e( 'Template Preview', 'altitude-accountability-audit' ); ?></h2>
			<button type="button" class="altitude-modal-close">&times;</button>
		</div>
		<div class="altitude-modal-body" id="template-preview-content" style="max-height: 70vh; overflow-y: auto;">
			<!-- Preview content will be loaded here -->
		</div>
		<div class="altitude-modal-footer">
			<button type="button" class="button button-primary" id="apply-from-preview-btn">
				<?php esc_html_e( 'Apply This Template', 'altitude-accountability-audit' ); ?>
			</button>
			<button type="button" class="button altitude-modal-close"><?php esc_html_e( 'Close', 'altitude-accountability-audit' ); ?></button>
		</div>
	</div>
</div>

<style>
.altitude-templates-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
	gap: 25px;
}

.template-card {
	background: white;
	border-radius: 12px;
	padding: 30px;
	box-shadow: 0 2px 8px rgba(0,0,0,0.1);
	transition: all 0.3s;
	border: 2px solid transparent;
	display: flex;
	flex-direction: column;
}

.template-card:hover {
	transform: translateY(-5px);
	box-shadow: 0 8px 24px rgba(0,0,0,0.15);
	border-color: #667eea;
}

.template-card.custom-template {
	background: linear-gradient(135deg, #f5f7fa 0%, #f0f3ff 100%);
	border: 2px dashed #667eea;
}

.template-icon {
	font-size: 60px;
	text-align: center;
	margin-bottom: 20px;
}

.template-header {
	text-align: center;
	margin-bottom: 15px;
}

.template-header h3 {
	margin: 0 0 10px 0;
	color: #333;
	font-size: 22px;
}

.template-category {
	display: inline-block;
	background: #667eea;
	color: white;
	padding: 4px 12px;
	border-radius: 20px;
	font-size: 12px;
	font-weight: 600;
	text-transform: uppercase;
	letter-spacing: 0.5px;
}

.template-description {
	color: #666;
	line-height: 1.6;
	margin: 0 0 20px 0;
	flex: 1;
}

.template-stats {
	display: flex;
	justify-content: space-around;
	padding: 15px 0;
	border-top: 1px solid #e5e5e5;
	border-bottom: 1px solid #e5e5e5;
	margin-bottom: 20px;
}

.stat-item {
	text-align: center;
	font-size: 14px;
	color: #666;
}

.stat-item strong {
	display: block;
	font-size: 24px;
	color: #667eea;
	margin-bottom: 4px;
}

.template-categories-preview {
	margin-bottom: 20px;
	padding: 15px;
	background: #f7f9fc;
	border-radius: 8px;
}

.template-categories-preview strong {
	display: block;
	margin-bottom: 10px;
	color: #333;
	font-size: 13px;
	text-transform: uppercase;
	letter-spacing: 0.5px;
}

.template-categories-preview ul {
	list-style: none;
	padding: 0;
	margin: 0;
}

.template-categories-preview li {
	padding: 6px 0;
	color: #555;
	font-size: 14px;
}

.template-actions {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.template-actions .button {
	width: 100%;
	justify-content: center;
}

/* Preview Modal Styles */
.preview-category {
	margin-bottom: 30px;
	padding: 20px;
	background: #f7f9fc;
	border-radius: 8px;
	border-left: 4px solid #667eea;
}

.preview-category h4 {
	margin: 0 0 10px 0;
	display: flex;
	align-items: center;
	gap: 10px;
	font-size: 18px;
}

.preview-category-icon {
	font-size: 24px;
}

.preview-category-description {
	color: #666;
	font-size: 14px;
	margin: 0 0 15px 0;
	font-style: italic;
}

.preview-questions {
	list-style: none;
	padding: 0;
	margin: 0;
}

.preview-questions li {
	padding: 12px;
	margin-bottom: 8px;
	background: white;
	border-radius: 6px;
	border: 1px solid #e5e5e5;
	font-size: 14px;
	color: #333;
}

.preview-questions li::before {
	content: "❓ ";
	margin-right: 8px;
	opacity: 0.5;
}

/* Responsive */
@media (max-width: 768px) {
	.altitude-templates-grid {
		grid-template-columns: 1fr;
	}
}
</style>

<script>
jQuery(document).ready(function($) {
	let currentTemplateId = null;
	const templates = <?php echo json_encode( $templates ); ?>;

	// Apply Template
	$(document).on('click', '.apply-template-btn', function() {
		const templateId = $(this).data('template');
		const templateName = templates[templateId].name;

		if (!confirm('<?php esc_html_e( 'This will replace your current form with the selected template. This action cannot be undone. Continue?', 'altitude-accountability-audit' ); ?>')) {
			return;
		}

		$(this).prop('disabled', true).text('<?php esc_html_e( 'Applying...', 'altitude-accountability-audit' ); ?>');

		$.post(ajaxurl, {
			action: 'altitude_audit_apply_template',
			nonce: altitudeAuditAdmin.nonce,
			template_id: templateId
		}, function(response) {
			if (response.success) {
				alert('✓ ' + response.data.message);
				window.location.href = '<?php echo esc_url( admin_url( 'admin.php?page=altitude-audit-builder' ) ); ?>';
			} else {
				alert('Error: ' + response.data.message);
				$('.apply-template-btn').prop('disabled', false).text('<?php esc_html_e( 'Apply Template', 'altitude-accountability-audit' ); ?>');
			}
		});
	});

	// Preview Template
	$(document).on('click', '.preview-template-btn', function() {
		const templateId = $(this).data('template');
		const template = templates[templateId];
		currentTemplateId = templateId;

		// Build preview HTML
		let previewHTML = '<div style="padding: 20px;">';
		previewHTML += '<p style="font-size: 15px; color: #666; margin-bottom: 25px;">' + template.description + '</p>';

		for (const catKey in template.categories) {
			const category = template.categories[catKey];
			previewHTML += '<div class="preview-category">';
			previewHTML += '<h4><span class="preview-category-icon">' + category.icon + '</span>' + category.label + '</h4>';
			previewHTML += '<p class="preview-category-description">' + category.description + '</p>';
			previewHTML += '<ul class="preview-questions">';

			category.questions.forEach(function(question) {
				previewHTML += '<li>' + question.label + '</li>';
			});

			previewHTML += '</ul></div>';
		}

		previewHTML += '</div>';

		$('#preview-template-name').text(template.name);
		$('#template-preview-content').html(previewHTML);
		$('#template-preview-modal').fadeIn();
	});

	// Apply from Preview
	$('#apply-from-preview-btn').on('click', function() {
		if (!currentTemplateId) return;
		$('.apply-template-btn[data-template="' + currentTemplateId + '"]').click();
		$('#template-preview-modal').fadeOut();
	});

	// Close Modal
	$('.altitude-modal-close, .altitude-modal-overlay').on('click', function() {
		$(this).closest('.altitude-modal').fadeOut();
	});
});
</script>
