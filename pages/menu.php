<?php
// Start session first, before any output or includes
session_start();

// Then include other files
// Use require_once instead of include to prevent multiple inclusions
require_once($_SERVER['DOCUMENT_ROOT'] . '/burgeez/includes/db.php');

// Buffer output to catch any potential output from header.php
ob_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/burgeez/includes/header.php');

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
    
    // Clear any output before redirect
    ob_clean();
    // Use absolute URL for redirect
    header("Location: /burgeez/pages/cart.php");
    exit;
}
?>

<!-- Menu Section -->
<section class="py-16 px-4 bg-gray-50">
<<<<<<< HEAD
  <div class="container mx-auto">
    <h2 class="text-4xl font-bold text-center text-red-600 mb-4">Our Menu</h2>
    <p class="text-gray-600 text-center mb-12 max-w-2xl mx-auto">Discover our selection of handcrafted gourmet burgers made with premium ingredients</p>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php foreach ($menu_items as $item): ?>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
          <div class="relative overflow-hidden group">
            <img src="/burgeez/assets/images/<?= htmlspecialchars($item['image']) ?>" 
                 alt="<?= htmlspecialchars($item['name']) ?>" 
                 class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105" 
                 onerror="this.src='/burgeez/assets/images/default-burger.jpg';" />
            
            <?php if (!empty($item['featured']) && $item['featured'] == 1): ?>
              <div class="absolute top-4 left-4 bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                Featured
              </div>
            <?php endif; ?>
          </div>
          
          <div class="p-6">
            <div class="flex justify-between items-center mb-3">
              <h3 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($item['name']) ?></h3>
              <span class="text-xl font-bold text-red-600">Rs. <?= number_format($item['price'], 2) ?></span>
            </div>
            
            <p class="text-gray-600 mb-4 line-clamp-3"><?= htmlspecialchars($item['description']) ?></p>
            
            <?php if (isset($item['spicy_level']) && $item['spicy_level'] > 0): ?>
              <div class="flex items-center mb-4">
                <span class="text-sm text-gray-600 mr-2">Spiciness:</span>
                <?php for($i = 1; $i <= 5; $i++): ?>
                  <i class="fas fa-pepper-hot text-<?= $i <= $item['spicy_level'] ? 'red' : 'gray' ?>-<?= $i <= $item['spicy_level'] ? '500' : '300' ?> mr-1"></i>
                <?php endfor; ?>
              </div>
            <?php endif; ?>
            
            <form method="POST" action="/burgeez/pages/menu.php">
              <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
              <button type="submit" name="add_to_cart" class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-medium transition-colors flex items-center justify-center">
                <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
              </button>
            </form>
          </div>
=======
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
>>>>>>> 3447ad93c4a0f374f0af178462f763802e5a3c91
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php if ($show_qty_form && $qty_item): ?>
<!-- Quantity Modal -->
<div id="qty-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
  <div class="bg-white p-8 rounded-xl shadow-2xl max-w-md w-full animate__animated animate__fadeInUp">
    <h3 class="text-2xl font-bold mb-4">Add to Cart</h3>
    <p class="mb-6">How many <span class="text-red-600 font-medium"><?= htmlspecialchars($qty_item['name']) ?></span> would you like?</p>
    
    <form method="POST" action="/burgeez/pages/menu.php" class="flex flex-col gap-4">
      <input type="hidden" name="item_id" value="<?= $qty_item['id'] ?>">
      
      <div class="flex items-center border rounded-lg overflow-hidden">
        <button type="button" onclick="decrementQty()" class="bg-gray-100 px-4 py-3 hover:bg-gray-200 transition">−</button>
        <input type="number" id="quantity-input" name="quantity" min="1" value="1" required 
              class="border-0 text-center w-full focus:ring-0 focus:outline-none" />
        <button type="button" onclick="incrementQty()" class="bg-gray-100 px-4 py-3 hover:bg-gray-200 transition">+</button>
      </div>
      
      <div class="flex gap-3 mt-2">
        <a href="/burgeez/pages/menu.php" class="w-1/2 text-center border border-gray-300 px-4 py-3 rounded-lg hover:bg-gray-50 transition">
          Cancel
        </a>
        <button type="submit" name="confirm_add_to_cart" class="w-1/2 bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 transition">
          Add to Cart
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  // Prevent page scroll while modal is open
  document.body.style.overflow = "hidden";
  
  // Quantity controls
  function incrementQty() {
    const input = document.getElementById('quantity-input');
    input.value = parseInt(input.value) + 1;
  }
  
  function decrementQty() {
    const input = document.getElementById('quantity-input');
    if (parseInt(input.value) > 1) {
      input.value = parseInt(input.value) - 1;
    }
  }
</script>
<?php elseif ($show_qty_form): ?>
  <script>window.location.href="/burgeez/pages/menu.php";</script>
<?php endif; ?>

<?php 
// Include footer
require_once($_SERVER['DOCUMENT_ROOT'] . '/burgeez/includes/footer.php'); 
// Flush the output buffer
ob_end_flush();
?>