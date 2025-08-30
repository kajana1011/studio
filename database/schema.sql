-- b25studio Database Schema
-- IT SOME DATA TO INSERT
CREATE DATABASE IF NOT EXISTS b25studiostudio;
USE b25studio;

-- role-based Users table for authentication
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'client', 'staff') DEFAULT 'client',
    phone VARCHAR(20),
    address TEXT NULL
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);


-- Services table
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    category ENUM('photography', 'videography', 'editing', 'printing') NOT NULL,
    base_price DECIMAL(10, 2),
    duration_hours INT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Packages table
CREATE TABLE packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    duration_hours INT,
    features JSON,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE budgets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    min_amount INT NOT NULL,       -- minimum amount in TSh
    max_amount INT NULL,           -- maximum amount in TSh, NULL means open-ended
    label VARCHAR(50) NOT NULL,    -- display label, e.g., "TSh 10,000 - 50,000"
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bookings table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    client_id INT NULL, -- Nullable for guest bookings
    service_id INT NOT NULL,
    package_id INT NULL,
    event_date DATE NOT NULL,
    event_time TIME,
    location VARCHAR(255),
    budget_id INT NULL,
    message TEXT,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
    
    FOREIGN KEY (budget_id) REFERENCES budgets(id) ON DELETE SET NULL,
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE SET NULL,
    FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Portfolio table
CREATE TABLE portfolio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    category ENUM('wedding', 'portrait', 'event', 'commercial', 'video') NOT NULL,
    media_type ENUM('image', 'video') NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    thumbnail_path VARCHAR(500),
    is_featured BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Client galleries table (for storing client-specific photos/videos)
CREATE TABLE client_galleries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    booking_id INT,
    gallery_name VARCHAR(200) NOT NULL,
    description TEXT,
    access_code VARCHAR(50) UNIQUE,
    is_public BOOLEAN DEFAULT FALSE,
    expiry_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);

-- Client media files table
CREATE TABLE client_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gallery_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_type ENUM('image', 'video') NOT NULL,
    file_size BIGINT,
    mime_type VARCHAR(100),
    is_edited BOOLEAN DEFAULT FALSE,
    download_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (gallery_id) REFERENCES client_galleries(id) ON DELETE CASCADE
);

-- Testimonials table
CREATE TABLE testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    client_email VARCHAR(100),
    client_id INT NULL, -- Nullable for anonymous testimonials
    service_type VARCHAR(100),
    rating INT CHECK (rating >= 1 AND rating <= 5),
    testimonial TEXT NOT NULL,
    client_photo VARCHAR(500),
    is_featured BOOLEAN DEFAULT FALSE,
    is_approved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Contact inquiries table
CREATE TABLE contact_inquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    status ENUM('new', 'responded', 'closed') DEFAULT 'new',
    admin_response TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Website settings table
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('text', 'number', 'boolean', 'json') DEFAULT 'text',
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Activity logs table
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(100),
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- INSERTING DEFAULT DATA

-- Insert default budget ranges
INSERT INTO budgets (min_amount, max_amount, label) VALUES
(NULL, 50000, 'Less than TSh 50,000'),
(50000, 200000, 'TSh 50,000 - 200,000'),
(200000, 500000, 'TSh 200,000 - 500,000'),
(500000, 1000000, 'TSh 500,000 - 1,000,000'),
(1000001, NULL, 'TSh 1,000,000+');

-- Insert default services
INSERT INTO services (name, description, category, base_price, duration_hours) VALUES
('Wedding Photography', 'Complete wedding day photography coverage', 'photography', 500000.00, 8),
('Wedding Videography', 'Full wedding ceremony and reception videography', 'videography', 800000.00, 10),
('Portrait Session', 'Individual or family portrait photography', 'photography', 150000.00, 2),
('Event Photography', 'Corporate and social event photography', 'photography', 200000.00, 4),
('Commercial Photography', 'Product and business photography', 'photography', 100000.00, 3),
('Photo Editing', 'Professional photo retouching and enhancement', 'editing', 5000.00, 1),
('Video Editing', 'Complete video post-production services', 'editing', 100000.00, 5),
('Photo Printing', 'High-quality photo printing services', 'printing', 2000.00, 1);

