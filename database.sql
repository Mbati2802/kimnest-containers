-- KimNest Containers Database Schema
-- Import this into your database via phpMyAdmin
-- Make sure you have selected your database first

-- Site Settings
CREATE TABLE site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Site Content (CMS)
CREATE TABLE site_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_slug VARCHAR(50) NOT NULL,
    section_key VARCHAR(50) NOT NULL,
    content_key VARCHAR(100) NOT NULL,
    content_value LONGTEXT,
    content_type ENUM('text', 'textarea', 'html', 'image') DEFAULT 'text',
    is_visible TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_content (page_slug, section_key, content_key)
) ENGINE=InnoDB;

-- Admin Users
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Blog Posts
CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    image VARCHAR(255),
    category VARCHAR(100),
    status ENUM('draft', 'published') DEFAULT 'draft',
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Projects
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    category VARCHAR(100) NOT NULL,
    description TEXT,
    full_description LONGTEXT,
    client_testimonial TEXT,
    client_name VARCHAR(100),
    location VARCHAR(255),
    completed_date DATE,
    status ENUM('completed', 'ongoing') DEFAULT 'completed',
    featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Project Images
CREATE TABLE project_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    image VARCHAR(255) NOT NULL,
    caption VARCHAR(255),
    sort_order INT DEFAULT 0,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Quote Requests
CREATE TABLE quotes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    company_name VARCHAR(100),
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    project_type VARCHAR(100),
    container_size VARCHAR(50),
    quantity INT DEFAULT 1,
    project_location VARCHAR(255),
    intended_use TEXT,
    budget VARCHAR(50),
    completion_date VARCHAR(50),
    description TEXT,
    attachment VARCHAR(255),
    contact_method VARCHAR(20) DEFAULT 'phone',
    cart_data TEXT NULL,
    status ENUM('new', 'contacted', 'quoted', 'closed') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Contact Submissions
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    attachment VARCHAR(255),
    status ENUM('unread', 'read', 'replied') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Media Library
CREATE TABLE media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    filepath VARCHAR(500) NOT NULL,
    filetype VARCHAR(50),
    filesize INT DEFAULT 0,
    alt_text VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Products
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    category VARCHAR(100) NOT NULL,
    size VARCHAR(50),
    description TEXT,
    features TEXT,
    specs JSON,
    image VARCHAR(255),
    price_label VARCHAR(100),
    status ENUM('available', 'limited', 'unavailable') DEFAULT 'available',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Product Images
CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image VARCHAR(255) NOT NULL,
    caption VARCHAR(255),
    sort_order INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Newsletter Subscribers
