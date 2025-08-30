<?php
    require_once 'includes/config.php';

 // functions to retrieve services from the database
    function getServices() {
        global $pdo; // $pdo is the database connection in config.php
        $query = "SELECT * FROM services ORDER BY id DESC";
        $result = $pdo->query($query);
        if (!$result) {
            return false; // Return false if the query fails
        }
        $services = $result->fetchAll(PDO::FETCH_ASSOC);
        return $services;
    }

    function getServiceById($id) {
        global $pdo;
        $query = "SELECT * FROM services WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getPhotographyServices() {
        global $pdo;
        $query = "SELECT * FROM services WHERE category = 'photography' ORDER BY id DESC";
        $result = $pdo->query($query);
        if (!$result) {
            return false; // Return false if the query fails
        }
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }   

    function getVideoServices() {
        global $pdo;
        $query = "SELECT * FROM services WHERE category = 'videography' ORDER BY id DESC";
        $result = $pdo->query($query);
        if (!$result) {
            return false; // Return false if the query fails
        }
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    function getEditingServices() {
        global $pdo;
        $query = "SELECT * FROM services WHERE category = 'editing' ORDER BY id DESC";
        $result = $pdo->query($query);
        if (!$result) {
            return false; // Return false if the query fails
        }
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    function getPrintingServices() {
        global $pdo;
        $query = "SELECT * FROM services WHERE category = 'printing' ORDER BY id DESC";
        $result = $pdo->query($query);
        if (!$result) {
            return false; // Return false if the query fails
        }
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    function getServicePackages() {
        global $pdo;
        $query = "SELECT * FROM packages ORDER BY id ASC";
        $result = $pdo->query($query);
        if (!$result) {
            return false; // Return false if the query fails
        }
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    function getServicePackageById($id) {
        global $pdo;
        $query = "SELECT * FROM packages WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getPackageFeatures($packageId) {
        global $pdo;
        $query = "SELECT features FROM packages WHERE id = :package_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':package_id', $packageId, PDO::PARAM_INT);
        $stmt->execute();
        $Packagefeatures = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get the features string
        $featuresJson = $Packagefeatures[0]['features'];
        
        // Decode JSON into PHP array
        $features = json_decode($featuresJson, true);
        return $features;
    }

    function getFeaturedTestimonials() {
        global $pdo;
        $query = "SELECT * FROM testimonials WHERE is_featured = 1 ORDER BY id DESC LIMIT 3";
        $result = $pdo->query($query);
        if (!$result) {
            return false; // Return false if the query fails
        }
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    function getRemainingTestimonials() {
        global $pdo;

        // Get IDs of the first 3 featured ones
        $featuredQuery = "SELECT id FROM testimonials WHERE is_featured = 1 ORDER BY id DESC LIMIT 3";
        $featuredIds = $pdo->query($featuredQuery)->fetchAll(PDO::FETCH_COLUMN);

        if (empty($featuredIds)) {
            return []; // No featured testimonials, return empty
        }

        // Fetch all others (unfeatured + remaining featured)
        $placeholders = implode(',', array_fill(0, count($featuredIds), '?'));
        $query = "SELECT * FROM testimonials 
                WHERE id NOT IN ($placeholders) 
                ORDER BY id DESC limit 6";

        $stmt = $pdo->prepare($query);
        $stmt->execute($featuredIds);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        
    
    function getInitials($name) {
        $parts = explode(' ', $name);
        $initials = '';

        foreach ($parts as $p) {
            $initials .= strtoupper($p[0]); // first letter of each part
        }

        return substr($initials, 0, 2); // keep only 2 characters (e.g. RK)
    }

    function getProjectsCount() {
        global $pdo;
        $query = "SELECT COUNT(*) as count FROM bookings";
        $result = $pdo->query($query);
        if ($result) {
            $data = $result->fetch(PDO::FETCH_ASSOC);
            return $data['count'];
        }
        return 0;
    }

    function getClientsCount() {
        global $pdo;
        $query = "SELECT COUNT(*) as count FROM testimonials";
        $result = $pdo->query($query);
        if ($result) {
            $data = $result->fetch(PDO::FETCH_ASSOC);
            return $data['count'];
        }
        return 0;
    }

    function getAverageRating() {
        global $pdo;
        $query = "SELECT AVG(rating) as average FROM testimonials";
        $result = $pdo->query($query);
        if ($result) {
            $data = $result->fetch(PDO::FETCH_ASSOC);
            return round($data['average'], 1); // Round to 1 decimal place
        }
        return 0;
    }

    function getYearsOfExperience() {
        $startYear = 2018; // Example start year
        $currentYear = date("Y");   
        return $currentYear - $startYear;
    }
    
    function sanitizeInput($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
    
    function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    function isValidPhone($phone) {
        // Basic validation: digits, spaces, +, -, ()
        return preg_match('/^[0-9+\-\s\(\)]+$/', $phone);
    }

    function isValidRating($rating) {
        return in_array($rating, [1, 2, 3, 4, 5]);
    }

    function isValidDate($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    function isValidTime($time) {
        $t = DateTime::createFromFormat('H:i', $time);
        return $t && $t->format('H:i') === $time;
    }

    function isValidURL($url) {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    function sendEmail($to, $subject, $body, $from) {
        $headers = "From: " . $from . "\r\n";
        $headers .= "Reply-To: " . $from . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        return mail($to, $subject, $body, $headers);
    }


    function saveTestimonial($name, $email, $phone, $service, $rating, $message) {
        global $pdo;
        $query = "INSERT INTO testimonials (name, email, phone, service, rating, message, is_featured, created_at) 
                  VALUES (:name, :email, :phone, :service, :rating, :message, 0, NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':service', $service);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':message', $message);
        return $stmt->execute();
    }

    function getPortfolios() {
        global $pdo; // $pdo is the database connection in config.php
        $query = "SELECT * FROM portfolio ORDER BY id DESC";
        $result = $pdo->query($query);
        if (!$result) {
            return false; // Return false if the query fails
        }
        $portfolios = $result->fetchAll(PDO::FETCH_ASSOC);
        return $portfolios;
    }

    function getPortfolioByCategory($category) {
        global $pdo;
        $query = "SELECT * FROM portfolio WHERE category = :category ORDER BY id DESC";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':category', $category);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getBudgets() {
        global $pdo; // $pdo is the database connection in config.php
        $query = "SELECT * FROM budgets ORDER BY min_amount ASC";   
        $result = $pdo->query($query);
        if (!$result) {
            return false; // Return false if the query fails
        }
        $budgets = $result->fetchAll(PDO::FETCH_ASSOC);
        return $budgets;
    }

    function getPackages() {
        global $pdo; // $pdo is the database connection in config.php
        $query = "SELECT * FROM packages ORDER BY id ASC";   
        $result = $pdo->query($query);
        if (!$result) {
            return false; // Return false if the query fails
        }
        $packages = $result->fetchAll(PDO::FETCH_ASSOC);
        return $packages;
    }

    