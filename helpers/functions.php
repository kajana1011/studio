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
        $query = "SELECT * FROM services WHERE category = 'video' ORDER BY id DESC";
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
        $query = "SELECT * FROM packages ORDER BY id DESC";
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