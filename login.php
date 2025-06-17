<?php
// Start output buffering and session at the very beginning
ob_start();
session_start();

// Include database connection
include('includes/db.php');

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $success = true;
        // Redirect after a short delay to show success message
        header("refresh:1.5;url=index.php");
    } else {
        $error = "Invalid email or password.";
    }
}

// Include header AFTER all possible redirects
include('includes/header.php');
?>

<!-- Modern Login Page -->
<section class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-yellow-50 to-red-50 relative overflow-hidden">
  <!-- Animated burger shapes in background -->
  <div class="absolute top-20 right-10 opacity-10 animate-float">
    <i class="fas fa-burger text-8xl text-red-400"></i>
  </div>
  <div class="absolute bottom-20 left-10 opacity-10 animate-float" style="animation-delay: 1.5s">
    <i class="fas fa-utensils text-8xl text-red-400"></i>
  </div>
  
  <div class="w-full max-w-5xl flex rounded-2xl shadow-xl overflow-hidden bg-white">
    <!-- Left side - Login Form -->
    <div class="w-full lg:w-1/2 p-10">
      <div class="text-center mb-10">
        <div class="inline-block p-3 rounded-full bg-red-50 mb-3">
          <i class="fas fa-sign-in-alt text-2xl text-red-600"></i>
        </div>
        <h2 class="text-3xl font-bold text-gray-800">Welcome Back</h2>
        <p class="text-gray-600 mt-2">Sign in to your Burgeez account</p>
      </div>
      
      <?php if ($success): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md animate__animated animate__fadeIn">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div class="ml-3">
              <p class="text-green-700 font-medium">Login successful! Redirecting...</p>
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
      
      <form action="login.php" method="POST" class="space-y-6">
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
        </div>
        
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input id="remember-me" name="remember-me" type="checkbox" 
                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
            <label for="remember-me" class="ml-2 block text-sm text-gray-700">
              Remember me
            </label>
          </div>
          
          <div class="text-sm">
            <a href="#" class="text-red-600 hover:text-red-700 font-medium">
              Forgot password?
            </a>
          </div>
        </div>
        
        <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white py-3 px-4 rounded-lg hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 font-medium shadow-md transform hover:scale-[1.02] transition-all">
          <i class="fas fa-sign-in-alt mr-2"></i> Sign In
        </button>
      </form>
      
      <div class="mt-6 text-center">
        <p class="text-gray-600">
          Don't have an account? 
          <a href="register.php" class="text-red-600 hover:text-red-700 font-medium">Create Account</a>
        </p>
      </div>
      
      <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
          <span class="px-2 bg-white text-gray-500">Or continue with</span>
        </div>
      </div>
      
      <div class="grid grid-cols-3 gap-3">
        <button type="button" class="py-2.5 px-4 border border-gray-300 rounded-lg flex justify-center items-center hover:bg-gray-50 transition">
          <i class="fab fa-google text-red-500"></i>
        </button>
        <button type="button" class="py-2.5 px-4 border border-gray-300 rounded-lg flex justify-center items-center hover:bg-gray-50 transition">
          <i class="fab fa-facebook-f text-blue-600"></i>
        </button>
        <button type="button" class="py-2.5 px-4 border border-gray-300 rounded-lg flex justify-center items-center hover:bg-gray-50 transition">
          <i class="fab fa-apple text-gray-800"></i>
        </button>
      </div>
    </div>
    
    <!-- Right side - Image/Illustration -->
    <div class="hidden lg:block lg:w-1/2 bg-gradient-to-br from-red-600 to-red-800 p-12 text-white relative">
      <div class="absolute inset-0 bg-black opacity-20"></div>
      <div class="relative z-10">
        <h2 class="text-4xl font-bold mb-6">Welcome Back!</h2>
        <p class="text-xl opacity-90 mb-8">Sign in to access your account, view your order history, and enjoy a personalized Burgeez experience.</p>
        
        <div class="space-y-4 mt-10">
          <div class="flex items-center">
            <div class="bg-white/20 p-2 rounded-full mr-4">
              <i class="fas fa-hamburger text-white"></i>
            </div>
            <p>Personalized recommendations based on your taste</p>
          </div>
          <div class="flex items-center">
            <div class="bg-white/20 p-2 rounded-full mr-4">
              <i class="fas fa-truck text-white"></i>
            </div>
            <p>Quick reordering of your favorite meals</p>
          </div>
          <div class="flex items-center">
            <div class="bg-white/20 p-2 rounded-full mr-4">
              <i class="fas fa-star text-white"></i>
            </div>
            <p>Earn reward points with every order</p>
          </div>
        </div>
      </div>
      
      <!-- Animated food illustrations -->
      <img src="/burgeez/assets/images/burger-fries.png" 
           alt="Burger and Fries" 
           class="absolute bottom-0 right-0 w-64 h-64 object-contain animate-float"
           onerror="this.src='https://cdn-icons-png.flaticon.com/512/2515/2515183.png'; this.onerror=null;">
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
  const inputs = form.querySelectorAll('input:not([type="checkbox"])');
  
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

<?php include('includes/footer.php'); 
// End output buffering at the end of the file
ob_end_flush();
?>