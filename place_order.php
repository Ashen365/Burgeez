<?php
session_start();
include('includes/db.php');
$cart = $_SESSION['cart'] ?? [];

if ($_SERVER["REQUEST_METHOD"] === "POST" && count($cart) > 0) {
    $name    = htmlspecialchars($_POST['name']);
    $email   = htmlspecialchars($_POST['email']);
    $phone   = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);
    $total = 0;
    foreach ($cart as $item) $total += $item['price'] * $item['qty'];

    // Insert user if not exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user) {
        $user_id = $user['id'];
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$name, $email]);
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

    $message = "Order placed successfully for $name! We will contact you soon.";
    unset($_SESSION['cart']);
} else {
    header("Location: index.php");
    exit;
}
?>

<?php include('includes/header.php'); ?>

<!-- Order Confirmation Page -->
<section class="min-h-screen flex flex-col justify-center items-center bg-green-50 text-center p-6">
  <h1 class="text-4xl font-bold text-green-700 mb-6">🎉 Thank You!</h1>
  <p class="text-lg text-gray-700 mb-4"><?php echo $message; ?></p>
  <a href="index.php" class="text-red-600 hover:underline">← Back to Home</a>
</section>

<?php include('includes/footer.php'); ?>