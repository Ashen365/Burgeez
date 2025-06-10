<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}
?>

<?php
include('../includes/db.php');
include('../includes/header.php');

// Handle Soft Delete Request
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    // Get image file name before soft delete (optional, can be used for audit)
    $stmt = $pdo->prepare("SELECT image FROM menu_items WHERE id = ?");
    $stmt->execute([$id]);
    $image = $stmt->fetchColumn();

    // Soft delete the item
    $stmt = $pdo->prepare("UPDATE menu_items SET is_active = 0 WHERE id = ?");
    if ($stmt->execute([$id])) {
        header("Location: manage_menu.php?msg=deleted");
        exit;
    }
}

$msg = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'deleted') {
        $msg = "Menu item deleted successfully.";
    }
}
?>

<div class="max-w-5xl mx-auto mt-12 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Manage Menu Items</h2>

    <?php if ($msg): ?>
        <div class="mb-4 p-3 bg-green-200 text-green-800 rounded"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <a href="add_menu.php" class="inline-block mb-4 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">+ Add New Item</a>

    <!-- Search bar -->
    <input type="text" id="searchInput" placeholder="Search by name..." 
           class="mb-4 px-3 py-2 border rounded w-full max-w-sm"
           onkeyup="filterTable()" />

    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 p-2 text-left">Image</th>
                <th class="border border-gray-300 p-2 text-left">Name</th>
                <th class="border border-gray-300 p-2 text-left">Price (Rs.)</th>
                <th class="border border-gray-300 p-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM menu_items WHERE is_active = 1 ORDER BY id DESC");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) === 0): ?>
                <tr>
                    <td colspan="4" class="text-center p-4">No menu items found.</td>
                </tr>
            <?php else:
                foreach ($rows as $row): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 p-2">
                            <img src="../assets/images/<?= htmlspecialchars($row['image']) ?>" 
                                 alt="<?= htmlspecialchars($row['name']) ?>" 
                                 class="w-24 h-16 object-cover rounded" />
                        </td>
                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($row['name']) ?></td>
                        <td class="border border-gray-300 p-2"><?= 'Rs. ' . number_format($row['price'], 2) ?></td>
                        <td class="border border-gray-300 p-2 space-x-2">
                            <a href="edit_menu.php?id=<?= $row['id'] ?>" 
                               class="inline-block bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                               Update
                            </a>
                            <a href="manage_menu.php?delete=<?= $row['id'] ?>" 
                               onclick="return confirm('Are you sure you want to delete this item?');" 
                               class="text-red-600 hover:underline px-2 py-1 inline-block">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach;
            endif; ?>
        </tbody>
    </table>
</div>

<!-- Filter Script -->
<script>
function filterTable() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const name = row.cells[1].textContent.toLowerCase();
        row.style.display = name.includes(input) ? '' : 'none';
    });
}
</script>

<?php include('../includes/footer.php'); ?>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#searchInput').on('keyup', function() {
        filterTable();
    });
});
</script>
