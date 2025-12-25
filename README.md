# Altitude Within - Accountability Audit Plugin

**A completely standalone WordPress plugin** for creating a Personal Accountability Audit with quiz functionality, automated scoring, and personalized email results. **NO external dependencies required!**

## Features

### 🚀 Fully Standalone
- **No external plugins required** - completely self-contained
- Custom-built form system from scratch
- Independent of any form builder plugins
- All functionality built into the plugin

### 📋 Complete Quiz System
- **18 Questions** across 6 accountability categories
- 4-point scoring scale per question (0-3 points)
- Real-time score calculation as users answer
- Automatic determination of accountability type
- Progress indicator showing completion status

### 🧪 Built-in Testing Tools
- **Auto-Fill Button** (when WP_DEBUG is enabled)
- Instantly populate the form with random test data
- Perfect for testing and demonstrations
- One-click form completion

### 📧 Email System
- Customizable HTML email templates
- 12+ merge tags for personalization
- Conditional content blocks
- Email preview and test sending
- Automatic delivery of results

### 💾 Database & Analytics
- Custom database table for all submissions
- Detailed statistics by accountability type
- Average scores across categories
- Submission history tracking

### 🎯 Smart Redirects
- Redirect users to type-specific result pages
- Pass data via URL parameters
- Customizable redirect URLs per type

## Quiz Categories

1. **Distraction** → **The Drifter**
   - Measures tendency to lose focus

2. **Comfort** → **The Comfort Addict**
   - Assesses prioritization of comfort over growth

3. **Ego** → **The Performer**
   - Evaluates need for external validation

4. **Emotion** → **The Reactor**
   - Measures emotional vs. principle-driven decisions

5. **Boundaries** → **The Open Door**
   - Assesses ability to set healthy boundaries

6. **Spiritual Delay** → **The Spiritual Delayer**
   - Evaluates postponement of spiritual growth

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- **NO other plugins required!**

## Installation

1. Download the plugin files
2. Upload the `altitude-accountability-audit` folder to `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Navigate to "Accountability Audit" in the admin menu
5. Start using the form immediately - no setup required!

## Quick Start

### 1. Add Form to Page
Simply add the shortcode to any page:
```
[accountability_audit]
```

That's it! The form is ready to use immediately.

### 2. Configure Settings (Optional)
Go to **Accountability Audit > Settings** to:
- Customize form title and button text
- Set email sender information
- Configure result page URLs

### 3. Customize Email (Optional)
Go to **Accountability Audit > Email Template** to:
- Edit the HTML email template
- Add your branding
- Preview and test emails

### 4. Test the Form
Enable `WP_DEBUG` in wp-config.php to see the **Auto-Fill button**:
```php
define( 'WP_DEBUG', true );
```

The auto-fill button will appear on the form for quick testing!

## Shortcodes

### Display the Audit Form
```
[accountability_audit]
```
Renders the complete accountability audit form with all 18 questions.

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

## Form Features

### Personal Information Fields
- First Name (required)
- Email Address (required)

### Quiz Questions
Each question offers 4 responses:
- **Not true** (0 points)
- **Sometimes true** (1 point)
- **Often true** (2 points)
- **This is my pattern** (3 points)

### Scoring
- Category scores: 0-9 points each
- Total score: 0-54 points
- Highest category determines accountability type

### User Experience
- Clean, modern interface
- Mobile responsive design
- Progress indicator
- Real-time validation
- Visual feedback on answered questions
- Keyboard navigation support

## Configuration

### Form Settings
- **Form Title**: Heading displayed above the form
- **Submit Button Text**: Text on the submit button
- **Success Message**: Message after successful submission

### Email Settings
- **From Name**: Sender name in result emails
- **From Email**: Sender email address
- **Email Subject**: Subject line for result emails
- **Send to Admin**: Send copy to administrator

### Result Pages
Set redirect URLs for each accountability type:
- **Distraction** → The Drifter page
- **Comfort** → The Comfort Addict page
- **Ego** → The Performer page
- **Emotion** → The Reactor page
- **Boundaries** → The Open Door page
- **Spiritual Delay** → The Spiritual Delayer page

URL parameters automatically passed:
- `name` - User's first name
- `type` - Accountability type
- `score` - Total score

## Email Merge Tags

Available tags for email templates:

**User Information:**
- `{first_name}` - User's first name
- `{email}` - User's email address

**Results:**
- `{accountability_type}` - Determined type (label)
- `{type_description}` - Description of type
- `{total_score}` - Total score (0-54)

**Category Scores:**
- `{distraction_score}` - Distraction score (0-9)
- `{comfort_score}` - Comfort score (0-9)
- `{ego_score}` - Ego score (0-9)
- `{emotion_score}` - Emotion score (0-9)
- `{boundaries_score}` - Boundaries score (0-9)
- `{spiritual_score}` - Spiritual score (0-9)

**URLs:**
- `{result_url}` - Link to detailed results page

### Conditional Blocks
```html
{if_result_url}
  Content shown only if result URL is configured
{endif_result_url}
```

## Admin Features

### Dashboard
- Submission statistics
- Form status overview
- Quick action buttons
- Shortcode reference with copy buttons
- Getting started guide

### Settings Page
- Complete form configuration
- Email settings
- Result page URL mapping
- Import/Export configuration

### Statistics Page
- Total submissions count
- Submissions breakdown by type
- Average scores by category
- Visual bar charts

### Email Template Editor
- HTML template editor
- Merge tag reference
- Email preview
- Test email sending

## Developer Hooks

### Actions
```php
// Before form render
do_action( 'altitude_audit_before_form' );

