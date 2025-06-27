<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../includes/db.php';
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
    $price = filter_var($_POST['price'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $description = trim($_POST['description'] ?? '');
    $featured = isset($_POST['featured']) ? 1 : 0;
    
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
        
        if (!$message) {
            try {
                // Build update query
                $sql = "UPDATE menu_items SET name = ?, price = ?, description = ?, image = ?";
                $params = [$name, $price, $description, $imageName];
                
                // Check if featured column exists
                $stmt = $pdo->prepare("SHOW COLUMNS FROM menu_items LIKE 'featured'");
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    $sql .= ", featured = ?";
                    $params[] = $featured;
                }
                
                // Add updated_at if exists
                $stmt = $pdo->prepare("SHOW COLUMNS FROM menu_items LIKE 'updated_at'");
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    $sql .= ", updated_at = NOW()";
                }
                
                $sql .= " WHERE id = ?";
                $params[] = $id;
                
                // Execute update
                $stmt = $pdo->prepare($sql);
                
                if ($stmt->execute($params)) {
                    $message = "Menu item updated successfully!";
                    $messageType = "success";
                    
                    // Refresh item data after update
                    $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = ?");
                    $stmt->execute([$id]);
                    $item = $stmt->fetch();
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
            background-color: #f9fafb;
        }
        
        .input-field {
            @apply bg-white border border-gray-300 text-gray-900 text-sm rounded focus:outline-none focus:border-primary-500 block w-full p-2;
        }
        
        /* Make description textarea much taller and wider */
        textarea#description {
            min-height: 200px;
            width: 100%; /* Full width of its container */
            resize: vertical;
            line-height: 1.5;
        }
        
        /* Simple buttons that match the screenshot */
        .btn-danger {
            @apply text-red-600 hover:text-red-800;
        }
        
        .btn-cancel {
            @apply text-gray-700 hover:text-gray-900;
        }
        
        .btn-save {
            @apply bg-primary-500 text-white hover:bg-primary-600 font-medium rounded px-5 py-2;
        }
    </style>
</head>
<body>
    <div class="min-h-screen bg-white">
        <!-- Top Navigation -->
        <nav class="bg-white border-b border-gray-200">
            <div class="px-4 py-2.5 lg:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <a href="dashboard.php" class="flex items-center">
                            <div class="bg-primary-500 text-white h-8 w-8 rounded-md flex items-center justify-center mr-3">
                                <span class="text-xl font-bold">B</span>
                            </div>
                            <span class="self-center text-xl font-semibold whitespace-nowrap">Burgeez Admin</span>
                        </a>
                        <div class="hidden md:flex ml-8">
                            <ul class="flex space-x-8">
                                <li><a href="dashboard.php" class="text-gray-500 hover:text-primary-600">Dashboard</a></li>
                                <li><a href="manage_menu.php" class="text-primary-500 border-b-2 border-primary-500">Menu</a></li>
                                <li><a href="orders.php" class="text-gray-500 hover:text-primary-600">Orders</a></li>
                                <li><a href="settings.php" class="text-gray-500 hover:text-primary-600">Settings</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="text-sm text-gray-600 mr-2">Admin</span>
                        <div class="bg-primary-100 text-primary-800 w-8 h-8 rounded-full flex items-center justify-center">
                            <span class="font-semibold">AD</span>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content - Matching the screenshot's simple layout -->
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="flex items-center mb-4">
                <a href="manage_menu.php" class="text-gray-500 hover:text-primary-600 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Menu Management
                </a>
            </div>
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold">Edit Menu Item</h1>
                    <p class="text-gray-600">Update details for "<?= htmlspecialchars($item['name']) ?>"</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Last Updated</p>
                    <p class="text-gray-700"><?= $lastUpdated ?></p>
                </div>
            </div>

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

            <form method="post" enctype="multipart/form-data" class="mb-6">
                <!-- Modified grid layout - 60% for form fields, 40% for image -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                    <!-- Left Column - Expanded to take 8 columns (67%) of the grid -->
                    <div class="md:col-span-8">
                        <div class="mb-6">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Burger Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" class="input-field" value="<?= htmlspecialchars($item['name']) ?>" required>
                        </div>
                        
                        <div class="mb-6">
                            <label for="price" class="block mb-2 text-sm font-medium text-gray-900">Price (Rs.) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">Rs.</span>
                                <input type="text" id="price" name="price" class="input-field pl-10" value="<?= htmlspecialchars($item['price']) ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                            <!-- Wider textarea for description - takes full width of its larger container -->
                            <textarea id="description" name="description" class="input-field"><?= htmlspecialchars($item['description']) ?></textarea>
                        </div>
                    </div>
                    
                    <!-- Right Column - Reduced to take 4 columns (33%) of the grid -->
                    <div class="md:col-span-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Item Image</label>
                        <div class="mb-4">
                            <img id="imagePreview" src="../assets/images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-64 object-cover rounded" onerror="this.src='https://via.placeholder.com/400x300/f97316/ffffff?text=Burger+Image'">
                        </div>
                        
                        <div id="uploadArea" class="flex flex-col items-center justify-center p-6 border border-gray-300 border-dashed rounded bg-gray-50 cursor-pointer">
                            <svg class="w-10 h-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="mb-1 text-sm text-gray-500">Click to upload or drag and drop</p>
                            <p class="text-xs text-gray-500">PNG, JPG or WEBP (MAX. 5MB)</p>
                            <input id="image" name="image" type="file" accept="image/*" class="hidden">
                        </div>
                    </div>
                </div>
                
                <!-- Bottom buttons - simple layout matching the screenshot -->
                <div class="mt-10 flex justify-between items-center">
                    <button type="button" id="deleteBtn" class="btn-danger flex items-center">
                        <i class="fas fa-trash-alt mr-2"></i> Delete Item
                    </button>
                    <div class="space-x-4">
                        <button type="button" onclick="window.location.href='manage_menu.php'" class="btn-cancel">Cancel</button>
                        <button type="submit" class="btn-save">Save Changes</button>
                    </div>
                </div>
            </form>
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
                    <button type="button" id="cancelDeleteBtn" class="btn-cancel px-4 py-2">Cancel</button>
                    <a href="delete_menu_item.php?id=<?= $id ?>&confirm=true" class="bg-red-600 hover:bg-red-700 text-white font-medium rounded px-4 py-2">
                        <i class="fas fa-trash mr-2"></i> Delete
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Image preview functionality
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const uploadArea = document.getElementById('uploadArea');
        
        if (imageInput && imagePreview && uploadArea) {
            uploadArea.addEventListener('click', function() {
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
            
            // Add drag and drop functionality
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, highlight, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, unhighlight, false);
            });
            
            function highlight() {
                uploadArea.classList.add('bg-gray-100');
            }
            
            function unhighlight() {
                uploadArea.classList.remove('bg-gray-100');
            }
            
            uploadArea.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                if (files && files.length) {
                    imageInput.files = files;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                    }
                    reader.readAsDataURL(files[0]);
                }
            });
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