<?php
session_start();
require_once '../includes/config.php';

// Redirect if already logged in
if (is_admin_logged_in()) {
    redirect('../admin/dashboard.php');
}

$error_message = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error_message = 'Please enter both username and password.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, email, password, role, username FROM users WHERE (username = ? OR email = ?) AND is_active = 1");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['email'] = $user['email'];

                // Log activity
                $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, ip_address, user_agent, created_at) VALUES (?, 'login', ?, ?, NOW())");
                $stmt->execute([$user['id'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']]);

                // Redirect to dashboard based on role
                if($user['role'] == 'admin') {
                    $_SESSION['is_admin'] = true;
                    redirect('../admin/dashboard.php');
                } elseif($user['role'] == 'client') {
                    $_SESSION['is_client'] = true;
                    redirect('../client/dashboard.php');
                } elseif($user['role'] == 'staff') {
                    $_SESSION['is_staff'] = true;
                    redirect('../staff/dashboard.php');
                }
                
            } else {
                $error_message = 'Invalid username or password.';
            }
        } catch(PDOException $e) {
            $error_message = 'Login system temporarily unavailable. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Studio Media TZ</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #007bff, #0056b3);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }

        .login-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            text-align: center;
            padding: 2rem;
        }

        .login-header h2 {
            margin: 0;
            font-weight: 700;
        }

        .login-body {
            padding: 2rem;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }

        .btn-login {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            width: 100%;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #0056b3, #004494);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,123,255,0.3);
        }

        .back-link {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card mb-5 mt-5">
            <div class="login-header">
                <i class="fas fa-user-shield fa-3x"></i>
                <h2>Login</h2>
            </div>

            <div class="login-body">
                <?php if ($error_message): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo $error_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="login.php">
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            <i class="fas fa-user me-2"></i>
                            Username or Email
                        </label>
                        <input type="text" class="form-control" id="username" name="username" required
                               value="<?php echo isset($username) ? $username : ''; ?>"
                               placeholder="Enter your username or email">
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>
                            Password
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required
                                   placeholder="Enter your password">
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Login to Dashboard
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="../index.php" class="back-link">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Website
                    </a>
                </div>

                <div class="text-center mt-3">
                    <small class="text-muted">
                        Having trouble?  <a href="../contact">Contact us</a>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        // Focus on username field
        document.getElementById('username').focus();

        // Form submission loading state
        document.querySelector('form').addEventListener('submit', function() {
            const submitBtn = document.querySelector('.btn-login');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Logging in...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>
