<?php

include_once '../init.php';

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

$twig = getTwig();
$manager = getMongoDbManager();

if (!empty($_POST)) {
    $titre             = $_POST['title'] ?? '';
    $auteur            = $_POST['author'] ?? '';
    $auteur_principal  = $_POST['auteur_principal'] ?? '';
    $siecle            = isset($_POST['century']) ? (int) $_POST['century'] : null;
    $edition           = $_POST['edition'] ?? '';
    $langue            = $_POST['langue'] ?? '';
    $cote              = $_POST['cote'] ?? '';
    $objectid          = isset($_POST['objectid']) ? (int) $_POST['objectid'] : null;

    $tp = [
        'titre'            => $titre,
        'auteur'           => $auteur,
        'auteur_principal' => $auteur_principal,
        'siecle'           => $siecle,
        'edition'          => $edition,
        'langue'           => $langue,
        'cote'             => $cote,
        'objectid'         => $objectid
    ];

    $collection = $manager->selectCollection('tp');
    $collection->insertOne($tp);

    header('Location: app.php');
    exit();
} else {
    try {
        echo $twig->render('create.html.twig');
    } catch (LoaderError|RuntimeError|SyntaxError $e) {
        echo $e->getMessage();
    }
}
