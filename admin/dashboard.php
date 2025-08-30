<?php
session_start();
require_once '../includes/config.php';

// Check if admin is logged in
if (!is_admin_logged_in()) {
    redirect('login.php');
}

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
                    $stmt->execute([$_SESSION['admin_id'], $booking_id]);

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
        }
    }
}

// Get dashboard statistics
try {
    // Total bookings
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM bookings");
    $total_bookings = $stmt->fetch()['total'];

    // Pending bookings
    $stmt = $pdo->query("SELECT COUNT(*) as pending FROM bookings WHERE status = 'pending'");
    $pending_bookings = $stmt->fetch()['pending'];

    // Total clients
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM clients WHERE is_active = 1");
    $total_clients = $stmt->fetch()['total'];

    // Total portfolio items
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM portfolio WHERE is_active = 1");
    $total_portfolio = $stmt->fetch()['total'];

    // Recent bookings
    $stmt = $pdo->query("SELECT * FROM bookings ORDER BY created_at DESC LIMIT 5");
    $recent_bookings = $stmt->fetchAll();

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

    // Monthly booking stats
    $stmt = $pdo->query("
        SELECT MONTH(created_at) as month, COUNT(*) as count
        FROM bookings
        WHERE YEAR(created_at) = YEAR(CURDATE())
        GROUP BY MONTH(created_at)
        ORDER BY month
    ");
    $monthly_bookings = $stmt->fetchAll();

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
                        <small class="text-muted">Admin Panel</small>
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
                    <h1 class="h2">Welcome, <?php echo $_SESSION['admin_name']; ?>!</h1>
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
                                        <i class="fas fa-star me-2"></i>
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
                <div id="bookings-section" class="admin-section" style="display: none;">
                    <h2>Bookings Management</h2>
                    <p class="text-muted">Manage client bookings and appointments</p>
                    <!-- Bookings management content will be added here -->
                </div>

                <!-- More sections... -->

            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
