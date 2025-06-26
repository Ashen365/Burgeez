<?php
// Include header - fix the path by going up one directory
include('../includes/header.php');
// Note: Don't add session_start() here as it's likely already in header.php
?>

<!-- About Hero Section -->
<section class="relative py-20 bg-gradient-to-br from-yellow-50 via-red-50 to-yellow-100">
  <div class="container mx-auto px-4">
    <div class="text-center max-w-3xl mx-auto">
      <span class="inline-block bg-red-100 text-red-600 px-4 py-1 rounded-full text-sm font-semibold mb-3">
        ABOUT US
      </span>
      <h1 class="text-4xl md:text-5xl font-bold text-red-600 mb-6">Welcome to Burgeez</h1>
      <p class="text-gray-700 text-lg mb-8">Serving the juiciest, most delicious burgers since 2018. A culinary journey dedicated to burger perfection.</p>
    </div>
  </div>
</section>

<!-- Our Story Section -->
<section class="py-16 px-4 bg-white">
  <div class="container mx-auto">
    <div class="flex flex-col md:flex-row items-center gap-12">
      <div class="md:w-1/2">
        <img src="../assets/images/restaurant.jpg" alt="Burgeez Restaurant" class="rounded-xl shadow-lg w-full h-auto" onerror="this.src='https://images.unsplash.com/photo-1555992336-fb0d29498b13?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'; this.onerror=null;">
      </div>
      <div class="md:w-1/2">
        <h2 class="text-3xl font-bold mb-4 text-red-600">Our Story</h2>
        <p class="text-gray-700 mb-6">Burgeez was born from a simple yet powerful idea: to create the perfect burger experience. Founded in 2018 by Chef Rahul Sharma, our journey began in a small kitchen with a big dream.</p>
        <p class="text-gray-700 mb-6">What started as a passion project quickly became a local favorite. Our commitment to using only the freshest ingredients, handcrafted patties, and unique flavor combinations earned us a loyal following that continues to grow every day.</p>
        <p class="text-gray-700">Today, Burgeez stands as a testament to our dedication to quality, taste, and the simple joy that comes from biting into the perfect burger. While we've grown, our core values remain the same – premium ingredients, exceptional taste, and friendly service.</p>
      </div>
    </div>
  </div>
</section>

<!-- Our Values -->
<section class="py-16 px-4 bg-gray-50">
  <div class="container mx-auto">
    <div class="text-center mb-12">
      <h2 class="text-3xl font-bold text-red-600 mb-4">Our Values</h2>
      <p class="text-gray-600 max-w-2xl mx-auto">At Burgeez, these core principles guide everything we do.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <!-- Value 1 -->
      <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-6 mx-auto">
          <i class="fas fa-leaf text-2xl text-red-600"></i>
        </div>
        <h3 class="text-xl font-bold text-center mb-4">Quality Ingredients</h3>
        <p class="text-gray-600 text-center">We source only the freshest, highest-quality ingredients from trusted local suppliers.</p>
      </div>
      
      <!-- Value 2 -->
      <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-6 mx-auto">
          <i class="fas fa-heart text-2xl text-red-600"></i>
        </div>
        <h3 class="text-xl font-bold text-center mb-4">Passion for Taste</h3>
        <p class="text-gray-600 text-center">Every burger is crafted with passion and dedication to deliver exceptional flavor in every bite.</p>
      </div>
      
      <!-- Value 3 -->
      <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-6 mx-auto">
          <i class="fas fa-users text-2xl text-red-600"></i>
        </div>
        <h3 class="text-xl font-bold text-center mb-4">Customer First</h3>
        <p class="text-gray-600 text-center">Your satisfaction is our priority. We strive to exceed your expectations with every order.</p>
      </div>
    </div>
  </div>
</section>

