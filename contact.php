<?php
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($baseDir === '/') $baseDir = '';
header('Location: ' . ($baseDir ? $baseDir . '/kontak' : '/kontak'), true, 301);
exit;
