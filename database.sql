-- KimNest Containers Database Schema

CREATE DATABASE IF NOT EXISTS kimnest_db;
USE kimnest_db;

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

-- Newsletter Subscribers
CREATE TABLE newsletter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) NOT NULL UNIQUE,
    status ENUM('active', 'unsubscribed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Insert default admin user
INSERT INTO admin_users (username, password, full_name, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@kimnestcontainers.co.ke');

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
