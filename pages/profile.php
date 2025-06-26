<?php
// Start session and check if user is logged in
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /burgeez/login.php");
    exit;
}

// Include database connection
require_once($_SERVER['DOCUMENT_ROOT'] . '/burgeez/includes/db.php');

// Get user information
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // Handle user not found
    $_SESSION = array();
    session_destroy();
    header("Location: /burgeez/login.php");
    exit;
}

// Handle profile update
$update_success = false;
$update_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address'] ?? '');
    
    // Validate inputs
    if (empty($name) || empty($email)) {
        $update_error = "Name and email are required fields";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $update_error = "Please enter a valid email address";
    } else {
        // Check if email already exists (excluding current user)
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->rowCount() > 0) {
            $update_error = "Email address is already in use by another account";
        } else {
            // Check if address column exists in users table
            $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'address'");
            $has_address_column = ($stmt->rowCount() > 0);
            
            if ($has_address_column) {
                $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, address = ? WHERE id = ?");
                $success = $stmt->execute([$name, $email, $address, $user_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
                $success = $stmt->execute([$name, $email, $user_id]);
            }
            
            if ($success) {
                $update_success = true;
                
                // Update session data
                $_SESSION['name'] = $name;
                
                // Refresh user data
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $update_error = "Failed to update profile. Please try again.";
            }
        }
    }
}

// Handle password change
$password_success = false;
$password_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate inputs
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $password_error = "All password fields are required";
    } elseif ($new_password !== $confirm_password) {
        $password_error = "New passwords do not match";
    } elseif (strlen($new_password) < 6) {
        $password_error = "New password must be at least 6 characters long";
    } else {
        // Verify current password
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $stored_hash = $stmt->fetchColumn();
        
        if (password_verify($current_password, $stored_hash)) {
            // Update password
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            if ($stmt->execute([$new_hash, $user_id])) {
                $password_success = true;
            } else {
                $password_error = "Failed to update password. Please try again.";
            }
        } else {
            $password_error = "Current password is incorrect";
        }
    }
}

// Get order history
try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // If the orders table doesn't exist or has errors
    $orders = [];
}

// Include header
require_once($_SERVER['DOCUMENT_ROOT'] . '/burgeez/includes/header.php');
?>

