<?php
session_start();
require_once 'includes/config.php';
require_once 'helpers/functions.php';
include 'includes/header.php';
$page_title = 'Portfolio';

$portfolios = getPortfolios(); // Fetch portfolio items from the database


?>

<main>
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold mb-3">Our Portfolio</h1>
                    <p class="lead">Capturing moments, creating memories</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Filter -->
    <section class="portfolio-filter py-4 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="filter-buttons text-center">
                        <button class="btn btn-outline-primary active me-2 mb-2" data-filter="all">All Work</button>
                        <button class="btn btn-outline-primary me-2 mb-2" data-filter="wedding">Weddings</button>
                        <button class="btn btn-outline-primary me-2 mb-2" data-filter="portrait">Portraits</button>
                        <button class="btn btn-outline-primary me-2 mb-2" data-filter="event">Events</button>
                        <button class="btn btn-outline-primary me-2 mb-2" data-filter="commercial">Commercial</button>
                        <button class="btn btn-outline-primary me-2 mb-2" data-filter="video">Videos</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Gallery -->
    <section class="portfolio-gallery py-5">
        <div class="container">
            <div class="row g-4" id="portfolio-grid">

                <!-- dynamic content from database -->
                 <?php foreach ($portfolios as $item): ?>
                
                    <div class="col-lg-4 col-md-6 portfolio-item" data-category="<?= htmlspecialchars($item['category']) ?>">
                        <div class="portfolio-card">
                            <img src="<?= htmlspecialchars($item['file_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="img-fluid rounded">
                            <div class="portfolio-overlay">
                                <div class="portfolio-content">
                                    <h5 class="text-white fw-bold"><?= htmlspecialchars($item['title']) ?></h5>
                                    <p class="text-light mb-3"><?= htmlspecialchars($item['short_description']) ?></p>
                                    <?php if ($item['type'] === 'video'): ?>        
                                        <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#videoModal"
                                                data-title="<?= htmlspecialchars($item['title']) ?>"
                                                data-description="<?= htmlspecialchars($item['description']) ?>"
                                                data-video="<?= htmlspecialchars($item['video_url']) ?>">
                                            Watch Video
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#portfolioModal"
                                                data-title="<?= htmlspecialchars($item['title']) ?>"
                                                data-description="<?= htmlspecialchars($item['description']) ?>"
                                                data-image="<?= htmlspecialchars($item['image_url']) ?>">
                                            View Details                                
                                        </button>
                                    <?php endif; ?>         
                                </div>
                            </div>          
                        </div>
                    </div>
                <?php endforeach; ?>
                <!-- Static content as fallback or example -->
                <div class="col-lg-4 col-md-6 portfolio-item" data-category="wedding">
                    <div class="portfolio-card">
                        <img src="https://images.unsplash.com/photo-1519741497674-611481863552?w=500&h=400&fit=crop" alt="Wedding Photography" class="img-fluid rounded">
                        <div class="portfolio-overlay">
                            <div class="portfolio-content">
                                <h5 class="text-white fw-bold">Sarah & John's Wedding</h5>
                                <p class="text-light mb-3">Elegant beach wedding ceremony</p>
                                <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#portfolioModal"
                                        data-title="Sarah & John's Wedding"
                                        data-description="A beautiful beach wedding in Dar es Salaam with stunning sunset views."
                                        data-image="https://images.unsplash.com/photo-1519741497674-611481863552?w=800&h=600&fit=crop">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wedding Photos -->
                <div class="col-lg-4 col-md-6 portfolio-item" data-category="wedding">
                    <div class="portfolio-card">
                        <img src="https://images.unsplash.com/photo-1519741497674-611481863552?w=500&h=400&fit=crop" alt="Wedding Photography" class="img-fluid rounded">
                        <div class="portfolio-overlay">
                            <div class="portfolio-content">
                                <h5 class="text-white fw-bold">Sarah & John's Wedding</h5>
                                <p class="text-light mb-3">Elegant beach wedding ceremony</p>
                                <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#portfolioModal"
                                        data-title="Sarah & John's Wedding"
                                        data-description="A beautiful beach wedding in Dar es Salaam with stunning sunset views."
                                        data-image="https://images.unsplash.com/photo-1519741497674-611481863552?w=800&h=600&fit=crop">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 portfolio-item" data-category="wedding">
                    <div class="portfolio-card">
                        <img src="https://images.unsplash.com/photo-1606216794074-735e91aa2c92?w=500&h=400&fit=crop" alt="Wedding Photography" class="img-fluid rounded">
                        <div class="portfolio-overlay">
                            <div class="portfolio-content">
                                <h5 class="text-white fw-bold">Grace & Michael's Wedding</h5>
                                <p class="text-light mb-3">Traditional church ceremony</p>
                                <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#portfolioModal"
                                        data-title="Grace & Michael's Wedding"
                                        data-description="Beautiful traditional wedding ceremony with cultural elements."
                                        data-image="https://images.unsplash.com/photo-1606216794074-735e91aa2c92?w=800&h=600&fit=crop">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Portrait Photos -->
                <div class="col-lg-4 col-md-6 portfolio-item" data-category="portrait">
                    <div class="portfolio-card">
                        <img src="https://images.unsplash.com/photo-1554151228-14d9def656e4?w=500&h=400&fit=crop" alt="Portrait Photography" class="img-fluid rounded">
                        <div class="portfolio-overlay">
                            <div class="portfolio-content">
                                <h5 class="text-white fw-bold">Professional Headshots</h5>
                                <p class="text-light mb-3">Corporate portrait session</p>
                                <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#portfolioModal"
                                        data-title="Professional Headshots"
                                        data-description="High-quality corporate headshots for business professionals."
                                        data-image="https://images.unsplash.com/photo-1554151228-14d9def656e4?w=800&h=600&fit=crop">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 portfolio-item" data-category="portrait">
                    <div class="portfolio-card">
                        <img src="https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=500&h=400&fit=crop" alt="Family Portrait" class="img-fluid rounded">
                        <div class="portfolio-overlay">
                            <div class="portfolio-content">
                                <h5 class="text-white fw-bold">Family Portrait Session</h5>
                                <p class="text-light mb-3">Outdoor family photography</p>
                                <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#portfolioModal"
                                        data-title="Family Portrait Session"
                                        data-description="Beautiful outdoor family portraits capturing precious moments."
                                        data-image="https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=800&h=600&fit=crop">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Event Photos -->
                <div class="col-lg-4 col-md-6 portfolio-item" data-category="event">
                    <div class="portfolio-card">
                        <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?w=500&h=400&fit=crop" alt="Corporate Event" class="img-fluid rounded">
                        <div class="portfolio-overlay">
                            <div class="portfolio-content">
                                <h5 class="text-white fw-bold">Corporate Conference</h5>
                                <p class="text-light mb-3">Business event coverage</p>
                                <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#portfolioModal"
                                        data-title="Corporate Conference"
                                        data-description="Professional coverage of corporate events and conferences."
                                        data-image="https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&h=600&fit=crop">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 portfolio-item" data-category="event">
                    <div class="portfolio-card">
                        <img src="https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=500&h=400&fit=crop" alt="Birthday Party" class="img-fluid rounded">
                        <div class="portfolio-overlay">
                            <div class="portfolio-content">
                                <h5 class="text-white fw-bold">Birthday Celebration</h5>
                                <p class="text-light mb-3">Private party coverage</p>
                                <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#portfolioModal"
                                        data-title="Birthday Celebration"
                                        data-description="Fun and vibrant birthday party photography."
                                        data-image="https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=800&h=600&fit=crop">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commercial Photos -->
                <div class="col-lg-4 col-md-6 portfolio-item" data-category="commercial">
                    <div class="portfolio-card">
                        <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=500&h=400&fit=crop" alt="Product Photography" class="img-fluid rounded">
                        <div class="portfolio-overlay">
                            <div class="portfolio-content">
                                <h5 class="text-white fw-bold">Product Photography</h5>
                                <p class="text-light mb-3">Commercial product shots</p>
                                <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#portfolioModal"
                                        data-title="Product Photography"
                                        data-description="High-quality product photography for e-commerce and marketing."
                                        data-image="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&h=600&fit=crop">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 portfolio-item" data-category="commercial">
                    <div class="portfolio-card">
                        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=500&h=400&fit=crop" alt="Business Photography" class="img-fluid rounded">
                        <div class="portfolio-overlay">
                            <div class="portfolio-content">
                                <h5 class="text-white fw-bold">Business Photography</h5>
                                <p class="text-light mb-3">Office and workplace shots</p>
                                <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#portfolioModal"
                                        data-title="Business Photography"
                                        data-description="Professional business environment photography."
                                        data-image="https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&h=600&fit=crop">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video Portfolio -->
                <div class="col-lg-4 col-md-6 portfolio-item" data-category="video">
                    <div class="portfolio-card video-card">
                        <img src="https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=500&h=400&fit=crop" alt="Wedding Video" class="img-fluid rounded">
                        <div class="video-play-button">
                            <i class="fas fa-play"></i>
                        </div>
                        <div class="portfolio-overlay">
                            <div class="portfolio-content">
                                <h5 class="text-white fw-bold">Wedding Highlight Reel</h5>
                                <p class="text-light mb-3">3-minute wedding video</p>
                                <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#videoModal"
                                        data-title="Wedding Highlight Reel"
                                        data-description="Beautiful wedding highlights with music and professional editing."
                                        data-video="https://www.youtube.com/embed/dQw4w9WgXcQ">
                                    Watch Video
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 portfolio-item" data-category="video">
                    <div class="portfolio-card video-card">
                        <img src="https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=500&h=400&fit=crop" alt="Corporate Video" class="img-fluid rounded">
                        <div class="video-play-button">
                            <i class="fas fa-play"></i>
                        </div>
                        <div class="portfolio-overlay">
                            <div class="portfolio-content">
                                <h5 class="text-white fw-bold">Corporate Promo</h5>
                                <p class="text-light mb-3">Business promotional video</p>
                                <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#videoModal"
                                        data-title="Corporate Promotional Video"
                                        data-description="Professional corporate promotional video with interviews and b-roll."
                                        data-video="https://www.youtube.com/embed/dQw4w9WgXcQ">
                                    Watch Video
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Load More Button -->
            <div class="text-center mt-5">
                <button class="btn btn-outline-primary btn-lg">Load More Work</button>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5 bg-black text-white">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="display-6 fw-bold mb-3">Like What You See?</h2>
                    <p class="lead mb-4">Let's create something beautiful together</p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="booking.php" class="btn btn-primary btn-lg px-4">Book a Session</a>
                        <a href="contact.php" class="btn btn-outline-light btn-lg px-4">Get in Touch</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Portfolio Modal -->
<div class="modal fade" id="portfolioModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="portfolioModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <img id="portfolioModalImage" src="" alt="" class="img-fluid rounded mb-3">
                <p id="portfolioModalDescription"></p>
            </div>
            <div class="modal-footer">
                <a href="booking.php" class="btn btn-primary">Book Similar Session</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="ratio ratio-16x9">
                    <iframe id="videoModalFrame" src="" allowfullscreen></iframe>
                </div>
                <p id="videoModalDescription" class="mt-3"></p>
            </div>
            <div class="modal-footer">
                <a href="booking.php" class="btn btn-primary">Book Video Service</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
