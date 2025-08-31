<?php
session_start();
require_once '../helpers/functions.php';

// Check if admin is logged in
if (!is_admin_logged_in()) {
    redirect('login.php');
}

$services = getServices();
$packages = getPackages();
$budgets = getBudgets();
$pcategories = getPortfolioCategories();
$portfolio = getPortfolios();


// Handle quick actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_booking_status':
                $booking_id = (int)$_POST['booking_id'];
                $status = sanitize_input($_POST['status']);

                try {
                    $stmt = $pdo->prepare("UPDATE bookings SET status = ?, updated_at = NOW() WHERE id = ?");
                    $stmt->execute([$status, $booking_id]);

                    // Log activity
                    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, table_name, record_id, created_at) VALUES (?, 'update_booking_status', 'bookings', ?, NOW())");
                    $stmt->execute([$_SESSION['id'], $booking_id]);

                    $success_message = 'Booking status updated successfully!';
                } catch(PDOException $e) {
                    $error_message = 'Failed to update booking status.';
                }
                break;

            case 'approve_testimonial':
                $testimonial_id = (int)$_POST['testimonial_id'];

                try {
                    $stmt = $pdo->prepare("UPDATE testimonials SET is_approved = 1, updated_at = NOW() WHERE id = ?");
                    $stmt->execute([$testimonial_id]);

                    $success_message = 'Testimonial approved successfully!';
                } catch(PDOException $e) {
                    $error_message = 'Failed to approve testimonial.';
                }
                break;

            case 'submit_booking_request':
                $success_message = [];
                $error_message = [];

                $name = sanitize_input($_POST['name']);
                $email = sanitize_input($_POST['email']);
                $phone = sanitize_input($_POST['phone']);
                $service = sanitize_input($_POST['service']);
                $event_date = sanitize_input($_POST['event_date']);
                $event_time = sanitize_input($_POST['event_time']);
                $location = sanitize_input($_POST['location']);    
                $message = sanitize_input($_POST['message']);
                $package = !empty($_POST['package']) ? $_POST['package'] : null;
                $budget = !empty($_POST['budget']) ? $_POST['budget'] : null;

                // If a package is selected, ignore the budget
                if (!empty($package)) {
                    $budget = null; // send NULL to the DB
                } else {
                    // User did not select a package, so budget is optional
                    if ($budget === '') {
                        $budget = null;
                    }
                }
                                                                                                         
                // Validation
                if (empty($name) || empty($email) || empty($phone) || empty($service) || empty($event_date)) {
                    $error_messages = [];

                    if (empty($name)) $error_messages[] = "Name is required.";
                    if (empty($email)) $error_messages[] = "Email is required.";
                    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error_messages[] = "Invalid email format.";
                    if (empty($phone)) $error_messages[] = "Phone is required.";
                    if (empty($service)) $error_messages[] = "Service is required.";
                    if (empty($event_date)) $error_messages[] = "Event date is required.";
                    $error_message = implode(' ', $error_messages);

                    $_SESSION['error_message'] = $error_message;

                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error_message = 'Please enter a valid email address.';
                } else {
                    try {
                        // Insert booking into database
                        $stmt = $pdo->prepare("
                            INSERT INTO bookings (name, email, phone, service_id, event_date, event_time, location, package_id, budget_id, message, created_at)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
                        ");

                        $stmt->execute([$name, $email, $phone, $service, $event_date, $event_time, $location, $package, $budget, $message]);

                        // Send email notification
                        // $email_subject = "New Booking Request - b25studio";
                        // $email_body = "
                        //     <h2>New Booking Request</h2>
                        //     <p><strong>Name:</strong> $name</p>
                        //     <p><strong>Email:</strong> $email</p>
                        //     <p><strong>Phone:</strong> $phone</p>
                        //     <p><strong>Service:</strong> $service</p>
                        //     <p><strong>Event Date:</strong> $event_date</p>
                        //     <p><strong>Event Time:</strong> $event_time</p>
                        //     <p><strong>Location:</strong> $location</p>
                        //     <p><strong>Package:</strong> $package</p>
                        //     <p><strong>Budget:</strong> $budget</p>
                        //     <p><strong>Message:</strong> $message</p>
                        // ";

                        // send_email(ADMIN_EMAIL, $email_subject, $email_body);

                        $success_message = 'New booking request has been submitted successfully';

                        // Clear form data
                        $name = $email = $phone = $service = $event_date = $event_time = $location = $package = $budget = $message = '';

                        $_SESSION['success_message'] = $success_message;
                        header('Location: dashboard.php');
                        exit;


                    } catch(PDOException $e) {
                        $error_message = 'Sorry, there was an error processing your request. Please try again later.: ' . $e->getMessage();
                    }
                }
                break;

            case 'submit_portfolio':

                $title = $_POST['title'];
                $description = $_POST['description'];
                $category = $_POST['category'];
                $media_type = $_POST['media_type'];

                // Handle file upload
                $file_path = '';
                $thumbnail_path = '';
                if ($media_type === 'image' && isset($_FILES['file']['tmp_name'])) {
                    $file_path = 'uploads/' . basename($_FILES['file']['name']);
                    move_uploaded_file($_FILES['file']['tmp_name'], '../' . $file_path);
                    $thumbnail_path = $file_path; // optional thumbnail handling
                } elseif ($media_type === 'video' && isset($_FILES['file']['tmp_name'])) {
                    $file_path = 'uploads/' . basename($_FILES['file']['name']);
                    move_uploaded_file($_FILES['file']['tmp_name'], '../' . $file_path);
                }

                addPortfolioItem($title, $description, $category, $media_type, $file_path, $thumbnail_path);
                redirect('dashboard.php#portfolio'); // refresh page to show new item

                break;
    
        }
    }
}

