<?php
session_start();
include('includes/db.php');
include('includes/header.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        header('Location: index.php');
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<section class="min-h-screen flex items-center justify-center bg-yellow-50 px-4 py-12">
  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold text-center mb-6 text-red-600">Login</h2>
    <?php if ($error): ?>
      <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form action="login.php" method="POST" class="space-y-4">
      <input type="email" name="email" placeholder="Email Address" class="w-full border rounded px-4 py-2" required>
      <input type="password" name="password" placeholder="Password" class="w-full border rounded px-4 py-2" required>
      <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">Login</button>
    </form>
    <p class="text-sm text-center mt-4 text-gray-600">
      Don’t have an account? <a href="register.php" class="text-red-600 hover:underline">Register</a>
    </p>
  </div>
</section>

<?php include('includes/footer.php'); ?>