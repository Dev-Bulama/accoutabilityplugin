# Altitude Within - Accountability Audit Plugin

A comprehensive WordPress plugin for Fluent Forms that implements a "Personal Accountability Audit" form with intelligent quiz functionality, automated scoring, and personalized email results.

## Features

### Core Functionality
- **Programmatic Form Creation**: Creates Fluent Forms dynamically from configuration (no JSON import needed)
- **Quiz System**: 6 categories with 3 questions each (18 total questions)
- **Automated Scoring**: Calculates scores for each category and determines accountability type
- **Email Integration**: Sends personalized results via customizable HTML email templates
- **Database Storage**: Stores all submissions for analytics and reporting
- **Result Display**: Shortcodes for displaying user results on any page

### Admin Features
- **Dashboard**: Overview of submissions, quick actions, and getting started guide
- **Settings Page**: Configure form settings, email settings, and result page URLs
- **Email Template Editor**: WYSIWYG-style editor with merge tags and preview
- **Statistics**: Visual analytics of submissions by type and average scores
- **Import/Export**: Backup and restore your configuration
- **Form Rebuilding**: Regenerate form from configuration at any time

### Quiz Categories
1. **Distraction** → The Drifter
2. **Comfort** → The Comfort Addict
3. **Ego** → The Performer
4. **Emotion** → The Reactor
5. **Boundaries** → The Open Door
6. **Spiritual Delay** → The Spiritual Delayer

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- Fluent Forms plugin (free or pro)

## Installation

1. Download the plugin files
2. Upload the `altitude-accountability-audit` folder to `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Navigate to "Accountability Audit" in the admin menu
5. Click "Create Audit Form" to generate the form

## Quick Start

### 1. Create the Form
Upon activation, go to **Accountability Audit > Dashboard** and click **"Create Audit Form"**. This will programmatically generate a Fluent Form with all 18 questions.

### 2. Configure Settings
Go to **Accountability Audit > Settings** to:
- Set form title and submit button text
- Configure email sender information
- Set result page URLs for each accountability type

### 3. Customize Email Template
Go to **Accountability Audit > Email Template** to:
- Edit the HTML email template
- Use merge tags for personalization
- Preview and test emails

### 4. Add to Your Site
Use the shortcode `[accountability_audit]` on any page or post to display the form.

## Shortcodes

### Display the Audit Form
```
[accountability_audit]
```
Renders the complete accountability audit form.

### Display User Results (Latest)
```
[audit_results]
```
Shows the most recent audit results for logged-in users.

### Display User Results (History)
```
[audit_results show_history="yes"]
```
Shows all historical audit results for logged-in users.

## Configuration

### Form Settings
- **Form Title**: The heading displayed above the form
- **Submit Button Text**: Text on the submit button
- **Success Message**: Message shown after successful submission

### Email Settings
- **From Name**: Sender name in result emails
- **From Email**: Sender email address
- **Email Subject**: Subject line for result emails
- **Send to Admin**: Option to send copy to site administrator

### Result Pages
Set URLs for each accountability type where users will be directed for detailed information:
- Distraction → The Drifter page
- Comfort → The Comfort Addict page
- Ego → The Performer page
- Emotion → The Reactor page
- Boundaries → The Open Door page
- Spiritual Delay → The Spiritual Delayer page

## Email Merge Tags

Use these merge tags in your email template:

- `{first_name}` - User's first name
- `{email}` - User's email address
- `{accountability_type}` - Determined accountability type (label)
- `{type_description}` - Description of the accountability type
- `{distraction_score}` - Distraction category score
- `{comfort_score}` - Comfort category score
- `{ego_score}` - Ego category score
- `{emotion_score}` - Emotion category score
- `{boundaries_score}` - Boundaries category score
- `{spiritual_score}` - Spiritual Delay category score
- `{total_score}` - Total score across all categories
- `{result_url}` - URL to detailed results page

### Conditional Blocks
```html
{if_result_url}
<a href="{result_url}">View Detailed Results</a>
{endif_result_url}
```

## Developer Hooks

### Actions

**Before Form Render**
```php
do_action( 'altitude_audit_before_form' );
```

**After Form Render**
```php
do_action( 'altitude_audit_after_form' );
```

**Before Scoring**
```php
do_action( 'altitude_audit_before_scoring', $submission_id, $form_data, $form );
```

**After Scoring**
```php
do_action( 'altitude_audit_after_scoring', $result_id, $result_data, $form_data );
```

### Filters

**Modify Type Determination**
```php
apply_filters( 'altitude_audit_determine_type', $accountability_type, $scores, $form_data );
```

**Modify Email Data**
```php
apply_filters( 'altitude_audit_email_data', $merge_data, $result_data, $config );
```

## Database Schema

The plugin creates a custom table `wp_altitude_audit_results` with the following structure:

- `id` - Unique result ID
- `user_id` - WordPress user ID (if logged in)
- `submission_id` - Fluent Forms submission ID
- `first_name` - User's first name
- `email` - User's email
- `distraction_score` - Score for distraction category
- `comfort_score` - Score for comfort category
- `ego_score` - Score for ego category
- `emotion_score` - Score for emotion category
- `boundaries_score` - Score for boundaries category
- `spiritual_score` - Score for spiritual delay category
- `total_score` - Total score
- `accountability_type` - Determined type
- `created_at` - Timestamp

## Scoring Logic

Each question has 4 possible answers:
- "Not true" = 0 points
- "Sometimes true" = 1 point
- "Often true" = 2 points
- "This is my pattern" = 3 points

Each category has 3 questions, so:
- Category score range: 0-9 points
- Total score range: 0-54 points

The accountability type is determined by the highest scoring category.

## Import/Export Configuration

### Export
1. Go to **Accountability Audit > Settings**
2. Click **"Export Configuration"**
3. Save the JSON file

### Import
1. Go to **Accountability Audit > Settings**
2. Click **"Import Configuration"**
3. Paste the JSON data
4. Click **"Process Import"**

## Security Features

- Nonce verification for all AJAX requests
- Data sanitization on all inputs
- SQL injection prevention through prepared statements
- XSS protection via output escaping
- Capability checks for admin functions
- CSRF protection on forms

## Troubleshooting

### Form Not Appearing
- Ensure Fluent Forms is installed and activated
- Check that the form was created (Dashboard > Form ID should show a number)
- Try rebuilding the form from Dashboard

### Email Not Sending
- Check WordPress email configuration
- Verify SMTP settings if using an SMTP plugin
- Send a test email from Email Template page
- Check spam folder

### Scores Not Calculating
- Ensure all questions are answered
- Check browser console for JavaScript errors
- Verify Fluent Forms submission was successful

### Database Errors
- Check database table was created during activation
- Try deactivating and reactivating the plugin
- Check file permissions

## Support

For issues and feature requests, please contact support through your preferred channel.

## Changelog

### 1.0.0 - 2024
- Initial release
- Programmatic form creation
- Automated scoring engine
- Email template system
- Admin dashboard and statistics
- Import/export functionality
- Shortcode support

## Credits

Developed by Altitude Within
Built with WordPress and Fluent Forms

## License

GPL v2 or later
