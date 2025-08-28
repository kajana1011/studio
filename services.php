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
    <section class="services-detail py-3">
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
                                    <?php foreach ($photographyServices as $service): ?>
                                    <div class="col-md-6">
                                        <div class="service-item p-3 border rounded">
                                            <h5 class="fw-bold"><?= $service['name'] ?></h5>
                                            <p class="text-muted mb-2"><?= $service['description'] ?></p>
                                            <div class="price">
                                                <span class="text-primary fw-bold">From TSh <?= $service['base_price'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
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
                                    <?php foreach ($videoServices as $service): ?>
                                    <div class="col-md-6">
                                        <div class="service-item p-3 border rounded">
                                            <h5 class="fw-bold"><?= $service['name'] ?></h5>
                                            <p class="text-muted mb-2"><?= $service['description'] ?></p>
                                            <div class="price">
                                                <span class="text-primary fw-bold">From TSh <?= $service['base_price'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
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
                        <img src="assets/images/editing-services.jpg" alt="Editing Services" class="img-fluid rounded-3 shadow">
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
                                    <?php foreach ($editingServices as $service): ?>
                                    <div class="col-md-6">
                                        <div class="service-item p-3 border rounded">
                                            <h5 class="fw-bold"><?= $service['name'] ?></h5>
                                            <p class="text-muted mb-2"><?= $service['description'] ?></p>
                                            <div class="price">
                                                <span class="text-primary fw-bold">From TSh <?= $service['base_price'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
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
                        <img src="assets/images/printing-services.jpg" alt="Printing Services" class="img-fluid rounded-3 shadow">
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
                                    <?php foreach ($printingServices as $service): ?>
                                    <div class="col-md-6">
                                        <div class="service-item p-3 border rounded">
                                            <h5 class="fw-bold"><?= $service['name'] ?></h5>
                                            <p class="text-muted mb-2"><?= $service['description'] ?></p>
                                            <div class="price">
                                                <span class="text-primary fw-bold">From TSh <?= $service['base_price'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
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
                    <?php foreach ($servicePackages as $package): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow">
                            <?php if ($package['name'] == 'Basic Package'): ?>
                                <div class="card-header bg-primary text-white text-center py-3">
                            <?php elseif ($package['name'] == 'Premium Package'): ?>
                                <div class="card-header bg-success text-white text-center py-3">
                            <?php elseif ($package['name'] == 'Luxury Package'): ?>
                                <div class="card-header bg-warning text-dark text-center py-3">
                            <?php endif; ?>
                                <h4 class="card-title mb-0"><?= $package['name'] ?></h4>
                                <div class="package-price mt-2">
                                    <span class="h3 fw-bold">TSh <?= $package['price'] ?></span>
                                </div>
                            </div>
                            
                            <?php $features = getPackageFeatures($package['id']); ?>
                            <div class="card-body p-4">
                                <ul class="list-unstyled">
                                    <?php foreach( $features as $feature ): ?>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i><?php echo $feature; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="card-footer text-center">
                            <?php if ($package['name'] == 'Basic Package'): ?>
                                <a href="booking.php?package=basic" class="btn btn-primary">Choose Package</a>
                            <?php elseif ($package['name'] == 'Premium Package'): ?>
                                <a href="booking.php?package=basic" class="btn btn-success">Choose Package</a>
                            <?php elseif ($package['name'] == 'Luxury Package'): ?>
                                <a href="booking.php?package=basic" class="btn btn-warning">Choose Package</a>
                            <?php endif; ?>
                                
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5 bg-black text-white">
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
