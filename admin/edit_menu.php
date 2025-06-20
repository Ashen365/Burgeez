<?php
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    header('Location: manage_menu.php');
    exit;
}

$id = $_GET['id'];

// Fetch the item data
$stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
    echo "Item not found!";
    exit;
}

$message = '';

// On form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $desc = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';

    // Validate inputs
    if (!$name || !$price || !is_numeric($price)) {
        $message = "Please provide valid name and price.";
    } else {
        $imageName = $item['image']; // default to old image

        // Check if new image uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageTmp = $_FILES['image']['tmp_name'];
            $newImageName = basename($_FILES['image']['name']);
            $targetDir = '../assets/images/';
            $targetFile = $targetDir . $newImageName;

            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $maxFileSize = 2 * 1024 * 1024; // 2MB
            $fileType = mime_content_type($imageTmp);
            $fileSize = $_FILES['image']['size'];

            if (!in_array($fileType, $allowedTypes)) {
                $message = "Invalid file type. Only JPG, PNG, WebP, and GIF allowed.";
            } elseif ($fileSize > $maxFileSize) {
                $message = "File too large. Maximum 2MB allowed.";
            } elseif (!move_uploaded_file($imageTmp, $targetFile)) {
                $message = "Failed to upload new image.";
            } else {
                if ($item['image'] && file_exists($targetDir . $item['image']) && $item['image'] !== $newImageName) {
                    unlink($targetDir . $item['image']);
                }
                $imageName = $newImageName;
            }
        }

        if (!$message) {
            $stmt = $pdo->prepare("UPDATE menu_items SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
            if ($stmt->execute([$name, $desc, $price, $imageName, $id])) {
                header('Location: manage_menu.php?msg=updated');
                exit;
            } else {
                $message = "Database error while updating.";
            }
        }
    }
}
?>

<div class="max-w-screen-lg mx-auto mt-12 bg-gray-50 p-8 rounded-lg shadow-lg">
    <h2 class="text-3xl font-extrabold text-center text-red-600 mb-6">Edit Menu Item</h2>

    <?php if ($message): ?>
        <div class="mb-6 p-4 text-white bg-red-500 rounded"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="flex flex-col">
            <label class="block text-lg font-semibold mb-2" for="name">Burger Name</label>
            <input class="w-full border border-gray-300 rounded-lg px-4 py-2" type="text" id="name" name="name" required value="<?= htmlspecialchars($item['name']) ?>" />
        </div>

        <div class="flex flex-col">
            <label class="block text-lg font-semibold mb-2" for="price">Price (Rs.)</label>
            <input class="w-full border border-gray-300 rounded-lg px-4 py-2" type="number" step="0.01" id="price" name="price" required value="<?= htmlspecialchars($item['price']) ?>" />
        </div>

        <div class="flex flex-col md:col-span-2">
            <label class="block text-lg font-semibold mb-2" for="description">Description</label>
            <textarea class="w-full border border-gray-300 rounded-lg px-4 py-2" id="description" name="description" rows="4"><?= htmlspecialchars($item['description']) ?></textarea>
        </div>

        <div class="md:col-span-2">
            <h3 class="text-lg font-semibold mb-2">Current Image</h3>
            <div class="flex items-center gap-4">
                <img id="imagePreview" src="../assets/images/<?= htmlspecialchars($item['image']) ?>" alt="Current Image" class="w-48 h-32 object-cover rounded-lg shadow-md" />
                <div class="flex flex-col">
                    <label class="block text-lg font-semibold mb-2" for="image">Upload New Image (optional)</label>
                    <input class="w-full" type="file" id="image" name="image" accept="image/*" />
                </div>
            </div>
        </div>

        <div class="md:col-span-2 flex justify-center mt-6">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-lg px-6 py-3 rounded-lg shadow-lg">Update Item</button>
        </div>
    </form>

    <div class="text-center mt-8">
        <a href="manage_menu.php" class="text-red-600 hover:underline text-lg">← Back to Manage Menu</a>
    </div>
</div>

<script>
    const fileInput = document.getElementById('image');
    const preview = document.getElementById('imagePreview');

    fileInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            if (!['image/jpeg', 'image/png', 'image/webp', 'image/gif'].includes(file.type)) {
                alert('Invalid file type.');
                fileInput.value = '';
                preview.style.display = 'none';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Image size exceeds 2MB.');
                fileInput.value = '';
                preview.style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                preview.setAttribute('src', e.target.result);
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
</script>