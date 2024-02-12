<?php
$pageTitle = 'Home';
?>

<h1 align="center">
    Home
</h1>

<!-- language_switcher.php -->

<form method="post" action="change_language">
    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken(); ?>">

    <label for="language">Select Language:</label>
    <select name="language" id="language">
        <option value="en">English</option>
        <!-- Add more options for other languages as needed -->
    </select>
    <button type="submit">Change Language</button>
</form>