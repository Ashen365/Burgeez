<?php
include('../includes/db.php');
include('../includes/header.php');
session_start();

// Fetch menu items from DB
$stmt = $pdo->query("SELECT * FROM menu_items WHERE is_active = 1 ORDER BY id DESC");
$menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Add to Cart Quantity Step
$show_qty_form = false;
$qty_item = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $item_id = (int)$_POST['item_id'];
    // Find the menu item
    foreach ($menu_items as $item) {
        if ($item['id'] == $item_id) {
            $qty_item = $item;
            $show_qty_form = true;
            break;
        }
    }
}

// Handle Add with Quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_add_to_cart'])) {
    $item_id = (int)$_POST['item_id'];
    $qty = max(1, (int)$_POST['quantity']); // Minimum 1
    // Find the menu item
    foreach ($menu_items as $item) {
        if ($item['id'] == $item_id) {
            // Add to session cart
            if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
            $found = false;
            foreach ($_SESSION['cart'] as &$cart_item) {
                if ($cart_item['id'] == $item_id) {
                    $cart_item['qty'] += $qty;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $_SESSION['cart'][] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'qty' => $qty
                ];
            }
            break;
        }
    }
    header("Location: cart.php");
    exit;
}
?>

<!-- Menu Section -->
<section class="py-16 px-4 bg-gray-50">
  <h2 class="text-4xl font-bold text-center text-red-600 mb-12">Our Menu</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
    <?php foreach ($menu_items as $item): ?>
      <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <img src="/burgeez/assets/images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-48 object-cover" />
        <div class="p-4">
          <h3 class="text-xl font-semibold text-gray-800 mb-2"><?= htmlspecialchars($item['name']) ?></h3>
          <p class="text-gray-600 mb-3">Rs. <?= number_format($item['price'], 2) ?></p>
          <p class="text-gray-500 mb-4"><?= htmlspecialchars($item['description']) ?></p> <!-- Display description -->
          <form method="POST" action="menu.php">
            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
            <button type="submit" name="add_to_cart" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">Add to Cart</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php if ($show_qty_form && $qty_item): ?>
<!-- Quantity Modal -->
<div id="qty-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
  <div class="bg-white p-8 rounded shadow-lg max-w-md w-full">
    <h3 class="text-xl font-semibold mb-4">How many <span class="text-red-600"><?= htmlspecialchars($qty_item['name']) ?></span> would you like?</h3>
    <form method="POST" action="menu.php" class="flex flex-col gap-4">
      <input type="hidden" name="item_id" value="<?= $qty_item['id'] ?>">
      <input type="number" name="quantity" min="1" value="1" required class="border rounded px-4 py-2 w-full" />
      <button type="submit" name="confirm_add_to_cart" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">Add to Cart</button>
      <a href="menu.php" class="text-center text-gray-500 mt-2 hover:underline">Cancel</a>
    </form>
  </div>
</div>
<script>
  // Prevent page scroll while modal is open
  document.body.style.overflow = "hidden";
</script>
<?php elseif ($show_qty_form): ?>
  <script>window.location.href="menu.php";</script>
<?php endif; ?>

<?php include('../includes/footer.php'); ?>