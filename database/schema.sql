-- ShopEasy E-commerce Database Schema
CREATE DATABASE IF NOT EXISTS shopeasy;
USE shopeasy;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(50),
    state VARCHAR(50),
    zip_code VARCHAR(10),
    country VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    image VARCHAR(255),
    parent_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    short_description VARCHAR(500),
    price DECIMAL(10,2) NOT NULL,
    sale_price DECIMAL(10,2),
    sku VARCHAR(100) UNIQUE,
    stock_quantity INT DEFAULT 0,
    category_id INT,
    image VARCHAR(255),
    gallery TEXT, -- JSON array of image URLs
    featured BOOLEAN DEFAULT FALSE,
    status ENUM('active', 'inactive', 'draft') DEFAULT 'active',
    meta_title VARCHAR(255),
    meta_description TEXT,
    weight DECIMAL(8,2),
    dimensions VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Product attributes table
CREATE TABLE product_attributes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    attribute_name VARCHAR(100) NOT NULL,
    attribute_value VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    tax_amount DECIMAL(10,2) DEFAULT 0,
    shipping_amount DECIMAL(10,2) DEFAULT 0,
    discount_amount DECIMAL(10,2) DEFAULT 0,
    shipping_address TEXT NOT NULL,
    billing_address TEXT,
    payment_method VARCHAR(50) NOT NULL,
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Order items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Reviews table
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(255),
    comment TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Wishlist table
CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_wishlist (user_id, product_id)
);

-- Coupons table
CREATE TABLE coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    discount_type ENUM('percentage', 'fixed') NOT NULL,
    discount_value DECIMAL(10,2) NOT NULL,
    minimum_amount DECIMAL(10,2) DEFAULT 0,
    maximum_discount DECIMAL(10,2),
    usage_limit INT,
    used_count INT DEFAULT 0,
    valid_from TIMESTAMP,
    valid_until TIMESTAMP,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Newsletter subscribers table
CREATE TABLE newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    status ENUM('active', 'unsubscribed') DEFAULT 'active',
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact messages table
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample categories
INSERT INTO categories (name, slug, description) VALUES
('Electronics', 'electronics', 'Electronic devices and gadgets'),
('Clothing', 'clothing', 'Fashion and apparel'),
('Home & Garden', 'home', 'Home improvement and garden supplies'),
('Sports', 'sports', 'Sports equipment and accessories'),
('Books', 'books', 'Books and educational materials'),
('Toys', 'toys', 'Toys and games for all ages');

-- Insert sample products
INSERT INTO products (name, slug, description, short_description, price, sale_price, sku, stock_quantity, category_id, image, featured, status) VALUES
('iPhone 15 Pro', 'iphone-15-pro', 'The latest iPhone with advanced camera system and A17 Pro chip', 'Latest iPhone with Pro camera system', 999.00, 899.00, 'IPH15PRO', 50, 1, 'assets/images/products/iphone15pro.jpg', 1, 'active'),
('Samsung Galaxy S24', 'samsung-galaxy-s24', 'Premium Android smartphone with AI features', 'Premium Android smartphone', 799.00, NULL, 'SGS24', 30, 1, 'assets/images/products/galaxy-s24.jpg', 1, 'active'),
('MacBook Air M3', 'macbook-air-m3', 'Ultra-thin laptop with M3 chip for maximum performance', 'Ultra-thin laptop with M3 chip', 1199.00, NULL, 'MBA-M3', 25, 1, 'assets/images/products/macbook-air-m3.jpg', 1, 'active'),
('Nike Air Max 270', 'nike-air-max-270', 'Comfortable running shoes with Air Max technology', 'Comfortable running shoes', 150.00, 120.00, 'NAM270', 100, 2, 'assets/images/products/nike-air-max-270.jpg', 0, 'active'),
('Adidas Ultraboost 22', 'adidas-ultraboost-22', 'High-performance running shoes with Boost technology', 'High-performance running shoes', 180.00, NULL, 'AUB22', 75, 2, 'assets/images/products/adidas-ultraboost-22.jpg', 0, 'active'),
('Levi\'s 501 Jeans', 'levis-501-jeans', 'Classic straight-fit jeans in authentic denim', 'Classic straight-fit jeans', 89.00, 69.00, 'L501', 200, 2, 'assets/images/products/levis-501.jpg', 0, 'active'),
('Dyson V15 Detect', 'dyson-v15-detect', 'Cordless vacuum with laser dust detection', 'Cordless vacuum with laser detection', 649.00, NULL, 'DV15', 40, 3, 'assets/images/products/dyson-v15.jpg', 1, 'active'),
('KitchenAid Stand Mixer', 'kitchenaid-stand-mixer', 'Professional stand mixer in multiple colors', 'Professional stand mixer', 329.00, 279.00, 'KASM', 60, 3, 'assets/images/products/kitchenaid-mixer.jpg', 0, 'active'),
('Wilson Pro Staff Tennis Racket', 'wilson-pro-staff-racket', 'Professional tennis racket for advanced players', 'Professional tennis racket', 199.00, NULL, 'WPSR', 35, 4, 'assets/images/products/wilson-pro-staff.jpg', 0, 'active'),
('Yoga Mat Premium', 'yoga-mat-premium', 'Non-slip yoga mat with carrying strap', 'Non-slip yoga mat', 45.00, 35.00, 'YMP', 150, 4, 'assets/images/products/yoga-mat.jpg', 0, 'active');

-- Insert sample users (password is 'password123' hashed)
INSERT INTO users (username, email, password, first_name, last_name, phone, address, city, state, zip_code, country) VALUES
('admin', 'admin@shopeasy.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', '555-0100', '123 Admin St', 'New York', 'NY', '10001', 'USA'),
('john_doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John', 'Doe', '555-0101', '456 Main St', 'Los Angeles', 'CA', '90210', 'USA'),
('jane_smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane', 'Smith', '555-0102', '789 Oak Ave', 'Chicago', 'IL', '60601', 'USA');

-- Insert sample coupons
INSERT INTO coupons (code, description, discount_type, discount_value, minimum_amount, valid_from, valid_until, status) VALUES
('WELCOME10', 'Welcome discount for new customers', 'percentage', 10.00, 50.00, NOW(), DATE_ADD(NOW(), INTERVAL 1 YEAR), 'active'),
('SAVE50', 'Save $50 on orders over $200', 'fixed', 50.00, 200.00, NOW(), DATE_ADD(NOW(), INTERVAL 6 MONTH), 'active'),
('SUMMER20', 'Summer sale - 20% off', 'percentage', 20.00, 100.00, NOW(), DATE_ADD(NOW(), INTERVAL 3 MONTH), 'active');




