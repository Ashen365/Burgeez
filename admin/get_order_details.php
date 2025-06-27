<?php
include('../includes/db.php');

// Ensure we have an order ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<div class="text-red-500 text-center p-4">Invalid order ID</div>';
    exit;
}

$order_id = $_GET['id'];

try {
    // Get order details
    $orderStmt = $pdo->prepare("SELECT orders.*, users.username, users.email FROM orders JOIN users ON orders.user_id = users.id WHERE orders.id = ?");
    $orderStmt->execute([$order_id]);
    $order = $orderStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        echo '<div class="text-red-500 text-center p-4">Order not found</div>';
        exit;
    }
    
    // Get order items - you'll need to have a order_items table with product details
    $itemsStmt = $pdo->prepare("SELECT oi.*, m.name, m.price FROM order_items oi JOIN menu_items m ON oi.menu_item_id = m.id WHERE oi.order_id = ?");
    $itemsStmt->execute([$order_id]);
    $orderItems = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Display order information
    ?>
    <div class="border-b pb-4">
        <h4 class="font-semibold text-lg">Order Information</h4>
        <div class="grid grid-cols-2 gap-2 mt-2">
            <div class="text-gray-600">Order ID:</div>
            <div>#<?= htmlspecialchars($order['id']) ?></div>
            
            <div class="text-gray-600">Customer:</div>
            <div><?= htmlspecialchars($order['username']) ?></div>
            
            <div class="text-gray-600">Email:</div>
            <div><?= htmlspecialchars($order['email']) ?></div>
            
            <div class="text-gray-600">Date:</div>
            <div><?= htmlspecialchars($order['created_at']) ?></div>
            
            <div class="text-gray-600">Status:</div>
            <div>
                <span class="<?= getStatusClass($order['status']) ?>"><?= ucfirst(htmlspecialchars($order['status'])) ?></span>
            </div>
            
            <div class="text-gray-600">Payment Method:</div>
            <div><?= htmlspecialchars($order['payment_method'] ?? 'Not specified') ?></div>
        </div>
    </div>
    
    <div class="mt-4">
        <h4 class="font-semibold text-lg mb-2">Ordered Items</h4>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-2 text-left">Item</th>
                    <th class="p-2 text-right">Price</th>
                    <th class="p-2 text-center">Quantity</th>
                    <th class="p-2 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php 
                $subtotal = 0;
                foreach ($orderItems as $item): 
                    $itemSubtotal = $item['price'] * $item['quantity'];
                    $subtotal += $itemSubtotal;
                ?>
                <tr>
                    <td class="p-2"><?= htmlspecialchars($item['name']) ?></td>
                    <td class="p-2 text-right">LKR <?= number_format($item['price'], 2) ?></td>
                    <td class="p-2 text-center"><?= $item['quantity'] ?></td>
                    <td class="p-2 text-right">LKR <?= number_format($itemSubtotal, 2) ?></td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (count($orderItems) === 0): ?>
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500">No items found for this order</td>
                </tr>
                <?php endif; ?>
            </tbody>
            <tfoot class="border-t-2 border-gray-200">
                <tr>
                    <td colspan="3" class="p-2 text-right font-medium">Subtotal:</td>
                    <td class="p-2 text-right">LKR <?= number_format($subtotal, 2) ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="p-2 text-right font-medium">Delivery Fee:</td>
                    <td class="p-2 text-right">LKR <?= number_format($order['delivery_fee'] ?? 0, 2) ?></td>
                </tr>
                <tr class="font-bold">
                    <td colspan="3" class="p-2 text-right">Total:</td>
                    <td class="p-2 text-right">LKR <?= number_format($order['total'], 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <?php if (!empty($order['delivery_address'])): ?>
    <div class="mt-4 border-t pt-4">
        <h4 class="font-semibold text-lg mb-2">Delivery Address</h4>
        <p class="whitespace-pre-line"><?= htmlspecialchars($order['delivery_address']) ?></p>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($order['note'])): ?>
    <div class="mt-4 border-t pt-4">
        <h4 class="font-semibold text-lg mb-2">Customer Note</h4>
        <p class="italic"><?= htmlspecialchars($order['note']) ?></p>
    </div>
    <?php endif; ?>
    
    <?php
} catch (PDOException $e) {
    echo '<div class="text-red-500 text-center p-4">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

// Helper function to get status color classes
function getStatusClass($status) {
    switch ($status) {
        case 'pending':
            return 'px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs';
        case 'preparing':
            return 'px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs';
        case 'completed':
            return 'px-2 py-1 bg-green-100 text-green-800 rounded text-xs';
        case 'cancelled':
            return 'px-2 py-1 bg-red-100 text-red-800 rounded text-xs';
        default:
            return 'px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs';
    }
}
?>