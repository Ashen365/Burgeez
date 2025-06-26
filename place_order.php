<?php
session_start();
include('includes/db.php');
$cart = $_SESSION['cart'] ?? [];

if ($_SERVER["REQUEST_METHOD"] === "POST" && count($cart) > 0) {
    $name    = htmlspecialchars($_POST['name']);
    $email   = htmlspecialchars($_POST['email']);
    $phone   = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);
    
    // Generate a username from email (to avoid duplicate username error)
    $username = explode('@', $email)[0] . '_' . time();
    
    $total = 0;
    foreach ($cart as $item) $total += $item['price'] * $item['qty'];

    try {
        // Start transaction
        $pdo->beginTransaction();
        
        // Insert user if not exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user) {
            $user_id = $user['id'];
        } else {
            // Include username in the INSERT query to prevent constraint violation
            $stmt = $pdo->prepare("INSERT INTO users (name, email, username, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$name, $email, $username]);
            $user_id = $pdo->lastInsertId();
        }

        // Insert order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, address, phone, status, created_at) VALUES (?, ?, ?, ?, 'pending', NOW())");
        $stmt->execute([$user_id, $total, $address, $phone]);
        $order_id = $pdo->lastInsertId();

        // Insert order items
        foreach ($cart as $item) {
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$order_id, $item['id'], $item['qty'], $item['price']]);
        }
        
        // Commit transaction
        $pdo->commit();
        
        $message = "Order placed successfully for $name! We will contact you soon.";
        $order_success = true;
        unset($_SESSION['cart']);
    } catch (PDOException $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        $message = "Sorry, there was an error processing your order. Please try again.";
        $order_success = false;
        // Log the error for debugging
        error_log("Order Error: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit;
}
?>

<?php include('includes/header.php'); ?>

<!-- Order Confirmation Page -->
<section class="py-16 px-4 <?= $order_success ? 'bg-green-50' : 'bg-red-50' ?>">
  <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
    <div class="p-8 text-center">
      <?php if($order_success): ?>
        <!-- Success Message -->
        <div class="mb-6 flex justify-center">
          <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center">
            <i class="fas fa-check text-3xl text-green-600"></i>
          </div>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Thank You for Your Order!</h1>
        <p class="text-lg text-gray-600 mb-6"><?= $message ?></p>
        <p class="text-gray-600 mb-8">A confirmation email has been sent to <strong><?= htmlspecialchars($email) ?></strong></p>
        
        <div class="border-t border-gray-200 pt-6 mt-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-2">Order Summary</h3>
          <p class="text-gray-600">Order #<?= sprintf('%06d', $order_id) ?></p>
          <p class="font-medium text-red-600 mt-4">Total: Rs. <?= number_format($total, 2) ?></p>
        </div>
        
        <div class="mt-8">
          <a href="index.php" class="inline-block bg-red-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-red-700 transition">
            <i class="fas fa-home mr-2"></i> Return to Home
          </a>
          <a href="pages/menu.php" class="inline-block bg-gray-100 text-gray-800 px-6 py-3 rounded-lg font-medium ml-4 hover:bg-gray-200 transition">
            <i class="fas fa-utensils mr-2"></i> Order Again
          </a>
        </div>
      <?php else: ?>
        <!-- Error Message -->
        <div class="mb-6 flex justify-center">
          <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center">
            <i class="fas fa-exclamation-triangle text-3xl text-red-600"></i>
          </div>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Oops! Something went wrong</h1>
        <p class="text-lg text-gray-600 mb-8"><?= $message ?></p>
        
        <div class="mt-8">
          <a href="pages/cart.php" class="inline-block bg-red-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-red-700 transition">
            <i class="fas fa-shopping-cart mr-2"></i> Return to Cart
          </a>
          <a href="index.php" class="inline-block bg-gray-100 text-gray-800 px-6 py-3 rounded-lg font-medium ml-4 hover:bg-gray-200 transition">
            <i class="fas fa-home mr-2"></i> Go Home
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php include('includes/footer.php'); ?>