<!-- Profile Section -->
<section class="py-12 px-4 bg-gray-50">
  <div class="container mx-auto max-w-6xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">My Account</h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      <!-- Profile Sidebar -->
      <div class="col-span-1">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="bg-gradient-to-r from-red-600 to-red-700 p-6 text-center text-white">
            <div class="w-20 h-20 rounded-full bg-white text-red-600 flex items-center justify-center mx-auto mb-3 text-2xl font-bold">
              <?= strtoupper(substr($user['name'], 0, 1)) ?>
            </div>
            <h3 class="text-xl font-semibold"><?= htmlspecialchars($user['name']) ?></h3>
            <p class="text-sm opacity-80">@<?= htmlspecialchars($user['username']) ?></p>
            <p class="text-sm opacity-80 mt-1">Member since <?= date('F Y', strtotime($user['created_at'])) ?></p>
          </div>
          
          <div class="py-4">
            <a href="#profile" class="flex items-center px-6 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
              <i class="fas fa-user-circle w-6"></i>
              <span>Profile Information</span>
            </a>
            <a href="#orders" class="flex items-center px-6 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
              <i class="fas fa-shopping-bag w-6"></i>
              <span>Order History</span>
            </a>
            <a href="#password" class="flex items-center px-6 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
              <i class="fas fa-lock w-6"></i>
              <span>Change Password</span>
            </a>
            <a href="/burgeez/logout.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors border-t">
              <i class="fas fa-sign-out-alt w-6"></i>
              <span>Logout</span>
            </a>
          </div>
        </div>
      </div>
      
      <!-- Profile Content -->
      <div class="col-span-1 lg:col-span-3">
        <!-- Profile Information -->
        <div id="profile" class="bg-white rounded-lg shadow-md p-6 mb-8">
          <h2 class="text-2xl font-semibold text-gray-800 mb-6 pb-2 border-b">Profile Information</h2>
          
          <?php if ($update_success): ?>
            <div class="bg-green-50 text-green-800 p-4 rounded-lg mb-6">
              <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <p>Your profile has been updated successfully.</p>
              </div>
            </div>
          <?php endif; ?>
          
          <?php if ($update_error): ?>
            <div class="bg-red-50 text-red-800 p-4 rounded-lg mb-6">
              <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <p><?= $update_error ?></p>
              </div>
            </div>
          <?php endif; ?>
          
          <form method="POST" action="" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" id="username" value="<?= htmlspecialchars($user['username']) ?>" 
                      class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg" disabled>
                <p class="mt-1 text-sm text-gray-500">Username cannot be changed</p>
              </div>
              
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" 
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500" required>
              </div>
              
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" 
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500" required>
              </div>
              
              <div>
                <label for="created" class="block text-sm font-medium text-gray-700 mb-1">Member Since</label>
                <input type="text" id="created" value="<?= date('F j, Y', strtotime($user['created_at'])) ?>" 
                      class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg" disabled>
              </div>
            </div>
            
            <div>
              <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Delivery Address</label>
              <textarea id="address" name="address" rows="3" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
            </div>
            
            <div class="pt-2">
              <button type="submit" name="update_profile" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                <i class="fas fa-save mr-2"></i> Update Profile
              </button>
            </div>
          </form>
        </div>
        
        <!-- Order History -->
        <div id="orders" class="bg-white rounded-lg shadow-md p-6 mb-8">
          <h2 class="text-2xl font-semibold text-gray-800 mb-6 pb-2 border-b">Order History</h2>
          
          <?php if (!empty($orders)): ?>
            <div class="overflow-x-auto">
              <table class="w-full text-left">
                <thead>
                  <tr class="bg-gray-50">
                    <th class="px-4 py-3 text-sm font-medium text-gray-500">Order ID</th>
                    <th class="px-4 py-3 text-sm font-medium text-gray-500">Date</th>
                    <th class="px-4 py-3 text-sm font-medium text-gray-500">Items</th>
                    <th class="px-4 py-3 text-sm font-medium text-gray-500">Total</th>
                    <th class="px-4 py-3 text-sm font-medium text-gray-500">Status</th>
                    <th class="px-4 py-3 text-sm font-medium text-gray-500">Action</th>
                  </tr>
                </thead>
                <tbody class="divide-y">
                  <?php foreach ($orders as $order): ?>
                    <tr class="hover:bg-gray-50">
                      <td class="px-4 py-4">#<?= $order['id'] ?></td>
                      <td class="px-4 py-4"><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                      <td class="px-4 py-4">
                        <?php 
                        try {
                            // Get order items count
                            $stmt = $pdo->prepare("SELECT COUNT(*) FROM order_items WHERE order_id = ?");
                            $stmt->execute([$order['id']]);
                            $item_count = $stmt->fetchColumn();
                            echo $item_count . ' item' . ($item_count != 1 ? 's' : '');
                        } catch (PDOException $e) {
                            echo "N/A";
                        }
                        ?>
                      </td>
                      <td class="px-4 py-4 font-medium">Rs. <?= number_format($order['total'] ?? 0, 2) ?></td>
                      <td class="px-4 py-4">
                        <?php if ($order['status'] == 'delivered'): ?>
                          <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">Delivered</span>
                        <?php elseif ($order['status'] == 'processing'): ?>
                          <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-medium">Processing</span>
                        <?php elseif ($order['status'] == 'cancelled'): ?>
                          <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full font-medium">Cancelled</span>
                        <?php else: ?>
                          <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-medium">Pending</span>
                        <?php endif; ?>
                      </td>
                      <td class="px-4 py-4">
                        <a href="/burgeez/pages/order_details.php?id=<?= $order['id'] ?>" class="text-red-600 hover:text-red-700">
                          View Details
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="text-center py-8">
              <div class="text-gray-400 text-5xl mb-4">
                <i class="fas fa-shopping-bag"></i>
              </div>
              <h3 class="text-xl font-medium text-gray-600 mb-2">No Orders Yet</h3>
              <p class="text-gray-500 mb-6">You haven't placed any orders yet.</p>
              <a href="/burgeez/pages/menu.php" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors inline-flex items-center">
                <i class="fas fa-utensils mr-2"></i> Browse Menu
              </a>
            </div>
          <?php endif; ?>
        </div>
        
        <!-- Change Password -->
        <div id="password" class="bg-white rounded-lg shadow-md p-6 mb-8">
          <h2 class="text-2xl font-semibold text-gray-800 mb-6 pb-2 border-b">Change Password</h2>
          
          <?php if ($password_success): ?>
            <div class="bg-green-50 text-green-800 p-4 rounded-lg mb-6">
              <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <p>Your password has been changed successfully.</p>
              </div>
            </div>
          <?php endif; ?>
          
          <?php if ($password_error): ?>
            <div class="bg-red-50 text-red-800 p-4 rounded-lg mb-6">
              <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <p><?= $password_error ?></p>
              </div>
            </div>
          <?php endif; ?>
          
          <form method="POST" action="" class="space-y-6">
            <div>
              <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
              <input type="password" id="current_password" name="current_password" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500" required>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input type="password" id="new_password" name="new_password" 
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500" required>
              </div>
              
              <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" 
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500" required>
              </div>
            </div>
            
            <div class="pt-2">
              <button type="submit" name="change_password" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                <i class="fas fa-lock mr-2"></i> Update Password
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/burgeez/includes/footer.php'); ?>