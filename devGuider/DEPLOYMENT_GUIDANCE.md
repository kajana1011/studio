# Studio Media Tanzania - Deployment Guide

## 🚀 Quick Deployment Checklist

### Pre-Deployment
- [ ] Server with PHP 8.0+ and MySQL 8.0+
- [ ] Domain name configured
- [ ] SSL certificate installed
- [ ] Email server configured (optional)

### Database Setup
- [ ] Create MySQL database
- [ ] Import `database/schema.sql`
- [ ] Create database user with appropriate permissions
- [ ] Test database connection

### File Configuration
- [ ] Update `includes/config.php` with production settings
- [ ] Set file permissions correctly
- [ ] Create upload directories
- [ ] Configure email settings

### Testing
- [ ] Test all forms (booking, contact, testimonials)
- [ ] Verify admin login works
- [ ] Check responsive design on all devices
- [ ] Test email notifications
- [ ] Verify database operations

## 📋 Detailed Deployment Steps

### 1. Server Setup

#### Shared Hosting (Recommended for beginners)
1. Choose a hosting provider with PHP/MySQL support
2. Upload files via FTP/cPanel File Manager
3. Create database through hosting control panel
4. Configure database connection

#### VPS/Dedicated Server
```bash
# Install required packages (Ubuntu/Debian)
sudo apt update
sudo apt install apache2 php php-mysql php-mbstring php-xml php-curl
sudo apt install mysql-server

# Enable Apache modules
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 2. Database Configuration

#### Create Database
```sql
CREATE DATABASE studio_media_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'studio_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON studio_media_db.* TO 'studio_user'@'localhost';
FLUSH PRIVILEGES;
```

#### Import Schema
```bash
mysql -u studio_user -p studio_media_db < database/schema.sql
```

### 3. Configuration Files

#### Update `includes/config.php`
```php
<?php
// Production Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'studio_user');
define('DB_PASS', 'your_secure_password');
define('DB_NAME', 'studio_media_db');

// Production Site Configuration
define('SITE_NAME', 'Studio Media Tanzania');
define('SITE_URL', 'https://yourdomain.com');
define('ADMIN_EMAIL', 'info@yourdomain.com');

// Security Settings
define('SECURE_CONNECTION', true); // HTTPS only
define('SESSION_TIMEOUT', 3600); // 1 hour

// File Upload (adjust based on server limits)
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('UPLOAD_PATH', 'assets/uploads/');
?>
```

### 4. File Permissions

#### Set Correct Permissions
```bash
# Make directories writable
chmod 755 assets/uploads/
chmod 755 assets/images/

# Secure configuration files
chmod 644 includes/config.php
chmod 644 database/schema.sql

# Make PHP files executable
find . -name "*.php" -exec chmod 644 {} \;
```

### 5. Apache Configuration

#### Create `.htaccess` file
```apache
# Studio Media Tanzania - Apache Configuration

# Security Headers
Header always set X-Frame-Options DENY
Header always set X-Content-Type-Options nosniff
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"

# Force HTTPS (if SSL is available)
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Pretty URLs
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^([^/]+)/?$ $1.php [L,QSA]

# Security - Deny access to sensitive files
<Files ~ "^\.">
    Order allow,deny
    Deny from all
</Files>

<Files "config.php">
    Order allow,deny
    Deny from all
</Files>

<Files "*.sql">
    Order allow,deny
    Deny from all
</Files>

# Cache Control
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/gif "access plus 1 month"
    ExpiresByType image/webp "access plus 1 month"
</IfModule>

# Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
```

### 6. Email Configuration

#### For Production Email
Update contact forms to use SMTP:
```php
// In config.php, add SMTP settings
define('SMTP_HOST', 'smtp.yourdomain.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'noreply@yourdomain.com');
define('SMTP_PASSWORD', 'smtp_password');
define('SMTP_ENCRYPTION', 'tls');
```

### 7. SSL Certificate

#### Let's Encrypt (Free SSL)
```bash
sudo apt install certbot python3-certbot-apache
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com
```

### 8. Final Testing Checklist

#### Functionality Tests
- [ ] Homepage loads correctly
- [ ] Navigation works on all pages
- [ ] Portfolio gallery functions properly
- [ ] Booking form submits successfully
- [ ] Contact form sends emails
- [ ] Admin login works
- [ ] Database operations successful
- [ ] File uploads work (if implemented)

#### Performance Tests
- [ ] Page load times under 3 seconds
- [ ] Images optimized and loading
- [ ] Mobile responsiveness verified
- [ ] Cross-browser compatibility

#### Security Tests
- [ ] HTTPS working properly
- [ ] Admin panel secured
- [ ] File upload restrictions
- [ ] SQL injection prevention
- [ ] XSS protection enabled

### 9. Post-Deployment Tasks

#### Immediate Tasks
1. Change default admin password
2. Update contact information
3. Replace placeholder images
4. Test all contact forms
5. Set up Google Analytics (optional)
6. Submit sitemap to search engines

#### Ongoing Maintenance
1. Regular database backups
2. Keep PHP and packages updated
3. Monitor error logs
4. Review security settings
5. Update portfolio content regularly

## 🔧 Troubleshooting

### Common Issues

#### Database Connection Errors
```
Error: "Connection failed: Access denied"
```
**Solution**: Check database credentials and user permissions

#### File Upload Problems
```
Error: "Failed to upload file"
```
**Solutions**:
- Check directory permissions (755 for directories)
- Verify PHP upload limits
- Ensure upload directory exists

#### Email Not Sending
```
Error: "Failed to send email"
```
**Solutions**:
- Check SMTP settings
- Verify server mail configuration
- Check spam/firewall settings

#### PHP Errors
```
Error: "Parse error" or "Fatal error"
```
**Solutions**:
- Check PHP syntax
- Verify PHP version compatibility
- Review error logs

### Debug Mode
For development/testing, enable debug mode in `config.php`:
```php
// Debug settings (disable in production)
define('DEBUG_MODE', false); // Set to true for debugging
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
```

## 📞 Support

### Technical Issues
- **Developer**: Revocatus Kajana
- **Email**: revocajana@gmail.com
- **Location**: Dar es Salaam, Tanzania

### Hosting Support
Contact your hosting provider for:
- Server configuration issues
- Database access problems
- SSL certificate installation
- Email server setup

## 📋 Deployment Checklist Summary

**Before Going Live:**
- [ ] All placeholder content replaced
- [ ] Contact information updated
- [ ] Admin credentials changed
- [ ] SSL certificate installed
- [ ] All forms tested
- [ ] Mobile responsiveness verified
- [ ] Performance optimized

**After Going Live:**
- [ ] Google Analytics/Search Console setup
- [ ] Social media integration
- [ ] Regular backups scheduled
- [ ] Monitoring tools configured
- [ ] SEO optimization completed

---

**Good luck with your deployment! 🚀**

For any deployment assistance, feel free to contact the developer.
