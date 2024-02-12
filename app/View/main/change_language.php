<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['language'])) {
        $selectedLanguage = $_POST['language'];
        if (in_array($selectedLanguage, \Config\App::$allowedLanguages)) {
            $_SESSION['language'] = $selectedLanguage;
        }
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    header('Location:' .baseUrl());
}

exit();