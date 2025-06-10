<?php
// delete_menu.php

require 'db.php'; // Your DB connection

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Get the image filename before deleting
    $stmt = $conn->prepare("SELECT image FROM menu_items WHERE id = ?");
    $stmt->execute([$id]);
    $menu = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($menu) {
        // Delete image file if it exists
        $imagePath = 'uploads/' . $menu['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Delete record from database
        $stmt = $conn->prepare("DELETE FROM menu_items WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// Redirect back to menu list
header("Location: menu_list.php");
exit;
?>
