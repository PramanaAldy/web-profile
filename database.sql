-- ============================================
-- Web Profile - I Ketut Aldy Pramana Putra
-- Database Schema
-- ============================================



-- Admin users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Profile / Hero section
CREATE TABLE IF NOT EXISTS profile (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL DEFAULT 'I Ketut Aldy Pramana Putra',
    title VARCHAR(100) NOT NULL DEFAULT 'IT Support & Full Stack Developer',
    tagline VARCHAR(255) DEFAULT 'Building reliable digital solutions from Bali.',
    bio TEXT,
    location VARCHAR(100) DEFAULT 'Denpasar, Bali',
    email VARCHAR(100),
    phone VARCHAR(20),
    github_url VARCHAR(255),
    linkedin_url VARCHAR(255),
    instagram_url VARCHAR(255),
    profile_image VARCHAR(255) DEFAULT 'assets/images/profile.jpg',
    is_available TINYINT(1) DEFAULT 1,
    availability_text VARCHAR(100) DEFAULT 'Available for Projects',
    cv_title VARCHAR(100) DEFAULT 'I Ketut Aldy Pramana Putra - CV',
    years_experience INT DEFAULT 10,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Skills (grouped by category)
CREATE TABLE IF NOT EXISTS skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL,
    name VARCHAR(100) NOT NULL,
    icon VARCHAR(50),
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1
);

-- Work experience / timeline
CREATE TABLE IF NOT EXISTS experience (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL,
    location VARCHAR(100),
    start_date DATE NOT NULL,
    end_date DATE NULL COMMENT 'NULL = Present',
    description TEXT,
    achievements TEXT COMMENT 'JSON array of achievement strings',
    is_current TINYINT(1) DEFAULT 0,
    sort_order INT DEFAULT 0
);

-- Portfolio projects
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    tech_stack VARCHAR(255) COMMENT 'Comma-separated technologies',
    image VARCHAR(255),
    project_url VARCHAR(255),
    github_url VARCHAR(255),
    category VARCHAR(50) DEFAULT 'Web',
    is_featured TINYINT(1) DEFAULT 0,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Services offered
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1
);

-- Contact messages (from visitors)
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- SEED DATA
-- ============================================

-- Default profile
INSERT INTO profile (full_name, title, tagline, bio, location, email, years_experience) VALUES
(
    'I Ketut Aldy Pramana Putra',
    'IT Support & Full Stack Developer',
    'Turning ideas into reliable digital solutions — from server rooms to sleek web apps.',
    '10 tahun berkecimpung di dunia IT, dari troubleshooting infrastruktur hingga membangun aplikasi web full stack. Saya percaya bahwa teknologi yang baik adalah teknologi yang benar-benar memecahkan masalah nyata.',
    'Denpasar, Bali',
    'aldy@example.com',
    10
);

-- Default skills
INSERT INTO skills (category, name, icon, sort_order) VALUES
-- Frontend
('Frontend', 'HTML5 & CSS3', 'fab fa-html5', 1),
('Frontend', 'JavaScript', 'fab fa-js', 2),
('Frontend', 'Alpine.js', 'fas fa-mountain', 3),
('Frontend', 'Tailwind CSS', 'fas fa-wind', 4),
-- Backend
('Backend', 'PHP 8.x', 'fab fa-php', 1),
('Backend', 'MySQL / MariaDB', 'fas fa-database', 2),
('Backend', 'REST API', 'fas fa-code', 3),
('Backend', 'Laravel', 'fab fa-laravel', 4),
-- DevOps & IT
('IT & DevOps', 'Cloud & Web Server', 'fas fa-server', 1),
('IT & DevOps', 'cPanel / WHM', 'fas fa-server', 2),
('IT & DevOps', 'Network Troubleshooting', 'fas fa-network-wired', 3),
('IT & DevOps', 'Hardware & Maintenance', 'fas fa-tools', 4),
-- Tools
('Tools', 'Git & GitHub', 'fab fa-github', 1),
('Tools', 'VS Code', 'fas fa-code', 2),
('Tools', 'Laragon', 'fas fa-box', 3),
('Tools', 'Vibe Coding / AI Dev', 'fas fa-robot', 4);

-- Default services
INSERT INTO services (title, description, icon, sort_order) VALUES
('Full Stack Development', 'Membangun web aplikasi end-to-end: dari database, backend PHP, hingga tampilan frontend yang responsif.', 'fas fa-layer-group', 1),
('Web Maintenance', 'Perawatan dan update website yang sudah berjalan — bug fix, update konten, keamanan, dan performa.', 'fas fa-wrench', 2),
('IT Support', 'Troubleshooting hardware, software, jaringan, dan infrastruktur IT untuk bisnis maupun personal.', 'fas fa-headset', 3),
('Joki Project Website', 'Bantu penyelesaian project website, tugas kuliah, atau prototype dengan deadline ketat.', 'fas fa-bolt', 4),
('Vibe Coding', 'Kolaborasi pengembangan dengan AI-assisted workflow — cepat, efisien, dan hasil berkualitas tinggi.', 'fas fa-robot', 5);