// Get dashboard statistics
try {
    // Total bookings
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM bookings");
    $total_bookings = $stmt->fetch()['total'];
    if (!$total_bookings) $total_bookings = 0;
    // Pending bookings
    $stmt = $pdo->query("SELECT COUNT(*) as pending FROM bookings WHERE status = 'pending'");
    $pending_bookings = $stmt->fetch()['pending'];
    if (!$pending_bookings) $pending_bookings = 0;

    // Total clients
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'client' and is_active = 1");
    $total_clients = $stmt->fetch()['total'];
    if (!$total_clients) $total_clients = 0;

    // Total portfolio items
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM portfolio WHERE is_active = 1");
    $total_portfolio = $stmt->fetch()['total'];
    if (!$total_portfolio) $total_portfolio = 0;

    // Recent bookings
    $stmt = $pdo->query("SELECT b.*, s.name as service  FROM bookings b JOIN services s on b.service_id = s.id ORDER BY created_at DESC LIMIT 5");
    $recent_bookings = $stmt->fetchAll();
    if (!$recent_bookings) $recent_bookings = [];

    // Pending testimonials
   try {
        $stmt = $pdo->query("SELECT * FROM testimonials WHERE is_approved = 0 ORDER BY created_at DESC LIMIT 5");
        $pending_testimonials = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    } catch (PDOException $e) {
        $pending_testimonials = [];
    }


    // Recent contacts
    $stmt = $pdo->query("SELECT * FROM contact_inquiries WHERE status = 'new' ORDER BY created_at DESC LIMIT 5");
    $recent_contacts = $stmt->fetchAll();
    if (!$recent_contacts) $recent_contacts = [];

    // Monthly booking stats
    $stmt = $pdo->query("
        SELECT MONTH(created_at) as month, COUNT(*) as count
        FROM bookings
        WHERE YEAR(created_at) = YEAR(CURDATE())
        GROUP BY MONTH(created_at)
        ORDER BY month
    ");
    $monthly_bookings = $stmt->fetchAll();
    $monthly_data = array_fill(1, 12, 0); // Initialize all months to 0
    foreach ($monthly_bookings as $row) {
        $monthly_data[(int)$row['month']] = (int)$row['count'];
    }

} catch(PDOException $e) {
    $error_message = 'Unable to load dashboard data.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - b25studio</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #343a40, #495057);
        }

        .sidebar .nav-link {
            color: #adb5bd;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }

        .stat-card {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .stat-card.success {
            background: linear-gradient(135deg, #28a745, #1e7e34);
        }

        .stat-card.warning {
            background: linear-gradient(135deg, #ffc107, #e0a800);
        }

        .stat-card.info {
            background: linear-gradient(135deg, #17a2b8, #138496);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: #495057;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white fw-bold">
                            <i class="fas fa-camera me-2"></i>
                            b25studio
                        </h4>
                        <small class="text-danger">Admin Panel</small>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#dashboard" onclick="showSection('dashboard')">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#bookings" onclick="showSection('bookings')">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Bookings
                                <?php if ($pending_bookings > 0): ?>
                                    <span class="badge bg-warning ms-2"><?php echo $pending_bookings; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#portfolio" onclick="showSection('portfolio')">
                                <i class="fas fa-images me-2"></i>
                                Portfolio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#clients" onclick="showSection('clients')">
                                <i class="fas fa-users me-2"></i>
                                Clients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#testimonials" onclick="showSection('testimonials')">
                                <i class="fas fa-star me-2"></i>
                                Testimonials
                                <?php if (isset($pending_testimonials) && count($pending_testimonials) > 0): ?>
                                    <span class="badge bg-info ms-2"><?php echo count($pending_testimonials); ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contacts" onclick="showSection('contacts')">
                                <i class="fas fa-envelope me-2"></i>
                                Messages
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#settings" onclick="showSection('settings')">
                                <i class="fas fa-cog me-2"></i>
                                Settings
                            </a>
                        </li>
                    </ul>

                    <hr class="text-white my-4">

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="../index.php" target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i>
                                View Website
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../includes/logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">

                <!-- Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Welcome, <?php echo $_SESSION['username']; ?>!</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-calendar me-1"></i>
                                <?php echo date('M j, Y'); ?>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Dashboard Section -->
                <div id="dashboard-section" class="admin-section">
                    <h2>Dashboard</h2>
                    <p class="text-muted">Overview of recent activity and statistics</p>
                    
                    <?php if($_SESSION['success_message'] ?? false): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php 
                                echo $_SESSION['success_message'];                    
                                unset($_SESSION['success_message']);                                
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <?php if($_SESSION['error_message'] ?? false): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php 
                                echo $_SESSION['error_message'];
                                unset($_SESSION['error_message']);                                
                            ?>
                            <a href="#bookings" onclick="showSection('bookings')">Try again</a>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <!-- Statistics Cards -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="fw-bold"><?php echo $total_bookings; ?></h3>
                                        <p class="mb-0">Total Bookings</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card warning">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="fw-bold"><?php echo $pending_bookings; ?></h3>
                                        <p class="mb-0">Pending Bookings</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-clock fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card success">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="fw-bold"><?php echo $total_clients; ?></h3>
                                        <p class="mb-0">Active Clients</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-users fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card info">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="fw-bold"><?php echo $total_portfolio; ?></h3>
                                        <p class="mb-0">Portfolio Items</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-images fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts and Recent Activity -->
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-chart-line me-2"></i>
                                        Monthly Bookings
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="bookingsChart" height="100"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-tasks me-2"></i>
                                        Quick Actions
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary" onclick="showSection('bookings')">
                                            <i class="fas fa-plus me-2"></i>
                                            View New Bookings
                                        </button>
                                        <button class="btn btn-success" onclick="showSection('portfolio')">
                                            <i class="fas fa-upload me-2"></i>
                                            Add Portfolio Item
                                        </button>
                                        <button class="btn btn-info" onclick="showSection('testimonials')">
                                            <i class="fas fa-star me-2"></i>
                                            Review Testimonials
                                        </button>
                                        <button class="btn btn-outline-primary" onclick="showSection('contacts')">
                                            <i class="fas fa-envelope me-2"></i>
                                            Check Messages
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="row mt-4">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-clock me-2"></i>
                                        Recent Bookings
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($recent_bookings)): ?>
                                        <p class="text-muted text-center">No recent bookings</p>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Client</th>
                                                        <th>Service</th>
                                                        <th>Date</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($recent_bookings as $booking): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($booking['name']); ?></td>
                                                            <td><?php echo htmlspecialchars($booking['service']); ?></td>
                                                            <td><?php echo date('M j', strtotime($booking['event_date'])); ?></td>
                                                            <td>
                                                                <?php
                                                                $status_colors = [
                                                                    'pending' => 'warning',
                                                                    'confirmed' => 'success',
                                                                    'completed' => 'info',
                                                                    'cancelled' => 'danger'
                                                                ];
                                                                $color = $status_colors[$booking['status']] ?? 'secondary';
                                                                ?>
                                                                <span class="badge bg-<?php echo $color; ?>"><?php echo ucfirst($booking['status']); ?></span>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-star me-2 text-warning"></i>
                                        Pending Testimonials
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($pending_testimonials)): ?>
                                        <p class="text-muted text-center">No pending testimonials</p>
                                    <?php else: ?>
                                        <?php foreach ($pending_testimonials as $testimonial): ?>
                                            <div class="border-bottom pb-2 mb-2">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-1"><?php echo htmlspecialchars($testimonial['client_name']); ?></h6>
                                                        <p class="small text-muted mb-1"><?php echo substr(htmlspecialchars($testimonial['testimonial']), 0, 80) . '...'; ?></p>
                                                        <div class="rating">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <i class="fas fa-star <?php echo $i <= $testimonial['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                                            <?php endfor; ?>
                                                        </div>
                                                    </div>
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="action" value="approve_testimonial">
                                                        <input type="hidden" name="testimonial_id" value="<?php echo $testimonial['id']; ?>">
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Other sections will be loaded via AJAX or shown/hidden -->
                <div id="bookings-section" class="admin-section" style="display:none;">
                    <h2>Bookings Management</h2>
                    <p class="text-muted">Manage client bookings and appointments</p>

                    <!-- Add New Booking Form -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Add New Booking</h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" novalidate>
                                <div class="row g-3">

                                    <input type='hidden' name='action' value='submit_booking_request'>

                                    <!-- Personal Information -->
                                    <div class="col-12">
                                        <h5 class="fw-bold text-dark mb-3">Personal Information</h5>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" required value="<?php echo isset($name) ? $name : ''; ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" required value="<?php echo isset($email) ? $email : ''; ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" id="phone" name="phone" required value="<?php echo isset($phone) ? $phone : ''; ?>" placeholder="+255 XXX XXX XXX">
                                    </div>

                                    <!-- Service Details -->
                                    <div class="col-12 mt-4">
                                        <h5 class="fw-bold text-dark mb-3">Service Details</h5>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="service" class="form-label">Service Type <span class="text-danger">*</span></label>
                                        <select class="form-select" id="service" name="service" required>
                                            <option value="">Select a service</option>
                                            <?php foreach ($services as $srv): ?>
                                                <option value="<?php echo htmlspecialchars($srv['id']); ?>" <?php echo (isset($service) && $service == $srv['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($srv['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="package" class="form-label">Package</label>
                                        <select class="form-select " id="package" name="package">
                                            <option value="">Select a package (optional)</option>
                                            <?php foreach ($packages as $pkg): ?>
                                                <option value="<?php echo htmlspecialchars($pkg['id']); ?>" <?php echo (isset($package) && $package == $pkg['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($pkg['name'] . ' - TSh ' . number_format($pkg['price'])); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="event_date" class="form-label">Event Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="event_date" name="event_date" required value="<?php echo isset($event_date) ? $event_date : ''; ?>" min="<?php echo date('Y-m-d'); ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="event_time" class="form-label">Event Time</label>
                                        <input type="time" class="form-control" id="event_time" name="event_time" value="<?php echo isset($event_time) ? $event_time : ''; ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="location" class="form-label">Event Location</label>
                                        <input type="text" class="form-control" id="location" name="location" value="<?php echo isset($location) ? $location : ''; ?>" placeholder="e.g., Mazenze Dar-es-Salaam">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="budget" class="form-label">Budget Range</label>
                                        <select class="form-select" id="budget" name="budget">
                                            <option value="">Select budget range</option>
                                            <?php foreach ($budgets as $b): ?>
                                                <option value="<?php echo htmlspecialchars($b['id']); ?>" <?php echo (isset($budget) && $budget == $b['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($b['label']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="message" class="form-label">Additional Details</label>
                                        <textarea class="form-control" id="message" name="message" rows="4" placeholder="Tell us more about your event, special requirements, or any questions you have..."><?php echo isset($message) ? $message : ''; ?></textarea>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg px-4">
                                            <i class="fas fa-paper-plane me-2"></i>
                                            Submit Booking Request
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Table of All Bookings -->
                    <div class="card">
                        <div class="card-header">
                            <h5>All Bookings</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Client</th>
                                            <th>Phone</th>
                                            <th>Service</th>
                                            <th> Date of event</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        try {
                                            $stmt = $pdo->query("SELECT b.*, s.name as service FROM bookings b JOIN services s ON b.service_id = s.id ORDER BY event_date ASC");
                                            $all_bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        } catch(PDOException $e) {
                                            $all_bookings = [];
                                        }

                                        if(empty($all_bookings)) {
                                            echo '<tr><td colspan="5" class="text-center">No bookings found</td></tr>';
                                        } else {
                                            foreach($all_bookings as $booking) {
                                                $status_colors = [
                                                    'pending' => 'warning',
                                                    'confirmed' => 'success',
                                                    'completed' => 'info',
                                                    'cancelled' => 'danger'
                                                ];
                                                $color = $status_colors[$booking['status']] ?? 'secondary';
                                                echo "<tr>
                                                    <td>".htmlspecialchars($booking['name'])."</td>
                                                    <td>".htmlspecialchars($booking['phone'])."</td>
                                                    <td>".htmlspecialchars($booking['service'])."</td>
                                                    <td>".date('M j, Y', strtotime($booking['event_date']))."</td>
                                                    <td><span class='badge bg-$color'>".ucfirst($booking['status'])."</span></td>
                                                    <td>
                                                        <form method='POST' style='display:inline'>
                                                            <input type='hidden' name='action' value='update_booking_status'>
                                                            <input type='hidden' name='booking_id' value='".$booking['id']."'>
                                                            <select name='status' class='form-select form-select-sm d-inline w-auto'>
                                                                <option value='pending'>Pending</option>
                                                                <option value='confirmed'>Confirmed</option>
                                                                <option value='completed'>Completed</option>
                                                                <option value='cancelled'>Cancelled</option>
                                                            </select>
                                                            <button type='submit' class='btn btn-sm btn-primary ms-1'>Update</button>
                                                        </form>
                                                    </td>
                                                </tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Client sections... -->
                 <div id="clients-section" class="admin-section" style="display: none;">
                    <h2>Clients Management</h2>
                    <p class="text-muted">Manage clients</p>

                    <!-- Clients management content -->
                </div>

                <!-- Portfolio section -->
                <div id="portfolio-section" class="admin-section" style="display: none;">
                    <h2>Portfolio Management</h2>
                    <p class="text-muted">Manage portfolio items</p>

                    <!-- Portfolio management content will be added here -->

                    <!-- Add Portfolio Form -->
                    <div class="card mb-4">
                        <div class="card-header">Add New Portfolio Item</div>
                        <div class="card-body">
                            <form id="portfolioForm" enctype="multipart/form-data">
                                <input type='hidden' name='action' value='submit_portfolio'>
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" id="title" name="title" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select id="category" name="category" class="form-control" required>
                                        <option value="">Select a category</option>
                                        <?php foreach($pcategories as $ctgry): ?>
                                            <option value="<?php echo htmlspecialchars($ctgry['name']); ?>" <?php echo (isset($category) && $category == $ctgry['name']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($ctgry['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="media_type" class="form-label">Media Type</label>
                                    <select id="media_type" name="media_type" class="form-control" required>
                                        <option value="image">Image</option>
                                        <option value="video">Video</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="file" class="form-label">Upload File</label>
                                    <input type="file" id="file" name="file" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Portfolio</button>
                            </form>
                        </div>
                    </div>

                    <!-- Existing Portfolio Table -->
                    <div class="card">
                        <div class="card-header">Existing Portfolio Items</div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Type</th>
                                        <th>Media</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="portfolioTableBody">
                                    <!-- PHP loop or JS can populate rows here -->
                                    <!-- Example row: -->
                                    <?php foreach($portfolio as $ptf): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($ptf['title']); ?></td>
                                        <td><?php echo htmlspecialchars($ptf['category']); ?></td>
                                        <td><?php echo htmlspecialchars($ptf['media_type']); ?></td>
                                        <td><a href="../<?php echo htmlspecialchars($ptf['file_path']); ?>"><img src="../<?php echo htmlspecialchars($ptf['file_path']); ?>" width="100" height="100"></a></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning">Edit</button>
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td>Weddg Shoot</td>
                                        <td>wedding</td>
                                        <td>image</td>
                                        <td><img src="uploads/wedding1.jpg" width="100"></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning">Edit</button>
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </td>
                                    </tr>
                                   
                                </tbody>
                            </table>
                        </div>
                    </div>
  
                </div>

                <div id="testimonials-section" class="admin-section" style="display: none;">
                    <h2>Testimonials Management</h2>
                    <p class="text-muted">Manage client testimonials</p>
                    <!-- Testimonials management content will be added here --> 
                </div>

                <div id="contacts-section" class="admin-section" style="display: none;">
                    <h2>Contact Inquiries</h2>
                    <p class="text-muted">Manage messages from clients</p>
                    <!-- Contact inquiries content will be added here -->
                </div>

                <div id="settings-section" class="admin-section" style="display: none;">
                    <h2>Settings</h2>
                    <p class="text-muted">Manage admin settings</p>
                    <!-- Settings content will be added here -->
                </div>

                

            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const packageSelect = document.getElementById('package');
            const budgetSelect = document.getElementById('budget');

            function toggleBudget() {
                if (packageSelect.value) {
                    budgetSelect.disabled = true;       // disable budget
                    budgetSelect.value = '';            // clear selection
                } else {
                    budgetSelect.disabled = false;      // enable budget
                }
            }

            // Initial check on page load
            toggleBudget();

            // Listen for changes on package select
            packageSelect.addEventListener('change', toggleBudget);
        });
    </script>

    <script>
        // Navigation functionality
        function showSection(sectionName) {
            // Hide all sections
            document.querySelectorAll('.admin-section').forEach(section => {
                section.style.display = 'none';
            });

            // Show selected section
            document.getElementById(sectionName + '-section').style.display = 'block';

            // Update active nav link
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });

            document.querySelector(`[href="#${sectionName}"]`).classList.add('active');
        }

        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            // Bookings chart
            const ctx = document.getElementById('bookingsChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Bookings',
                        data: [
                            <?php
                            $monthly_data = array_fill(1, 12, 0);
                            foreach ($monthly_bookings as $data) {
                                $monthly_data[$data['month']] = $data['count'];
                            }
                            echo implode(',', array_values($monthly_data));
                            ?>
                        ],
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
