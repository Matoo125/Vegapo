<?php

session_start();

if (!isset($_POST[ini_get('session.upload_progress.name')])) {
    exit(json_encode(array('success' => false)));
}

$key = ini_get('session.upload_progress.prefix') . $_POST[ini_get('session.upload_progress.name')];

if (!isset($_SESSION[$key])) {
    exit(json_encode(array('success' => false)));
}

$progress = $_SESSION[$key];
$pct = 0;
$size = 0;

if (is_array($progress)) {

    if (array_key_exists('bytes_processed', $progress) && array_key_exists('content_length', $progress)) {

        if ($progress['content_length'] > 0) {
            $pct = round(($progress['bytes_processed'] / $progress['content_length']) * 100);
            $size = round($progress['content_length'] / 1024);
        }
    }
}

echo json_encode(array('success' => true, 'pct' => $pct, 'size' => $size));