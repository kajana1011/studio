<?php
session_start();
require_once 'includes/config.php';
require_once 'helpers/functions.php';
require_once 'helpers/email.php';
$page_title = 'Book Our Services';

$success_message = '';
$error_message = '';

$budgets = getBudgets(); // Fetch budget ranges from the database
$packages = getPackages(); // Fetch packages from the database

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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


    // $budget = '';
    // if (!empty($budget_id)) {
    //     // Fetch budget label from database
    //     $stmt = $pdo->prepare("SELECT label FROM budgets WHERE id = ?");
    //     $stmt->execute([$budget_id]);
    //     $budget_row = $stmt->fetch(PDO::FETCH_ASSOC);
    //     if ($budget_row) {
    //         $budget = $budget_row['label'];
    //     }
    // }

    // Validation
    if (empty($name) || empty($email) || empty($phone) || empty($service) || empty($event_date)) {
        $error_message = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } else {
        try {
            // Insert booking into database
            $stmt = $pdo->prepare("
                INSERT INTO bookings (name, email, phone, service, event_date, event_time, location, package_id, budget_id, message, created_at)
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

            $success_message = 'Thank you! Your booking request has been submitted successfully. We will contact you within 24 hours.';

            // Clear form data
            $name = $email = $phone = $service = $event_date = $event_time = $location = $package = $budget = $message = '';

        } catch(PDOException $e) {
            $error_message = 'Sorry, there was an error processing your request. Please try again later.: ' . $e->getMessage();
        }
    }
    // print_r($_SERVER);
}
// Get package from URL if set
$selected_package = isset($_GET['package']) ? sanitize_input($_GET['package']) : '';

include 'includes/header.php';
?>

<main>
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold mb-3">Book Our Services</h1>
                    <p class="lead">Let's capture your special moments together</p>
                    
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Form Section -->
    <section class="booking-form py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">

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

                    <div class="card shadow border-0">
                        <div class="card-header bg-light py-3">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-calendar-alt text-primary me-2"></i>
                                Book Your Session
                            </h3>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="booking.php" novalidate>
                                <div class="row g-3">

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
                                            <option value="wedding-photography" <?php echo (isset($service) && $service == 'wedding-photography') ? 'selected' : ''; ?>>Wedding Photography</option>
                                            <option value="wedding-videography" <?php echo (isset($service) && $service == 'wedding-videography') ? 'selected' : ''; ?>>Wedding Videography</option>
                                            <option value="event-photography" <?php echo (isset($service) && $service == 'event-photography') ? 'selected' : ''; ?>>Event Photography</option>
                                            <option value="event-videography" <?php echo (isset($service) && $service == 'event-videography') ? 'selected' : ''; ?>>Event Videography</option>
                                            <option value="portrait-session" <?php echo (isset($service) && $service == 'portrait-session') ? 'selected' : ''; ?>>Portrait Session</option>
                                            <option value="commercial-photography" <?php echo (isset($service) && $service == 'commercial-photography') ? 'selected' : ''; ?>>Commercial Photography</option>
                                            <option value="photo-editing" <?php echo (isset($service) && $service == 'photo-editing') ? 'selected' : ''; ?>>Photo Editing</option>
                                            <option value="video-editing" <?php echo (isset($service) && $service == 'video-editing') ? 'selected' : ''; ?>>Video Editing</option>
                                            <option value="printing" <?php echo (isset($service) && $service == 'printing') ? 'selected' : ''; ?>>Printing Services</option>
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
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="booking-sidebar ps-lg-4">

                        <!-- Contact Info -->
                        <div class="card border-0 shadow mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-phone me-2"></i>
                                    Contact Us Directly
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="contact-item mb-3">
                                    <i class="fas fa-phone text-primary me-2"></i>
                                    <span>+255 742 478 700</span>
                                </div>
                                <div class="contact-item mb-3">
                                    <i class="fab fa-whatsapp text-success me-2"></i>
                                    <a href="https://wa.me/255742478700" class="text-decoration-none">WhatsApp Us</a>
                                </div>
                                <div class="contact-item mb-3">
                                    <i class="fas fa-envelope text-info me-2"></i>
                                    <span>info@b25studio.com</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                    <span>Dar es Salaam, Tanzania</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Tips -->
                        <div class="card border-0 shadow">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-lightbulb text-warning me-2"></i>
                                    Booking Tips
                                </h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Book at least 2 weeks in advance
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Weekend dates fill up quickly
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Consultations are always free
                                    </li>
                                    <li class="mb-0">
                                        <i class="fas fa-check text-success me-2"></i>
                                        We provide equipment and backup
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
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

<?php include 'includes/footer.php'; ?>
