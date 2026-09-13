<?php
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($baseDir === '/') $baseDir = '';
header('Location: ' . ($baseDir ? $baseDir . '/tentang' : '/tentang'), true, 301);
exit;
