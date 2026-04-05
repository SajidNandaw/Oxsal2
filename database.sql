CREATE DATABASE IF NOT EXISTS nanz_store;
USE nanz_store;

-- ================= USERS =================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    alamat TEXT,
    role ENUM('admin','petugas','user') DEFAULT 'user',
    status ENUM('aktif','nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================= PRODUK =================
CREATE TABLE produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    stok INT DEFAULT 0,
    harga INT NOT NULL,
    gambar VARCHAR(255),
    terjual INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ulasan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    produk_id INT,
    rating INT, -- 1 sampai 5
    komentar TEXT,
    transaksi_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================= KERANJANG (BARU) =================
CREATE TABLE keranjang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    produk_id INT NOT NULL,
    jumlah INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY unique_cart (user_id, produk_id),

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
);

-- ================= TRANSAKSI =================
CREATE TABLE transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total INT DEFAULT 0,
    status ENUM('pending','dibayar','dikirim','selesai','dibatalkan') DEFAULT 'pending',

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ================= DETAIL TRANSAKSI =================
CREATE TABLE detail_transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaksi_id INT NOT NULL,
    produk_id INT NOT NULL,
    qty INT NOT NULL,
    subtotal INT NOT NULL,

    FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE CASCADE,
    FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
);

-- ================= BACKUP DATA =================
CREATE TABLE backup_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    original_id INT,
    table_name VARCHAR(50),
    data_backup LONGTEXT,
    deleted_by VARCHAR(100),
    deleted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    restored_at DATETIME NULL
);

-- ================= ADMIN DEFAULT =================
INSERT INTO users (name,email,password,role,status)
VALUES (
    'Administrator',
    'admin@gmail.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin',
    'aktif'
);