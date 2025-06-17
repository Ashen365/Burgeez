<?php
session_start();
include('includes/db.php');
include('includes/header.php');

$error = '';
$success = false;
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
                    $success = true;
                    // Redirect after a short delay to show success message
                    header("refresh:2;url=index.php");
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        }
    }
}
?>

<!-- Modern Registration Page -->
<section class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-yellow-50 to-red-50 relative overflow-hidden">
  <!-- Animated burger shapes in background -->
  <div class="absolute top-20 left-10 opacity-10 animate-float">
    <i class="fas fa-hamburger text-8xl text-red-400"></i>
  </div>
  <div class="absolute bottom-20 right-10 opacity-10 animate-float" style="animation-delay: 1s">
    <i class="fas fa-pizza-slice text-8xl text-red-400"></i>
  </div>
  <div class="absolute top-1/2 left-1/4 opacity-10 animate-float" style="animation-delay: 2s">
    <i class="fas fa-french-fries text-8xl text-red-400"></i>
  </div>
  
  <div class="w-full max-w-5xl flex rounded-2xl shadow-xl overflow-hidden bg-white">
    <!-- Left side - Image/Illustration -->
    <div class="hidden lg:block lg:w-1/2 bg-gradient-to-br from-red-600 to-red-800 p-12 text-white relative">
      <div class="absolute inset-0 bg-black opacity-20"></div>
      <div class="relative z-10">
        <h2 class="text-4xl font-bold mb-6">Welcome to Burgeez</h2>
        <p class="text-xl opacity-90 mb-8">Create an account to enjoy our juicy burgers, special offers and faster checkout.</p>
        
        <div class="space-y-4 mt-10">
          <div class="flex items-center">
            <div class="bg-white/20 p-2 rounded-full mr-4">
              <i class="fas fa-utensils text-white"></i>
            </div>
            <p>Order your favorite meals quickly</p>
          </div>
          <div class="flex items-center">
            <div class="bg-white/20 p-2 rounded-full mr-4">
              <i class="fas fa-history text-white"></i>
            </div>
            <p>Track your order history</p>
          </div>
          <div class="flex items-center">
            <div class="bg-white/20 p-2 rounded-full mr-4">
              <i class="fas fa-tag text-white"></i>
            </div>
            <p>Get exclusive deals and offers</p>
          </div>
        </div>
      </div>
      
      <!-- Animated food illustrations -->
      <img src="/burgeez/assets/images/burger-illustration.png" 
           alt="Burger Illustration" 
           class="absolute bottom-0 right-0 w-64 h-64 object-contain animate-float"
           onerror="this.src='https://cdn-icons-png.flaticon.com/512/3075/3075977.png'; this.onerror=null;">
    </div>
    
    <!-- Right side - Registration Form -->
    <div class="w-full lg:w-1/2 p-10">
      <div class="text-center mb-10">
        <div class="inline-block p-3 rounded-full bg-red-50 mb-3">
          <i class="fas fa-user-plus text-2xl text-red-600"></i>
        </div>
        <h2 class="text-3xl font-bold text-gray-800">Create Account</h2>
        <p class="text-gray-600 mt-2">Enter your details to register</p>
      </div>
      
      <?php if ($success): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md animate__animated animate__fadeIn">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div class="ml-3">
              <p class="text-green-700 font-medium">Registration successful! Redirecting...</p>
            </div>
          </div>
        </div>
      <?php endif; ?>
      
      <?php if ($error): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md animate__animated animate__shakeX">
          <div class="flex">
            <div class="flex-shrink-0">
              <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
            </div>
            <div class="ml-3">
              <p class="text-red-700"><?= htmlspecialchars($error) ?></p>
            </div>
          </div>
        </div>
      <?php endif; ?>
      
      <form action="register.php" method="POST" class="space-y-6">
        <div class="relative">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
            <i class="fas fa-user"></i>
          </div>
          <input type="text" name="username" placeholder="Username" 
                 class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition" 
                 required>
          <p class="mt-1 text-xs text-gray-500">3-30 characters, letters, numbers, or underscores</p>
        </div>
        
        <div class="relative">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
            <i class="fas fa-id-card"></i>
          </div>
          <input type="text" name="name" placeholder="Full Name" 
                 class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition" 
                 required>
        </div>
        
        <div class="relative">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
            <i class="fas fa-envelope"></i>
          </div>
          <input type="email" name="email" placeholder="Email Address" 
                 class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition" 
                 required>
        </div>
        
        <div class="relative">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
            <i class="fas fa-lock"></i>
          </div>
          <input type="password" name="password" id="password" placeholder="Password" 
                 class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition" 
                 required>
          <div class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" onclick="togglePassword()">
            <i class="fas fa-eye text-gray-400 hover:text-gray-600" id="password-toggle"></i>
          </div>
          <p class="mt-1 text-xs text-gray-500">At least 6 characters</p>
        </div>
        
        <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white py-3 px-4 rounded-lg hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 font-medium shadow-md transform hover:scale-[1.02] transition-all">
          <i class="fas fa-user-plus mr-2"></i> Create Account
        </button>
      </form>
      
      <div class="mt-6 text-center">
        <p class="text-gray-600">
          Already have an account? 
          <a href="login.php" class="text-red-600 hover:text-red-700 font-medium">Log In</a>
        </p>
      </div>
      
      <div class="mt-10 pt-6 border-t border-gray-200 text-center">
        <p class="text-sm text-gray-500">
          By creating an account, you agree to our
          <a href="#" class="text-red-600 hover:underline">Terms of Service</a> and
          <a href="#" class="text-red-600 hover:underline">Privacy Policy</a>
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Add some custom animations and script -->
<style>
  @keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
  }
  
  .animate-float {
    animation: float 6s ease-in-out infinite;
  }
  
  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }
  
  .animate__animated {
    animation-duration: 1s;
  }
  
  .animate__fadeIn {
    animation-name: fadeIn;
  }
  
  @keyframes shakeX {
    from,
    to { transform: translate3d(0, 0, 0); }
    10%,
    30%,
    50%,
    70%,
    90% { transform: translate3d(-10px, 0, 0); }
    20%,
    40%,
    60%,
    80% { transform: translate3d(10px, 0, 0); }
  }
  
  .animate__shakeX {
    animation-name: shakeX;
  }
</style>

<script>
  function togglePassword() {
    const passwordField = document.getElementById('password');
    const passwordToggle = document.getElementById('password-toggle');
    
    if (passwordField.type === 'password') {
      passwordField.type = 'text';
      passwordToggle.classList.remove('fa-eye');
      passwordToggle.classList.add('fa-eye-slash');
    } else {
      passwordField.type = 'password';
      passwordToggle.classList.remove('fa-eye-slash');
      passwordToggle.classList.add('fa-eye');
    }
  }
  
  // Simple validation feedback
  const form = document.querySelector('form');
  const inputs = form.querySelectorAll('input');
  
  inputs.forEach(input => {
    input.addEventListener('blur', function() {
      if (this.value.trim() === '') {
        this.classList.add('border-red-300', 'bg-red-50');
        this.classList.remove('border-green-300', 'bg-green-50');
      } else {
        this.classList.remove('border-red-300', 'bg-red-50');
        this.classList.add('border-green-300', 'bg-green-50');
      }
    });
  });
</script>

<?php include('includes/footer.php'); ?>