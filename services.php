<?php
session_start();
require_once 'includes/config.php';
require_once 'helpers/functions.php';
$page_title = 'Our Services';
include 'includes/header.php';

$photographyServices = getPhotographyServices(); 
$videoServices = getVideoServices();
$editingServices = getEditingServices();
$printingServices = getPrintingServices();
$servicePackages = getServicePackages();
?>

<main>
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold mb-3">Our Services</h1>
                    <p class="lead">Professional media services tailored to capture your special moments</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-detail py-5">
        <div class="container">

            <!-- Photography Services -->
            <div class="service-category mb-5">
                <div class="row align-items-center mb-5">
                    <div class="col-lg-6">
                        <img src="assets/images/photography-service.jpg" alt="Photography Services" class="img-fluid rounded-3 shadow">
                    </div>
                    <div class="col-lg-6">
                        <div class="service-content ps-lg-4">
                            <h2 class="display-6 fw-bold mb-3">
                                <i class="fas fa-camera text-primary me-3"></i>
                                Photography Services
                            </h2>
                            <p class="lead text-muted mb-4">Capture life's precious moments with our professional photography services.</p>

                            <div class="service-list">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="service-item p-3 border rounded">
                                            <h5 class="fw-bold">Wedding Photography</h5>
                                            <p class="text-muted mb-2">Complete wedding coverage</p>
                                            <div class="price">
                                                <span class="text-primary fw-bold">From TSh 500,000</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="service-item p-3 border rounded">
                                            <h5 class="fw-bold">Portrait Sessions</h5>
                                            <p class="text-muted mb-2">Individual & family portraits</p>
                                            <div class="price">
                                                <span class="text-primary fw-bold">From TSh 150,000</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="service-item p-3 border rounded">
                                            <h5 class="fw-bold">Event Coverage</h5>
                                            <p class="text-muted mb-2">Corporate & social events</p>
                                            <div class="price">
                                                <span class="text-primary fw-bold">From TSh 200,000</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="service-item p-3 border rounded">
                                            <h5 class="fw-bold">Commercial Photography</h5>
                                            <p class="text-muted mb-2">Product & business photography</p>
                                            <div class="price">
                                                <span class="text-primary fw-bold">From TSh 100,000</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Video Services -->
            <div class="service-category mb-5">
                <div class="row align-items-center mb-5">
                    <div class="col-lg-6 order-lg-2">
                        <img src="assets/images/videography-service.jpg" alt="Videography Services" class="img-fluid rounded-3 shadow">
                    </div>
                    <div class="col-lg-6 order-lg-1">
                        <div class="service-content pe-lg-4">
                            <h2 class="display-6 fw-bold mb-3">
                                <i class="fas fa-video text-primary me-3"></i>
                                Videography Services
                            </h2>
                            <p class="lead text-muted mb-4">Professional video production to tell your story in motion.</p>

                            <div class="service-list">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="service-item p-3 border rounded">
                                            <h5 class="fw-bold">Wedding Videography</h5>
                                            <p class="text-muted mb-2">Full ceremony & reception</p>
                                            <div class="price">
                                                <span class="text-primary fw-bold">From TSh 800,000</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="service-item p-3 border rounded">
                                            <h5 class="fw-bold">Event Videography</h5>
                                            <p class="text-muted mb-2">Corporate & social events</p>
                                            <div class="price">
                                                <span class="text-primary fw-bold">From TSh 300,000</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="service-item p-3 border rounded">
                                            <h5 class="fw-bold">Promotional Videos</h5>
                                            <p class="text-muted mb-2">Business & marketing videos</p>
                                            <div class="price">
                                                <span class="text-primary fw-bold">From TSh 250,000</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="service-item p-3 border rounded">
                                            <h5 class="fw-bold">Music Videos</h5>
                                            <p class="text-muted mb-2">Creative music video production</p>
                                            <div class="price">
                                                <span class="text-primary fw-bold">From TSh 500,000</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Editing Services -->
            <div class="service-category mb-5">
                <div class="row align-items-center mb-5">
                    <div class="col-lg-6">
                        <img src="assets/images/editing-service.jpg" alt="Editing Services" class="img-fluid rounded-3 shadow">
                    </div>
                    <div class="col-lg-6">
                        <div class="service-content ps-lg-4">
                            <h2 class="display-6 fw-bold mb-3">
                                <i class="fas fa-edit text-primary me-3"></i>
                                Photo & Video Editing
                            </h2>
                            <p class="lead text-muted mb-4">Professional editing services using Adobe Creative Suite.</p>

                            <div class="service-list">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="service-item p-3 border rounded">
                                            <h5 class="fw-bold">Photo Retouching</h5>
                                            <p class="text-muted mb-2">Professional photo enhancement</p>
                                            <div class="price">
                                                <span class="text-primary fw-bold">TSh 5,000/photo</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="service-item p-3 border rounded">
                                            <h5 class="fw-bold">Video Editing</h5>
                                            <p class="text-muted mb-2">Complete video post-production</p>
                                            <div class="price">
                                                <span class="text-primary fw-bold">From TSh 100,000</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="service-item p-3 border rounded">
                                            <h5 class="fw-bold">Color Grading</h5>
                                            <p class="text-muted mb-2">Professional color correction</p>
                                            <div class="price">
                                                <span class="text-primary fw-bold">From TSh 50,000</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="service-item p-3 border rounded">
                                            <h5 class="fw-bold">Motion Graphics</h5>
                                            <p class="text-muted mb-2">Animated graphics & titles</p>
                                            <div class="price">
                                                <span class="text-primary fw-bold">From TSh 80,000</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Printing Services -->
            <div class="service-category mb-5">
                <div class="row align-items-center mb-5">
                    <div class="col-lg-6 order-lg-2">
                        <img src="assets/images/printing-service.jpg" alt="Printing Services" class="img-fluid rounded-3 shadow">
                    </div>
                    <div class="col-lg-6 order-lg-1">
                        <div class="service-content pe-lg-4">
                            <h2 class="display-6 fw-bold mb-3">
                                <i class="fas fa-print text-primary me-3"></i>
                                Printing Services
                            </h2>
                            <p class="lead text-muted mb-4">High-quality printing for all your photo and marketing needs.</p>

                            <div class="service-list">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="service-item p-3 border rounded">
                                            <h5 class="fw-bold">Photo Prints</h5>
                                            <p class="text-muted mb-2">Various sizes available</p>
                                            <div class="price">
                                                <span class="text-primary fw-bold">From TSh 2,000</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="service-item p-3 border rounded">
                                            <h5 class="fw-bold">Canvas Prints</h5>
                                            <p class="text-muted mb-2">Premium canvas printing</p>
                                            <div class="price">
                                                <span class="text-primary fw-bold">From TSh 25,000</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="service-item p-3 border rounded">
                                            <h5 class="fw-bold">Banners & Posters</h5>
                                            <p class="text-muted mb-2">Large format printing</p>
                                            <div class="price">
                                                <span class="text-primary fw-bold">From TSh 15,000</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="service-item p-3 border rounded">
                                            <h5 class="fw-bold">Album Design</h5>
                                            <p class="text-muted mb-2">Custom photo albums</p>
                                            <div class="price">
                                                <span class="text-primary fw-bold">From TSh 50,000</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Packages -->
            <div class="service-packages">
                <div class="row">
                    <div class="col-12 text-center mb-5">
                        <h2 class="display-5 fw-bold text-dark">Service Packages</h2>
                        <p class="lead text-muted">Choose the perfect package for your event</p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow">
                            <div class="card-header bg-primary text-white text-center py-3">
                                <h4 class="card-title mb-0">Basic Package</h4>
                                <div class="package-price mt-2">
                                    <span class="h3 fw-bold">TSh 300,000</span>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>4 hours coverage</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>50 edited photos</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Online gallery</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Basic editing</li>
                                </ul>
                            </div>
                            <div class="card-footer text-center">
                                <a href="booking.php?package=basic" class="btn btn-primary">Choose Package</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow border-primary">
                            <div class="card-header bg-success text-white text-center py-3">
                                <h4 class="card-title mb-0">Premium Package</h4>
                                <div class="package-price mt-2">
                                    <span class="h3 fw-bold">TSh 600,000</span>
                                </div>
                                <small class="badge bg-warning text-dark">Most Popular</small>
                            </div>
                            <div class="card-body p-4">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>8 hours coverage</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>100 edited photos</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Video highlights (3 min)</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Premium editing</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>USB drive included</li>
                                </ul>
                            </div>
                            <div class="card-footer text-center">
                                <a href="booking.php?package=premium" class="btn btn-success">Choose Package</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow">
                            <div class="card-header bg-warning text-dark text-center py-3">
                                <h4 class="card-title mb-0">Luxury Package</h4>
                                <div class="package-price mt-2">
                                    <span class="h3 fw-bold">TSh 1,000,000</span>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Full day coverage</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>200+ edited photos</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Full ceremony video</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Same-day editing</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Custom album included</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Two photographers</li>
                                </ul>
                            </div>
                            <div class="card-footer text-center">
                                <a href="booking.php?package=luxury" class="btn btn-warning">Choose Package</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5 bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="display-6 fw-bold mb-3">Ready to Book Your Session?</h2>
                    <p class="lead mb-4">Contact us today to discuss your requirements and get a custom quote</p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="booking.php" class="btn btn-primary btn-lg px-4">Book Now</a>
                        <a href="contact.php" class="btn btn-outline-light btn-lg px-4">Get Quote</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