CREATE TABLE newsletter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) NOT NULL UNIQUE,
    status ENUM('active', 'unsubscribed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Notification Templates
CREATE TABLE notification_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    template_key VARCHAR(100) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    body LONGTEXT NOT NULL,
    placeholders TEXT,
    notification_email VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Insert default admin user
INSERT INTO admin_users (username, password, full_name, email) VALUES
('admin', '$2y$10$O.pxPhWMsjoCRZz0Bf7JA.jDQjDlRmaudE/fom7hb6ksRqnUnSsfK', 'Administrator', 'admin@kimnestcontainers.co.ke');

CREATE TABLE IF NOT EXISTS faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(100) NOT NULL DEFAULT 'General',
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO faqs (category, question, answer, sort_order) VALUES
('General', 'Do you sell both new and used shipping containers?', 'Yes. We supply both new and used shipping containers depending on your requirements, budget, and intended application.', 1),
('General', 'Can you customize containers?', 'Absolutely. We specialize in custom fabrication, including offices, homes, shops, restaurants, classrooms, clinics, and other modular structures.', 2),
('General', 'Do you deliver across Kenya?', 'Yes. We provide nationwide delivery and professional installation services to all parts of Kenya.', 3),
('Fabrication', 'How long does fabrication take?', 'Project timelines depend on the size and complexity of the work. Once your requirements are confirmed, we will provide an estimated completion schedule.', 4),
('Design & Planning', 'Are container homes durable?', 'Yes. Shipping containers are built from high-strength steel designed to withstand harsh environmental conditions.', 5),
('Design & Planning', 'Do container buildings require planning approvals?', 'Depending on your location and intended use, approvals may be required. Consult local authorities before construction.', 6);

CREATE TABLE IF NOT EXISTS newsletter_campaigns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    sent_count INT DEFAULT 0,
    total_count INT DEFAULT 0,
    status ENUM('draft', 'sending', 'sent', 'failed') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default Site Settings
INSERT INTO site_settings (setting_key, setting_value) VALUES
('site_name', 'Kimnest Containers'),
('site_tagline', 'We weave your property dream into reality. Transforming shipping containers into exceptional living, working, and business spaces.'),
('site_phone', '+254 712 345 678'),
('site_email', 'info@kimnestcontainers.co.ke'),
('site_whatsapp', '254712345678'),
('site_address', 'Nairobi, Kenya'),
('facebook_url', '#'),
('instagram_url', '#'),
('linkedin_url', '#'),
('tiktok_url', '#'),
('youtube_url', '#'),
('mail_from_email', 'noreply@kimnestcontainers.co.ke'),
('mail_from_name', 'Kimnest Containers');

-- Default Notification Templates
INSERT INTO notification_templates (template_key, name, subject, body, placeholders, notification_email) VALUES
('contact_notification', 'Contact Form Submission', '📬 New Contact: {subject}', '<!DOCTYPE html>\n<html>\n<head>\n<meta charset=\"UTF-8\">\n<style>\nbody{font-family:Arial,sans-serif;background:#f5f7fa;margin:0;padding:20px}\n.container{max-width:600px;margin:0 auto;background:#fff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden}\n.header{background:linear-gradient(135deg,#3e7ac5 0%,#2a5a8a 100%);color:#fff;padding:24px;text-align:center}\n.header h1{margin:0;font-size:22px;font-weight:600}\n.header .badge{display:inline-block;background:rgba(255,255,255,0.2);padding:4px 12px;border-radius:20px;font-size:11px;margin-top:8px}\n.content{padding:24px}\n.field{margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid #eef1f5}\n.field:last-of-type{border-bottom:none;margin-bottom:0;padding-bottom:0}\n.field-label{display:block;font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;margin-bottom:4px;font-weight:600}\n.field-value{font-size:14px;color:#1f2937;word-break:break-word}\n.message-box{background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;padding:16px;margin-top:8px;white-space:pre-wrap}\n.footer{background:#f9fafb;padding:16px 24px;text-align:center;border-top:1px solid #eef1f5}\n.footer a{display:inline-block;background:#3e7ac5;color:#fff;padding:10px 20px;border-radius:6px;text-decoration:none;font-size:13px;font-weight:600}\n.footer a:hover{background:#2a5a8a}\n.footer p{margin:12px 0 0;font-size:11px;color:#9ca3af}\n.divider{height:1px;background:linear-gradient(90deg,transparent,#eef1f5,transparent);margin:16px 0}\n</style>\n</head>\n<body>\n<div class=\"container\">\n<div class=\"header\">\n<h1><i class=\"fas fa-envelope\"></i> New Contact Message</h1>\n<div class=\"badge\">KIMNEST CONTAINERS</div>\n</div>\n<div class=\"content\">\n<div class=\"field\"><span class=\"field-label\">From</span><span class=\"field-value\">{name}</span></div>\n<div class=\"field\"><span class=\"field-label\">Email</span><span class=\"field-value\"><a href=\"mailto:{email}\" style=\"color:#3e7ac5;text-decoration:none\">{email}</a></span></div>\n<div class=\"field\"><span class=\"field-label\">Phone</span><span class=\"field-value\">{phone}</span></div>\n<div class=\"field\"><span class=\"field-label\">Subject</span><span class=\"field-value\">{subject}</span></div>\n<div class=\"field\"><span class=\"field-label\">Message</span><div class=\"message-box\">{message}</div></div>\n</div>\n<div class=\"divider\"></div>\n<div class=\"footer\">\n<a href=\"{admin_url}\">View in Admin Panel</a>\n<p>This notification was sent automatically from the Kimnest Containers website.</p>\n</div>\n</div>\n</body>\n</html>', 'name, email, phone, subject, message, admin_url', NULL),
('quote_notification', 'Quote Request Submission', '📋 New Quote Request from {name}', '<!DOCTYPE html>\n<html>\n<head>\n<meta charset=\"UTF-8\">\n<style>\nbody{font-family:Arial,sans-serif;background:#f5f7fa;margin:0;padding:20px}\n.container{max-width:600px;margin:0 auto;background:#fff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden}\n.header{background:linear-gradient(135deg,#f5c140 0%,#d4a736 100%);color:#1a2e4a;padding:24px;text-align:center}\n.header h1{margin:0;font-size:22px;font-weight:600}\n.header .badge{display:inline-block;background:rgba(26,46,74,0.15);padding:4px 12px;border-radius:20px;font-size:11px;margin-top:8px}\n.content{padding:24px}\n.field{margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid #eef1f5}\n.field:last-of-type{border-bottom:none;margin-bottom:0;padding-bottom:0}\n.field-label{display:block;font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;margin-bottom:4px;font-weight:600}\n.field-value{font-size:14px;color:#1f2937;word-break:break-word}\n.field-value a{color:#3e7ac5;text-decoration:none}\n.message-box{background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;padding:16px;margin-top:8px;white-space:pre-wrap}\n.cart-section{background:#fffbeb;border:1px solid #fde68a;border-radius:6px;padding:16px;margin-top:8px}\n.cart-section h3{margin:0 0 12px;font-size:14px;color:#92400e}\n.cart-section ul{margin:0;padding-left:20px}\n.cart-section li{margin:6px 0;color:#1f2937}\n.footer{background:#f9fafb;padding:16px 24px;text-align:center;border-top:1px solid #eef1f5}\n.footer a{display:inline-block;background:#f5c140;color:#1a2e4a;padding:10px 20px;border-radius:6px;text-decoration:none;font-size:13px;font-weight:600}\n.footer a:hover{background:#d4a736}\n.footer p{margin:12px 0 0;font-size:11px;color:#9ca3af}\n.divider{height:1px;background:linear-gradient(90deg,transparent,#eef1f5,transparent);margin:16px 0}\n</style>\n</head>\n<body>\n<div class=\"container\">\n<div class=\"header\">\n<h1><i class=\"fas fa-file-invoice\"></i> New Quote Request</h1>\n<div class=\"badge\">KIMNEST CONTAINERS</div>\n</div>\n<div class=\"content\">\n<div class=\"field\"><span class=\"field-label\">From</span><span class=\"field-value\">{name}</span></div>\n<div class=\"field\"><span class=\"field-label\">Company</span><span class=\"field-value\">{company}</span></div>\n<div class=\"field\"><span class=\"field-label\">Email</span><span class=\"field-value\"><a href=\"mailto:{email}\">{email}</a></span></div>\n<div class=\"field\"><span class=\"field-label\">Phone</span><span class=\"field-value\">{phone}</span></div>\n<div class=\"field\"><span class=\"field-label\">Preferred Contact</span><span class=\"field-value\">{contact_method}</span></div>\n<div class=\"field\"><span class=\"field-label\">Project Type</span><span class=\"field-value\">{project_type}</span></div>\n<div class=\"field\"><span class=\"field-label\">Container Size</span><span class=\"field-value\">{container_size}</span></div>\n<div class=\"field\"><span class=\"field-label\">Quantity</span><span class=\"field-value\">{quantity}</span></div>\n<div class=\"field\"><span class=\"field-label\">Location</span><span class=\"field-value\">{location}</span></div>\n<div class=\"field\"><span class=\"field-label\">Intended Use</span><span class=\"field-value\">{intended_use}</span></div>\n<div class=\"field\"><span class=\"field-label\">Budget</span><span class=\"field-value\">{budget}</span></div>\n<div class=\"field\"><span class=\"field-label\">Completion Date</span><span class=\"field-value\">{completion_date}</span></div>\n<div class=\"field\"><span class=\"field-label\">Description</span><div class=\"message-box\">{description}</div></div>\n{cart_section}\n</div>\n<div class=\"divider\"></div>\n<div class=\"footer\">\n<a href=\"{admin_url}\">View in Admin Panel</a>\n<p>This notification was sent automatically from the Kimnest Containers website.</p>\n</div>\n</div>\n</body>\n</html>', 'name, company, email, phone, contact_method, project_type, container_size, quantity, location, intended_use, budget, completion_date, description, cart_section, admin_url', NULL);

-- Default Homepage Content
INSERT INTO site_content (page_slug, section_key, content_key, content_value, content_type, is_visible) VALUES
('home', 'hero', 'overline', 'Kenya''s #1 Container Company', 'text', 1),
('home', 'hero', 'heading', 'Transforming Containers Into Exceptional Spaces', 'text', 1),
('home', 'hero', 'description', 'We design, fabricate, and deliver custom container solutions — from homes and offices to shops, restaurants, and more.', 'textarea', 1),
('home', 'hero', 'bg_image', '', 'image', 1),
('home', 'hero', 'overlay_color', '#1a2e4a', 'text', 1),
('home', 'hero', 'btn1_text', 'View Products', 'text', 1),
('home', 'hero', 'btn1_link', '/products', 'text', 1),
('home', 'hero', 'btn2_text', 'Get a Quote', 'text', 1),
('home', 'hero', 'btn2_link', '/request-quote', 'text', 1),
('home', 'stats', 'stat1_number', '500+', 'text', 1),
('home', 'stats', 'stat1_label', 'Projects Completed', 'text', 1),
('home', 'stats', 'stat2_number', '10+', 'text', 1),
('home', 'stats', 'stat2_label', 'Years Experience', 'text', 1),
('home', 'stats', 'stat3_number', '100%', 'text', 1),
('home', 'stats', 'stat3_label', 'Customer Satisfaction', 'text', 1),
('home', 'stats', 'stat4_number', '24/7', 'text', 1),
('home', 'stats', 'stat4_label', 'Support Available', 'text', 1),
('home', 'about', 'overline', 'About Us', 'text', 1),
('home', 'about', 'heading', 'Kenya''s Leading Container Solutions Provider', 'text', 1),
('home', 'about', 'description', 'With over a decade of experience, Kimnest Containers has established itself as the premier provider of container sales, fabrication, and modification services in Kenya.', 'textarea', 1),
('home', 'about', 'btn_text', 'Learn More', 'text', 1),
('home', 'about', 'btn_link', '/about', 'text', 1),
('home', 'cta', 'heading', 'Ready to Start Your Container Project?', 'text', 1),
('home', 'cta', 'description', 'Get in touch with our team today for a free consultation and quote.', 'textarea', 1),
('home', 'cta', 'btn_text', 'Request a Quote', 'text', 1),
('home', 'cta', 'btn_link', '/request-quote', 'text', 1);

-- Default About Page Content
INSERT INTO site_content (page_slug, section_key, content_key, content_value, content_type, is_visible) VALUES
('about', 'hero', 'overline', 'About Us', 'text', 1),
('about', 'hero', 'description', 'Learn more about Kenya''s leading container solutions provider.', 'textarea', 1),
('about', 'hero', 'bg_image', '', 'image', 1),
('about', 'hero', 'overlay_color', '#1a2e4a', 'text', 1);

-- Default Services Page Content
INSERT INTO site_content (page_slug, section_key, content_key, content_value, content_type, is_visible) VALUES
('services', 'hero', 'overline', 'Our Services', 'text', 1),
('services', 'hero', 'description', 'We offer comprehensive container solutions that combine functionality, innovation, and exceptional craftsmanship.', 'textarea', 1),
('services', 'hero', 'bg_image', '', 'image', 1),
('services', 'hero', 'overlay_color', '#1a2e4a', 'text', 1);

-- Default Products Page Content
INSERT INTO site_content (page_slug, section_key, content_key, content_value, content_type, is_visible) VALUES
('products', 'hero', 'overline', 'Our Products', 'text', 1),
('products', 'hero', 'description', 'Explore our range of shipping containers available in various sizes and configurations.', 'textarea', 1),
('products', 'hero', 'bg_image', '', 'image', 1),
('products', 'hero', 'overlay_color', '#1a2e4a', 'text', 1);

-- Default Projects Page Content
INSERT INTO site_content (page_slug, section_key, content_key, content_value, content_type, is_visible) VALUES
('projects', 'hero', 'overline', 'Our Projects', 'text', 1),
('projects', 'hero', 'description', 'See our completed container fabrication and modification projects across Kenya.', 'textarea', 1),
('projects', 'hero', 'bg_image', '', 'image', 1),
('projects', 'hero', 'overlay_color', '#1a2e4a', 'text', 1);

-- Default Blog Page Content
INSERT INTO site_content (page_slug, section_key, content_key, content_value, content_type, is_visible) VALUES
('blog', 'hero', 'overline', 'Blog', 'text', 1),
('blog', 'hero', 'description', 'Insights, tips, and news from the container industry.', 'textarea', 1),
('blog', 'hero', 'bg_image', '', 'image', 1),
('blog', 'hero', 'overlay_color', '#1a2e4a', 'text', 1);

-- Default FAQ Page Content
INSERT INTO site_content (page_slug, section_key, content_key, content_value, content_type, is_visible) VALUES
('faq', 'hero', 'overline', 'FAQ', 'text', 1),
('faq', 'hero', 'description', 'Frequently asked questions about our container solutions.', 'textarea', 1),
('faq', 'hero', 'bg_image', '', 'image', 1),
('faq', 'hero', 'overlay_color', '#1a2e4a', 'text', 1);

-- Default Contact Page Content
INSERT INTO site_content (page_slug, section_key, content_key, content_value, content_type, is_visible) VALUES
('contact', 'hero', 'overline', 'Contact Us', 'text', 1),
('contact', 'hero', 'description', 'Get in touch with our team for inquiries and quotes.', 'textarea', 1),
('contact', 'hero', 'bg_image', '', 'image', 1),
('contact', 'hero', 'overlay_color', '#1a2e4a', 'text', 1);

-- Default Quote Page Content
INSERT INTO site_content (page_slug, section_key, content_key, content_value, content_type, is_visible) VALUES
('quote', 'hero', 'overline', 'Request a Quote', 'text', 1),
('quote', 'hero', 'description', 'Tell us about your project and we''ll provide a detailed quote.', 'textarea', 1),
('quote', 'hero', 'bg_image', '', 'image', 1),
('quote', 'hero', 'overlay_color', '#1a2e4a', 'text', 1);

-- Default Cart Page Content
INSERT INTO site_content (page_slug, section_key, content_key, content_value, content_type, is_visible) VALUES
('cart', 'hero', 'overline', 'Quotation Cart', 'text', 1),
('cart', 'hero', 'description', 'Review your selected products and submit for a quotation.', 'textarea', 1),
('cart', 'hero', 'bg_image', '', 'image', 1),
('cart', 'hero', 'overlay_color', '#1a2e4a', 'text', 1);

-- Seed Products
INSERT INTO products (name, slug, category, size, description, features, price_label, status, sort_order) VALUES
('20ft Standard Container', '20ft-standard', 'Standard', '20ft', 'A versatile 20ft shipping container suitable for storage, office conversion, or retail space.', 'Weatherproof steel construction\nSecure locking mechanism\nStandard ISO dimensions\nVentilation system', 'From KES 280,000', 'available', 1),
('40ft Standard Container', '40ft-standard', 'Standard', '40ft', 'Spacious 40ft container ideal for large storage needs or multi-room conversions.', 'Weatherproof steel construction\nSecure locking mechanism\nStandard ISO dimensions\nVentilation system', 'From KES 450,000', 'available', 2),
('20ft High Cube Container', '20ft-high-cube', 'High Cube', '20ft', 'Extra height 20ft container providing additional headroom for comfortable living or working spaces.', 'Extra 1ft height (9.6ft)\nWeatherproof steel\nReinforced flooring\nMultiple door options', 'From KES 350,000', 'available', 3),
('40ft High Cube Container', '40ft-high-cube', 'High Cube', '40ft', 'Maximum space 40ft high cube container perfect for large-scale projects.', 'Extra 1ft height (9.6ft)\nWeatherproof steel\nReinforced flooring\nMultiple door options', 'From KES 550,000', 'available', 4),
('20ft Office Container', '20ft-office', 'Modified', '20ft', 'Pre-fitted office container with electrical wiring, insulation, and finishings.', 'Pre-installed electrical system\nInsulated walls and ceiling\nAir conditioning provisions\nWindows and doors', 'From KES 650,000', 'available', 5),
('40ft Residential Container', '40ft-residential', 'Modified', '40ft', 'Turnkey container home with bathroom, kitchen, and living space.', 'Full electrical and plumbing\nInsulated construction\nBathroom and kitchen fixtures\nCustom layout options', 'From KES 1,200,000', 'available', 6),
('10ft Mini Container', '10ft-mini', 'Standard', '10ft', 'Compact container ideal for small storage or security cabin conversion.', 'Compact footprint\nWeatherproof steel\nSecure locking\nEasy to transport', 'From KES 150,000', 'available', 7),
('Container Restaurant', 'container-restaurant', 'Modified', '40ft', 'Custom-built container restaurant with serving counter, kitchen, and dining area.', 'Commercial kitchen fittings\nService counter\nCustomizable layout\nClimate control provisions', 'From KES 1,500,000', 'limited', 8);
