<?php
session_start();
require_once 'includes/config.php';
$page_title = 'Client Testimonials';

// Fetch approved testimonials from database
try {
    $stmt = $pdo->prepare("
        SELECT * FROM testimonials
        WHERE is_approved = 1
        ORDER BY is_featured DESC, created_at DESC
    ");
    $stmt->execute();
    $testimonials = $stmt->fetchAll();
} catch(PDOException $e) {
    $testimonials = [];
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
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card card h-100 border-0 shadow">
                        <div class="card-body p-4 text-center">
                            <div class="client-photo mb-3">
                                <img src="https://images.unsplash.com/photo-1494790108755-2616b612b47c?w=100&h=100&fit=crop&crop=face"
                                     alt="Sarah Johnson" class="rounded-circle" width="80" height="80">
                            </div>
                            <div class="rating mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <blockquote class="blockquote">
                                <p class="mb-3">"Studio Media Tanzania captured our wedding day perfectly! Every moment was beautifully documented, and the final photos exceeded our expectations. Highly professional and creative team."</p>
                            </blockquote>
                            <div class="client-info">
                                <h6 class="fw-bold mb-1">Sarah & Michael Johnson</h6>
                                <small class="text-muted">Wedding Photography</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card card h-100 border-0 shadow">
                        <div class="card-body p-4 text-center">
                            <div class="client-photo mb-3">
                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop&crop=face"
                                     alt="David Mwanza" class="rounded-circle" width="80" height="80">
                            </div>
                            <div class="rating mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <blockquote class="blockquote">
                                <p class="mb-3">"Outstanding corporate event coverage! The team was professional, unobtrusive, and delivered high-quality photos that perfectly captured our company's milestone celebration."</p>
                            </blockquote>
                            <div class="client-info">
                                <h6 class="fw-bold mb-1">David Mwanza</h6>
                                <small class="text-muted">Corporate Event</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card card h-100 border-0 shadow">
                        <div class="card-body p-4 text-center">
                            <div class="client-photo mb-3">
                                <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop&crop=face"
                                     alt="Grace Kimaro" class="rounded-circle" width="80" height="80">
                            </div>
                            <div class="rating mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <blockquote class="blockquote">
                                <p class="mb-3">"Amazing portrait session for our family! The photographer made us feel comfortable and natural. The final photos are absolutely stunning and we treasure them forever."</p>
                            </blockquote>
                            <div class="client-info">
                                <h6 class="fw-bold mb-1">Grace Kimaro</h6>
                                <small class="text-muted">Family Portrait</small>
                            </div>
                        </div>
                    </div>
                </div>
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

                <!-- Testimonial 1 -->
                <div class="col-lg-6">
                    <div class="testimonial-item d-flex">
                        <div class="client-avatar me-4">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=60&h=60&fit=crop&crop=face"
                                 alt="John Mbeki" class="rounded-circle" width="60" height="60">
                        </div>
                        <div class="testimonial-content">
                            <div class="rating mb-2">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <blockquote class="mb-3">
                                <p class="text-muted">"Professional video editing service that transformed our raw footage into a beautiful wedding film. The attention to detail and creative touch made all the difference."</p>
                            </blockquote>
                            <div class="client-info">
                                <h6 class="fw-bold mb-0">John Mbeki</h6>
                                <small class="text-muted">Video Editing Service</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="col-lg-6">
                    <div class="testimonial-item d-flex">
                        <div class="client-avatar me-4">
                            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=60&h=60&fit=crop&crop=face"
                                 alt="Fatima Hassan" class="rounded-circle" width="60" height="60">
                        </div>
                        <div class="testimonial-content">
                            <div class="rating mb-2">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <blockquote class="mb-3">
                                <p class="text-muted">"Excellent printing quality for our wedding album! Colors are vibrant, paper quality is superb, and the album design exceeded our expectations. Highly recommended!"</p>
                            </blockquote>
                            <div class="client-info">
                                <h6 class="fw-bold mb-0">Fatima Hassan</h6>
                                <small class="text-muted">Album Printing</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="col-lg-6">
                    <div class="testimonial-item d-flex">
                        <div class="client-avatar me-4">
                            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=60&h=60&fit=crop&crop=face"
                                 alt="Robert Temba" class="rounded-circle" width="60" height="60">
                        </div>
                        <div class="testimonial-content">
                            <div class="rating mb-2">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <blockquote class="mb-3">
                                <p class="text-muted">"Great experience working with Studio Media for our product photography. Professional setup, excellent lighting, and quick turnaround. Our e-commerce sales improved significantly!"</p>
                            </blockquote>
                            <div class="client-info">
                                <h6 class="fw-bold mb-0">Robert Temba</h6>
                                <small class="text-muted">Commercial Photography</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 4 -->
                <div class="col-lg-6">
                    <div class="testimonial-item d-flex">
                        <div class="client-avatar me-4">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=60&h=60&fit=crop&crop=face"
                                 alt="Amina Salim" class="rounded-circle" width="60" height="60">
                        </div>
                        <div class="testimonial-content">
                            <div class="rating mb-2">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <blockquote class="mb-3">
                                <p class="text-muted">"The team captured our graduation ceremony beautifully. Every important moment was documented, and the photos bring back such wonderful memories. Professional and friendly service!"</p>
                            </blockquote>
                            <div class="client-info">
                                <h6 class="fw-bold mb-0">Amina Salim</h6>
                                <small class="text-muted">Graduation Photography</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 5 -->
                <div class="col-lg-6">
                    <div class="testimonial-item d-flex">
                        <div class="client-avatar me-4">
                            <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=60&h=60&fit=crop&crop=face"
                                 alt="Paul Mwakitalu" class="rounded-circle" width="60" height="60">
                        </div>
                        <div class="testimonial-content">
                            <div class="rating mb-2">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <blockquote class="mb-3">
                                <p class="text-muted">"Incredible birthday party videography! The highlights video perfectly captured the joy and excitement of the celebration. Our family watches it over and over again."</p>
                            </blockquote>
                            <div class="client-info">
                                <h6 class="fw-bold mb-0">Paul Mwakitalu</h6>
                                <small class="text-muted">Event Videography</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 6 -->
                <div class="col-lg-6">
                    <div class="testimonial-item d-flex">
                        <div class="client-avatar me-4">
                            <img src="https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=60&h=60&fit=crop&crop=face"
                                 alt="Maria Joseph" class="rounded-circle" width="60" height="60">
                        </div>
                        <div class="testimonial-content">
                            <div class="rating mb-2">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <blockquote class="mb-3">
                                <p class="text-muted">"Wonderful experience from booking to final delivery. The team was punctual, professional, and delivered exactly what they promised. Will definitely book again for future events!"</p>
                            </blockquote>
                            <div class="client-info">
                                <h6 class="fw-bold mb-0">Maria Joseph</h6>
                                <small class="text-muted">Wedding Photography</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section py-5 bg-primary text-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                        <h3 class="fw-bold">500+</h3>
                        <p class="mb-0">Happy Clients</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-camera fa-3x"></i>
                        </div>
                        <h3 class="fw-bold">1000+</h3>
                        <p class="mb-0">Projects Completed</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-star fa-3x"></i>
                        </div>
                        <h3 class="fw-bold">4.9/5</h3>
                        <p class="mb-0">Average Rating</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-award fa-3x"></i>
                        </div>
                        <h3 class="fw-bold">5+</h3>
                        <p class="mb-0">Years Experience</p>
                    </div>
                </div>
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
                <form id="testimonialForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="testimonial_name" class="form-label">Your Name *</label>
                            <input type="text" class="form-control" id="testimonial_name" required>
                        </div>

                        <div class="col-md-6">
                            <label for="testimonial_email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="testimonial_email" required>
                        </div>

                        <div class="col-md-6">
                            <label for="service_type" class="form-label">Service Used *</label>
                            <select class="form-select" id="service_type" required>
                                <option value="">Select service</option>
                                <option value="Wedding Photography">Wedding Photography</option>
                                <option value="Wedding Videography">Wedding Videography</option>
                                <option value="Event Photography">Event Photography</option>
                                <option value="Portrait Session">Portrait Session</option>
                                <option value="Commercial Photography">Commercial Photography</option>
                                <option value="Video Editing">Video Editing</option>
                                <option value="Photo Editing">Photo Editing</option>
                                <option value="Printing Services">Printing Services</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="rating" class="form-label">Rating *</label>
                            <select class="form-select" id="rating" required>
                                <option value="">Select rating</option>
                                <option value="5">⭐⭐⭐⭐⭐ (5 stars)</option>
                                <option value="4">⭐⭐⭐⭐ (4 stars)</option>
                                <option value="3">⭐⭐⭐ (3 stars)</option>
                                <option value="2">⭐⭐ (2 stars)</option>
                                <option value="1">⭐ (1 star)</option>
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
