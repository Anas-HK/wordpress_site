# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

This is a WordPress child theme called "Leadership Coach" based on the CoachPress Lite parent theme. It's designed for coaches, mentors, and leadership consultants with features like appointment booking, service management, and testimonial showcases.

### Theme Information
- **Theme Name**: Leadership Coach
- **Parent Theme**: CoachPress Lite (must be installed)
- **WordPress Version**: 6.2+ required
- **PHP Version**: 7.3+ required

## Development Environment

### Local Setup with Laragon
The theme is currently running in a Laragon environment at:
- **Theme Path**: `C:/laragon/www/coaching-site/wp-content/themes/leadership-coach/`
- **Site URL**: Typically `http://coaching-site.test` (depending on Laragon configuration)

### Alternative Docker Setup
A `docker-compose.yml` is provided for containerized development:
```bash
# Start the WordPress environment
docker-compose up -d

# Access at http://localhost:8080
```

## Common Development Commands

### Theme Development
```bash
# No build process required - this is a traditional WordPress theme
# CSS and JS files are directly edited in their respective directories

# When making style changes, edit:
# - style.css (main theme styles)
# - assets/css/custom-styles.css (custom coaching styles)
# - assets/css/responsive.css (responsive design)
# - assets/css/calendar-styles.css (calendar widget)
# - assets/css/booking-system.css (appointment booking)
```

### WordPress CLI (if available)
```bash
# Clear cache
wp cache flush

# Regenerate thumbnails after image size changes
wp media regenerate

# Check theme status
wp theme status leadership-coach

# Export/Import database
wp db export backup.sql
wp db import backup.sql
```

## Architecture & File Structure

### Key Theme Files
- **functions.php**: Main theme setup, custom post types (services, testimonials), meta boxes, booking system initialization
- **style.css**: Child theme declaration and custom CSS variables for the purple/lilac color scheme
- **front-page.php**: Homepage template with hero section and service previews
- **Page Templates**:
  - `page-about.php`: About page with coach credentials meta boxes
  - `page-services.php`: Services listing
  - `page-contact.php`: Contact form with custom styling
  - `page-calendar.php`: Appointment booking calendar
  - `page-blog.php`: Blog listing

### Custom Features

#### 1. Appointment Booking System
- **Database Table**: `wp_coaching_appointments` (created on theme activation)
- **Model**: `inc/models/class-appointment.php`
- **Admin Interface**: `inc/admin/class-appointments-admin.php`
- **Frontend JS**: `inc/js/booking-system.js`, `inc/js/calendar-widget.js`
- **AJAX Endpoints**:
  - `wp_ajax_get_available_time_slots`
  - `wp_ajax_get_month_availability`
  - `wp_ajax_book_appointment`

#### 2. Custom Post Types
- **Coaching Services** (`coaching_service`): Service offerings with price, duration, and booking settings
- **Testimonials** (`testimonial`): Client testimonials with ratings and featured status

#### 3. Enhanced Customizer Options
- Color palette controls (primary purple, secondary lilac, accent pink)
- Typography settings (Nunito for headings, Lora for body)
- Header layout options (two styles available)
- CTA banner layout options

### JavaScript Files
- **customize.js**: Customizer enhancements
- **contact-form.js**: Contact form validation and submission
- **appointments-admin.js**: Admin interface for appointment management
- **custom-nav.js**: Navigation menu enhancements
- **calendly-integration.js**: Enhanced Calendly widget integration with error handling and analytics tracking

## Theme Customization

### Color Scheme
The theme uses CSS custom properties defined in `style.css`:
```css
--primary-purple: #9b5de5;
--secondary-lilac: #cba6f7;
--accent-pink: #f6c6ea;
--light-background: #f8f6fa;
--dark-text: #2e2c38;
```

### Adding New Page Templates
1. Create a new PHP file with the template header:
   ```php
   <?php
   /*
   Template Name: Your Template Name
   */
   ```
2. Place in the theme root directory
3. The template will appear in the WordPress page editor

### Modifying the Appointment System
- Time slots are defined in `class-appointment.php::generate_time_slots()`
- Business hours: Monday-Friday 9 AM - 5 PM
- 1-hour slots with 1-hour duration
- To modify, edit the time generation logic in the model

## Testing

### Manual Testing Checklist
1. **Appointment Booking**:
   - Calendar displays correctly
   - Available time slots load via AJAX
   - Booking form validation works
   - Confirmation emails are sent

2. **Custom Post Types**:
   - Services can be created/edited
   - Testimonials display correctly
   - Meta boxes save data properly

3. **Responsive Design**:
   - Test on mobile devices (breakpoints at 768px, 1024px, 1350px)
   - Navigation hamburger menu on mobile
   - Contact form responsive layout

4. **Theme Customizer**:
   - Color changes apply correctly
   - Typography settings work
   - Header layout switches properly

## Important Considerations

1. **Parent Theme Dependency**: The CoachPress Lite parent theme must be installed and activated
2. **Database Changes**: The theme creates custom database tables on activation
3. **Email Configuration**: Appointment confirmations require proper WordPress email setup
4. **Timezone Handling**: Appointment system uses WordPress timezone settings
5. **Child Theme Updates**: Be careful not to override parent theme functions without proper unhooking

## Debugging

### Enable WordPress Debug Mode
Add to `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### Common Issues
1. **Appointments not saving**: Check database table exists and user permissions
2. **Styles not loading**: Verify parent theme is active
3. **AJAX errors**: Check nonce verification and WordPress AJAX URL
4. **Custom post types not showing**: Flush permalinks (Settings > Permalinks > Save)

## Security Notes

- All user inputs are sanitized using WordPress functions
- Nonces are used for all AJAX requests
- Database queries use prepared statements
- File uploads are not implemented (security consideration)
- Contact form includes honeypot field for spam protection
