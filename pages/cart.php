<?php
session_start();
include('../includes/header.php');

// Cart data from session
$cart = $_SESSION['cart'] ?? [];

// Calculate totals
$subtotal = 0;
foreach ($cart as $item) {
  $subtotal += $item['price'] * $item['qty'];
}

// Calculate delivery fee
$delivery_fee = ($subtotal > 2000) ? 0 : 250;
$total = $subtotal + $delivery_fee;

// Check if cart is empty
if (empty($cart)) {
  header("Location: /burgeez/pages/cart.php");
  exit;
}
?>

<!-- Checkout Page -->
<section class="py-12 px-4 bg-gradient-to-br from-red-50 to-white">
  <div class="container mx-auto max-w-6xl">
    <div class="flex flex-col items-center justify-center mb-10">
      <div class="bg-red-100 rounded-full p-4 mb-4">
        <i class="fas fa-credit-card text-2xl text-red-600"></i>
      </div>
      <h2 class="text-4xl font-bold text-gray-800 mb-2">Checkout</h2>
      <p class="text-gray-600">Complete your order in a few simple steps</p>
    </div>

    <!-- Checkout Steps -->
    <div class="flex justify-center mb-10">
      <div class="relative flex items-center text-sm font-medium">
        <div class="flex flex-col items-center">
          <div class="bg-red-600 text-white w-8 h-8 rounded-full flex items-center justify-center">
            <i class="fas fa-shopping-cart"></i>
          </div>
          <span class="mt-1 text-gray-800">Cart</span>
        </div>
        <div class="w-16 sm:w-32 h-1 bg-red-600 mx-1 sm:mx-3"></div>
        <div class="flex flex-col items-center">
          <div class="bg-red-600 text-white w-8 h-8 rounded-full flex items-center justify-center">
            <i class="fas fa-file-invoice"></i>
          </div>
          <span class="mt-1 font-semibold text-red-600">Checkout</span>
        </div>
        <div class="w-16 sm:w-32 h-1 bg-gray-300 mx-1 sm:mx-3"></div>
        <div class="flex flex-col items-center">
          <div class="bg-gray-200 text-gray-500 w-8 h-8 rounded-full flex items-center justify-center">
            <i class="fas fa-check"></i>
          </div>
          <span class="mt-1 text-gray-500">Complete</span>
        </div>
      </div>
    </div>

    <div class="grid md:grid-cols-5 gap-8">
      <!-- Left Column - Customer Form -->
      <div class="md:col-span-3">
        <div class="bg-white p-6 rounded-xl shadow-md">
          <form action="/burgeez/place_order.php" method="POST" id="checkout-form">
            <!-- Personal Details -->
            <div class="mb-8">
              <h3 class="text-xl font-semibold mb-4 text-gray-800 flex items-center">
                <i class="fas fa-user-circle mr-2 text-red-500"></i> Personal Details
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label for="name" class="block text-gray-700 text-sm font-medium mb-1">Full Name</label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input 
                      type="text" 
                      id="name" 
                      name="name" 
                      class="pl-10 w-full py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition" 
                      placeholder="John Doe" 
                      required
                    />
                  </div>
                </div>
                <div>
                  <label for="email" class="block text-gray-700 text-sm font-medium mb-1">Email Address</label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input 
                      type="email" 
                      id="email" 
                      name="email" 
                      class="pl-10 w-full py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition" 
                      placeholder="email@example.com" 
                      required
                    />
                  </div>
                </div>
                <div>
                  <label for="phone" class="block text-gray-700 text-sm font-medium mb-1">Phone Number</label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <i class="fas fa-phone text-gray-400"></i>
                    </div>
                    <input 
                      type="tel" 
                      id="phone" 
                      name="phone" 
                      class="pl-10 w-full py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition" 
                      placeholder="0712345678" 
                      required
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- Delivery Details -->
            <div class="mb-8">
              <h3 class="text-xl font-semibold mb-4 text-gray-800 flex items-center">
                <i class="fas fa-truck mr-2 text-red-500"></i> Delivery Details
              </h3>
              <div class="mb-4">
                <label for="address" class="block text-gray-700 text-sm font-medium mb-1">Delivery Address</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 pt-3 pointer-events-none">
                    <i class="fas fa-home text-gray-400"></i>
                  </div>
                  <textarea 
                    id="address" 
                    name="address" 
                    rows="3" 
                    class="pl-10 w-full py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition" 
                    placeholder="Enter your full delivery address" 
                    required
                  ></textarea>
                </div>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label for="city" class="block text-gray-700 text-sm font-medium mb-1">City</label>
                  <input 
                    type="text" 
                    id="city" 
                    name="city" 
                    class="w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition" 
                    placeholder="Colombo" 
                    required
                  />
                </div>
                <div>
                  <label for="postal_code" class="block text-gray-700 text-sm font-medium mb-1">Postal Code</label>
                  <input 
                    type="text" 
                    id="postal_code" 
                    name="postal_code" 
                    class="w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition" 
                    placeholder="10300" 
                  />
                </div>
              </div>
            </div>

            <!-- Delivery Options -->
            <div class="mb-8">
              <h3 class="text-xl font-semibold mb-4 text-gray-800 flex items-center">
                <i class="fas fa-clock mr-2 text-red-500"></i> Delivery Options
              </h3>
              <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex items-center mb-3">
                  <input type="radio" id="delivery-standard" name="delivery_option" value="standard" checked class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                  <label for="delivery-standard" class="ml-2 flex items-center justify-between w-full">
                    <div>
                      <span class="text-sm font-medium text-gray-900">Standard Delivery</span>
                      <p class="text-xs text-gray-500">Delivery within 45-60 minutes</p>
                    </div>
                    <span class="text-sm font-medium text-gray-900">
                      <?= ($delivery_fee === 0) ? '<span class="text-green-600">FREE</span>' : 'Rs. ' . number_format($delivery_fee, 2) ?>
                    </span>
                  </label>
                </div>
                <div class="flex items-center">
                  <input type="radio" id="delivery-express" name="delivery_option" value="express" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                  <label for="delivery-express" class="ml-2 flex items-center justify-between w-full">
                    <div>
                      <span class="text-sm font-medium text-gray-900">Express Delivery</span>
                      <p class="text-xs text-gray-500">Delivery within 30 minutes</p>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Rs. <?= number_format($delivery_fee + 150, 2) ?></span>
                  </label>
                </div>
              </div>
            </div>
            
            <!-- Payment Method -->
            <div class="mb-8">
              <h3 class="text-xl font-semibold mb-4 text-gray-800 flex items-center">
                <i class="fas fa-money-check-alt mr-2 text-red-500"></i> Payment Method
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="border border-gray-200 rounded-lg p-4 flex items-center cursor-pointer hover:border-red-500 transition-colors">
                  <input type="radio" name="payment_method" value="cash" checked class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                  <span class="ml-2 flex items-center">
                    <i class="fas fa-money-bill-wave text-green-600 mr-2 text-xl"></i>
                    <span>Cash on Delivery</span>
                  </span>
                </label>
                <label class="border border-gray-200 rounded-lg p-4 flex items-center cursor-pointer hover:border-red-500 transition-colors">
                  <input type="radio" name="payment_method" value="card" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                  <span class="ml-2 flex items-center">
                    <i class="fas fa-credit-card text-blue-600 mr-2 text-xl"></i>
                    <span>Credit/Debit Card</span>
                  </span>
                </label>
              </div>
            </div>

            <!-- Special Instructions -->
            <div class="mb-8">
              <h3 class="text-lg font-medium mb-2 text-gray-800">Special Instructions (Optional)</h3>
              <textarea 
                name="instructions" 
                rows="2" 
                class="w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition" 
                placeholder="Any special instructions for delivery or food preparation"
              ></textarea>
            </div>

            <div class="lg:hidden mb-8">
              <h3 class="text-xl font-semibold mb-4 text-gray-800">Order Summary</h3>
              <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex justify-between text-sm mb-1">
                  <span>Subtotal (<?= count($cart) ?> items)</span>
                  <span>Rs. <?= number_format($subtotal, 2) ?></span>
                </div>
                <div class="flex justify-between text-sm mb-1">
                  <span>Delivery Fee</span>
                  <span><?= ($delivery_fee === 0) ? '<span class="text-green-600">FREE</span>' : 'Rs. ' . number_format($delivery_fee, 2) ?></span>
                </div>
                <div class="flex justify-between font-bold text-lg mt-3 pt-3 border-t border-gray-200">
                  <span>Total</span>
                  <span>Rs. <?= number_format($total, 2) ?></span>
                </div>
              </div>
            </div>

            <!-- Submit Button -->
            <button 
              type="submit" 
              class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white py-4 rounded-lg font-medium shadow-md hover:from-red-700 hover:to-red-800 transition transform hover:scale-[1.01] flex items-center justify-center"
            >
              <i class="fas fa-lock mr-2"></i> Place Order - Rs. <?= number_format($total, 2) ?>
            </button>

            <div class="mt-4 text-center text-xs text-gray-500 flex items-center justify-center">
              <i class="fas fa-shield-alt mr-1 text-green-600"></i>
              Your personal data will be used to process your order and support your experience
            </div>
          </form>
        </div>
      </div>
      
      <!-- Right Column - Order Summary -->
      <div class="md:col-span-2 hidden lg:block">
        <div class="bg-white p-6 rounded-xl shadow-md sticky top-6">
          <h3 class="text-xl font-semibold mb-4 text-gray-800 flex items-center">
            <i class="fas fa-receipt mr-2 text-red-500"></i> Order Summary
          </h3>
          
          <div class="divide-y">
            <?php foreach ($cart as $item): ?>
              <div class="py-3 flex items-center">
                <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded-md overflow-hidden mr-3">
                  <img 
                    src="/burgeez/assets/images/<?= htmlspecialchars($item['image'] ?? 'default-burger.jpg') ?>" 
                    alt="<?= htmlspecialchars($item['name']) ?>" 
                    class="w-full h-full object-cover"
                    onerror="this.src='/burgeez/assets/images/default-burger.jpg';">
                </div>
                <div class="flex-grow">
                  <div class="flex justify-between">
                    <h4 class="font-medium text-gray-800">
                      <?= htmlspecialchars($item['name']) ?>
                      <span class="text-gray-500 text-sm">x<?= $item['qty'] ?></span>
                    </h4>
                    <span class="font-medium">Rs. <?= number_format($item['price'] * $item['qty'], 2) ?></span>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          
          <div class="mt-4 pt-4 border-t">
            <div class="flex justify-between text-sm mb-1">
              <span>Subtotal</span>
              <span>Rs. <?= number_format($subtotal, 2) ?></span>
            </div>
            <div class="flex justify-between text-sm mb-1">
              <span>Delivery</span>
              <span><?= ($delivery_fee === 0) ? '<span class="text-green-600">FREE</span>' : 'Rs. ' . number_format($delivery_fee, 2) ?></span>
            </div>
            <?php if ($subtotal < 2000): ?>
            <div class="bg-yellow-50 text-yellow-800 text-xs p-2 rounded mt-2 flex items-center">
              <i class="fas fa-info-circle mr-1"></i>
              Add items worth Rs. <?= number_format(2000 - $subtotal, 2) ?> more for free delivery!
            </div>
            <?php endif; ?>
            
            <div class="flex justify-between font-bold text-lg mt-3 pt-3 border-t">
              <span>Total</span>
              <span class="text-red-600">Rs. <?= number_format($total, 2) ?></span>
            </div>

            <div class="mt-6 flex flex-col items-center text-center">
              <div class="mb-2">
                <img src="/burgeez/assets/images/secure-payment.png" alt="Secure Payment" class="h-6" 
                     onerror="this.src='https://cdn-icons-png.flaticon.com/512/2519/2519367.png'; this.style='height:24px'; this.onerror=null;">
              </div>
              <div class="flex justify-center gap-1">
                <i class="fab fa-cc-visa text-2xl text-blue-800"></i>
                <i class="fab fa-cc-mastercard text-2xl text-red-500"></i>
                <i class="fab fa-cc-amex text-2xl text-blue-600"></i>
              </div>
            </div>
          </div>

          <div class="mt-6">
            <a href="/burgeez/pages/cart.php" class="text-red-600 hover:text-red-700 transition flex items-center justify-center text-sm font-medium">
              <i class="fas fa-arrow-left mr-1"></i> Return to cart
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  // Simple form validation 
  document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const phone = document.getElementById('phone').value;
    const phonePattern = /^\d{10}$/;
    
    if (!phonePattern.test(phone)) {
      e.preventDefault();
      alert('Please enter a valid 10-digit phone number');
      return false;
    }
    
    return true;
  });
</script>

<?php include('../includes/footer.php'); ?>