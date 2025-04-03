<?php
// Konfigurasi database
$host = 'localhost'; // Host MySQL (biasanya localhost)
$dbname = 'zym'; // Nama database
$username = 'root'; // Username default di XAMPP
$password = ''; // Password kosong untuk default XAMPP

try {
    // Membuat koneksi ke database menggunakan PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode
} catch (PDOException $e) {
    // Menangani kesalahan koneksi database
    die("Connection failed: " . $e->getMessage());
}

// Fungsi untuk mendaftar (sign up)
function signUp($email, $password) {
    global $pdo;
    
    // Hash password menggunakan bcrypt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    // Query untuk memasukkan data ke dalam tabel users
    $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
    $stmt = $pdo->prepare($sql);
    
    // Binding parameter
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    
    // Menjalankan query
    try {
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Fungsi untuk login (sign in)
function signIn($email, $password) {
    global $pdo;
    
    // Query untuk mendapatkan data user berdasarkan email
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    // Ambil data user
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Verifikasi password menggunakan password_verify
    if ($user && password_verify($password, $user['password'])) {
        return true; // Login berhasil
    } else {
        return false; // Login gagal
    }
}
?>
