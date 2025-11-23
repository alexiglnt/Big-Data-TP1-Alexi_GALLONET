<?php

include_once '../init.php';

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

$twig = getTwig();
$manager = getMongoDbManager();

$collection = $manager->selectCollection('tp');

// ----- RECHERCHE -----
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

$filter = [];
if ($search !== '') {
    // reecherche insensible à la casse sur plusieurs champs
    $filter = [
        '$or' => [
            ['titre'  => ['$regex' => $search, '$options' => 'i']],
            ['auteur' => ['$regex' => $search, '$options' => 'i']],
            ['cote'   => ['$regex' => $search, '$options' => 'i']],
        ],
    ];
}

// ----- PAGINATION -----
$perPage = 50;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}
$skip = ($page - 1) * $perPage;

$options = [
    'limit' => $perPage,
    'skip'  => $skip,
    'sort'  => ['_id' => 1],
];

// cest la liste filtrée + paginée
$cursor = $collection->find($filter, $options);
$list = $cursor->toArray();

// le total est filtré pour que la pagination tienne compte de la recherche
$totalDocuments = $collection->countDocuments($filter);
$totalPages = (int) ceil($totalDocuments / $perPage);

// render template
try {
    echo $twig->render('index.html.twig', [
        'list'        => $list,
        'currentPage' => $page,
        'totalPages'  => $totalPages,
        'search'      => $search,
    ]);
} catch (LoaderError|RuntimeError|SyntaxError $e) {
    echo $e->getMessage();
}
