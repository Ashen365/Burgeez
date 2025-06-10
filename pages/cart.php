<?php
session_start();
include('../includes/header.php');

// Get cart from session
$cart = $_SESSION['cart'] ?? [];
?>

<!-- Cart Page -->
<section class="py-16 px-4 bg-gray-100">
  <h2 class="text-4xl font-bold text-center text-red-600 mb-12">Your Cart</h2>
  <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <?php if (count($cart) > 0): ?>
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="border-b text-gray-600">
            <th class="pb-2">Item</th>
            <th class="pb-2">Price</th>
            <th class="pb-2">Quantity</th>
            <th class="pb-2">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $total = 0;
            foreach ($cart as $item):
              $subtotal = $item['price'] * $item['qty'];
              $total += $subtotal;
          ?>
            <tr class="border-b">
              <td class="py-2"><?php echo htmlspecialchars($item['name']); ?></td>
              <td class="py-2">Rs. <?php echo number_format($item['price'], 2); ?></td>
              <td class="py-2"><?php echo $item['qty']; ?></td>
              <td class="py-2">Rs. <?php echo number_format($subtotal, 2); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" class="text-right font-bold pt-4">Total:</td>
            <td class="pt-4 font-bold">Rs. <?php echo number_format($total, 2); ?></td>
          </tr>
        </tfoot>
      </table>
      <div class="mt-6 text-right">
        <a href="/burgeez/pages/checkout.php" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">Proceed to Checkout</a>
      </div>
    <?php else: ?>
      <p class="text-center text-gray-600">Your cart is empty.</p>
    <?php endif; ?>
  </div>
</section>

<?php include('../includes/footer.php'); ?>