<!-- Our Team -->
<section class="py-16 px-4 bg-white">
  <div class="container mx-auto">
    <div class="text-center mb-12">
      <h2 class="text-3xl font-bold text-red-600 mb-4">Meet Our Team</h2>
      <p class="text-gray-600 max-w-2xl mx-auto">The passionate people behind your favorite burgers.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- Team Member 1 -->
      <div class="bg-gray-50 p-6 rounded-xl text-center">
        <div class="w-32 h-32 rounded-full overflow-hidden mx-auto mb-6">
          <img src="../assets/images/chef1.jpg" alt="Chef Rahul Sharma" class="w-full h-full object-cover" onerror="this.src='https://randomuser.me/api/portraits/men/32.jpg'; this.onerror=null;">
        </div>
        <h3 class="text-xl font-bold mb-2">Ashen Shanilka</h3>
        <p class="text-red-600 font-medium mb-4">Founder & Head Chef</p>
        <p class="text-gray-600">A culinary expert with over 15 years of experience, Chef Rahul brings his passion for perfect burgers to every recipe.</p>
      </div>
      
      <!-- Team Member 2 -->
      <div class="bg-gray-50 p-6 rounded-xl text-center">
        <div class="w-32 h-32 rounded-full overflow-hidden mx-auto mb-6">
          <img src="../assets/images/chef2.jpg" alt="Priya Malhotra" class="w-full h-full object-cover" onerror="this.src='https://randomuser.me/api/portraits/women/44.jpg'; this.onerror=null;">
        </div>
        <h3 class="text-xl font-bold mb-2">Shanilka Herath</h3>
        <p class="text-red-600 font-medium mb-4">Executive Chef</p>
        <p class="text-gray-600">Ashen's innovative approach to flavor combinations has led to some of our most popular signature burgers.</p>
      </div>
      
      <!-- Team Member 3 -->
      <div class="bg-gray-50 p-6 rounded-xl text-center">
        <div class="w-32 h-32 rounded-full overflow-hidden mx-auto mb-6">
          <img src="../assets/images/manager.jpg" alt="Arjun Patel" class="w-full h-full object-cover" onerror="this.src='https://randomuser.me/api/portraits/men/76.jpg'; this.onerror=null;">
        </div>
        <h3 class="text-xl font-bold mb-2">Ashen Herath</h3>
        <p class="text-red-600 font-medium mb-4">Operations Manager</p>
        <p class="text-gray-600">Ensuring smooth operations and quick delivery, Herath makes sure your burger experience is perfect from order to delivery.</p>
      </div>
    </div>
  </div>
</section>

<!-- Contact Info -->
<section class="py-16 px-4 bg-gray-50">
  <div class="container mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
      <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="p-8 md:p-12">
          <h2 class="text-3xl font-bold text-red-600 mb-6">Visit Us</h2>
          <div class="space-y-4 mb-6">
            <div class="flex items-start">
              <div class="text-red-600 mr-4 mt-1">
                <i class="fas fa-map-marker-alt text-xl"></i>
              </div>
              <div>
                <h3 class="font-semibold text-lg">Address</h3>
                <p class="text-gray-600">123 Burger Street, Foodie District, Mumbai 400001</p>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="text-red-600 mr-4 mt-1">
                <i class="fas fa-phone text-xl"></i>
              </div>
              <div>
                <h3 class="font-semibold text-lg">Phone</h3>
                <p class="text-gray-600">+91 9876543210</p>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="text-red-600 mr-4 mt-1">
                <i class="fas fa-envelope text-xl"></i>
              </div>
              <div>
                <h3 class="font-semibold text-lg">Email</h3>
                <p class="text-gray-600">info@burgeez.com</p>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="text-red-600 mr-4 mt-1">
                <i class="fas fa-clock text-xl"></i>
              </div>
              <div>
                <h3 class="font-semibold text-lg">Hours</h3>
                <p class="text-gray-600">Monday - Sunday: 11:00 AM - 11:00 PM</p>
              </div>
            </div>
          </div>
          
          <h3 class="font-semibold text-lg mb-3">Connect With Us</h3>
          <div class="flex space-x-4">
            <a href="#" class="bg-red-100 hover:bg-red-200 text-red-600 w-10 h-10 rounded-full flex items-center justify-center transition-colors">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="bg-red-100 hover:bg-red-200 text-red-600 w-10 h-10 rounded-full flex items-center justify-center transition-colors">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="bg-red-100 hover:bg-red-200 text-red-600 w-10 h-10 rounded-full flex items-center justify-center transition-colors">
              <i class="fab fa-twitter"></i>
            </a>
          </div>
        </div>
        
        <div class="bg-gray-200 h-full">
          <!-- Placeholder for Google Map or restaurant image -->
          <div class="h-full w-full bg-cover bg-center" style="background-image: url('../assets/images/resturant.jpg');" onerror="this.style.backgroundColor='#f3f4f6'; this.innerHTML='<div class=\'flex items-center justify-center h-full\'><p class=\'text-gray-500 text-xl\'>Map Loading...</p></div>'; this.onerror=null;"></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Call to Action -->
<section class="py-12 px-4 bg-gradient-to-r from-red-600 to-red-700 text-white">
  <div class="container mx-auto text-center">
    <h2 class="text-3xl font-bold mb-6">Ready to Experience the Best Burgers in Town?</h2>
    <div class="flex flex-wrap justify-center gap-4">
      <a href="../pages/menu.php" class="bg-white text-red-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold transition transform hover:scale-105 shadow-lg">
        <i class="fas fa-hamburger mr-2"></i> View Our Menu
      </a>
      <a href="#" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-red-600 px-8 py-3 rounded-lg font-semibold transition transform hover:scale-105">
        <i class="fas fa-phone-alt mr-2"></i> Contact Us
      </a>
    </div>
  </div>
</section>

<?php include('../includes/footer.php'); ?>