<?php
// Unified Search Router: Seamlessly directs search queries to products catalog
$query = trim($_GET['query'] ?? ($_GET['search'] ?? ''));
$category = trim($_GET['category'] ?? '');

$params = [];
if ($query !== '') {
    $params['search'] = $query;
}
if ($category !== '') {
    $params['category'] = $category;
}

$url = 'products.php' . (!empty($params) ? '?' . http_build_query($params) : '');
header('Location: ' . $url);
exit();

