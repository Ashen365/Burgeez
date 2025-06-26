<?php include('includes/header.php'); ?>

<!-- Hero Section -->
<section class="relative min-h-[80vh] flex items-center justify-center bg-gradient-to-br from-yellow-50 via-red-50 to-yellow-100 overflow-hidden">
  <!-- Decorative elements -->
  <div class="absolute top-20 left-10 w-32 h-32 rounded-full bg-red-100 opacity-50 animate-pulse"></div>
  <div class="absolute bottom-10 right-10 w-48 h-48 rounded-full bg-yellow-100 opacity-50 animate-pulse"></div>
  
  <div class="container mx-auto px-4 py-16 z-10">
    <div class="flex flex-col md:flex-row items-center gap-8">
      <div class="w-full md:w-1/2 space-y-6">
        <h1 class="text-5xl md:text-6xl font-extrabold text-red-600 leading-tight">
          Delicious <span class="relative inline-block">
            Burgers
            <span class="absolute bottom-2 left-0 w-full h-3 bg-yellow-200 opacity-50 -z-10"></span>
          </span> for Everyone
        </h1>
        <p class="text-gray-600 text-xl">
          <i class="fas fa-utensils mr-2"></i> Premium ingredients
          <span class="mx-2">•</span>
          <i class="fas fa-shipping-fast mr-2"></i> Fast delivery
          <span class="mx-2">•</span>
          <i class="fas fa-tag mr-2"></i> Great prices
        </p>
        <div class="flex flex-wrap gap-4 pt-4">
          <a href="pages/menu.php" class="flex items-center bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg text-lg font-semibold transition transform hover:scale-105 shadow-md">
            <i class="fas fa-hamburger mr-2"></i> View Menu
          </a>
          <a href="login.php" class="flex items-center bg-white hover:bg-gray-100 text-red-700 border-2 border-red-600 px-6 py-3 rounded-lg text-lg font-semibold transition transform hover:scale-105 shadow-sm">
            <i class="fas fa-user mr-2"></i> Login
          </a>
        </div>
      </div>
      <div class="w-full md:w-1/2 flex justify-center">
        <div class="relative">
          <img src="assets/images/burger1.png" alt="Delicious Burger" class="w-[400px] h-[400px] object-contain drop-shadow-2xl animate-float">
          <div class="absolute -top-4 -right-4 bg-red-600 text-white text-lg font-bold rounded-full w-20 h-20 flex items-center justify-center rotate-12 shadow-lg">
            NEW!
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Special Offer Banner -->
<div class="bg-red-600 text-white py-4">
  <div class="container mx-auto px-4">
    <div class="flex flex-col md:flex-row items-center justify-between">
      <div class="flex items-center mb-3 md:mb-0">
        <i class="fas fa-fire-alt text-2xl mr-3 text-yellow-300"></i>
        <p class="font-semibold text-lg">Limited Time Offer: Free delivery on orders above Rs. 2000!</p>
      </div>
      <a href="pages/menu.php" class="bg-white text-red-600 hover:bg-gray-100 px-4 py-2 rounded-md font-medium transition">
        Order Now
      </a>
    </div>
  </div>
</div>