-- Insert default packages
INSERT INTO packages (name, description, price, duration_hours, features) VALUES
('Basic Package', 'Perfect for small events and sessions', 300000.00, 4, '["4 hours coverage", "50 edited photos", "Online gallery", "Basic editing"]'),
('Premium Package', 'Most popular choice for weddings and events', 600000.00, 8, '["8 hours coverage", "100 edited photos", "Video highlights (3 min)", "Premium editing", "USB drive included"]'),
('Luxury Package', 'Complete premium experience', 1000000.00, 12, '["Full day coverage", "200+ edited photos", "Full ceremony video", "Same-day editing", "Custom album included", "Two photographers"]');

-- Insert default settings
INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES
('site_name', 'b25studio', 'text', 'Website name'),
('site_email', 'info@b25studio.com', 'text', 'Main contact email'),
('site_phone', '+255 742 478 700', 'text', 'Main contact phone'),
('site_address', 'Dar es Salaam, Tanzania', 'text', 'Business address'),
('booking_advance_days', '14', 'number', 'Minimum days in advance for booking'),
('max_file_size', '10485760', 'number', 'Maximum file upload size in bytes (10MB)'),
('gallery_access_duration', '90', 'number', 'Days client gallery remains accessible'),
('auto_approve_testimonials', 'false', 'boolean', 'Auto approve client testimonials');

-- Create indexes for better performance
CREATE INDEX idx_bookings_date ON bookings(event_date);
CREATE INDEX idx_bookings_status ON bookings(status);
CREATE INDEX idx_portfolio_category ON portfolio(category);
CREATE INDEX idx_client_galleries_access_code ON client_galleries(access_code);
CREATE INDEX idx_client_media_gallery ON client_media(gallery_id);
CREATE INDEX idx_testimonials_approved ON testimonials(is_approved);
CREATE INDEX idx_contact_status ON contact_inquiries(status);
CREATE INDEX idx_activity_logs_date ON activity_logs(created_at);

INSERT INTO testimonials 
(client_name, client_email, service_type, rating, testimonial, client_photo, is_featured, is_approved)
VALUES
('Sarah & Michael Johnson', 'sarah.mj@example.com', 'Wedding Photography', 5,
 'b25studio captured our wedding day perfectly! Every moment was beautifully documented, and the final photos exceeded our expectations. Highly professional and creative team.',
 'assets/images/profile-icon.jpg', TRUE, TRUE),

('John Smith', 'john.smith@example.com', 'Corporate Event', 5,
 'The team did an amazing job covering our annual corporate event. Every important detail was captured professionally.',
 'assets/images/profile-icon.jpg', FALSE, TRUE),

('Fatma Hassan', 'fatma.h@example.com', 'Birthday Photoshoot', 4,
 'I loved the photos from my birthday! The editing was excellent and the online gallery made it easy to share with family.',
 'assets/images/profile-icon.jpg', FALSE, TRUE),

('David Kamau', 'david.k@example.com', 'Wedding Photography', 5,
 'Our wedding photos look like a fairytale! Thank you for making our day extra special.',
 'assets/images/profile-icon.jpg', TRUE, TRUE),

('Anna Brown', 'anna.b@example.com', 'Portrait Photography', 4,
 'Great experience with the portrait session. The photographer was very friendly and made me feel comfortable.',
 'assets/images/profile-icon.jpg', FALSE, TRUE),

('George Mushi', 'george.m@example.com', 'Graduation Photography', 5,
 'Perfect coverage of my graduation ceremony. I will cherish these photos forever.',
 'assets/images/profile-icon.jpg', TRUE, TRUE),

('Mary Johnson', 'mary.j@example.com', 'Family Photoshoot', 4,
 'Beautiful family portraits, we will definitely book again next year.',
 'assets/images/profile-icon.jpg', FALSE, TRUE),

('Ahmed Ali', 'ahmed.ali@example.com', 'Corporate Headshots', 5,
 'Very professional headshots that helped me update my LinkedIn profile. Highly recommended!',
 'assets/images/profile-icon.jpg', FALSE, TRUE),

('Grace Nambala', 'grace.n@example.com', 'Engagement Photoshoot', 5,
 'Our engagement photoshoot was magical! Every shot looks perfect and natural.',
 'assets/images/profile-icon.jpg', TRUE, TRUE),

('Peter Wilson', 'peter.w@example.com', 'Product Photography', 4,
 'The studio helped me showcase my products online with high-quality images. Sales improved immediately!',
 'assets/images/profile-icon.jpg', FALSE, TRUE);

-- Grant permissions (adjust as needed for your setup)
-- GRANT ALL PRIVILEGES ON studio_media_db.* TO 'studio_user'@'localhost' IDENTIFIED BY 'your_password';
-- FLUSH PRIVILEGES;