// After form render
do_action( 'altitude_audit_after_form' );
```

### Filters
```php
// Modify email merge data
apply_filters( 'altitude_audit_email_data', $merge_data, $result_data, $config );
```

## Database Schema

Custom table: `wp_altitude_audit_results`

Fields:
- `id` - Unique result ID
- `user_id` - WordPress user ID (if logged in)
- `submission_id` - Same as ID
- `first_name` - User's first name
- `email` - User's email
- `distraction_score` - Distraction category score
- `comfort_score` - Comfort category score
- `ego_score` - Ego category score
- `emotion_score` - Emotion category score
- `boundaries_score` - Boundaries category score
- `spiritual_score` - Spiritual score
- `total_score` - Total score
- `accountability_type` - Determined type
- `created_at` - Submission timestamp

## Security Features

- Nonce verification on all form submissions
- Data sanitization and validation
- SQL injection prevention (prepared statements)
- XSS protection (output escaping)
- CSRF protection
- Capability checks for admin functions

## Troubleshooting

### Form Not Appearing
- Clear WordPress cache
- Check shortcode spelling
- Verify plugin is activated

### Auto-Fill Button Not Showing
- Enable WP_DEBUG in wp-config.php:
  ```php
  define( 'WP_DEBUG', true );
  ```

### Emails Not Sending
- Check WordPress email configuration
- Test with a known working email address
- Use an SMTP plugin if needed
- Send test email from Email Template page

### Scores Not Calculating
- Ensure JavaScript is enabled
- Check browser console for errors
- Verify all questions are answered

## Changelog

### 2.0.0 - Standalone Release
- **BREAKING**: Removed Fluent Forms dependency
- Complete standalone form system
- Built-in auto-fill testing button
- Real-time score calculation
- Enhanced form styling
- Improved mobile responsiveness
- Better accessibility features
- Keyboard navigation support

### 1.0.0 - Initial Release
- Fluent Forms integration
- Basic quiz functionality
- Email system
- Database storage

## Credits

Developed by **Altitude Within**

Built with WordPress best practices and coding standards.

## License

GPL v2 or later

---

## Why This Plugin is Different

✅ **Truly Standalone** - No dependencies on other plugins
✅ **Auto-Fill Testing** - Built-in testing tools
✅ **Production Ready** - Secure, tested, and optimized
✅ **Developer Friendly** - Clean code, well documented
✅ **User Friendly** - Beautiful UI, mobile responsive
✅ **Fully Configurable** - Everything customizable via admin

Perfect for coaches, consultants, and organizations running accountability assessments!
