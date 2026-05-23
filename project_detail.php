<?php
// Redirect to project.desadroid.shop
if (isset($_GET['slug'])) {
    $slug = urlencode($_GET['slug']);
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: https://project.desadroid.shop/project/' . $slug);
} else {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: https://project.desadroid.shop');
}
exit;
?>
