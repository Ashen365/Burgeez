<?php
// Start output buffering to prevent "headers already sent" error
ob_start();

// Start session at the very beginning
session_start();

// Get cart from session
$cart = $_SESSION['cart'] ?? [];

// If there's a redirect needed, do it before including the header
if (isset($_GET['removed']) || isset($_GET['updated'])) {
    // Store any messages in session instead of using header redirects
    if (isset($_GET['removed'])) {
        $_SESSION['message'] = "Item removed from cart successfully!";
        $_SESSION['message_type'] = "success";
    } elseif (isset($_GET['updated'])) {
        $_SESSION['message'] = "Cart updated successfully!";
        $_SESSION['message_type'] = "success";
    }
    // Redirect without using header() function
    echo "<script>window.location = '/burgeez/pages/cart.php';</script>";
    exit;
}

// Now it's safe to include the header
include('../includes/header.php');
?>

<!-- Cart Page -->
<section class="py-12 px-4 bg-gradient-to-br from-red-50 to-white">
  <div class="container mx-auto max-w-6xl">
    <div class="flex flex-col items-center justify-center mb-10">
      <div class="bg-red-100 rounded-full p-4 mb-4">
        <i class="fas fa-shopping-cart text-2xl text-red-600"></i>
      </div>
      <h2 class="text-4xl font-bold text-gray-800 mb-2">Your Cart</h2>
      <p class="text-gray-600">Review your items before checking out</p>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
    <div class="mb-6 flex justify-center">
      <div class="px-4 py-3 rounded-lg <?= $_SESSION['message_type'] === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?> max-w-lg text-center">
        <p><?= $_SESSION['message'] ?></p>
      </div>
    </div>
    <?php 
      // Clear the message after displaying it
      unset($_SESSION['message']); 
      unset($_SESSION['message_type']);
    endif; 
    ?>

    <?php if (empty($cart)): ?>
    <!-- Empty Cart -->
    <div class="text-center py-16">
      <div class="mb-6">
        <img src="/burgeez/assets/images/empty-cart.svg" alt="Empty Cart" class="w-48 h-48 mx-auto opacity-75"
             onerror="this.src='https://cdn-icons-png.flaticon.com/512/2038/2038854.png'; this.style.width='120px'; this.style.height='120px'; this.onerror=null;">
      </div>
      <h3 class="text-2xl font-bold text-gray-700 mb-2">Your cart is empty</h3>
      <p class="text-gray-500 mb-8">Looks like you haven't added any items to your cart yet.</p>
      <a href="/burgeez/pages/menu.php" 
         class="px-8 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg font-medium shadow-md hover:from-red-600 hover:to-red-700 transition">
        <i class="fas fa-utensils mr-2"></i> Browse Menu
      </a>
    </div>
    <?php else: ?>
    
    <!-- Cart Items -->
    <div class="grid md:grid-cols-3 gap-8">
      <div class="md:col-span-2">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
          <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
              <i class="fas fa-shopping-basket text-red-500 mr-2"></i>
              Your Items <span class="ml-2 text-gray-500 text-sm">(<?= count($cart) ?> items)</span>
            </h3>
          </div>
          
          <div class="divide-y">
            <?php foreach ($cart as $item_id => $item): ?>
            <div class="p-6 flex flex-col sm:flex-row">
              <div class="flex-shrink-0 w-full sm:w-24 h-24 bg-gray-100 rounded-lg overflow-hidden mb-4 sm:mb-0 sm:mr-4">
                <img 
                  src="/burgeez/assets/images/<?= htmlspecialchars($item['image'] ?? 'default-burger.jpg') ?>" 
                  alt="<?= htmlspecialchars($item['name']) ?>" 
                  class="w-full h-full object-cover"
                  onerror="this.src='/burgeez/assets/images/default-burger.jpg';">
              </div>
              <div class="flex-grow flex flex-col sm:flex-row items-start sm:items-center justify-between">
                <div class="mb-3 sm:mb-0">
                  <h4 class="font-medium text-gray-800"><?= htmlspecialchars($item['name']) ?></h4>
                  <p class="text-sm text-gray-500"><?= htmlspecialchars($item['description'] ?? '') ?></p>
                  <div class="mt-1 text-red-600 font-medium">Rs. <?= number_format($item['price'], 2) ?></div>
                </div>
                
                <div class="flex items-center space-x-4">
                  <form action="/burgeez/update_cart.php" method="post" class="flex items-center">
                    <input type="hidden" name="item_id" value="<?= $item_id ?>">
                    <button type="submit" name="action" value="decrease" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition">
                      <i class="fas fa-minus text-xs text-gray-600"></i>
                    </button>
                    <span class="mx-3 w-6 text-center"><?= $item['qty'] ?></span>
                    <button type="submit" name="action" value="increase" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition">
                      <i class="fas fa-plus text-xs text-gray-600"></i>
                    </button>
                  </form>
                  
                  <form action="/burgeez/update_cart.php" method="post">
                    <input type="hidden" name="item_id" value="<?= $item_id ?>">
                    <button type="submit" name="action" value="remove" class="text-gray-400 hover:text-red-600 transition">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </form>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          
          <div class="p-6 bg-gray-50">
            <div class="flex items-center justify-between">
              <a href="/burgeez/pages/menu.php" class="text-red-600 hover:text-red-700 transition flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Continue Shopping
              </a>
              <form action="/burgeez/update_cart.php" method="post">
                <button type="submit" name="action" value="clear" class="text-gray-600 hover:text-gray-700 transition flex items-center">
                  <i class="fas fa-times mr-2"></i> Clear Cart
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
      
      <div class="md:col-span-1">
        <div class="bg-white p-6 rounded-xl shadow-md sticky top-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h3>
          
          <?php
            // Calculate totals
            $subtotal = 0;
            foreach ($cart as $item) {
              $subtotal += $item['price'] * $item['qty'];
            }
            
            // Calculate delivery fee
            $delivery_fee = ($subtotal > 2000) ? 0 : 250;
            $total = $subtotal + $delivery_fee;
          ?>
          
          <div class="space-y-2 mb-4">
            <div class="flex justify-between">
              <span class="text-gray-600">Subtotal</span>
              <span>Rs. <?= number_format($subtotal, 2) ?></span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Delivery</span>
              <span><?= ($delivery_fee === 0) ? '<span class="text-green-600">FREE</span>' : 'Rs. ' . number_format($delivery_fee, 2) ?></span>
            </div>
            
            <?php if ($subtotal < 2000): ?>
            <div class="bg-yellow-50 text-yellow-800 text-xs p-2 rounded mt-2">
              <i class="fas fa-info-circle mr-1"></i>
              Add items worth Rs. <?= number_format(2000 - $subtotal, 2) ?> more for free delivery!
            </div>
            <?php endif; ?>
          </div>
          
          <div class="border-t pt-4 mb-6">
            <div class="flex justify-between items-center">
              <span class="font-bold text-gray-800">Total</span>
              <span class="font-bold text-xl text-red-600">Rs. <?= number_format($total, 2) ?></span>
            </div>
          </div>
          
          <a href="/burgeez/pages/checkout.php" class="block w-full py-3 px-4 bg-gradient-to-r from-red-600 to-red-700 text-white text-center rounded-lg font-medium shadow-md hover:from-red-700 hover:to-red-800 transition">
            Proceed to Checkout <i class="fas fa-arrow-right ml-2"></i>
          </a>
          
          <div class="mt-4 flex items-center justify-center text-gray-500 text-sm">
            <i class="fas fa-lock mr-2"></i> Secure Checkout
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php 
include('../includes/footer.php');
// End output buffering and flush the output
ob_end_flush();
?>