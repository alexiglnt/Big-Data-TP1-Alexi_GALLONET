<?php

include_once '../init.php';

use MongoDB\BSON\ObjectId;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

$twig = getTwig();
$manager = getMongoDbManager();

$id = $_GET['id'];

// @todo implementez la récupération des données d'une entité
// petite aide : https://github.com/VSG24/mongodb-php-examples
$collection = $manager->selectCollection('tp');
$entity = $collection->findOne(['_id' => new ObjectId($id)]);

// render template
try {
    echo $twig->render('get.html.twig', ['entity' => $entity]);
} catch (LoaderError|RuntimeError|SyntaxError $e) {
    echo $e->getMessage();
}