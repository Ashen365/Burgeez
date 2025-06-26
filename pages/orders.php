<?php
session_start();
include('../includes/header.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: /burgeez/login.php');
    exit;
}

// In a real application, you would fetch orders from database
// This is a placeholder for demonstration
$orders = [
    // Sample order data - in a real app, this would come from database
    [
        'order_id' => '1001',
        'date' => '2025-06-15 14:30:22',
        'status' => 'Delivered',
        'total' => 2450.00,
        'items' => [
            ['name' => 'Classic Burger', 'qty' => 2, 'price' => 850.00],
            ['name' => 'French Fries', 'qty' => 1, 'price' => 350.00],
            ['name' => 'Coke', 'qty' => 2, 'price' => 200.00]
        ]
    ],
    [
        'order_id' => '1002',
        'date' => '2025-06-10 19:15:43',
        'status' => 'Processing',
        'total' => 1750.00,
        'items' => [
            ['name' => 'Chicken Burger', 'qty' => 1, 'price' => 950.00],
            ['name' => 'Onion Rings', 'qty' => 1, 'price' => 400.00],
            ['name' => 'Milkshake', 'qty' => 1, 'price' => 400.00]
        ]
    ]
];
?>

<!-- Orders Page -->
<section class="py-16 px-4 bg-gray-100">
  <div class="container mx-auto">
    <h2 class="text-4xl font-bold text-center text-red-600 mb-12">My Orders</h2>
    
    <?php if (count($orders) > 0): ?>
      <div class="max-w-4xl mx-auto space-y-8">
        <?php foreach ($orders as $order): ?>
          <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex flex-wrap justify-between items-center mb-4 pb-4 border-b">
              <div>
                <h3 class="font-bold text-lg">Order #<?php echo $order['order_id']; ?></h3>
                <p class="text-gray-600 text-sm">Placed on: <?php echo $order['date']; ?></p>
              </div>
              <div>
                <span class="px-3 py-1 rounded-full text-sm font-medium
                  <?php echo $order['status'] === 'Delivered' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                  <?php echo $order['status']; ?>
                </span>
              </div>
            </div>
            
            <div class="space-y-4">
              <div class="overflow-x-auto">
                <table class="min-w-full">
                  <thead>
                    <tr class="border-b text-gray-600">
                      <th class="text-left pb-2">Item</th>
                      <th class="text-left pb-2">Quantity</th>
                      <th class="text-left pb-2">Price</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($order['items'] as $item): ?>
                      <tr>
                        <td class="py-2"><?php echo htmlspecialchars($item['name']); ?></td>
                        <td class="py-2"><?php echo $item['qty']; ?></td>
                        <td class="py-2">Rs. <?php echo number_format($item['price'], 2); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              
              <div class="text-right font-bold">
                Total: Rs. <?php echo number_format($order['total'], 2); ?>
              </div>
              
              <div class="flex justify-end space-x-4 mt-4">
                <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">
                  <i class="fas fa-receipt mr-1"></i> View Receipt
                </a>
                <a href="#" class="text-red-600 hover:text-red-800 text-sm">
                  <i class="fas fa-sync-alt mr-1"></i> Reorder
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md text-center">
        <div class="text-5xl text-gray-300 mb-4">
          <i class="fas fa-clipboard-list"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-700 mb-2">No Orders Yet</h3>
        <p class="text-gray-500 mb-6">You haven't placed any orders yet.</p>
        <a href="/burgeez/pages/menu.php" class="inline-block bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
          Browse Menu
        </a>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php include('../includes/footer.php'); ?>