<?php
require_once 'includes/config.php';

// Admin details
$username   = "admin";
$email      = "admin@example.com";
$password   = "Admin@123";  // plain password
$full_name  = "System Administrator";
$phone      = "0712345678";

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insert query
$sql = "INSERT INTO users (username, email, password, role, full_name, phone, is_active) 
        VALUES (:username, :email, :password, 'admin', :full_name, :phone, 1)";

$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        ':username'  => $username,
        ':email'     => $email,
        ':password'  => $hashedPassword,
        ':full_name' => $full_name,
        ':phone'     => $phone
    ]);

    echo "✅ Admin user inserted successfully!";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
