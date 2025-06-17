<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../includes/db.php';
// Comment out auth_check if you're still having issues with it
require_once '../includes/auth_check.php';

if (!isset($_GET['id'])) {
    header('Location: manage_menu.php');
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
    header('Location: manage_menu.php?error=item_not_found');
    exit;
}

$message = '';
$messageType = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category = $_POST['category'] ?? '';
    $price = filter_var($_POST['price'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $description = trim($_POST['description'] ?? '');
    $featured = isset($_POST['featured']) ? 1 : 0;
    $spicy_level = (int)($_POST['spicy_level'] ?? 0);
    
    // Optional fields - check if columns exist in your table
    $nutritional_info = isset($_POST['nutritional_info']) ? trim($_POST['nutritional_info']) : '';
    $ingredients = isset($_POST['ingredients']) ? trim($_POST['ingredients']) : '';
    $allergens = $_POST['allergens'] ?? [];
    
    // Convert allergens array to JSON
    $allergensJson = json_encode($allergens);
    
    // Validate inputs
    if (empty($name) || $price <= 0) {
        $message = "Please provide valid name and price.";
        $messageType = "error";
    } else {
        $imageName = $item['image']; // default to existing image
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageTmp = $_FILES['image']['tmp_name'];
            $newImageName = 'burger_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $targetDir = '../assets/images/';
            $targetFile = $targetDir . $newImageName;
            
            // Ensure directory exists
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $maxFileSize = 5 * 1024 * 1024; // 5MB
            
            $fileType = mime_content_type($imageTmp);
            $fileSize = $_FILES['image']['size'];
            
            if (!in_array($fileType, $allowedTypes)) {
                $message = "Invalid file type. Only JPG, PNG, WebP, and GIF allowed.";
                $messageType = "error";
            } elseif ($fileSize > $maxFileSize) {
                $message = "File too large. Maximum 5MB allowed.";
                $messageType = "error";
            } elseif (!move_uploaded_file($imageTmp, $targetFile)) {
                $message = "Failed to upload new image.";
                $messageType = "error";
            } else {
                // Delete old image if it's not the default and not the same as new one
                if ($item['image'] && file_exists($targetDir . $item['image']) && $item['image'] !== $newImageName && $item['image'] !== 'default.jpg') {
                    unlink($targetDir . $item['image']);
                }
                $imageName = $newImageName;
            }
        }
        
        // Process gallery images if your table has this column
        $galleryImagesJson = '[]';
        if (isset($item['gallery_images'])) {
            $galleryImages = [];
            if (!empty($item['gallery_images'])) {
                $galleryImages = json_decode($item['gallery_images'], true) ?? [];
            }
            
            // Only process gallery uploads if your form has this field
            if (isset($_FILES['gallery']) && is_array($_FILES['gallery']['name'])) {
                $targetDir = '../assets/images/gallery/';
                
                // Ensure directory exists
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                
                for ($i = 0; $i < count($_FILES['gallery']['name']); $i++) {
                    if ($_FILES['gallery']['error'][$i] === UPLOAD_ERR_OK) {
                        $tmpName = $_FILES['gallery']['tmp_name'][$i];
                        $galleryImageName = 'gallery_' . time() . '_' . $i . '_' . bin2hex(random_bytes(4)) . '.' . 
                            pathinfo($_FILES['gallery']['name'][$i], PATHINFO_EXTENSION);
                        $targetFile = $targetDir . $galleryImageName;
                        
                        if (move_uploaded_file($tmpName, $targetFile)) {
                            $galleryImages[] = $galleryImageName;
                        }
                    }
                }
            }
            
            // Handle gallery image deletion
            if (isset($_POST['remove_gallery']) && is_array($_POST['remove_gallery'])) {
                $targetDir = '../assets/images/gallery/';
                foreach ($_POST['remove_gallery'] as $index) {
                    if (isset($galleryImages[$index])) {
                        $imageToRemove = $galleryImages[$index];
                        if (file_exists($targetDir . $imageToRemove)) {
                            unlink($targetDir . $imageToRemove);
                        }
                        unset($galleryImages[$index]);
                    }
                }
                $galleryImages = array_values($galleryImages); // Re-index array
            }
            
            $galleryImagesJson = json_encode(array_values($galleryImages));
        }
        
        if (!$message) {
            try {
                // Check which columns exist in your table
                $columns = [];
                $values = [];
                
                // Required fields
                $columns[] = "name = ?";
                $values[] = $name;
                
                $columns[] = "description = ?";
                $values[] = $description;
                
                $columns[] = "price = ?";
                $values[] = $price;
                
                $columns[] = "image = ?";
                $values[] = $imageName;
                
                $columns[] = "category = ?";
                $values[] = $category;
                
                // Optional fields - check if they exist in your DB schema
                // Use table information schema to check if columns exist
                $stmt = $pdo->prepare("SHOW COLUMNS FROM menu_items LIKE 'featured'");
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    $columns[] = "featured = ?";
                    $values[] = $featured;
                }
                
                $stmt = $pdo->prepare("SHOW COLUMNS FROM menu_items LIKE 'spicy_level'");
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    $columns[] = "spicy_level = ?";
                    $values[] = $spicy_level;
                }
                
                $stmt = $pdo->prepare("SHOW COLUMNS FROM menu_items LIKE 'nutritional_info'");
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    $columns[] = "nutritional_info = ?";
                    $values[] = $nutritional_info;
                }
                
                $stmt = $pdo->prepare("SHOW COLUMNS FROM menu_items LIKE 'ingredients'");
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    $columns[] = "ingredients = ?";
                    $values[] = $ingredients;
                }
                
                $stmt = $pdo->prepare("SHOW COLUMNS FROM menu_items LIKE 'allergens'");
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    $columns[] = "allergens = ?";
                    $values[] = $allergensJson;
                }
                
                $stmt = $pdo->prepare("SHOW COLUMNS FROM menu_items LIKE 'gallery_images'");
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    $columns[] = "gallery_images = ?";
                    $values[] = $galleryImagesJson;
                }
                
                $stmt = $pdo->prepare("SHOW COLUMNS FROM menu_items LIKE 'updated_at'");
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    $columns[] = "updated_at = NOW()";
                }
                
                // Add item ID to values array
                $values[] = $id;
                
                // Build and execute the update query
                $sql = "UPDATE menu_items SET " . implode(", ", $columns) . " WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                
                if ($stmt->execute($values)) {
                    $message = "Menu item updated successfully!";
                    $messageType = "success";
                } else {
                    $message = "Database error while updating.";
                    $messageType = "error";
                }
            } catch (PDOException $e) {
                $message = "Database error: " . $e->getMessage();
                $messageType = "error";
            }
        }
    }
}