<!-- Menu Highlights -->
<section class="py-20 px-4 bg-gray-50">
  <div class="container mx-auto">
    <div class="text-center mb-14">
      <span class="inline-block bg-red-100 text-red-600 px-4 py-1 rounded-full text-sm font-semibold mb-3">
        <i class="fas fa-star mr-1"></i> CUSTOMER FAVORITES
      </span>
      <h2 class="text-4xl font-bold text-red-600 mb-3">Popular Burgers</h2>
      <p class="text-gray-600 max-w-2xl mx-auto">Handcrafted with premium ingredients, our signature burgers are sure to satisfy your cravings.</p>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
      <!-- Item 1 -->
      <div class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300">
        <div class="relative overflow-hidden">
          <img src="assets/images/burger1.png" alt="Classic Burger" class="w-full h-64 object-cover transition-transform group-hover:scale-105" />
          <div class="absolute top-4 left-4 bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
            Best Seller
          </div>
        </div>
        <div class="p-6">
          <div class="flex justify-between items-center mb-3">
            <h3 class="text-2xl font-bold text-gray-800">Classic Burger</h3>
            <span class="text-xl font-bold text-red-600">Rs. 890</span>
          </div>
          <p class="text-gray-600 mb-4">Juicy beef patty with fresh lettuce, tomatoes, and our special sauce.</p>
          <div class="flex items-center justify-between">
            <div class="flex items-center text-yellow-500">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
              <span class="ml-1 text-gray-600 text-sm">(124)</span>
            </div>
            <a href="pages/menu.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition flex items-center">
              <i class="fas fa-shopping-cart mr-2"></i> Order
            </a>
          </div>
        </div>
      </div>
      
      <!-- Item 2 -->
      <div class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300">
        <div class="relative overflow-hidden">
          <img src="assets/images/burger2.jpg" alt="Cheese Burger" class="w-full h-64 object-cover transition-transform group-hover:scale-105" />
          <div class="absolute top-4 left-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
            Popular
          </div>
        </div>
        <div class="p-6">
          <div class="flex justify-between items-center mb-3">
            <h3 class="text-2xl font-bold text-gray-800">Cheese Burger</h3>
            <span class="text-xl font-bold text-red-600">Rs. 950</span>
          </div>
          <p class="text-gray-600 mb-4">Our classic burger topped with premium melted cheese and special sauce.</p>
          <div class="flex items-center justify-between">
            <div class="flex items-center text-yellow-500">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
              <span class="ml-1 text-gray-600 text-sm">(98)</span>
            </div>
            <a href="pages/menu.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition flex items-center">
              <i class="fas fa-shopping-cart mr-2"></i> Order
            </a>
          </div>
        </div>
      </div>
      
      <!-- Item 3 -->
      <div class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300">
        <div class="relative overflow-hidden">
          <img src="assets/images/burger3.jpg" alt="Spicy Burger" class="w-full h-64 object-cover transition-transform group-hover:scale-105" />
          <div class="absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
            <i class="fas fa-pepper-hot mr-1"></i> Spicy
          </div>
        </div>
        <div class="p-6">
          <div class="flex justify-between items-center mb-3">
            <h3 class="text-2xl font-bold text-gray-800">Spicy Burger</h3>
            <span class="text-xl font-bold text-red-600">Rs. 980</span>
          </div>
          <p class="text-gray-600 mb-4">Fire-grilled patty with jalapeños, spicy mayo, and pepper jack cheese.</p>
          <div class="flex items-center justify-between">
            <div class="flex items-center text-yellow-500">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <span class="ml-1 text-gray-600 text-sm">(156)</span>
            </div>
            <a href="pages/menu.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition flex items-center">
              <i class="fas fa-shopping-cart mr-2"></i> Order
            </a>
          </div>
        </div>
      </div>
    </div>
    
    <div class="text-center mt-12">
      <a href="pages/menu.php" class="inline-flex items-center text-red-600 font-semibold hover:text-red-700">
        View Full Menu <i class="fas fa-arrow-right ml-2"></i>
      </a>
    </div>
  </div>
</section>

<!-- Features Section -->
<section class="py-16 px-4 bg-white">
  <div class="container mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
      <!-- Feature 1 -->
      <div class="text-center p-6 rounded-xl hover:bg-gray-50 transition-all">
        <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
          <i class="fas fa-pizza-slice text-2xl text-red-600"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Premium Ingredients</h3>
        <p class="text-gray-600">We use only the freshest, highest quality ingredients in all our burgers.</p>
      </div>
      
      <!-- Feature 2 -->
      <div class="text-center p-6 rounded-xl hover:bg-gray-50 transition-all">
        <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
          <i class="fas fa-truck text-2xl text-red-600"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Fast Delivery</h3>
        <p class="text-gray-600">Your order delivered to your doorstep in 30 minutes or less, guaranteed.</p>
      </div>
      
      <!-- Feature 3 -->
      <div class="text-center p-6 rounded-xl hover:bg-gray-50 transition-all">
        <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
          <i class="fas fa-smile text-2xl text-red-600"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Satisfaction Guaranteed</h3>
        <p class="text-gray-600">Not happy with your order? We'll make it right or give you a full refund.</p>
      </div>
    </div>
  </div>
