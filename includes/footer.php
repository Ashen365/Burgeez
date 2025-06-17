<?php
// DO NOT include session_start() here - sessions should be started in header.php only
// DO NOT include header.php here - this creates a circular reference
?>

<!-- Footer -->
<footer class="bg-gray-800 text-white py-10">
  <div class="container mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
      <!-- Company Info -->
      <div>
        <h3 class="text-xl font-bold mb-4">Burgeez</h3>
        <p class="text-gray-400 mb-4">Serving the best burgers in town since 2018.</p>
        <div class="flex space-x-4">
          <a href="#" class="text-gray-400 hover:text-white">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="#" class="text-gray-400 hover:text-white">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="#" class="text-gray-400 hover:text-white">
            <i class="fab fa-twitter"></i>
          </a>
        </div>
      </div>
      
      <!-- Quick Links -->
      <div>
        <h3 class="text-xl font-bold mb-4">Quick Links</h3>
        <ul class="space-y-2">
          <li><a href="/burgeez/index.php" class="text-gray-400 hover:text-white">Home</a></li>
          <li><a href="/burgeez/pages/menu.php" class="text-gray-400 hover:text-white">Menu</a></li>
          <li><a href="/burgeez/pages/about.php" class="text-gray-400 hover:text-white">About Us</a></li>
          <li><a href="/burgeez/pages/contact.php" class="text-gray-400 hover:text-white">Contact</a></li>
        </ul>
      </div>
      
      <!-- Contact Info -->
      <div>
        <h3 class="text-xl font-bold mb-4">Contact Us</h3>
        <ul class="space-y-2 text-gray-400">
          <li class="flex items-start">
            <i class="fas fa-map-marker-alt mt-1 mr-2"></i>
            <span>123 Burger Street, Kandy District, Nawalapitiya 20350</span>
          </li>
          <li class="flex items-start">
            <i class="fas fa-phone mt-1 mr-2"></i>
            <span>+91 776376306</span>
          </li>
          <li class="flex items-start">
            <i class="fas fa-envelope mt-1 mr-2"></i>
            <span>info@burgeez.com</span>
          </li>
        </ul>
      </div>
    </div>
    
    <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400">
      <p>&copy; <?php echo date('Y'); ?> Burgeez. All rights reserved.</p>
    </div>
  </div>
</footer>
</body>
</html>