<?php

include_once '../init.php';

use MongoDB\BSON\ObjectId;

$manager = getMongoDbManager();
$collection = $manager->selectCollection('tp');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Requête invalide.');
}

$id               = $_POST['id'] ?? null;
$titre            = $_POST['title'] ?? '';
$auteur           = $_POST['author'] ?? '';
$auteur_principal = $_POST['auteur_principal'] ?? '';
$siecle           = isset($_POST['century']) ? (int) $_POST['century'] : null;
$edition          = $_POST['edition'] ?? '';
$langue           = $_POST['langue'] ?? '';
$cote             = $_POST['cote'] ?? '';

if (!$id) {
    die('ID manquant.');
}

// Met à jour le document dans MongoDB
$collection->updateOne(
    ['_id' => new ObjectId($id)],
    [
        '$set' => [
            'titre'            => $titre,
            'auteur'           => $auteur,
            'auteur_principal' => $auteur_principal,
            'siecle'           => $siecle,
            'edition'          => $edition,
            'langue'           => $langue,
            'cote'             => $cote
        ]
    ]
);

// Redirection vers la liste de base
header('Location: app.php');
exit;
