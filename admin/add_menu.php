<?php 
include('../includes/db.php'); 
include('../includes/header.php'); 
$message = '';
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $desc = $_POST['description'] ?? '';
    $imageName = '';
    // Validate input
    if (!$name || !$price || !is_numeric($price)) {
        $message = "Please provide a valid name and price.";
    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $fileType = mime_content_type($imageTmp);
        $fileSize = $_FILES['image']['size'];

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($fileType, $allowedTypes)) {
            $message = "Invalid file type. Only JPG, PNG, WebP, and GIF allowed.";
        } elseif ($fileSize > $maxFileSize) {
            $message = "File too large. Maximum 2MB allowed.";
        } else {
            $targetDir = '../assets/images/';
            $targetFile = $targetDir . $imageName;

            // Move uploaded file
            if (move_uploaded_file($imageTmp, $targetFile)) {
                // Insert into database
                $stmt = $pdo->prepare("INSERT INTO menu_items (name, description, price, image) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$name, $desc, $price, $imageName])) {
                    $message = "Menu item added successfully!";
                } else {
                    $message = "Database error. Please try again.";
                }
            } else {
                $message = "Failed to upload image.";
            }
        }
    } else {
        $message = "Please upload an image.";
    }
}
?>

<div class="max-w-xl mx-auto mt-12 bg-white p-6 rounded shadow">
  <h2 class="text-2xl font-bold mb-4">Add New Menu Item</h2>

  <?php if($message): ?>
    <div class="mb-4 p-3 text-white bg-red-500 rounded"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form action="add_menu.php" method="POST" enctype="multipart/form-data" class="space-y-4">
    <div>
      <label class="block font-semibold mb-1" for="name">Burger Name</label>
      <input class="w-full border border-gray-300 rounded px-3 py-2" type="text" id="name" name="name" required />
    </div>

    <div>
      <label class="block font-semibold mb-1" for="price">Price (Rs.)</label>
      <input class="w-full border border-gray-300 rounded px-3 py-2" type="number" step="0.01" id="price" name="price" required />
    </div>

    <div>
      <label class="block font-semibold mb-1" for="image">Upload Image</label>
      <input class="w-full" type="file" id="image" name="image" accept="image/*" required />
      <img id="imagePreview" src="#" alt="Image Preview" style="display:none; max-width: 300px; margin-top: 10px; border-radius: 8px;" />
    </div>

    <div>
      <label class="block font-semibold mb-1" for="description">Description</label>
      <textarea class="w-full border border-gray-300 rounded px-3 py-2" id="description" name="description" rows="3"></textarea>
    </div>

    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Add Item</button>
  </form>

  <a href="manage_menu.php" class="inline-block mt-4 text-red-600 hover:underline">← Back to Manage Menu</a>
</div>

<script>
  const fileInput = document.getElementById('image');
  const preview = document.getElementById('imagePreview');

fileInput.addEventListener('change', function() {
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
    reader.onload = function(e) {
      preview.setAttribute('src', e.target.result);
      preview.style.display = 'block';
    }
    reader.readAsDataURL(file);
  }
});

</script>

<?php include('../includes/footer.php'); ?>
