<?php

include_once '../init.php';

use MongoDB\BSON\ObjectId;

$id = $_GET['id'];

$manager = getMongoDbManager();
$collection = $manager->selectCollection('tp');

$result = $collection->deleteOne(['_id' => new ObjectId($id)]);


header('Location: /index.php');
exit();