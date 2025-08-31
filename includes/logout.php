<?php
session_start();
require_once 'config.php';

// Determine redirect based on user type
$redirect_url = '../index.php'; // Default redirect

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
    // Log admin logout activity
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' && isset($_SESSION['id'])) {
        try {
            $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, ip_address, user_agent, created_at) VALUES (?, 'logout', ?, ?, NOW())");
            $stmt->execute([$_SESSION['id'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']]);
        } catch(PDOException $e) {
            // Log error if needed, but continue with logout
        }
    }
    $redirect_url = '../index.php';
} else {
    $redirect_url = '../index.php';
}

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Clear session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect to appropriate page
header("Location: $redirect_url");
exit();
?>
