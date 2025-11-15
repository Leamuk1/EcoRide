<?php
session_start();

// Inclure la configuration
require_once __DIR__ . '/../src/Config/Database.php';

// Test de connexion
$db = new Database();
$pdo = $db->connect();

// Afficher la page d'accueil
include __DIR__ . '/../src/View/layout/header.php';
include __DIR__ . '/../src/View/pages/home.php';
include __DIR__ . '/../src/View/layout/footer.php';