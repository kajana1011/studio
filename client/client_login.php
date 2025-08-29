<?php
session_start();
require_once 'includes/config.php';

// Redirect if already logged in
if (is_client_logged_in()) {
    redirect('client-gallery.php');
}

$error_message = '';
$success_message = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error_message = 'Please enter both email and password.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, full_name, email, password FROM clients WHERE email = ? AND is_active = 1");
            $stmt->execute([$email]);
            $client = $stmt->fetch();

            if ($client && password_verify($password, $client['password'])) {
                // Set session variables
                $_SESSION['client_logged_in'] = true;
                $_SESSION['client_id'] = $client['id'];
                $_SESSION['client_email'] = $client['email'];
                $_SESSION['client_name'] = $client['full_name'];

                redirect('client-gallery.php');
            } else {
                $error_message = 'Invalid email or password.';
            }
        } catch(PDOException $e) {
            $error_message = 'Login system temporarily unavailable. Please try again later.';
        }
    }
}

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $full_name = sanitize_input($_POST['full_name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($full_name) || empty($email) || empty($password)) {
        $error_message = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error_message = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirm_password) {
        $error_message = 'Passwords do not match.';
    } else {
        try {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM clients WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $error_message = 'An account with this email already exists.';
            } else {
                // Create new client account
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("
                    INSERT INTO clients (full_name, email, phone, password, created_at)
                    VALUES (?, ?, ?, ?, NOW())
                ");

                $stmt->execute([$full_name, $email, $phone, $hashed_password]);

                $success_message = 'Account created successfully! You can now login.';

                // Clear form data
                $full_name = $email = $phone = '';
            }
        } catch(PDOException $e) {
            $error_message = 'Registration failed. Please try again later.';
        }
    }
}

$page_title = 'Client Login';
include 'includes/header.php';
?>

<main>
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold mb-3">Client Area</h1>
                    <p class="lead">Access your personal gallery and downloads</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Login/Register Section -->
    <section class="client-auth py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <?php if ($success_message): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo $success_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($error_message): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo $error_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Auth Tabs -->
                    <div class="card shadow border-0">
                        <div class="card-header bg-light">
                            <ul class="nav nav-tabs card-header-tabs" id="authTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">
                                        <i class="fas fa-sign-in-alt me-2"></i>Login
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab">
                                        <i class="fas fa-user-plus me-2"></i>Create Account
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body p-4">
                            <div class="tab-content" id="authTabContent">

                                <!-- Login Tab -->
                                <div class="tab-pane fade show active" id="login" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <h3 class="fw-bold mb-4">Welcome Back!</h3>
                                            <form method="POST" action="client-login.php">
                                                <input type="hidden" name="login" value="1">

                                                <div class="mb-3">
                                                    <label for="login_email" class="form-label">Email Address</label>
                                                    <input type="email" class="form-control" id="login_email" name="email" required
                                                           placeholder="Enter your email address">
                                                </div>

                                                <div class="mb-4">
                                                    <label for="login_password" class="form-label">Password</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" id="login_password" name="password" required
                                                               placeholder="Enter your password">
                                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('login_password', this)">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                                    <i class="fas fa-sign-in-alt me-2"></i>
                                                    Login to Gallery
                                                </button>
                                            </form>

                                            <div class="text-center">
                                                <small class="text-muted">
                                                    Don't have an account?
                                                    <a href="#register" class="text-primary" onclick="document.getElementById('register-tab').click()">Create one here</a>
                                                </small>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="login-info bg-light p-4 rounded">
                                                <h5 class="fw-bold mb-3">Access Your Gallery</h5>
                                                <ul class="list-unstyled">
                                                    <li class="mb-2">
                                                        <i class="fas fa-check text-success me-2"></i>
                                                        View your photos and videos
                                                    </li>
                                                    <li class="mb-2">
                                                        <i class="fas fa-check text-success me-2"></i>
                                                        Download high-resolution files
                                                    </li>
                                                    <li class="mb-2">
                                                        <i class="fas fa-check text-success me-2"></i>
                                                        Share with family and friends
                                                    </li>
                                                    <li class="mb-2">
                                                        <i class="fas fa-check text-success me-2"></i>
                                                        Access anytime, anywhere
                                                    </li>
                                                </ul>

                                                <div class="mt-4">
                                                    <h6 class="fw-bold">Need Help?</h6>
                                                    <p class="small text-muted mb-2">Contact us if you need assistance accessing your gallery:</p>
                                                    <p class="small">
                                                        <i class="fas fa-envelope me-2"></i>info@studiomediatz.com<br>
                                                        <i class="fas fa-phone me-2"></i>+255 XXX XXX XXX
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Register Tab -->
                                <div class="tab-pane fade" id="register" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-8 mx-auto">
                                            <h3 class="fw-bold mb-4 text-center">Create Your Account</h3>
                                            <form method="POST" action="client-login.php">
                                                <input type="hidden" name="register" value="1">

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="full_name" name="full_name" required
                                                               value="<?php echo isset($full_name) ? $full_name : ''; ?>"
                                                               placeholder="Enter your full name">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="phone" class="form-label">Phone Number</label>
                                                        <input type="tel" class="form-control" id="phone" name="phone"
                                                               value="<?php echo isset($phone) ? $phone : ''; ?>"
                                                               placeholder="+255 XXX XXX XXX">
                                                    </div>

                                                    <div class="col-12">
                                                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                                        <input type="email" class="form-control" id="email" name="email" required
                                                               value="<?php echo isset($email) ? $email : ''; ?>"
                                                               placeholder="Enter your email address">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <input type="password" class="form-control" id="password" name="password" required
                                                                   placeholder="Create a password">
                                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', this)">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </div>
                                                        <small class="text-muted">Minimum 6 characters</small>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required
                                                               placeholder="Confirm your password">
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="terms" required>
                                                            <label class="form-check-label" for="terms">
                                                                I agree to the <a href="#" class="text-primary">Terms of Service</a> and <a href="#" class="text-primary">Privacy Policy</a>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-primary btn-lg w-100">
                                                            <i class="fas fa-user-plus me-2"></i>
                                                            Create Account
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>

                                            <div class="text-center mt-3">
                                                <small class="text-muted">
                                                    Already have an account?
                                                    <a href="#login" class="text-primary" onclick="document.getElementById('login-tab').click()">Login here</a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Access Section -->
    <section class="gallery-access py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold">Quick Gallery Access</h2>
                    <p class="text-muted">Have an access code? Enter it below to view your gallery</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card border-0 shadow">
                        <div class="card-body p-4">
                            <form action="client-gallery.php" method="GET">
                                <div class="mb-3">
                                    <label for="access_code" class="form-label">Gallery Access Code</label>
                                    <input type="text" class="form-control text-center" id="access_code" name="code"
                                           placeholder="Enter your access code" style="letter-spacing: 2px; font-size: 1.2rem;">
                                </div>

                                <button type="submit" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-key me-2"></i>
                                    Access Gallery
                                </button>
                            </form>

                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    Access codes are provided after your photo session is complete
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

<?php include 'includes/footer.php'; ?>