</section>

<!-- Testimonials -->
<section class="py-20 px-4 bg-gray-50">
  <div class="container mx-auto">
    <div class="text-center mb-14">
      <h2 class="text-4xl font-bold text-red-600 mb-3">What Our Customers Say</h2>
      <p class="text-gray-600 max-w-2xl mx-auto">Don't just take our word for it - here's what our happy customers have to say!</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- Testimonial 1 -->
      <div class="bg-white p-6 rounded-xl shadow-md">
        <div class="flex items-center text-yellow-500 mb-4">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
        <p class="text-gray-600 mb-4">"The Classic Burger is absolutely amazing! Juicy, flavorful, and perfectly cooked. Will definitely order again!"</p>
        <div class="flex items-center">
          <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-3">
            <span class="text-red-600 font-bold">RS</span>
          </div>
          <div>
            <h4 class="font-semibold">Rahul Singh</h4>
            <p class="text-gray-500 text-sm">Loyal Customer</p>
          </div>
        </div>
      </div>
      
      <!-- Testimonial 2 -->
      <div class="bg-white p-6 rounded-xl shadow-md">
        <div class="flex items-center text-yellow-500 mb-4">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
        <p class="text-gray-600 mb-4">"The delivery was super fast and the Spicy Burger was exactly what I needed! The spice level was perfect and the quality was top-notch."</p>
        <div class="flex items-center">
          <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-3">
            <span class="text-red-600 font-bold">AP</span>
          </div>
          <div>
            <h4 class="font-semibold">Anjali Patel</h4>
            <p class="text-gray-500 text-sm">Food Enthusiast</p>
          </div>
        </div>
      </div>
      
      <!-- Testimonial 3 -->
      <div class="bg-white p-6 rounded-xl shadow-md">
        <div class="flex items-center text-yellow-500 mb-4">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star-half-alt"></i>
        </div>
        <p class="text-gray-600 mb-4">"Burgeez has the best cheese burger in town! The cheese is perfectly melted and the patty is cooked to perfection. Highly recommended!"</p>
        <div class="flex items-center">
          <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-3">
            <span class="text-red-600 font-bold">VK</span>
          </div>
          <div>
            <h4 class="font-semibold">Vikram Kumar</h4>
            <p class="text-gray-500 text-sm">Regular Customer</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Call to Action -->
<section class="py-16 px-4 bg-gradient-to-r from-red-600 to-red-700 text-white">
  <div class="container mx-auto">
    <div class="flex flex-col md:flex-row items-center justify-between">
      <div class="mb-8 md:mb-0 md:w-2/3">
        <h2 class="text-3xl font-bold mb-4">Ready to Satisfy Your Cravings?</h2>
        <p class="text-lg opacity-90">Order now and enjoy the best burgers in town, delivered straight to your doorstep!</p>
      </div>
      <div>
        <a href="pages/menu.php" class="inline-block bg-white text-red-600 hover:bg-gray-100 px-8 py-4 rounded-lg text-lg font-bold transition transform hover:scale-105 shadow-lg">
          <i class="fas fa-hamburger mr-2"></i> Order Your Burger
        </a>
      </div>
    </div>
  </div>
</section>

