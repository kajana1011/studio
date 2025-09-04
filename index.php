<?php
session_start();
require_once 'includes/config.php';
include 'includes/header.php';
?>

<main>
    <!-- Hero Section -->
    <section class="hero-section bg-dark text-white py-5" style="background: linear-gradient(rgba(0, 0, 0, 1), rgba(0, 0, 0, 1)), url('assets/images/hero-bg.jpg') center/cover;">
        <div class="container">
            <div class="row align-items-center min-vh-75">
                <div class="col-lg-6 text-center">
                    <img src="assets/images/camera-hero.jpg" alt="Professional Camera" class="img-fluid rounded-3 shadow-lg">
                </div>
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Capture Your Precious Moments</h1>
                    <p class="lead mb-4">Professional photography, videography, editing, and printing services in Dar es Salaam, Tanzania. We bring your vision to life with creativity and excellence.</p>
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <a href="booking.php" class="btn btn-primary btn-lg px-4">Book Now</a>
                        <a href="portfolio.php" class="btn btn-outline-light btn-lg px-4">View Portfolio</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5 mt-4">
                    <h2 class="display-5 fw-bold text-dark">Our Services</h2>
                    <p class="lead text-muted">Professional media services tailored to your needs</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <a class="text-decoration-none" href="services.php#photography">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="service-icon mb-3">
                                    <i class="fas fa-camera fa-3x text-primary"></i>
                                </div>
                                <h5 class="card-title fw-bold">Photography</h5>
                                <p class="card-text text-muted">Professional photo shoots for events, portraits, and commercial purposes.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-md-6">
                    <a class="text-decoration-none" href="services.php#videography">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="service-icon mb-3">
                                    <i class="fas fa-video fa-3x text-primary"></i>
                                </div>
                                <h5 class="card-title fw-bold">Videography</h5>
                                <p class="card-text text-muted">High-quality video coverage for weddings, events, and promotional content.</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <a class="text-decoration-none" href="services.php#editing">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="service-icon mb-3">
                                    <i class="fas fa-edit fa-3x text-primary"></i>
                                </div>
                                <h5 class="card-title fw-bold">Photo/Video Editing</h5>
                                <p class="card-text text-muted">Professional editing using Adobe Creative Suite for stunning results.</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <a class="text-decoration-none" href="services.php#printing">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="service-icon mb-3">
                                    <i class="fas fa-print fa-3x text-primary"></i>
                                </div>
                                <h5 class="card-title fw-bold">Printing</h5>
                                <p class="card-text text-muted">High-quality printing services for photos, banners, and marketing materials.</p>
                            </div>
                        </div>
                    </a>
                </div>
                 <div class="text-center mt-4">
                    <a href="services" class="btn btn-primary btn-lg">More services</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Preview Section -->
    <section class="portfolio-preview py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="display-5 fw-bold text-dark">Recent Work</h2>
                    <p class="lead text-muted">A glimpse of our creative excellence</p>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <div class="portfolio-item">
                        <img src="assets/images/portfolio-1.jpg" alt="Wedding Photography" class="img-fluid rounded-3 shadow">
                        <div class="portfolio-overlay">
                            <h5 class="text-white">Wedding Photography</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="portfolio-item">
                        <img src="assets/images/portfolio-2.jpg" alt="Corporate Event" class="img-fluid rounded-3 shadow">
                        <div class="portfolio-overlay">
                            <h5 class="text-white">Graduation Event</h5>

                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="portfolio-item">
                        <img src="assets/images/portfolio-3.jpg" alt="Portrait Session" class="img-fluid rounded-3 shadow">
                        <div class="portfolio-overlay">
                            <h5 class="text-white">Portrait Session</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="portfolio.php" class="btn btn-primary btn-lg">View Full Portfolio</a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5 bg-black text-white">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="display-6 fw-bold mb-3">Ready to Create Something Amazing?</h2>
                    <p class="lead mb-4">Let's bring your vision to life with our professional media services</p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="booking.php" class="btn btn-primary btn-lg px-4">Book a Session</a>
                        <a href="contact.php" class="btn btn-outline-light btn-lg px-4">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
