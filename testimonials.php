<?php
session_start();
require_once 'helpers/functions.php';
$page_title = 'Client Testimonials';

$featured_testimonials = getFeaturedTestimonials();
$other_testimonials = getRemainingTestimonials();

$project_completed = getProjectsCount();
$happy_clients = getClientsCount(); 
$average_rating = getAverageRating();
$years_experience = getYearsOfExperience();

$services = getServices();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = sanitizeInput($_POST['testimonial_name']);
    $email = filter_var($_POST['testimonial_email'], FILTER_SANITIZE_EMAIL);
    $service_type = sanitizeInput($_POST['service_type']);
    $rating = intval($_POST['rating']);
    $testimonial_text = sanitizeInput($_POST['testimonial_text']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif ($rating < 1 || $rating > 5) {
        $error_message = "Rating must be between 1 and 5.";
    } elseif (empty($name) || empty($service_type) || empty($testimonial_text)) {
        $error_message = "All fields are required.";
    } else {
        // Insert testimonial into database
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO testimonials (client_name, email, service_type, rating, testimonial, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        if ($stmt->execute([$name, $email, $service_type, $rating, $testimonial_text])) {
            $success_message = "Thank you for your testimonial!";
            // Refresh testimonials
            $featured_testimonials = getFeaturedTestimonials();
            $other_testimonials = getRemainingTestimonials();
        } else {
            $error_message = "There was an error submitting your testimonial. Please try again.";
        }
    }
}

include 'includes/header.php';
?>

<main>
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold mb-3">Client Testimonials</h1>
                    <p class="lead">What our clients say about working with us</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Testimonials -->
    <section class="featured-testimonials py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="display-6 fw-bold text-dark">Featured Reviews</h2>
                    <p class="lead text-muted">Stories from our satisfied clients</p>
                </div>
            </div>

            <div class="row g-4">
                <!-- Sample Testimonials (you can replace with dynamic data) -->
                <?php foreach ($featured_testimonials as $testimonial): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card card h-100 border-0 shadow">
                        <div class="card-body p-4 text-center">
                            <div class="client-photo mb-3">
                                <?php if(isset($testimonial['profile_image']) && !empty($testimonial['profile_image'])): ?>
                                    <img src="<?= htmlspecialchars($testimonial['profile_image']) ?>" alt="<?= htmlspecialchars($testimonial['client_name']) ?>" class="rounded-circle" width="80" height="80">
                                <?php else: ?>
                                    <?php $initials = getInitials($testimonial['client_name']); ?>
                                    <div class="avatar-circle">
                                        <span class="initials"><?php echo $initials; ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="rating mb-3">
                                <?php for ($i = 0; $i < $testimonial['rating']; $i++): ?>
                                    <i class="fas fa-star text-warning"></i>
                                <?php endfor; ?>
                                <?php for ($i = $testimonial['rating']; $i < 5; $i++): ?>
                                    <i class="far fa-star text-warning"></i>    
                                <?php endfor; ?>
                            </div>
                            <blockquote class="blockquote">
                                <p class="mb-3">"<?= $testimonial['testimonial'] ?>"</p>
                            </blockquote>
                            <div class="client-info">
                                <h6 class="fw-bold mb-1"><?= $testimonial['client_name'] ?></h6>
                                <small class="text-muted"><?= $testimonial['service_type'] ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- All Testimonials -->
    <section class="all-testimonials py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="display-6 fw-bold text-dark">More Client Reviews</h2>
                </div>
            </div>

            <div class="row g-4">

                <?php foreach ($other_testimonials as $testimonial): ?>
                <div class="col-lg-6">
                    <div class="testimonial-item d-flex">
                        <?php $initials = getInitials($testimonial['client_name']); ?>
                        <div class="client-avatar me-4">
                            <div class="avatar-circle">
                                <span class="initials"><?php echo $initials; ?></span>
                            </div>
                        </div>

                        <div class="testimonial-content">
                            <div class="rating mb-2">
                                <?php for ($i = 0; $i < $testimonial['rating']; $i++): ?>
                                    <i class="fas fa-star text-warning"></i> 
                                <?php endfor; ?>
                                <?php for ($i = $testimonial['rating']; $i < 5; $i++): ?>
                                    <i class="far fa-star text-warning"></i>
                                <?php endfor; ?>
                            </div>
                            <blockquote class="mb-3">
                                <p class="text-muted">"<?= $testimonial['testimonial'] ?>"</p>
                            </blockquote>
                            <div class="client-info">
                                <h6 class="fw-bold mb-0"><?= $testimonial['client_name'] ?></h6>
                                <small class="text-muted"><?= $testimonial['service_type'] ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    <!-- Add Testimonial CTA -->
    <section class="add-testimonial py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="fw-bold mb-3">Share Your Experience</h2>
                    <p class="lead text-muted mb-4">Worked with us? We'd love to hear about your experience!</p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <button class="btn btn-primary btn-lg px-4" data-bs-toggle="modal" data-bs-target="#testimonialModal">
                            <i class="fas fa-plus me-2"></i>
                            Add Your Review
                        </button>
                        <a href="contact.php" class="btn btn-outline-primary btn-lg px-4">
                            <i class="fas fa-envelope me-2"></i>
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section py-5 bg-black text-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-users fa-3x text-primary"></i>
                        </div>
                        <?php if($happy_clients < 600): ?>
                            <h3 class="fw-bold">617</h3>
                        <?php else: ?>
                            <h3 class="fw-bold"><?= $happy_clients -1 ?>+</h3>
                        <?php endif; ?>
                        <p class="mb-0">Happy Clients</p>
                    </div>
                </div>
    
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-camera fa-3x"></i>
                        </div>
                        <?php if($project_completed < 500): ?>
                            <h3 class="fw-bold">513</h3>
                        <?php else: ?>
                             <h3 class="fw-bold"><?= $project_completed -1 ?>+</h3>
                        <?php endif; ?>
                        <p class="mb-0">Projects Completed</p>
                    </div>
                </div>
    
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-star fa-3x text-warning"></i>
                        </div>
                        <h3 class="fw-bold"><?= $average_rating ?></h3>
                        <p class="mb-0">Average Rating</p>
                    </div>
                </div>
    
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-award fa-3x text-warning"></i>
                        </div>
                        <h3 class="fw-bold"><?= $years_experience ?></h3>
                        <p class="mb-0">Years Experience</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Add Testimonial Modal -->
<div class="modal fade" id="testimonialModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-star text-warning me-2"></i>
                    Share Your Experience
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action = "<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="testimonial_name" class="form-label">Your Name *</label>
                            <input type="text" class="form-control" id="testimonial_name" name='testimonial_name' required>
                        </div>

                        <div class="col-md-6">
                            <label for="testimonial_email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="testimonial_email" name='testimonial_email' required>
                        </div>

                        <div class="col-md-6">
                            <label for="service_type" class="form-label">Service Used *</label>
                            <select class="form-select" id="service_type" required>
                                <option value="">Select service</option>
                                <?php foreach($services as $service): ?>
                                    <option value="<?= htmlspecialchars($service['name']) ?>"><?= htmlspecialchars($service['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="rating" class="form-label">Rating *</label>
                            <select class="form-select" id="rating" required>
                                <option value="">Select rating</option>
                                <option value="5">⭐⭐⭐⭐⭐</option>
                                <option value="4">⭐⭐⭐⭐</option>
                                <option value="3">⭐⭐⭐</option>
                                <option value="2">⭐⭐</option>
                                <option value="1">⭐</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="testimonial_text" class="form-label">Your Review *</label>
                            <textarea class="form-control" id="testimonial_text" rows="4" required
                                      placeholder="Tell us about your experience working with Studio Media Tanzania..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="testimonialForm" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i>
                    Submit Review
                </button>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
