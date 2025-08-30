<?php
session_start();
require_once '../includes/config.php';

$error_message = '';

// Handle user resistration form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $phone = sanitize_input($_POST['phone']);
    $address = sanitize_input($_POST['address']) ?? Null; // Optional field

    if (empty($username) || empty($password) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($phone)) {
        $error_message = 'Please fill in all required fields.';
    } else {
        try {
            // Check if username or email already exists
            $stmt = $pdo->prepare("SELECT email, username FROM users WHERE (username = ? OR email = ?) AND is_active = 1");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();

            if ($user) {
                // Set session variables
                $error_message = 'Username or email already exists. Please choose another.';
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Insert new user into the database
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, phone, address, role, is_active, created_at) VALUES (?, ?, ?, ?, ?, 'client', 1, NOW())");
                $stmt->execute([$username, $email, $hashed_password, $phone, $address]);
                $user_id = $pdo->lastInsertId();

                // Set session variables
                $_SESSION['loged_in'] = true;
                $_SESSION['id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'client';      // Default role
                $_SESSION['success_message'] = 'Registration successful. Welcome, ' . htmlspecialchars($username) . '!';
                // redirection basing on role = client
                redirect('../client/dashboard.php');

            }
        } catch(PDOException $e) {
            $error_message = "Something went wrong. Please try again later or contact support.";
            echo "Error: " . $e->getMessage(); // For debugging purposes only
           
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

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    
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
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-user-plus fa-3x mb-3"></i>
                <h2>Register</h2>
                <p class="mb-0">b25studio</p>
            </div>

            <div class="login-body">
                <?php if ($error_message): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo $error_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="register.php">
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            <i class="fas fa-user me-2"></i>
                            Username
                        </label>
                        <input type="text" class="form-control" id="username" name="username" required
                            value="<?php echo isset($username) ? $username : ''; ?>"
                            placeholder="Enter your username">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>
                            Email
                        </label>
                        <input type="email" class="form-control" id="email" name="email" required
                            value="<?php echo isset($email) ? $email : ''; ?>"
                            placeholder="Enter your email address">
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">
                            <i class="fas fa-phone me-2"></i>
                            Phone
                        </label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            value="<?php echo isset($phone) ? $phone : ''; ?>"
                            placeholder="Enter your phone number">
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Physical Address
                        </label>
                        <textarea class="form-control" id="address" name="address" rows="2"
                                placeholder="Enter your address"><?php echo isset($address) ? $address : ''; ?></textarea>
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
                        <i class="fas fa-user-plus me-2"></i>
                        Register Account
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
                        Having trouble? Contact <a href="mailto:revocajana@gmail.com">revocajana@gmail.com</a>
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
