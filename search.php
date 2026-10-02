<?php
// Unified Search Router: Seamlessly directs search queries to products catalog
$query = trim($_GET['query'] ?? ($_GET['search'] ?? ''));
if ($query !== '') {
    header('Location: products.php?search=' . urlencode($query));
} else {
    header('Location: products.php');
}
exit();