<!-- About Section -->
<section class="py-20 px-4 bg-white border-t">
  <div class="container mx-auto">
    <div class="flex flex-col md:flex-row items-center gap-12">
      <div class="md:w-1/2">
        <img src="assets/images/restaurant.jpg" alt="Burgeez Restaurant" class="rounded-xl shadow-lg w-full h-auto" onerror="this.src='https://images.unsplash.com/photo-1555992336-fb0d29498b13?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'; this.onerror=null;">
      </div>
      <div class="md:w-1/2">
        <span class="inline-block bg-red-100 text-red-600 px-4 py-1 rounded-full text-sm font-semibold mb-3">
          OUR STORY
        </span>
        <h2 class="text-3xl font-bold mb-4 text-red-600">Why Choose Burgeez?</h2>
        <p class="text-gray-700 mb-6 text-lg">At Burgeez, we're passionate about creating the perfect burger experience. Our journey began with a simple idea: to serve gourmet burgers made from the freshest ingredients at affordable prices.</p>
        
        <div class="space-y-4 mb-8">
          <div class="flex items-start">
            <div class="bg-red-100 p-2 rounded-full mr-4 mt-1">
              <i class="fas fa-check text-red-600"></i>
            </div>
            <div>
              <h3 class="font-semibold text-gray-800">Premium Quality</h3>
              <p class="text-gray-600">We use only the freshest, locally-sourced ingredients in all our burgers.</p>
            </div>
          </div>
          
          <div class="flex items-start">
            <div class="bg-red-100 p-2 rounded-full mr-4 mt-1">
              <i class="fas fa-check text-red-600"></i>
            </div>
            <div>
              <h3 class="font-semibold text-gray-800">Handcrafted With Care</h3>
              <p class="text-gray-600">Each burger is handcrafted with care by our expert chefs.</p>
            </div>
          </div>
          
          <div class="flex items-start">
            <div class="bg-red-100 p-2 rounded-full mr-4 mt-1">
              <i class="fas fa-check text-red-600"></i>
            </div>
            <div>
              <h3 class="font-semibold text-gray-800">Fast & Reliable Delivery</h3>
              <p class="text-gray-600">Hot and fresh burgers delivered to your doorstep in record time.</p>
            </div>
          </div>
        </div>
        
        <a href="pages/about.php" class="inline-flex items-center text-red-600 font-semibold hover:text-red-700">
          Learn More About Us <i class="fas fa-arrow-right ml-2"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Download App Banner -->
<section class="py-12 px-4 bg-gray-50">
  <div class="container mx-auto">
    <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-2xl overflow-hidden shadow-xl">
      <div class="flex flex-col md:flex-row items-center p-8 md:p-12">
        <div class="md:w-2/3 text-white mb-8 md:mb-0">
          <h2 class="text-3xl font-bold mb-4">Download Our Mobile App</h2>
          <p class="text-lg opacity-90 mb-6">Get exclusive deals, track your orders in real-time, and earn rewards with every purchase!</p>
          <div class="flex flex-wrap gap-4">
            <a href="#" class="bg-black hover:bg-gray-900 text-white px-6 py-3 rounded-lg flex items-center transition">
              <i class="fab fa-apple text-2xl mr-3"></i>
              <div>
                <div class="text-xs">Download on the</div>
                <div class="text-lg font-semibold">App Store</div>
              </div>
            </a>
            <a href="#" class="bg-black hover:bg-gray-900 text-white px-6 py-3 rounded-lg flex items-center transition">
              <i class="fab fa-google-play text-2xl mr-3"></i>
              <div>
                <div class="text-xs">Get it on</div>
                <div class="text-lg font-semibold">Google Play</div>
              </div>
            </a>
          </div>
        </div>
        <div class="md:w-1/3 flex justify-center">
          <!-- Updated image path below. Replace 'your-ai-image.png' with your actual filename if different -->
          <img src="assets/images/Mobile.jpg" alt="Download Burgeez Mobile App" class="h-72 object-contain" onerror="this.src='assets/images/app-mockup.png'; this.onerror=null;">
        </div>
      </div>
    </div>
  </div>
</section>

<?php include('includes/footer.php'); ?>