// Get categories for dropdown
$categories = [];
try {
    $stmt = $pdo->query("SELECT DISTINCT category FROM menu_items ORDER BY category");
    while ($row = $stmt->fetch()) {
        if (!empty($row['category'])) {
            $categories[] = $row['category'];
        }
    }
} catch (PDOException $e) {
    // Silently handle error
}

// Common allergens list
$commonAllergens = [
    'gluten' => 'Gluten',
    'dairy' => 'Dairy',
    'eggs' => 'Eggs',
    'nuts' => 'Tree Nuts',
    'peanuts' => 'Peanuts',
    'shellfish' => 'Shellfish',
    'soy' => 'Soy',
    'fish' => 'Fish'
];

// Get item allergens
$itemAllergens = [];
if (!empty($item['allergens'])) {
    $itemAllergens = json_decode($item['allergens'], true) ?? [];
}

// Parse gallery images
$galleryImages = [];
if (isset($item['gallery_images']) && !empty($item['gallery_images'])) {
    $galleryImages = json_decode($item['gallery_images'], true) ?? [];
}

$spicy_level = $item['spicy_level'] ?? 0;
$lastUpdated = isset($item['updated_at']) ? date('F j, Y g:i A', strtotime($item['updated_at'])) : date('F j, Y g:i A');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu Item - Burgeez Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif']
                    },
                    colors: {
                        primary: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        },
                    },
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .input-field {
            @apply bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5;
        }
        .btn-primary {
            @apply text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none;
        }
        .btn-secondary {
            @apply text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 border border-gray-300 focus:outline-none;
        }
        .image-preview {
            max-height: 200px;
            object-fit: contain;
            object-position: center;
        }
        .transition-all {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen bg-gray-50">
        <!-- Top Navigation -->
        <nav class="bg-white border-b border-gray-200">
            <div class="px-4 py-2.5 lg:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <a href="dashboard.php" class="flex items-center">
                            <div class="bg-primary-500 text-white h-8 w-8 rounded-md flex items-center justify-center mr-3">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <span class="self-center text-xl font-semibold whitespace-nowrap">Burgeez Admin</span>
                        </a>
                        <div class="hidden md:flex pl-10">
                            <ul class="flex space-x-8">
                                <li><a href="dashboard.php" class="text-gray-500 hover:text-primary-600">Dashboard</a></li>
                                <li><a href="manage_menu.php" class="text-primary-600 border-b-2 border-primary-600 pb-1">Menu</a></li>
                                <li><a href="orders.php" class="text-gray-500 hover:text-primary-600">Orders</a></li>
                                <li><a href="settings.php" class="text-gray-500 hover:text-primary-600">Settings</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex items-center lg:order-2">
                        <button type="button" class="hidden sm:inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg hover:bg-gray-100">
                            <span>Admin</span>
                            <img class="w-8 h-8 rounded-full ml-2" src="../assets/images/admin-avatar.png" alt="Admin Avatar" onerror="this.src='https://ui-avatars.com/api/?name=Admin&background=random'">
                        </button>
                        <button type="button" class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <a href="manage_menu.php" class="text-gray-500 hover:text-primary-600 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Menu Management
                    </a>
                    <h1 class="text-2xl font-bold mt-2">Edit Menu Item</h1>
                    <p class="text-gray-600">Update details for "<?= htmlspecialchars($item['name']) ?>"</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Last Updated</p>
                    <p class="text-gray-700"><?= $lastUpdated ?></p>
                </div>
            </div>

            <!-- Status Messages -->
            <?php if ($messageType === 'success'): ?>
            <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                <div class="flex">
                    <i class="fas fa-check-circle flex-shrink-0 w-5 h-5 mr-2"></i>
                    <span><?= $message ?></span>
                </div>
            </div>
            <?php elseif ($messageType === 'error'): ?>
            <div class="p-4 mb-6 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                <div class="flex">
                    <i class="fas fa-exclamation-circle flex-shrink-0 w-5 h-5 mr-2"></i>
                    <span><?= $message ?></span>
                </div>
            </div>
            <?php endif; ?>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <form method="post" enctype="multipart/form-data">
                    <!-- Form Content -->
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <!-- Left Column -->
                        <div class="md:col-span-2 p-6 border-b md:border-b-0 md:border-r border-gray-200">
                            <div class="space-y-6">
                                <div>
                                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Burger Name <span class="text-red-500">*</span></label>
                                    <input type="text" id="name" name="name" class="input-field" value="<?= htmlspecialchars($item['name']) ?>" required>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="category" class="block mb-2 text-sm font-medium text-gray-900">Category</label>
                                        <div class="relative">
                                            <select id="category" name="category" class="input-field pr-8">
                                                <option value="">Select Category</option>
                                                <?php foreach ($categories as $cat): ?>
                                                <option value="<?= htmlspecialchars($cat) ?>" <?= ($item['category'] ?? '') === $cat ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($cat) ?>
                                                </option>
                                                <?php endforeach; ?>
                                                <option value="new">+ Add New Category</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <i class="fas fa-chevron-down text-gray-400"></i>
                                            </div>
                                        </div>
                                        
                                        <div id="newCategoryField" class="mt-3 hidden">
                                            <input type="text" id="newCategory" class="input-field" placeholder="Enter new category name">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="price" class="block mb-2 text-sm font-medium text-gray-900">Price (Rs.) <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">Rs.</span>
                                            <input type="number" id="price" name="price" step="0.01" min="0" class="input-field pl-10" value="<?= htmlspecialchars($item['price']) ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                                    <textarea id="description" name="description" rows="4" class="input-field"><?= htmlspecialchars($item['description']) ?></textarea>
                                </div>
                                
                                <?php if (isset($item['featured'])): ?>
                                <div class="flex items-center">
                                    <input id="featured" name="featured" type="checkbox" value="1" class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500" 
                                        <?= (!empty($item['featured']) && $item['featured'] == 1) ? 'checked' : '' ?>>
                                    <label for="featured" class="ml-2 text-sm font-medium text-gray-900">Featured Item (display on homepage)</label>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (isset($item['spicy_level'])): ?>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900">Spiciness Level</label>
                                    <div class="flex items-center space-x-1" id="spicyLevelContainer" data-level="<?= $spicy_level ?>">
                                        <input type="hidden" name="spicy_level" id="spicy_level" value="<?= $spicy_level ?>">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                        <button type="button" class="spicy-btn p-1 focus:outline-none" data-level="<?= $i ?>">
                                            <i class="fas fa-pepper-hot text-xl <?= $i <= $spicy_level ? 'text-red-500' : 'text-gray-300' ?>"></i>
                                        </button>
                                        <?php endfor; ?>
                                        <span id="spicyText" class="ml-2 text-sm text-gray-700">
                                            <?php 
                                            echo $spicy_level == 0 ? 'Not Spicy' : 
                                                ($spicy_level == 1 ? 'Mild' : 
                                                ($spicy_level == 2 ? 'Medium' : 
                                                ($spicy_level == 3 ? 'Spicy' : 
                                                ($spicy_level == 4 ? 'Very Spicy' : 'Extreme'))));
                                            ?>
                                        </span>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (isset($item['ingredients'])): ?>
                                <div>
                                    <label for="ingredients" class="block mb-2 text-sm font-medium text-gray-900">Ingredients</label>
                                    <textarea id="ingredients" name="ingredients" rows="3" class="input-field" placeholder="Comma separated list of ingredients"><?= htmlspecialchars($item['ingredients']) ?></textarea>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (isset($item['nutritional_info'])): ?>
                                <div>
                                    <label for="nutritional_info" class="block mb-2 text-sm font-medium text-gray-900">Nutritional Information</label>
                                    <textarea id="nutritional_info" name="nutritional_info" rows="3" class="input-field" placeholder="E.g., Calories: 650, Protein: 35g, Carbs: 45g, Fat: 32g"><?= htmlspecialchars($item['nutritional_info']) ?></textarea>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (isset($item['allergens'])): ?>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900">Allergens</label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <?php foreach($commonAllergens as $key => $allergen): ?>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="allergen_<?= $key ?>" name="allergens[]" value="<?= $key ?>" class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500" 
                                                <?= in_array($key, $itemAllergens) ? 'checked' : '' ?>>
                                            <label for="allergen_<?= $key ?>" class="ml-2 text-sm font-medium text-gray-700">
                                                <?= htmlspecialchars($allergen) ?>
                                            </label>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Right Column - Image -->
                        <div class="p-6">
                            <div class="mb-6">
                                <label class="block mb-2 text-sm font-medium text-gray-900">Item Image</label>
                                <div class="flex flex-col items-center justify-center bg-gray-50 border border-gray-300 border-dashed rounded-lg p-6">
                                    <img id="imagePreview" src="../assets/images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="mb-4 w-full h-auto max-h-48 rounded-lg object-cover" onerror="this.src='https://via.placeholder.com/400x300/f97316/ffffff?text=Burger+Image'">
                                    
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-xs text-gray-500">PNG, JPG or WEBP (MAX. 5MB)</p>
                                    </div>
                                    <input id="image" name="image" type="file" accept="image/*" class="hidden">
                                </div>
                            </div>
                            
                            <?php if (isset($item['gallery_images'])): ?>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900">Gallery Images</label>
                                <div class="grid grid-cols-2 gap-2 mb-3" id="galleryPreviewContainer">
                                    <?php if (!empty($galleryImages)): ?>
                                        <?php foreach ($galleryImages as $index => $image): ?>
                                        <div class="relative group">
                                            <img src="../assets/images/gallery/<?= htmlspecialchars($image) ?>" alt="Gallery image" class="w-full h-24 object-cover rounded border border-gray-200 hover:opacity-75 transition">
                                            <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 text-xs opacity-0 group-hover:opacity-100 transition" 
                                                   onclick="removeGalleryImage(this, <?= $index ?>)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <input type="hidden" name="existing_gallery[]" value="<?= htmlspecialchars($image) ?>">
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <label class="cursor-pointer flex items-center justify-center py-2 border border-gray-300 border-dashed rounded-lg bg-gray-50 hover:bg-gray-100">
                                    <span class="text-sm text-gray-600"><i class="fas fa-plus mr-1"></i> Add more images</span>
                                    <input type="file" id="gallery" name="gallery[]" multiple accept="image/*" class="hidden">
                                </label>
                            </div>
                            <?php endif; ?>
                            
                            <div class="mt-6 border-t border-gray-200 pt-4">
                                <div class="flex justify-between items-center">
                                    <button type="button" id="deleteBtn" class="inline-flex items-center text-sm font-medium text-red-600 hover:text-red-700">
                                        <i class="fas fa-trash mr-1"></i> Delete Item
                                    </button>
                                    <div>
                                        <button type="button" onclick="window.location.href='manage_menu.php'" class="btn-secondary mr-2">Cancel</button>
                                        <button type="submit" class="btn-primary">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50"></div>
        <div class="relative z-10 flex min-h-screen items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">Delete Menu Item</h3>
                    <button type="button" id="closeDeleteModal" class="text-gray-400 hover:text-gray-900 rounded-lg p-1.5 inline-flex items-center">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-4 mb-4 bg-red-50 rounded-lg text-red-800">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle flex-shrink-0 mr-3 mt-0.5"></i>
                        <div>
                            <h4 class="font-semibold mb-1">Warning: This action cannot be undone</h4>
                            <p class="text-sm">Are you sure you want to delete "<?= htmlspecialchars($item['name']) ?>"? This will permanently remove this menu item.</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" id="cancelDeleteBtn" class="btn-secondary">Cancel</button>
                    <a href="delete_menu_item.php?id=<?= $id ?>&confirm=true" class="inline-flex items-center justify-center px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg text-sm focus:outline-none">
                        <i class="fas fa-trash mr-2"></i> Delete
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Category dropdown
        const categorySelect = document.getElementById('category');
        const newCategoryField = document.getElementById('newCategoryField');
        const newCategoryInput = document.getElementById('newCategory');
        
        if (categorySelect && newCategoryField && newCategoryInput) {
            categorySelect.addEventListener('change', function() {
                if (this.value === 'new') {
                    newCategoryField.classList.remove('hidden');
                    newCategoryInput.focus();
                    newCategoryInput.setAttribute('name', 'category');
                    categorySelect.removeAttribute('name');
                } else {
                    newCategoryField.classList.add('hidden');
                    newCategoryInput.removeAttribute('name');
                    categorySelect.setAttribute('name', 'category');
                }
            });
        }
        
        // Spicy level buttons
        const spicyBtns = document.querySelectorAll('.spicy-btn');
        const spicyInput = document.getElementById('spicy_level');
        const spicyText = document.getElementById('spicyText');
        const spicyTexts = ['Not Spicy', 'Mild', 'Medium', 'Spicy', 'Very Spicy', 'Extreme'];
        
        if (spicyBtns.length > 0 && spicyInput && spicyText) {
            spicyBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const level = parseInt(this.getAttribute('data-level'));
                    spicyInput.value = level;
                    spicyText.textContent = spicyTexts[level] || 'Not Spicy';
                    
                    // Update all pepper icons
                    document.querySelectorAll('.spicy-btn i').forEach((icon, index) => {
                        if (index < level) {
                            icon.classList.add('text-red-500');
                            icon.classList.remove('text-gray-300');
                        } else {
                            icon.classList.remove('text-red-500');
                            icon.classList.add('text-gray-300');
                        }
                    });
                });
            });
        }
        
        // Image preview
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const imageContainer = document.querySelector('.flex.flex-col.items-center.justify-center.bg-gray-50');
        
        if (imageInput && imagePreview && imageContainer) {
            imageContainer.addEventListener('click', function() {
                imageInput.click();
            });
            
            imageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
        
        // Gallery image upload
        const galleryInput = document.getElementById('gallery');
        const galleryPreviewContainer = document.getElementById('galleryPreviewContainer');
        
        if (galleryInput && galleryPreviewContainer) {
            galleryInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    for (let i = 0; i < this.files.length; i++) {
                        const file = this.files[i];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const div = document.createElement('div');
                                div.className = 'relative group';
                                div.innerHTML = `
                                    <img src="${e.target.result}" class="w-full h-24 object-cover rounded border border-gray-200 hover:opacity-75 transition">
                                    <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 text-xs opacity-0 group-hover:opacity-100 transition" 
                                           onclick="removeNewGalleryImage(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                `;
                                galleryPreviewContainer.appendChild(div);
                            }
                            reader.readAsDataURL(file);
                        }
                    }
                }
            });
        }
        
        // Gallery image removal
        function removeGalleryImage(button, index) {
            const container = button.closest('div');
            if (container) {
                container.remove();
                
                // Add hidden input to track removed images
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'remove_gallery[]';
                input.value = index;
                document.querySelector('form').appendChild(input);
            }
        }
        
        function removeNewGalleryImage(button) {
            const container = button.closest('div');
            if (container) {
                container.remove();
            }
        }
        
        // Delete modal functionality
        const deleteBtn = document.getElementById('deleteBtn');
        const deleteModal = document.getElementById('deleteModal');
        const closeDeleteModal = document.getElementById('closeDeleteModal');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
        
        if (deleteBtn && deleteModal && closeDeleteModal && cancelDeleteBtn) {
            deleteBtn.addEventListener('click', function() {
                deleteModal.classList.remove('hidden');
            });
            
            closeDeleteModal.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
            });
            
            cancelDeleteBtn.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
            });
            
            // Close when clicking outside
            deleteModal.addEventListener('click', function(e) {
                if (e.target === deleteModal || e.target.classList.contains('bg-black')) {
                    deleteModal.classList.add('hidden');
                }
            });
        }
    </script>
</body>
</html>