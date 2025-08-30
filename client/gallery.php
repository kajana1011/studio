<?php
session_start();
require_once '../includes/config.php';

$error_message = '';
$access_granted = false;
$gallery = null;
$media_files = [];

// Check access via session (logged in client) or access code
if (is_client_logged_in()) {
    // Client is logged in - show their galleries
    $client_id = $_SESSION['id'];

    try {
        // Get client's galleries
        $stmt = $pdo->prepare("
            SELECT cg.*, COUNT(cm.id) as media_count
            FROM client_galleries cg
            LEFT JOIN client_media cm ON cg.id = cm.gallery_id
            WHERE cg.client_id = ? AND (cg.expiry_date IS NULL OR cg.expiry_date > CURDATE())
            GROUP BY cg.id
            ORDER BY cg.created_at DESC
        ");
        $stmt->execute([$client_id]);
        $client_galleries = $stmt->fetchAll();

        $access_granted = true;

    } catch(PDOException $e) {
        $error_message = 'Unable to load your galleries. Please try again later.';
    }

} elseif (isset($_GET['code']) && !empty($_GET['code'])) {
    // Access via code
    $access_code = sanitize_input($_GET['code']);

    try {
        // Check if access code is valid
        $stmt = $pdo->prepare("
            SELECT * FROM client_galleries
            WHERE access_code = ? AND (expiry_date IS NULL OR expiry_date > CURDATE())
        ");
        $stmt->execute([$access_code]);
        $gallery = $stmt->fetch();

        if ($gallery) {
            // Get media files for this gallery
            $stmt = $pdo->prepare("
                SELECT * FROM client_media
                WHERE gallery_id = ?
                ORDER BY created_at DESC
            ");
            $stmt->execute([$gallery['id']]);
            $media_files = $stmt->fetchAll();

            $access_granted = true;

        } else {
            $error_message = 'Invalid or expired access code. Please check your code and try again.';
        }

    } catch(PDOException $e) {
        $error_message = 'Unable to access gallery. Please try again later.';
    }

} else {
    // No access method provided
    redirect('../auth/login.php');
}

// Handle gallery selection for logged-in clients
if (is_client_logged_in() && isset($_GET['gallery_id'])) {
    $gallery_id = (int)$_GET['gallery_id'];

    try {
        // Verify client owns this gallery
        $stmt = $pdo->prepare("
            SELECT * FROM client_galleries
            WHERE id = ? AND client_id = ? AND (expiry_date IS NULL OR expiry_date > CURDATE())
        ");
        $stmt->execute([$gallery_id, $_SESSION['client_id']]);
        $gallery = $stmt->fetch();

        if ($gallery) {
            // Get media files for this gallery
            $stmt = $pdo->prepare("
                SELECT * FROM client_media
                WHERE gallery_id = ?
                ORDER BY created_at DESC
            ");
            $stmt->execute([$gallery['id']]);
            $media_files = $stmt->fetchAll();
        }

    } catch(PDOException $e) {
        $error_message = 'Unable to load gallery. Please try again later.';
    }
}

// Handle download tracking
if (isset($_GET['download']) && $gallery) {
    $media_id = (int)$_GET['download'];

    try {
        // Update download count
        $stmt = $pdo->prepare("
            UPDATE client_media
            SET download_count = download_count + 1
            WHERE id = ? AND gallery_id = ?
        ");
        $stmt->execute([$media_id, $gallery['id']]);

        // Get file info for download
        $stmt = $pdo->prepare("SELECT * FROM client_media WHERE id = ? AND gallery_id = ?");
        $stmt->execute([$media_id, $gallery['id']]);
        $file = $stmt->fetch();

        if ($file && file_exists($file['file_path'])) {
            // Force download
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file['original_name'] . '"');
            header('Content-Length: ' . filesize($file['file_path']));
            readfile($file['file_path']);
            exit;
        }

    } catch(PDOException $e) {
        $error_message = 'Download failed. Please try again.';
    }
}

$page_title = 'Client Gallery';
include 'includes/header.php';
?>

<main>
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <?php if (is_client_logged_in()): ?>
                        <h1 class="display-4 fw-bold mb-3">Welcome, <?php echo $_SESSION['client_name']; ?>!</h1>
                        <p class="lead">Your personal photo and video galleries</p>
                    <?php else: ?>
                        <h1 class="display-4 fw-bold mb-3">Your Gallery</h1>
                        <p class="lead">View and download your photos and videos</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php if (!$access_granted): ?>
        <!-- Access Denied -->
        <section class="access-denied py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>

                        <div class="card border-0 shadow">
                            <div class="card-body p-5">
                                <i class="fas fa-lock fa-4x text-muted mb-4"></i>
                                <h3 class="fw-bold mb-3">Access Required</h3>
                                <p class="text-muted mb-4">You need to login or provide a valid access code to view your gallery.</p>

                                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                                    <a href="client-login.php" class="btn btn-primary">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        Login to Account
                                    </a>
                                    <a href="contact.php" class="btn btn-outline-primary">
                                        <i class="fas fa-envelope me-2"></i>
                                        Contact Us
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    <?php elseif (is_client_logged_in() && !$gallery): ?>
        <!-- Client Dashboard - Gallery List -->
        <section class="client-dashboard py-5">
            <div class="container">

                <!-- Client Info -->
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <h2 class="fw-bold">Your Galleries</h2>
                        <p class="text-muted">Access your photos and videos from recent sessions</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="includes/logout.php" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Logout
                        </a>
                    </div>
                </div>

                <?php if (empty($client_galleries)): ?>
                    <!-- No Galleries -->
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="card border-0 shadow">
                                <div class="card-body p-5">
                                    <i class="fas fa-images fa-4x text-muted mb-4"></i>
                                    <h3 class="fw-bold mb-3">No Galleries Yet</h3>
                                    <p class="text-muted mb-4">Your galleries will appear here after your photo sessions are processed and uploaded.</p>

                                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                                        <a href="booking.php" class="btn btn-primary">
                                            <i class="fas fa-calendar-alt me-2"></i>
                                            Book a Session
                                        </a>
                                        <a href="contact.php" class="btn btn-outline-primary">
                                            <i class="fas fa-envelope me-2"></i>
                                            Contact Us
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Gallery Grid -->
                    <div class="row g-4">
                        <?php foreach ($client_galleries as $client_gallery): ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="gallery-card card h-100 border-0 shadow">
                                    <div class="card-body p-4">
                                        <div class="gallery-icon text-center mb-3">
                                            <i class="fas fa-images fa-3x text-primary"></i>
                                        </div>

                                        <h5 class="card-title fw-bold text-center"><?php echo htmlspecialchars($client_gallery['gallery_name']); ?></h5>

                                        <?php if ($client_gallery['description']): ?>
                                            <p class="text-muted small"><?php echo htmlspecialchars($client_gallery['description']); ?></p>
                                        <?php endif; ?>

                                        <div class="gallery-stats mb-3">
                                            <div class="d-flex justify-content-between text-muted small">
                                                <span><i class="fas fa-camera me-1"></i><?php echo $client_gallery['media_count']; ?> files</span>
                                                <span><i class="fas fa-calendar me-1"></i><?php echo date('M j, Y', strtotime($client_gallery['created_at'])); ?></span>
                                            </div>
                                        </div>

                                        <a href="client-gallery.php?gallery_id=<?php echo $client_gallery['id']; ?>" class="btn btn-primary w-100">
                                            <i class="fas fa-eye me-2"></i>
                                            View Gallery
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

    <?php else: ?>
        <!-- Gallery View -->
        <section class="gallery-view py-5">
            <div class="container">

                <!-- Gallery Header -->
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <h2 class="fw-bold"><?php echo htmlspecialchars($gallery['gallery_name']); ?></h2>
                        <?php if ($gallery['description']): ?>
                            <p class="text-muted"><?php echo htmlspecialchars($gallery['description']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <?php if (is_client_logged_in()): ?>
                            <a href="client-gallery.php" class="btn btn-outline-primary me-2">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Galleries
                            </a>
                            <a href="includes/logout.php" class="btn btn-outline-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Logout
                            </a>
                        <?php else: ?>
                            <button onclick="downloadAll()" class="btn btn-success me-2">
                                <i class="fas fa-download me-2"></i>
                                Download All
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (empty($media_files)): ?>
                    <!-- No Media -->
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="card border-0 shadow">
                                <div class="card-body p-5">
                                    <i class="fas fa-photo-video fa-4x text-muted mb-4"></i>
                                    <h3 class="fw-bold mb-3">Gallery is Being Prepared</h3>
                                    <p class="text-muted mb-4">Your photos and videos are currently being processed and will be available soon.</p>
                                    <a href="contact.php" class="btn btn-primary">
                                        <i class="fas fa-envelope me-2"></i>
                                        Contact Us for Updates
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Media Grid -->
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted"><?php echo count($media_files); ?> files in this gallery</span>
                                </div>
                                <div class="view-options">
                                    <button class="btn btn-outline-secondary btn-sm active" onclick="setView('grid')" id="gridView">
                                        <i class="fas fa-th"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="setView('list')" id="listView">
                                        <i class="fas fa-list"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="media-container" id="mediaContainer">
                        <div class="row g-3" id="mediaGrid">
                            <?php foreach ($media_files as $media): ?>
                                <div class="col-lg-3 col-md-4 col-6 media-item">
                                    <div class="media-card card border-0 shadow-sm">
                                        <div class="media-thumbnail">
                                            <?php if ($media['file_type'] == 'image'): ?>
                                                <img src="<?php echo htmlspecialchars($media['file_path']); ?>"
                                                     alt="<?php echo htmlspecialchars($media['original_name']); ?>"
                                                     class="img-fluid"
                                                     onclick="openLightbox('<?php echo htmlspecialchars($media['file_path']); ?>', '<?php echo htmlspecialchars($media['original_name']); ?>')">
                                            <?php else: ?>
                                                <div class="video-thumbnail" onclick="openVideo('<?php echo htmlspecialchars($media['file_path']); ?>')">
                                                    <i class="fas fa-play-circle fa-3x text-white"></i>
                                                    <video width="100%" height="200" style="object-fit: cover;">
                                                        <source src="<?php echo htmlspecialchars($media['file_path']); ?>" type="<?php echo htmlspecialchars($media['mime_type']); ?>">
                                                    </video>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted text-truncate"><?php echo htmlspecialchars($media['original_name']); ?></small>
                                                <div class="media-actions">
                                                    <a href="client-gallery.php?download=<?php echo $media['id']; ?>&<?php echo isset($_GET['code']) ? 'code=' . urlencode($_GET['code']) : 'gallery_id=' . $gallery['id']; ?>"
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
</main>

<!-- Lightbox Modal -->
<div class="modal fade" id="lightboxModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="lightboxTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="lightboxImage" src="" alt="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Video Player</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <video id="videoPlayer" width="100%" height="600" controls>
                    <source src="" type="">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>
</div>

<style>
.media-thumbnail {
    position: relative;
    height: 200px;
    overflow: hidden;
    cursor: pointer;
}

.media-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.media-thumbnail:hover img {
    transform: scale(1.05);
}

.video-thumbnail {
    position: relative;
    height: 200px;
    background: #000;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.video-thumbnail video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.7;
}

.video-thumbnail i {
    position: relative;
    z-index: 2;
}

.gallery-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.gallery-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.media-card {
    transition: transform 0.3s ease;
}

.media-card:hover {
    transform: translateY(-2px);
}
</style>

<script>
function openLightbox(imageSrc, title) {
    document.getElementById('lightboxImage').src = imageSrc;
    document.getElementById('lightboxTitle').textContent = title;
    new bootstrap.Modal(document.getElementById('lightboxModal')).show();
}

function openVideo(videoSrc) {
    const video = document.getElementById('videoPlayer');
    video.querySelector('source').src = videoSrc;
    video.load();
    new bootstrap.Modal(document.getElementById('videoModal')).show();
}

function setView(viewType) {
    const gridBtn = document.getElementById('gridView');
    const listBtn = document.getElementById('listView');
    const container = document.getElementById('mediaGrid');

    if (viewType === 'grid') {
        gridBtn.classList.add('active');
        listBtn.classList.remove('active');
        container.className = 'row g-3';
        container.querySelectorAll('.media-item').forEach(item => {
            item.className = 'col-lg-3 col-md-4 col-6 media-item';
        });
    } else {
        listBtn.classList.add('active');
        gridBtn.classList.remove('active');
        container.className = 'row g-2';
        container.querySelectorAll('.media-item').forEach(item => {
            item.className = 'col-12 media-item';
        });
    }
}

function downloadAll() {
    if (confirm('This will download all files in the gallery. Continue?')) {
        // Implementation for bulk download would go here
        alert('Bulk download feature coming soon! Please download files individually for now.');
    }
}

// Auto-close video modal when hidden
document.getElementById('videoModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('videoPlayer').pause();
});
</script>

<?php include 'includes/footer.php'; ?>
