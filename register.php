<?php
session_start();
include('includes/db.php');
include('includes/header.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate required fields
    if (!$username || !$name || !$email || !$password) {
        $error = "All fields are required.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
        $error = "Username must be 3-30 characters, letters, numbers, or underscores.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Check for duplicate username
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = "Username already taken.";
        } else {
            // Check for duplicate email
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Email already registered.";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, name, email, password, created_at) VALUES (?, ?, ?, ?, NOW())");
                if ($stmt->execute([$username, $name, $email, $hashed])) {
                    $_SESSION['user_id'] = $pdo->lastInsertId();
                    $_SESSION['name'] = $name;
                    header('Location: index.php');
                    exit;
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        }
    }
}
?>

<section class="min-h-screen flex items-center justify-center bg-yellow-50 px-4 py-12">
  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold text-center mb-6 text-red-600">Create an Account</h2>
    <?php if ($error): ?>
      <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form action="register.php" method="POST" class="space-y-4">
      <input type="text" name="username" placeholder="Username" class="w-full border rounded px-4 py-2" required>
      <input type="text" name="name" placeholder="Full Name" class="w-full border rounded px-4 py-2" required>
      <input type="email" name="email" placeholder="Email Address" class="w-full border rounded px-4 py-2" required>
      <input type="password" name="password" placeholder="Password" class="w-full border rounded px-4 py-2" required>
      <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">Register</button>
    </form>
    <p class="text-sm text-center mt-4 text-gray-600">
      Already have an account? <a href="login.php" class="text-red-600 hover:underline">Login</a>
    </p>
  </div>
</section>

<?php include('includes/footer.php'); ?>