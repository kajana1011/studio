<?php
session_start();
require_once 'includes/config.php';
require_once 'helpers/functions.php';
include 'includes/header.php';
$page_title = 'Portfolio';

$portfolios = getPortfolios(); // Fetch portfolio items from the database
$pcategories = getPortfolioCategories();

$wportfolios = getPortfolioByCategory('wedding');
$potraitportfolios = getPortfolioByCategory('portrait');
$eportfolios = getPortfolioByCategory('event');
$cportfolios = getPortfolioByCategory('commercial');

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
    <section class="portfolio-filter bg-light">
        <div class="row">
            <div class="col-12">
                <div class="filter-buttons text-center py-2 mb-2">
                    <a class="btn btn-otline-none text-secondary" href="#all" onclick="showSection('all')">
                        All
                    </a>
                    <a class="btn text-secondary" href="#wedding" onclick="showSection('wedding')">
                        wedding
                    </a>
                    <a class="btn text-secondary" href="#portrait" onclick="showSection('portrait')">
                        Portrait
                    </a>
                    <a class="btn text-secondary" href="#event" onclick="showSection('event')">
                        event
                    </a>
                    <a class="btn text-secondary" href="#commercial" onclick="showSection('commercial')">
                        Commercial
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- All portfolio Section -->
    <section id="all-section" class="admin-section">
        <div class="container mb-4">
            <div class="row g-4" id="portfolio-grid">
                <?php foreach ($portfolios as $item): ?>
                    <div class="col-lg-4 col-md-6 portfolio-item" data-category="<?= htmlspecialchars($item['category']) ?>">
                        <div class="portfolio-card">
                            <img src="<?= htmlspecialchars($item['file_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="img-fluid rounded">
                            <div class="portfolio-overlay">
                                <div class="portfolio-content">
                                    <h5 class="text-white fw-bold"><?= htmlspecialchars($item['title']) ?></h5>
                                    <p class="text-light mb-3"><?= htmlspecialchars($item['description']) ?></p>
                                    <?php if ($item['media_type'] === 'video'): ?>        
                                        <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#videoModal"
                                                data-title="<?= htmlspecialchars($item['title']) ?>"
                                                data-description="<?= htmlspecialchars($item['description']) ?>"
                                                data-video="<?= htmlspecialchars($item['file_path']) ?>">
                                            Watch Video
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#portfolioModal"
                                                data-title="<?= htmlspecialchars($item['title']) ?>"
                                                data-description="<?= htmlspecialchars($item['description']) ?>"
                                                data-image="<?= htmlspecialchars($item['file_path']) ?>">
                                            View Details                                
                                        </button>
                                    <?php endif; ?>         
                                </div>
                            </div>          
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>


     <!-- wedding portfolio Section -->
    <section id="wedding-section" class="admin-section" style="display: none">
        <div class="container mb-4">
            <div class="row g-4" id="portfolio-grid">
                <?php foreach ($wportfolios as $item): ?>
                    <div class="col-lg-4 col-md-6 portfolio-item" data-category="<?= htmlspecialchars($item['category']) ?>">
                        <div class="portfolio-card">
                            <img src="<?= htmlspecialchars($item['file_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="img-fluid rounded">
                            <div class="portfolio-overlay">
                                <div class="portfolio-content">
                                    <h5 class="text-white fw-bold"><?= htmlspecialchars($item['title']) ?></h5>
                                    <p class="text-light mb-3"><?= htmlspecialchars($item['description']) ?></p>
                                    <?php if ($item['media_type'] === 'video'): ?>        
                                        <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#videoModal"
                                                data-title="<?= htmlspecialchars($item['title']) ?>"
                                                data-description="<?= htmlspecialchars($item['description']) ?>"
                                                data-video="<?= htmlspecialchars($item['file_path']) ?>">
                                            Watch Video
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#portfolioModal"
                                                data-title="<?= htmlspecialchars($item['title']) ?>"
                                                data-description="<?= htmlspecialchars($item['description']) ?>"
                                                data-image="<?= htmlspecialchars($item['file_path']) ?>">
                                            View Details                                
                                        </button>
                                    <?php endif; ?>         
                                </div>
                            </div>          
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

     <!-- Potrait portfolio Section -->
    <section id="portrait-section" class="admin-section" style="display: none">
        <div class="container mb-4">
            <div class="row g-4" id="portfolio-grid">
                <?php foreach ($potraitportfolios as $item): ?>
                    <div class="col-lg-4 col-md-6 portfolio-item" data-category="<?= htmlspecialchars($item['category']) ?>">
                        <div class="portfolio-card">
                            <img src="<?= htmlspecialchars($item['file_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="img-fluid rounded">
                            <div class="portfolio-overlay">
                                <div class="portfolio-content">
                                    <h5 class="text-white fw-bold"><?= htmlspecialchars($item['title']) ?></h5>
                                    <p class="text-light mb-3"><?= htmlspecialchars($item['description']) ?></p>
                                    <?php if ($item['media_type'] === 'video'): ?>        
                                        <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#videoModal"
                                                data-title="<?= htmlspecialchars($item['title']) ?>"
                                                data-description="<?= htmlspecialchars($item['description']) ?>"
                                                data-video="<?= htmlspecialchars($item['file_path']) ?>">
                                            Watch Video
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#portfolioModal"
                                                data-title="<?= htmlspecialchars($item['title']) ?>"
                                                data-description="<?= htmlspecialchars($item['description']) ?>"
                                                data-image="<?= htmlspecialchars($item['file_path']) ?>">
                                            View Details                                
                                        </button>
                                    <?php endif; ?>         
                                </div>
                            </div>          
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

     <!-- Event portfolio Section -->
    <section id="event-section" class="admin-section" style="display: none">
        <div class="container mb-4">
            <div class="row g-4" id="portfolio-grid">
                <?php foreach ($eportfolios as $item): ?>
                    <div class="col-lg-4 col-md-6 portfolio-item" data-category="<?= htmlspecialchars($item['category']) ?>">
                        <div class="portfolio-card">
                            <img src="<?= htmlspecialchars($item['file_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="img-fluid rounded">
                            <div class="portfolio-overlay">
                                <div class="portfolio-content">
                                    <h5 class="text-white fw-bold"><?= htmlspecialchars($item['title']) ?></h5>
                                    <p class="text-light mb-3"><?= htmlspecialchars($item['description']) ?></p>
                                    <?php if ($item['media_type'] === 'video'): ?>        
                                        <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#videoModal"
                                                data-title="<?= htmlspecialchars($item['title']) ?>"
                                                data-description="<?= htmlspecialchars($item['description']) ?>"
                                                data-video="<?= htmlspecialchars($item['file_path']) ?>">
                                            Watch Video
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#portfolioModal"
                                                data-title="<?= htmlspecialchars($item['title']) ?>"
                                                data-description="<?= htmlspecialchars($item['description']) ?>"
                                                data-image="<?= htmlspecialchars($item['file_path']) ?>">
                                            View Details                                
                                        </button>
                                    <?php endif; ?>         
                                </div>
                            </div>          
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>


     <!-- All portfolio Section -->
    <section id="commercial-section" class="admin-section" style="display: none">
        <div class="container mb-4">
            <div class="row g-4" id="portfolio-grid">
                <?php foreach ($cportfolios as $item): ?>
                    <div class="col-lg-4 col-md-6 portfolio-item" data-category="<?= htmlspecialchars($item['category']) ?>">
                        <div class="portfolio-card">
                            <img src="<?= htmlspecialchars($item['file_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="img-fluid rounded">
                            <div class="portfolio-overlay">
                                <div class="portfolio-content">
                                    <h5 class="text-white fw-bold"><?= htmlspecialchars($item['title']) ?></h5>
                                    <p class="text-light mb-3"><?= htmlspecialchars($item['description']) ?></p>
                                    <?php if ($item['media_type'] === 'video'): ?>        
                                        <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#videoModal"
                                                data-title="<?= htmlspecialchars($item['title']) ?>"
                                                data-description="<?= htmlspecialchars($item['description']) ?>"
                                                data-video="<?= htmlspecialchars($item['file_path']) ?>">
                                            Watch Video
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#portfolioModal"
                                                data-title="<?= htmlspecialchars($item['title']) ?>"
                                                data-description="<?= htmlspecialchars($item['description']) ?>"
                                                data-image="<?= htmlspecialchars($item['file_path']) ?>">
                                            View Details                                
                                        </button>
                                    <?php endif; ?>         
                                </div>
                            </div>          
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>


    <!-- CTA section -->
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

</main>

    <!-- Bootstrap JS -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

    <script src="assets/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    
    
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

    </script>

<?php include 'includes/footer.php'; ?>
