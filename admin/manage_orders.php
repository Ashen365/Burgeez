<?php
include('../includes/db.php');
include('../includes/header.php');

// Handle status update
if (isset($_POST['order_id']) && isset($_POST['status'])) {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['order_id']]);
    header("Location: manage_orders.php?msg=updated");
    exit;
}

// Fetch orders with user info
$stmt = $pdo->query("
    SELECT orders.*, users.username, users.email
    FROM orders
    JOIN users ON orders.user_id = users.id
    ORDER BY orders.created_at DESC
");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="max-w-5xl mx-auto mt-12 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Manage Orders</h2>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
        <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">Order status updated.</div>
    <?php endif; ?>

    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 p-2 text-left">Order ID</th>
                <th class="border border-gray-300 p-2 text-left">Customer</th>
                <th class="border border-gray-300 p-2 text-left">Total</th>
                <th class="border border-gray-300 p-2 text-left">Status</th>
                <th class="border border-gray-300 p-2 text-left">Date</th>
                <th class="border border-gray-300 p-2 text-left">Update</th>
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
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>
