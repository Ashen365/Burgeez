<?php
include('../includes/db.php');

// Start session only once at the beginning
session_start();

// Handle status update
if (isset($_POST['order_id']) && isset($_POST['status'])) {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['order_id']]);
    
    $_SESSION['order_message'] = 'Order status updated successfully.';
    
    header("Location: manage_orders.php");
    exit;
}

// Handle order update (from modal form)
if (isset($_POST['update_order']) && isset($_POST['edit_order_id'])) {
    $order_id = $_POST['edit_order_id'];
    $total = $_POST['edit_total'];
    
    try {
        $stmt = $pdo->prepare("UPDATE orders SET total = ? WHERE id = ?");
        $stmt->execute([$total, $order_id]);
        
        $_SESSION['order_message'] = 'Order details updated successfully.';
    } catch (PDOException $e) {
        $_SESSION['order_error'] = 'Error updating order: ' . $e->getMessage();
    }
    
    header("Location: manage_orders.php");
    exit;
}

// Include header AFTER any redirects
include('../includes/header.php');

// Fetch orders with user info and order items
$stmt = $pdo->query("
    SELECT orders.*, users.username, users.email
    FROM orders
    JOIN users ON orders.user_id = users.id
    ORDER BY orders.created_at DESC
");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check for message in session
$message = '';
$messageType = 'success';
if (isset($_SESSION['order_message'])) {
    $message = $_SESSION['order_message'];
    unset($_SESSION['order_message']);
}
if (isset($_SESSION['order_error'])) {
    $message = $_SESSION['order_error'];
    $messageType = 'error';
    unset($_SESSION['order_error']);
}
?>

<div class="max-w-5xl mx-auto mt-12 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Manage Orders</h2>

    <?php if ($message): ?>
        <div class="mb-4 p-3 <?= $messageType === 'success' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' ?> rounded">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 p-2 text-left">Order ID</th>
                <th class="border border-gray-300 p-2 text-left">Customer</th>
                <th class="border border-gray-300 p-2 text-left">Total</th>
                <th class="border border-gray-300 p-2 text-left">Status</th>
                <th class="border border-gray-300 p-2 text-left">Date</th>
                <th class="border border-gray-300 p-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($orders) === 0): ?>
                <tr>
                    <td colspan="6" class="text-center p-4">No orders found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 p-2"><?= $order['id'] ?></td>
                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($order['username']) ?> <br><small><?= htmlspecialchars($order['email']) ?></small></td>
                        <td class="border border-gray-300 p-2">LKR <?= number_format($order['total'], 2) ?></td>
                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($order['status']) ?></td>
                        <td class="border border-gray-300 p-2"><?= $order['created_at'] ?></td>
                        <td class="border border-gray-300 p-2">
                            <div class="flex gap-2 flex-col sm:flex-row">
                                <!-- Status update form -->
                                <form method="POST" class="flex gap-2">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <select name="status" class="border rounded p-1 text-sm">
                                        <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="preparing" <?= $order['status'] === 'preparing' ? 'selected' : '' ?>>Preparing</option>
                                        <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                        <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" class="text-white bg-red-500 hover:bg-red-600 px-2 py-1 rounded text-xs">Update</button>
                                </form>
                                
                                <!-- Edit button -->
                                <button type="button" 
                                        onclick="openEditModal(<?= $order['id'] ?>, '<?= htmlspecialchars($order['username']) ?>', '<?= number_format($order['total'], 2) ?>')" 
                                        class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded text-xs">
                                    Edit
                                </button>
                                
                                <!-- View details button -->
                                <button type="button" 
                                        onclick="viewOrderDetails(<?= $order['id'] ?>)" 
                                        class="text-gray-700 bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded text-xs">
                                    Details
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Edit Order Modal -->
<div id="editOrderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Edit Order</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="editOrderForm" method="POST">
            <input type="hidden" id="edit_order_id" name="edit_order_id">
            <input type="hidden" name="update_order" value="1">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                <div id="edit_customer" class="bg-gray-100 p-2 rounded"></div>
            </div>
            
            <div class="mb-4">
                <label for="edit_total" class="block text-sm font-medium text-gray-700 mb-1">Total (LKR)</label>
                <input type="number" step="0.01" id="edit_total" name="edit_total" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeEditModal()" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded text-sm">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Order Details Modal -->
<div id="orderDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6 mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Order Details</h3>
            <button type="button" onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div id="orderDetailsContent" class="mt-4">
            <!-- Content will be loaded via AJAX -->
            <div class="flex justify-center">
                <svg class="animate-spin h-8 w-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>
        
        <div class="flex justify-end mt-6">
            <button type="button" onclick="closeDetailsModal()" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded text-sm">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    // Edit Order Modal Functions
    function openEditModal(orderId, customer, total) {
        document.getElementById('edit_order_id').value = orderId;
        document.getElementById('edit_customer').textContent = customer;
        document.getElementById('edit_total').value = parseFloat(total.replace(/,/g, ''));
        
        document.getElementById('editOrderModal').classList.remove('hidden');
    }
    
    function closeEditModal() {
        document.getElementById('editOrderModal').classList.add('hidden');
    }
    
    // Order Details Modal Functions
    function viewOrderDetails(orderId) {
        // Show the modal first with loading state
        document.getElementById('orderDetailsModal').classList.remove('hidden');
        document.getElementById('orderDetailsContent').innerHTML = '<div class="flex justify-center"><svg class="animate-spin h-8 w-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>';
        
        // Fetch order details using AJAX
        fetch(`get_order_details.php?id=${orderId}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('orderDetailsContent').innerHTML = data;
            })
            .catch(error => {
                document.getElementById('orderDetailsContent').innerHTML = `<div class="text-red-500 p-4 text-center">Error loading order details: ${error.message}</div>`;
            });
    }
    
    function closeDetailsModal() {
        document.getElementById('orderDetailsModal').classList.add('hidden');
    }
</script>

<?php include('../includes/footer.php'); ?>