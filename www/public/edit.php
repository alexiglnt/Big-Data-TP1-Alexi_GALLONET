<?php

include_once '../init.php';

use MongoDB\BSON\ObjectId;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

$twig = getTwig();
$manager = getMongoDbManager();
$collection = $manager->selectCollection('tp');

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID manquant.");
}

$tp = $collection->findOne(['_id' => new ObjectId($id)]);

if (!$tp) {
    die("Document non trouvé.");
}

$tp['_id'] = (string) $tp['_id'];

try {
    echo $twig->render('update.html.twig', ['entity' => $tp]);
} catch (LoaderError|RuntimeError|SyntaxError $e) {
    echo $e->getMessage();
}
