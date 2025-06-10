<?php include('includes/header.php'); ?>

<!-- Hero Section -->
<section class="min-h-[60vh] flex items-center justify-center bg-gradient-to-br from-yellow-50 via-red-50 to-yellow-100 px-4 py-12">
  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-2xl animate">
    <h1 class="text-4xl md:text-5xl font-bold text-center mb-4 text-red-600">Welcome to Burgeez!</h1>
    <p class="text-center text-gray-600 mb-8 text-lg">Delicious burgers, lightning-fast delivery, and unbeatable prices. Order now or explore our menu!</p>
    <div class="flex justify-center gap-6">
      <a href="pages/menu.php" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded text-lg font-semibold transition">View Menu</a>
      <a href="login.php" class="bg-gray-200 hover:bg-gray-300 text-red-700 px-6 py-3 rounded text-lg font-semibold transition">Login</a>
    </div>
  </div>
</section>

<!-- Menu Highlights -->
<section class="py-16 px-4 bg-gray-50">
  <h2 class="text-3xl font-bold text-center text-red-600 mb-10">Popular Burgers</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
    <!-- Item 1 -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden animate">
      <img src="assets/images/burger1.png" alt="Classic Burger" class="w-full h-48 object-cover" />
      <div class="p-4">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Classic Burger</h3>
        <p class="text-gray-600 mb-3">Rs. 890</p>
        <a href="pages/menu.php" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">Order Now</a>
      </div>
    </div>
    <!-- Item 2 -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden animate">
      <img src="assets/images/burger2.jpg" alt="Cheese Burger" class="w-full h-48 object-cover" />
      <div class="p-4">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Cheese Burger</h3>
        <p class="text-gray-600 mb-3">Rs. 950</p>
        <a href="pages/menu.php" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">Order Now</a>
      </div>
    </div>
    <!-- Item 3 -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden animate">
      <img src="assets/images/burger3.jpg" alt="Spicy Burger" class="w-full h-48 object-cover" />
      <div class="p-4">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Spicy Burger</h3>
        <p class="text-gray-600 mb-3">Rs. 980</p>
        <a href="pages/menu.php" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">Order Now</a>
      </div>
    </div>
  </div>
</section>

<!-- About Section -->
<section class="py-16 px-4 bg-white border-t">
  <div class="max-w-4xl mx-auto text-center">
    <h2 class="text-2xl font-bold mb-4 text-red-600">Why Choose Burgeez?</h2>
    <p class="text-lg text-gray-700 mb-6">Burgeez brings you gourmet burgers made from the freshest ingredients. Fast delivery, friendly service, and unbeatable taste – that’s our promise!</p>
    <a href="pages/menu.php" class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded text-lg font-semibold transition">Order Your Burger</a>
  </div>
</section>

<?php include('includes/footer.php'); ?>