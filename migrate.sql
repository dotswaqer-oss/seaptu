-- SQL untuk membuat database dan tabel yang dibutuhkan
CREATE DATABASE IF NOT EXISTS toko_sepatu CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE toko_sepatu;

-- tabel customers
CREATE TABLE IF NOT EXISTS customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(50),
  address TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- tabel products
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  description TEXT,
  price DECIMAL(12,2) NOT NULL DEFAULT 0,
  stock INT NOT NULL DEFAULT 0,
  image VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- tabel orders
CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  total DECIMAL(12,2) NOT NULL,
  status ENUM('pending','paid','validated','shipped','cancelled') NOT NULL DEFAULT 'pending',
  payment_method VARCHAR(100),
  payment_ref VARCHAR(200),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (customer_id) REFERENCES customers(id)
);

-- tabel order_items
CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  price DECIMAL(12,2) NOT NULL,
  quantity INT NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id)
);

-- contoh produk
INSERT INTO products (name, description, price, stock, image) VALUES
('Ventela Public Cream','Sepatu Ventela model public cream',300000,10,'images/ventela_public_low_cream.jpeg'),
('Patrobas Equib High Maroon','Patrobas high maroon',289900,5,'images/patrobas equib high maroon.jpeg'),
('Warrior Rainbow','Warrior rainbow',239900,8,'images/warrior rainbow.jpeg');

-- tabel admins untuk autentikasi admin
CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  name VARCHAR(150),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
