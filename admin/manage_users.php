<?php
include('../includes/db.php');
include('../includes/header.php');

// Handle delete user
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage_users.php?msg=deleted");
    exit;
}

// Fetch users
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Message
$msg = '';
if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') {
    $msg = "User deleted successfully.";
}
?>

<div class="max-w-5xl mx-auto mt-12 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Manage Users</h2>

    <?php if ($msg): ?>
        <div class="mb-4 p-3 bg-green-200 text-green-800 rounded"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 p-2 text-left">Name</th>
                <th class="border border-gray-300 p-2 text-left">Email</th>
                <th class="border border-gray-300 p-2 text-left">Registered</th>
                <th class="border border-gray-300 p-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($users) === 0): ?>
                <tr>
                    <td colspan="4" class="text-center p-4">No users found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($user['name']) ?></td>
                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($user['email']) ?></td>
                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($user['created_at']) ?></td>
                        <td class="border border-gray-300 p-2">
                            <a href="manage_users.php?delete=<?= $user['id'] ?>" 
                               onclick="return confirm('Are you sure you want to delete this user?');"
                               class="text-red-600 hover:underline">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>
