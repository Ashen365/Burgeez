<?php
session_start();
include('../includes/header.php');

// Cart data from session
$cart = $_SESSION['cart'] ?? [];

$total = 0;
foreach ($cart as $item) {
  $total += $item['price'] * $item['qty'];
}
?>

<!-- Checkout Page -->
<section class="py-16 px-4 bg-gray-50">
  <h2 class="text-4xl font-bold text-center text-red-600 mb-12">Checkout</h2>

  <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-8">
    <!-- Customer Form -->
    <form action="/burgeez/place_order.php" method="POST" class="bg-white p-6 rounded shadow space-y-4">
      <h3 class="text-xl font-semibold mb-4 text-gray-700">Customer Info</h3>

      <input type="text" name="name" placeholder="Full Name" required class="w-full border px-4 py-2 rounded" />
      <input type="email" name="email" placeholder="Email" required class="w-full border px-4 py-2 rounded" />
      <input type="text" name="phone" placeholder="Phone Number" required class="w-full border px-4 py-2 rounded" />
      <textarea name="address" rows="3" placeholder="Delivery Address" required class="w-full border px-4 py-2 rounded"></textarea>

      <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 transition">
        Place Order
      </button>
    </form>

    <!-- Order Summary -->
    <div class="bg-white p-6 rounded shadow">
      <h3 class="text-xl font-semibold mb-4 text-gray-700">Order Summary</h3>
      <ul class="divide-y">
        <?php foreach ($cart as $item): ?>
          <li class="py-2 flex justify-between">
            <span><?php echo htmlspecialchars($item['name']); ?> x<?php echo $item['qty']; ?></span>
            <span>Rs. <?php echo number_format($item['price'] * $item['qty'], 2); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
      <div class="mt-4 flex justify-between font-bold text-lg">
        <span>Total:</span>
        <span>Rs. <?php echo number_format($total, 2); ?></span>
      </div>
    </div>
  </div>
</section>

<?php include('../includes/footer.php'); ?>