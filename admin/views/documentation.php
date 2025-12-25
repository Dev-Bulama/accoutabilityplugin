<?php
/**
 * Documentation page - Complete guide for using the plugin
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

<div class="wrap altitude-audit-admin">
	<h1><?php esc_html_e( 'Documentation', 'altitude-accountability-audit' ); ?></h1>
	<p class="description"><?php esc_html_e( 'Complete guide to creating and publishing accountability audit forms', 'altitude-accountability-audit' ); ?></p>

	<div class="altitude-audit-card" style="max-width: 1200px;">
		<!-- Header -->
		<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 8px 8px 0 0; margin: -25px -25px 25px -25px;">
			<h2 style="margin: 0 0 10px 0; color: white; border: none; padding: 0;"><?php esc_html_e( 'Getting Started Guide', 'altitude-accountability-audit' ); ?></h2>
			<p style="margin: 0; opacity: 0.9; font-size: 16px;"><?php esc_html_e( 'Learn how to create, customize, and publish your accountability audit forms', 'altitude-accountability-audit' ); ?></p>
		</div>

		<!-- Quick Start -->
		<div style="background: #f0f3ff; padding: 20px; border-radius: 8px; margin-bottom: 30px; border-left: 4px solid #667eea;">
			<h3 style="margin-top: 0;"><?php esc_html_e( '⚡ Quick Start', 'altitude-accountability-audit' ); ?></h3>
			<p style="font-size: 15px;"><?php esc_html_e( 'Want to get started quickly? Here are the basic steps:', 'altitude-accountability-audit' ); ?></p>
			<ol style="font-size: 15px; line-height: 1.8;">
				<li><?php esc_html_e( 'Go to Form Builder to customize your questions', 'altitude-accountability-audit' ); ?></li>
				<li><?php esc_html_e( 'Add the shortcode [accountability_audit] to any page or post', 'altitude-accountability-audit' ); ?></li>
				<li><?php esc_html_e( 'Configure email settings to receive results', 'altitude-accountability-audit' ); ?></li>
				<li><?php esc_html_e( 'Publish your page and start collecting responses!', 'altitude-accountability-audit' ); ?></li>
			</ol>
		</div>

		<!-- Table of Contents -->
		<div style="background: #fafafa; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
			<h3 style="margin-top: 0;"><?php esc_html_e( '📚 Table of Contents', 'altitude-accountability-audit' ); ?></h3>
			<ul style="column-count: 2; column-gap: 30px; list-style: none; padding-left: 0;">
				<li style="margin-bottom: 10px;">
					<a href="#step1" style="text-decoration: none; color: #667eea; font-weight: 500;">
						<?php esc_html_e( '1. Understanding the Form Builder', 'altitude-accountability-audit' ); ?>
					</a>
				</li>
				<li style="margin-bottom: 10px;">
					<a href="#step2" style="text-decoration: none; color: #667eea; font-weight: 500;">
						<?php esc_html_e( '2. Creating Categories', 'altitude-accountability-audit' ); ?>
					</a>
				</li>
				<li style="margin-bottom: 10px;">
					<a href="#step3" style="text-decoration: none; color: #667eea; font-weight: 500;">
						<?php esc_html_e( '3. Adding Questions', 'altitude-accountability-audit' ); ?>
					</a>
				</li>
				<li style="margin-bottom: 10px;">
					<a href="#step4" style="text-decoration: none; color: #667eea; font-weight: 500;">
						<?php esc_html_e( '4. Editing Form Elements', 'altitude-accountability-audit' ); ?>
					</a>
				</li>
				<li style="margin-bottom: 10px;">
					<a href="#step5" style="text-decoration: none; color: #667eea; font-weight: 500;">
						<?php esc_html_e( '5. Publishing Your Form', 'altitude-accountability-audit' ); ?>
					</a>
				</li>
				<li style="margin-bottom: 10px;">
					<a href="#step6" style="text-decoration: none; color: #667eea; font-weight: 500;">
						<?php esc_html_e( '6. Configuring Email Settings', 'altitude-accountability-audit' ); ?>
					</a>
				</li>
				<li style="margin-bottom: 10px;">
					<a href="#step7" style="text-decoration: none; color: #667eea; font-weight: 500;">
						<?php esc_html_e( '7. Viewing Statistics', 'altitude-accountability-audit' ); ?>
					</a>
				</li>
				<li style="margin-bottom: 10px;">
					<a href="#step8" style="text-decoration: none; color: #667eea; font-weight: 500;">
						<?php esc_html_e( '8. Troubleshooting', 'altitude-accountability-audit' ); ?>
					</a>
				</li>
			</ul>
		</div>

		<!-- Step 1: Understanding the Form Builder -->
		<div id="step1" class="doc-section" style="margin-bottom: 40px; padding-bottom: 30px; border-bottom: 2px solid #e5e5e5;">
			<h2 style="color: #667eea; display: flex; align-items: center; gap: 10px;">
				<span style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 18px;">1</span>
				<?php esc_html_e( 'Understanding the Form Builder', 'altitude-accountability-audit' ); ?>
			</h2>

			<p style="font-size: 15px; line-height: 1.8;">
				<?php esc_html_e( 'The Form Builder uses a modern 3-panel interface similar to professional form builders like Fluent Forms:', 'altitude-accountability-audit' ); ?>
			</p>

			<div style="background: white; padding: 20px; border: 2px solid #e5e5e5; border-radius: 8px; margin: 20px 0;">
				<h4 style="margin-top: 0;"><?php esc_html_e( '📌 Left Panel - Form Elements', 'altitude-accountability-audit' ); ?></h4>
				<ul style="line-height: 1.8;">
					<li><?php esc_html_e( 'Contains draggable elements you can add to your form', 'altitude-accountability-audit' ); ?></li>
					<li><?php esc_html_e( 'Click "Category Section" to create a new quiz category', 'altitude-accountability-audit' ); ?></li>
					<li><?php esc_html_e( 'Personal Info fields (Name & Email) are always included', 'altitude-accountability-audit' ); ?></li>
				</ul>

				<h4><?php esc_html_e( '📌 Center Panel - Live Preview', 'altitude-accountability-audit' ); ?></h4>
				<ul style="line-height: 1.8;">
					<li><?php esc_html_e( 'Shows exactly how your form will look to users', 'altitude-accountability-audit' ); ?></li>
					<li><?php esc_html_e( 'Click any category or question to edit it', 'altitude-accountability-audit' ); ?></li>
					<li><?php esc_html_e( 'Updates in real-time as you make changes', 'altitude-accountability-audit' ); ?></li>
				</ul>

				<h4><?php esc_html_e( '📌 Right Panel - Field Settings', 'altitude-accountability-audit' ); ?></h4>
				<ul style="line-height: 1.8;">
					<li><?php esc_html_e( 'Displays settings for the selected element', 'altitude-accountability-audit' ); ?></li>
					<li><?php esc_html_e( 'Edit category names, icons, and descriptions', 'altitude-accountability-audit' ); ?></li>
					<li><?php esc_html_e( 'Modify question text and help messages', 'altitude-accountability-audit' ); ?></li>
				</ul>
			</div>
		</div>

		<!-- Step 2: Creating Categories -->
		<div id="step2" class="doc-section" style="margin-bottom: 40px; padding-bottom: 30px; border-bottom: 2px solid #e5e5e5;">
			<h2 style="color: #667eea; display: flex; align-items: center; gap: 10px;">
				<span style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 18px;">2</span>
				<?php esc_html_e( 'Creating Categories', 'altitude-accountability-audit' ); ?>
			</h2>

			<p style="font-size: 15px; line-height: 1.8;">
				<?php esc_html_e( 'Categories group related questions together and help organize your audit form.', 'altitude-accountability-audit' ); ?>
			</p>

			<div style="background: #f0f3ff; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #667eea;">
				<h4 style="margin-top: 0;"><?php esc_html_e( '✅ Step-by-Step: Creating a Category', 'altitude-accountability-audit' ); ?></h4>
				<ol style="line-height: 2;">
					<li><strong><?php esc_html_e( 'Navigate to Form Builder', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'From the admin menu, click "Accountability Audit" → "Form Builder"', 'altitude-accountability-audit' ); ?></li>
					<li><strong><?php esc_html_e( 'Click Category Element', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'In the left panel, click the "Category Section" element', 'altitude-accountability-audit' ); ?></li>
					<li><strong><?php esc_html_e( 'Enter Category Details', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'A modal will appear asking for:', 'altitude-accountability-audit' ); ?>
						<ul style="margin-top: 10px;">
							<li><strong><?php esc_html_e( 'Category Name:', 'altitude-accountability-audit' ); ?></strong> <?php esc_html_e( 'e.g., "Distraction", "Comfort", "Ego"', 'altitude-accountability-audit' ); ?></li>
							<li><strong><?php esc_html_e( 'Icon (Emoji):', 'altitude-accountability-audit' ); ?></strong> <?php esc_html_e( 'e.g., 📱, 🛋️, 👑', 'altitude-accountability-audit' ); ?></li>
						</ul>
					</li>
					<li><strong><?php esc_html_e( 'Click "Create Category"', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'Your new category will appear in the preview', 'altitude-accountability-audit' ); ?></li>
				</ol>
			</div>

			<div style="background: #fff9e6; padding: 15px; border-radius: 6px; border-left: 4px solid #f0ad4e; margin-top: 20px;">
				<strong style="color: #f0ad4e;">💡 Pro Tip:</strong> <?php esc_html_e( 'Choose emojis that visually represent your category. This helps users quickly identify different sections of the audit.', 'altitude-accountability-audit' ); ?>
			</div>
		</div>

		<!-- Step 3: Adding Questions -->
		<div id="step3" class="doc-section" style="margin-bottom: 40px; padding-bottom: 30px; border-bottom: 2px solid #e5e5e5;">
			<h2 style="color: #667eea; display: flex; align-items: center; gap: 10px;">
				<span style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 18px;">3</span>
				<?php esc_html_e( 'Adding Questions', 'altitude-accountability-audit' ); ?>
			</h2>

			<p style="font-size: 15px; line-height: 1.8;">
				<?php esc_html_e( 'Each category can contain multiple questions. All questions use a standard 4-point scale for consistency.', 'altitude-accountability-audit' ); ?>
			</p>

			<div style="background: #f0f3ff; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #667eea;">
				<h4 style="margin-top: 0;"><?php esc_html_e( '✅ Step-by-Step: Adding a Question', 'altitude-accountability-audit' ); ?></h4>
				<ol style="line-height: 2;">
					<li><strong><?php esc_html_e( 'Select a Category', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'Click on a category in the center preview panel', 'altitude-accountability-audit' ); ?></li>
					<li><strong><?php esc_html_e( 'View Category Settings', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'The right panel will show category settings', 'altitude-accountability-audit' ); ?></li>
					<li><strong><?php esc_html_e( 'Click "Add Question"', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'Find the button in the category settings panel', 'altitude-accountability-audit' ); ?></li>
					<li><strong><?php esc_html_e( 'Enter Question Text', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'Type your question clearly and concisely', 'altitude-accountability-audit' ); ?></li>
					<li><strong><?php esc_html_e( 'Click "Create Question"', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'The question will be added to the category', 'altitude-accountability-audit' ); ?></li>
				</ol>
			</div>

			<div style="background: white; padding: 20px; border: 2px solid #e5e5e5; border-radius: 8px; margin: 20px 0;">
				<h4 style="margin-top: 0;"><?php esc_html_e( '📊 Understanding the 4-Point Scale', 'altitude-accountability-audit' ); ?></h4>
				<p><?php esc_html_e( 'All questions automatically use this scoring system:', 'altitude-accountability-audit' ); ?></p>
				<ul style="list-style: none; padding-left: 0;">
					<li style="padding: 10px; background: #f7f9fc; margin-bottom: 8px; border-radius: 4px;">
						<strong>0 points</strong> - <?php esc_html_e( 'Not true', 'altitude-accountability-audit' ); ?>
					</li>
					<li style="padding: 10px; background: #f7f9fc; margin-bottom: 8px; border-radius: 4px;">
						<strong>1 point</strong> - <?php esc_html_e( 'Sometimes true', 'altitude-accountability-audit' ); ?>
					</li>
					<li style="padding: 10px; background: #f7f9fc; margin-bottom: 8px; border-radius: 4px;">
						<strong>2 points</strong> - <?php esc_html_e( 'Often true', 'altitude-accountability-audit' ); ?>
					</li>
					<li style="padding: 10px; background: #f7f9fc; margin-bottom: 8px; border-radius: 4px;">
						<strong>3 points</strong> - <?php esc_html_e( 'This is my pattern', 'altitude-accountability-audit' ); ?>
					</li>
				</ul>
			</div>

			<div style="background: #fff9e6; padding: 15px; border-radius: 6px; border-left: 4px solid #f0ad4e; margin-top: 20px;">
				<strong style="color: #f0ad4e;">💡 Pro Tip:</strong> <?php esc_html_e( 'Write questions as "I" statements (e.g., "I pick up my phone without thinking") to make them more personal and relatable.', 'altitude-accountability-audit' ); ?>
			</div>
		</div>

		<!-- Step 4: Editing Form Elements -->
		<div id="step4" class="doc-section" style="margin-bottom: 40px; padding-bottom: 30px; border-bottom: 2px solid #e5e5e5;">
			<h2 style="color: #667eea; display: flex; align-items: center; gap: 10px;">
				<span style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 18px;">4</span>
				<?php esc_html_e( 'Editing Form Elements', 'altitude-accountability-audit' ); ?>
			</h2>

			<p style="font-size: 15px; line-height: 1.8;">
				<?php esc_html_e( 'The Form Builder makes it easy to edit categories and questions directly from the preview.', 'altitude-accountability-audit' ); ?>
			</p>

			<div style="background: #f0f3ff; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #667eea;">
				<h4 style="margin-top: 0;"><?php esc_html_e( '✏️ Editing a Category', 'altitude-accountability-audit' ); ?></h4>
				<ol style="line-height: 2;">
					<li><strong><?php esc_html_e( 'Click the Category', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'In the center preview, click on the category you want to edit', 'altitude-accountability-audit' ); ?></li>
					<li><strong><?php esc_html_e( 'Edit in Right Panel', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'The right panel will show editable fields', 'altitude-accountability-audit' ); ?></li>
					<li><strong><?php esc_html_e( 'Make Your Changes', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'Update the name, icon, or description', 'altitude-accountability-audit' ); ?></li>
					<li><strong><?php esc_html_e( 'Click "Update Category"', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'Changes are saved and preview updates automatically', 'altitude-accountability-audit' ); ?></li>
				</ol>

				<h4><?php esc_html_e( '✏️ Editing a Question', 'altitude-accountability-audit' ); ?></h4>
				<ol style="line-height: 2;">
					<li><strong><?php esc_html_e( 'Click the Question', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'In the center preview, click on the question you want to edit', 'altitude-accountability-audit' ); ?></li>
					<li><strong><?php esc_html_e( 'Edit in Right Panel', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'Modify the question text or help text', 'altitude-accountability-audit' ); ?></li>
					<li><strong><?php esc_html_e( 'Click "Update Question"', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'Changes are saved immediately', 'altitude-accountability-audit' ); ?></li>
				</ol>

				<h4><?php esc_html_e( '🗑️ Deleting Elements', 'altitude-accountability-audit' ); ?></h4>
				<p><?php esc_html_e( 'Both categories and questions have a "Delete" button in their settings panel. Deleting a category will also delete all its questions.', 'altitude-accountability-audit' ); ?></p>
			</div>
		</div>

		<!-- Step 5: Publishing Your Form -->
		<div id="step5" class="doc-section" style="margin-bottom: 40px; padding-bottom: 30px; border-bottom: 2px solid #e5e5e5;">
			<h2 style="color: #667eea; display: flex; align-items: center; gap: 10px;">
				<span style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 18px;">5</span>
				<?php esc_html_e( 'Publishing Your Form', 'altitude-accountability-audit' ); ?>
			</h2>

			<p style="font-size: 15px; line-height: 1.8;">
				<?php esc_html_e( 'Once your form is ready, you can publish it on any page or post using a shortcode.', 'altitude-accountability-audit' ); ?>
			</p>

			<div style="background: #f0f3ff; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #667eea;">
				<h4 style="margin-top: 0;"><?php esc_html_e( '✅ Step-by-Step: Publishing', 'altitude-accountability-audit' ); ?></h4>
				<ol style="line-height: 2;">
					<li><strong><?php esc_html_e( 'Create or Edit a Page', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'Go to Pages → Add New (or edit an existing page)', 'altitude-accountability-audit' ); ?></li>
					<li><strong><?php esc_html_e( 'Add the Shortcode', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'In the content editor, add:', 'altitude-accountability-audit' ); ?>
						<div style="background: #2c3e50; color: #fff; padding: 15px; border-radius: 6px; margin: 15px 0; font-family: monospace; font-size: 14px;">
							[accountability_audit]
						</div>
					</li>
					<li><strong><?php esc_html_e( 'Publish the Page', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'Click the "Publish" button', 'altitude-accountability-audit' ); ?></li>
					<li><strong><?php esc_html_e( 'View Your Form', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'Visit the page to see your live form', 'altitude-accountability-audit' ); ?></li>
				</ol>
			</div>

			<div style="background: white; padding: 20px; border: 2px solid #e5e5e5; border-radius: 8px; margin: 20px 0;">
				<h4 style="margin-top: 0;"><?php esc_html_e( '📝 Available Shortcodes', 'altitude-accountability-audit' ); ?></h4>
				<table style="width: 100%; border-collapse: collapse;">
					<tr>
						<th style="text-align: left; padding: 12px; background: #f7f9fc; border-bottom: 2px solid #e5e5e5;"><?php esc_html_e( 'Shortcode', 'altitude-accountability-audit' ); ?></th>
						<th style="text-align: left; padding: 12px; background: #f7f9fc; border-bottom: 2px solid #e5e5e5;"><?php esc_html_e( 'Description', 'altitude-accountability-audit' ); ?></th>
					</tr>
					<tr>
						<td style="padding: 12px; border-bottom: 1px solid #e5e5e5; font-family: monospace; background: #f7f9fc;">
							<code style="background: #667eea; color: white; padding: 4px 8px; border-radius: 3px;">[accountability_audit]</code>
						</td>
						<td style="padding: 12px; border-bottom: 1px solid #e5e5e5;">
							<?php esc_html_e( 'Displays the complete accountability audit form', 'altitude-accountability-audit' ); ?>
						</td>
					</tr>
					<tr>
						<td style="padding: 12px; font-family: monospace; background: #f7f9fc;">
							<code style="background: #667eea; color: white; padding: 4px 8px; border-radius: 3px;">[audit_results]</code>
						</td>
						<td style="padding: 12px;">
							<?php esc_html_e( 'Shows user results (if they\'ve completed the audit)', 'altitude-accountability-audit' ); ?>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<!-- Step 6: Configuring Email Settings -->
		<div id="step6" class="doc-section" style="margin-bottom: 40px; padding-bottom: 30px; border-bottom: 2px solid #e5e5e5;">
			<h2 style="color: #667eea; display: flex; align-items: center; gap: 10px;">
				<span style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 18px;">6</span>
				<?php esc_html_e( 'Configuring Email Settings', 'altitude-accountability-audit' ); ?>
			</h2>

			<p style="font-size: 15px; line-height: 1.8;">
				<?php esc_html_e( 'Automatically send audit results to users via email.', 'altitude-accountability-audit' ); ?>
			</p>

			<div style="background: #f0f3ff; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #667eea;">
				<h4 style="margin-top: 0;"><?php esc_html_e( '✅ Setting Up Emails', 'altitude-accountability-audit' ); ?></h4>
				<ol style="line-height: 2;">
					<li><strong><?php esc_html_e( 'Go to Settings', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'Navigate to Accountability Audit → Settings', 'altitude-accountability-audit' ); ?></li>
					<li><strong><?php esc_html_e( 'Configure Email Settings', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'Set "From Name" and "From Email"', 'altitude-accountability-audit' ); ?></li>
					<li><strong><?php esc_html_e( 'Customize Subject Line', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'Enter a subject for result emails', 'altitude-accountability-audit' ); ?></li>
					<li><strong><?php esc_html_e( 'Edit Email Template', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'Go to Email Template page to customize the email content', 'altitude-accountability-audit' ); ?></li>
					<li><strong><?php esc_html_e( 'Save Changes', 'altitude-accountability-audit' ); ?></strong> - <?php esc_html_e( 'Click "Save Settings"', 'altitude-accountability-audit' ); ?></li>
				</ol>
			</div>

			<div style="background: #fff9e6; padding: 15px; border-radius: 6px; border-left: 4px solid #f0ad4e; margin-top: 20px;">
				<strong style="color: #f0ad4e;">💡 Pro Tip:</strong> <?php esc_html_e( 'Use merge tags in your email template like {first_name}, {total_score}, and {accountability_type} to personalize each email.', 'altitude-accountability-audit' ); ?>
			</div>
		</div>

		<!-- Step 7: Viewing Statistics -->
		<div id="step7" class="doc-section" style="margin-bottom: 40px; padding-bottom: 30px; border-bottom: 2px solid #e5e5e5;">
			<h2 style="color: #667eea; display: flex; align-items: center; gap: 10px;">
				<span style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 18px;">7</span>
				<?php esc_html_e( 'Viewing Statistics', 'altitude-accountability-audit' ); ?>
			</h2>

			<p style="font-size: 15px; line-height: 1.8;">
				<?php esc_html_e( 'Track how many people have completed your audit and analyze the results.', 'altitude-accountability-audit' ); ?>
			</p>

			<div style="background: white; padding: 20px; border: 2px solid #e5e5e5; border-radius: 8px; margin: 20px 0;">
				<h4 style="margin-top: 0;"><?php esc_html_e( '📊 Available Statistics', 'altitude-accountability-audit' ); ?></h4>
				<ul style="line-height: 1.8;">
					<li><strong><?php esc_html_e( 'Total Submissions:', 'altitude-accountability-audit' ); ?></strong> <?php esc_html_e( 'How many people completed the audit', 'altitude-accountability-audit' ); ?></li>
					<li><strong><?php esc_html_e( 'Accountability Types:', 'altitude-accountability-audit' ); ?></strong> <?php esc_html_e( 'Distribution of result types', 'altitude-accountability-audit' ); ?></li>
					<li><strong><?php esc_html_e( 'Average Scores:', 'altitude-accountability-audit' ); ?></strong> <?php esc_html_e( 'Average scores for each category', 'altitude-accountability-audit' ); ?></li>
					<li><strong><?php esc_html_e( 'Recent Submissions:', 'altitude-accountability-audit' ); ?></strong> <?php esc_html_e( 'Latest form completions', 'altitude-accountability-audit' ); ?></li>
				</ul>
				<p style="margin-top: 20px;">
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=altitude-audit-statistics' ) ); ?>" class="button button-primary">
						<?php esc_html_e( 'View Statistics →', 'altitude-accountability-audit' ); ?>
					</a>
				</p>
			</div>
		</div>

		<!-- Step 8: Troubleshooting -->
		<div id="step8" class="doc-section" style="margin-bottom: 40px;">
			<h2 style="color: #667eea; display: flex; align-items: center; gap: 10px;">
				<span style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 18px;">8</span>
				<?php esc_html_e( 'Troubleshooting', 'altitude-accountability-audit' ); ?>
			</h2>

			<div style="background: white; padding: 20px; border: 2px solid #e5e5e5; border-radius: 8px; margin: 20px 0;">
				<h4 style="margin-top: 0;"><?php esc_html_e( '❓ Common Issues', 'altitude-accountability-audit' ); ?></h4>

				<div style="margin-bottom: 25px;">
					<h5 style="color: #667eea;"><?php esc_html_e( 'Form not displaying on page', 'altitude-accountability-audit' ); ?></h5>
					<ul style="line-height: 1.8;">
						<li><?php esc_html_e( 'Make sure you\'ve added the [accountability_audit] shortcode', 'altitude-accountability-audit' ); ?></li>
						<li><?php esc_html_e( 'Check that the shortcode is in a paragraph block (not heading or code)', 'altitude-accountability-audit' ); ?></li>
						<li><?php esc_html_e( 'Clear your site cache if using a caching plugin', 'altitude-accountability-audit' ); ?></li>
					</ul>
				</div>

				<div style="margin-bottom: 25px;">
					<h5 style="color: #667eea;"><?php esc_html_e( 'Emails not being sent', 'altitude-accountability-audit' ); ?></h5>
					<ul style="line-height: 1.8;">
						<li><?php esc_html_e( 'Verify your email settings in Settings page', 'altitude-accountability-audit' ); ?></li>
						<li><?php esc_html_e( 'Check your WordPress email configuration', 'altitude-accountability-audit' ); ?></li>
						<li><?php esc_html_e( 'Consider using an SMTP plugin for reliable email delivery', 'altitude-accountability-audit' ); ?></li>
					</ul>
				</div>

				<div style="margin-bottom: 25px;">
					<h5 style="color: #667eea;"><?php esc_html_e( 'Preview not updating in Form Builder', 'altitude-accountability-audit' ); ?></h5>
					<ul style="line-height: 1.8;">
						<li><?php esc_html_e( 'Click the "Refresh" button in the preview panel', 'altitude-accountability-audit' ); ?></li>
						<li><?php esc_html_e( 'Clear your browser cache', 'altitude-accountability-audit' ); ?></li>
						<li><?php esc_html_e( 'Check browser console for JavaScript errors (F12)', 'altitude-accountability-audit' ); ?></li>
					</ul>
				</div>
			</div>
		</div>

		<!-- Developer Credits -->
		<div style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; padding: 30px; border-radius: 8px; margin-top: 40px;">
			<h3 style="margin: 0 0 15px 0; color: white; border: none; padding: 0;"><?php esc_html_e( '👨‍💻 Developer Information', 'altitude-accountability-audit' ); ?></h3>
			<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
				<div>
					<p style="margin: 0 0 10px 0; opacity: 0.8;"><?php esc_html_e( 'Developed By:', 'altitude-accountability-audit' ); ?></p>
					<p style="margin: 0; font-size: 20px; font-weight: 600;">Tijani Bulama</p>
				</div>
				<div>
					<p style="margin: 0 0 10px 0; opacity: 0.8;"><?php esc_html_e( 'Company:', 'altitude-accountability-audit' ); ?></p>
					<p style="margin: 0; font-size: 20px; font-weight: 600;">Skillscore IT Solutions</p>
				</div>
			</div>
			<div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.2);">
				<p style="margin: 0 0 10px 0;">
					<strong><?php esc_html_e( 'Website:', 'altitude-accountability-audit' ); ?></strong>
					<a href="https://www.skillscore.com.ng" target="_blank" rel="noopener noreferrer" style="color: #3498db; text-decoration: none;">
						www.skillscore.com.ng
					</a>
				</p>
				<p style="margin: 0; opacity: 0.7; font-size: 14px;">
					<?php echo sprintf(
						esc_html__( 'Plugin Version: %s', 'altitude-accountability-audit' ),
						'<strong>3.0.0</strong>'
					); ?>
				</p>
			</div>
		</div>

		<!-- Support Section -->
		<div style="background: #f0f3ff; padding: 25px; border-radius: 8px; margin-top: 20px; text-align: center;">
			<h3 style="margin: 0 0 15px 0;"><?php esc_html_e( '💬 Need Help?', 'altitude-accountability-audit' ); ?></h3>
			<p style="margin: 0 0 20px 0; font-size: 15px;">
				<?php esc_html_e( 'If you need assistance or have questions, feel free to reach out:', 'altitude-accountability-audit' ); ?>
			</p>
			<a href="https://www.skillscore.com.ng" target="_blank" rel="noopener noreferrer" class="button button-primary button-large">
				<?php esc_html_e( 'Contact Support', 'altitude-accountability-audit' ); ?>
			</a>
		</div>
	</div>
</div>

<style>
/* Smooth scrolling */
html {
	scroll-behavior: smooth;
}

/* Link hover effects */
.doc-section a:not(.button) {
	transition: color 0.2s;
}

.doc-section a:not(.button):hover {
	color: #764ba2;
}

/* Responsive adjustments */
@media (max-width: 782px) {
	.altitude-audit-card {
		padding: 15px !important;
	}

	.doc-section h2 {
		font-size: 18px !important;
	}

	.doc-section h2 span {
		width: 35px !important;
		height: 35px !important;
		font-size: 16px !important;
	}

	div[style*="column-count"] {
		column-count: 1 !important;
	}

	div[style*="grid-template-columns: 1fr 1fr"] {
		grid-template-columns: 1fr !important;
	}

	table {
		font-size: 13px !important;
	}
}
</style>
