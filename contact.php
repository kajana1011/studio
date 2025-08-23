<?php
session_start();
require_once 'includes/config.php';
$page_title = 'Contact Us';

$success_message = '';
$error_message = '';

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $subject = sanitize_input($_POST['subject']);
    $message = sanitize_input($_POST['message']);

    // Validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } else {
        try {
            // Insert contact inquiry into database
            $stmt = $pdo->prepare("
                INSERT INTO contact_inquiries (name, email, phone, subject, message, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");

            $stmt->execute([$name, $email, $phone, $subject, $message]);

            // Send email notification
            $email_subject = "New Contact Inquiry - Studio Media TZ";
            $email_body = "
                <h2>New Contact Inquiry</h2>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Phone:</strong> $phone</p>
                <p><strong>Subject:</strong> $subject</p>
                <p><strong>Message:</strong></p>
                <p>$message</p>
            ";

            send_email(ADMIN_EMAIL, $email_subject, $email_body);

            $success_message = 'Thank you for your message! We will get back to you within 24 hours.';

            // Clear form data
            $name = $email = $phone = $subject = $message = '';

        } catch(PDOException $e) {
            $error_message = 'Sorry, there was an error sending your message. Please try again later.';
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
                    <h1 class="display-4 fw-bold mb-3">Contact Us</h1>
                    <p class="lead">Get in touch with us - we'd love to hear from you</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section py-5">
        <div class="container">
            <div class="row g-5">

                <!-- Contact Form -->
                <div class="col-lg-8">
                    <div class="contact-form">
                        <h2 class="fw-bold mb-4">Send us a Message</h2>

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

                        <form method="POST" action="contact.php" id="contactForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required value="<?php echo isset($name) ? $name : ''; ?>">
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required value="<?php echo isset($email) ? $email : ''; ?>">
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo isset($phone) ? $phone : ''; ?>" placeholder="+255 XXX XXX XXX">
                                </div>

                                <div class="col-md-6">
                                    <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                    <select class="form-select" id="subject" name="subject" required>
                                        <option value="">Select a subject</option>
                                        <option value="General Inquiry" <?php echo (isset($subject) && $subject == 'General Inquiry') ? 'selected' : ''; ?>>General Inquiry</option>
                                        <option value="Booking Request" <?php echo (isset($subject) && $subject == 'Booking Request') ? 'selected' : ''; ?>>Booking Request</option>
                                        <option value="Service Quote" <?php echo (isset($subject) && $subject == 'Service Quote') ? 'selected' : ''; ?>>Service Quote</option>
                                        <option value="Wedding Package" <?php echo (isset($subject) && $subject == 'Wedding Package') ? 'selected' : ''; ?>>Wedding Package</option>
                                        <option value="Portfolio Question" <?php echo (isset($subject) && $subject == 'Portfolio Question') ? 'selected' : ''; ?>>Portfolio Question</option>
                                        <option value="Technical Support" <?php echo (isset($subject) && $subject == 'Technical Support') ? 'selected' : ''; ?>>Technical Support</option>
                                        <option value="Other" <?php echo (isset($subject) && $subject == 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="message" name="message" rows="6" required placeholder="Tell us about your project, event, or any questions you have..."><?php echo isset($message) ? $message : ''; ?></textarea>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg px-4">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-lg-4">
                    <div class="contact-info">

                        <!-- Contact Details -->
                        <div class="card border-0 shadow mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    Contact Information
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="contact-item mb-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-map-marker-alt text-primary me-3"></i>
                                        <div>
                                            <h6 class="fw-bold mb-0">Address</h6>
                                            <p class="text-muted mb-0">Dar es Salaam, Tanzania</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="contact-item mb-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-phone text-primary me-3"></i>
                                        <div>
                                            <h6 class="fw-bold mb-0">Phone</h6>
                                            <p class="text-muted mb-0">+255 XXX XXX XXX</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="contact-item mb-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-envelope text-primary me-3"></i>
                                        <div>
                                            <h6 class="fw-bold mb-0">Email</h6>
                                            <p class="text-muted mb-0">info@studiomediatz.com</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="contact-item">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-clock text-primary me-3"></i>
                                        <div>
                                            <h6 class="fw-bold mb-0">Business Hours</h6>
                                            <p class="text-muted mb-1">Mon - Fri: 8:00 AM - 6:00 PM</p>
                                            <p class="text-muted mb-1">Saturday: 9:00 AM - 4:00 PM</p>
                                            <p class="text-muted mb-0">Sunday: By appointment</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div class="card border-0 shadow mb-4">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-link me-2"></i>
                                    Quick Actions
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-grid gap-2">
                                    <a href="https://wa.me/255XXXXXXXXX" class="btn btn-success">
                                        <i class="fab fa-whatsapp me-2"></i>
                                        WhatsApp Us
                                    </a>
                                    <a href="booking.php" class="btn btn-primary">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        Book a Session
                                    </a>
                                    <a href="tel:+255XXXXXXXXX" class="btn btn-outline-primary">
                                        <i class="fas fa-phone me-2"></i>
                                        Call Now
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="card border-0 shadow">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-share-alt me-2"></i>
                                    Follow Us
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="social-links">
                                    <a href="#" class="btn btn-outline-primary me-2 mb-2">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-danger me-2 mb-2">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-info me-2 mb-2">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-danger mb-2">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                </div>
                                <p class="text-muted small mt-3 mb-0">Follow us on social media for the latest updates and behind-the-scenes content!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Google Maps Section -->
    <section class="map-section py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-4">
                    <h2 class="fw-bold">Find Us on the Map</h2>
                    <p class="text-muted">We're located in the heart of Dar es Salaam</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="map-container rounded shadow">
                        <!-- Google Maps Embed -->
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.1956733725825!2d39.273499!3d-6.8162!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x185c4c9c6b1b1b1b%3A0x1b1b1b1b1b1b1b1b!2sDar%20es%20Salaam%2C%20Tanzania!5e0!3m2!1sen!2stz!4v1679000000000!5m2!1sen!2stz"
                            width="100%"
                            height="400"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            class="rounded">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold">Frequently Asked Questions</h2>
                    <p class="text-muted">Quick answers to common questions</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    How far in advance should I book?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We recommend booking at least 2-4 weeks in advance, especially for weddings and events during peak seasons. However, we can sometimes accommodate last-minute bookings based on availability.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    What's included in your packages?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Our packages include professional photography/videography, edited photos, online gallery access, and delivery of final files. Specific inclusions vary by package - check our services page for detailed information.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Do you travel outside Dar es Salaam?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes! We travel throughout Tanzania for weddings and events. Travel costs may apply depending on the location and distance from Dar es Salaam.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    How long does editing take?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Typical turnaround time is 1-2 weeks for photos and 2-4 weeks for videos, depending on the scope of work. Rush editing is available for an additional fee.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
