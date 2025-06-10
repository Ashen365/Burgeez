<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// ✅ Connect to DB
include('../includes/db.php');

// ✅ Fetch real counts from database
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalCustomers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalMenuItems = $pdo->query("SELECT COUNT(*) FROM menu_items WHERE is_active = 1")->fetchColumn();
?>

<?php include('../includes/header.php'); ?>

<div class="min-h-screen flex bg-gray-100">
  <!-- Sidebar -->
  <aside class="w-64 bg-white shadow-lg hidden md:block">
    <div class="p-6 text-center border-b">
      <h2 class="text-xl font-bold text-red-600">Burgeez Admin</h2>
    </div>
    <nav class="p-4 space-y-2">
      <a href="dashboard.php" class="block px-4 py-2 rounded hover:bg-red-100 text-red-600 font-medium">Dashboard</a>
      <a href="manage_menu.php" class="block px-4 py-2 rounded hover:bg-red-100">Manage Menu</a>
      <a href="manage_orders.php" class="block px-4 py-2 rounded hover:bg-red-100">Orders</a>
      <a href="manage_users.php" class="block px-4 py-2 rounded hover:bg-red-100">Users</a>
      <a href="../index.php" class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-500 mt-8">Back to Site</a>
      <a href="logout.php" class="block px-4 py-2 text-red-500 hover:underline">Logout</a>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-4">Welcome to Admin Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Total Orders -->
      <div class="bg-white p-4 rounded shadow text-center">
        <p class="text-sm text-gray-500">Total Orders</p>
        <h2 class="text-2xl font-bold text-red-600"><?= $totalOrders ?></h2>
      </div>

      <!-- Total Customers -->
      <div class="bg-white p-4 rounded shadow text-center">
        <p class="text-sm text-gray-500">Total Customers</p>
        <h2 class="text-2xl font-bold text-red-600"><?= $totalCustomers ?></h2>
      </div>

      <!-- Total Active Menu Items -->
      <div class="bg-white p-4 rounded shadow text-center">
        <p class="text-sm text-gray-500">Menu Items</p>
        <h2 class="text-2xl font-bold text-red-600"><?= $totalMenuItems ?></h2>
      </div>
    </div>
  </main>
</div>

<?php include('../includes/footer.php'); ?>
