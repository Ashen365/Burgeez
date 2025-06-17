<?php
// Include header
include_once($_SERVER['DOCUMENT_ROOT'] . '/burgeez/includes/header.php');
?>

<!-- Hero Section -->
<section class="bg-gradient-to-br from-red-600 to-red-700 text-white py-16 px-4">
  <div class="container mx-auto max-w-4xl">
    <div class="text-center">
      <h1 class="text-4xl md:text-5xl font-bold mb-4">Contact Us</h1>
      <p class="text-xl opacity-90 max-w-2xl mx-auto">We'd love to hear from you! Get in touch with our team.</p>
    </div>
  </div>
</section>

<!-- Contact Details Section -->
<section class="py-12 px-4 bg-white">
  <div class="container mx-auto max-w-6xl">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
      <!-- Contact Information -->
      <div class="bg-gray-50 p-8 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold text-red-600 mb-6">Get in Touch</h2>
        
        <div class="space-y-6">
          <div class="flex items-start">
            <div class="bg-red-100 p-3 rounded-full mr-4">
              <i class="fas fa-map-marker-alt text-red-600"></i>
            </div>
            <div>
              <h3 class="font-semibold text-gray-800 text-lg mb-1">Visit Us</h3>
              <p class="text-gray-600">123 Burger Street, Kandy District<br>Nawalapitiya 20350, Sri Lanka</p>
            </div>
          </div>
          
          <div class="flex items-start">
            <div class="bg-red-100 p-3 rounded-full mr-4">
              <i class="fas fa-phone-alt text-red-600"></i>
            </div>
            <div>
              <h3 class="font-semibold text-gray-800 text-lg mb-1">Call Us</h3>
              <p class="text-gray-600">+91 776376306</p>
              <p class="text-gray-600">+91 702376306</p>
            </div>
          </div>
          
          <div class="flex items-start">
            <div class="bg-red-100 p-3 rounded-full mr-4">
              <i class="fas fa-envelope text-red-600"></i>
            </div>
            <div>
              <h3 class="font-semibold text-gray-800 text-lg mb-1">Email Us</h3>
              <p class="text-gray-600">info@burgeez.com</p>
              <p class="text-gray-600">support@burgeez.com</p>
            </div>
          </div>
          
          <div class="flex items-start">
            <div class="bg-red-100 p-3 rounded-full mr-4">
              <i class="fas fa-clock text-red-600"></i>
            </div>
            <div>
              <h3 class="font-semibold text-gray-800 text-lg mb-1">Opening Hours</h3>
              <p class="text-gray-600">Monday - Sunday: 11:00 AM - 11:00 PM</p>
            </div>
          </div>
        </div>
        
        <div class="mt-8">
          <h3 class="font-semibold text-gray-800 text-lg mb-3">Connect With Us</h3>
          <div class="flex space-x-3">
            <a href="#" class="bg-red-600 hover:bg-red-700 text-white w-10 h-10 rounded-full flex items-center justify-center transition-colors">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="bg-red-600 hover:bg-red-700 text-white w-10 h-10 rounded-full flex items-center justify-center transition-colors">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="bg-red-600 hover:bg-red-700 text-white w-10 h-10 rounded-full flex items-center justify-center transition-colors">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="bg-red-600 hover:bg-red-700 text-white w-10 h-10 rounded-full flex items-center justify-center transition-colors">
              <i class="fab fa-youtube"></i>
            </a>
          </div>
        </div>
      </div>
      
      <!-- Contact Form -->
      <div>
        <h2 class="text-2xl font-bold text-red-600 mb-6">Send Us a Message</h2>
        <form action="#" method="post" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
              <input type="text" id="name" name="name" placeholder="John Doe" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500">
            </div>
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Your Email</label>
              <input type="email" id="email" name="email" placeholder="john@example.com" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500">
            </div>
          </div>
          
          <div>
            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
            <input type="text" id="subject" name="subject" placeholder="How can we help you?" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500">
          </div>
          
          <div>
            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
            <textarea id="message" name="message" rows="5" placeholder="Your message here..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500"></textarea>
          </div>
          
          <div>
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition transform hover:scale-105 shadow-md w-full md:w-auto flex items-center justify-center">
              <i class="fas fa-paper-plane mr-2"></i> Send Message
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Map Section -->
<section class="py-12 px-4 bg-gray-50">
  <div class="container mx-auto max-w-6xl">
    <h2 class="text-2xl font-bold text-red-600 mb-6 text-center">Find Us</h2>
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
      <!-- Replace with actual Google Maps embed code or use a static image -->
      <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
        <!-- Placeholder for the map -->
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63356.821799373356!2d80.47987355316624!3d7.032618600042129!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae3742c02d0d6a3%3A0x9356a516eb474246!2sNawalapitiya!5e0!3m2!1sen!2slk!4v1750149983382!5m2!1sen!2slk"
          width="100%" 
          height="100%" 
          style="border:0;" 
          allowfullscreen="" 
          loading="lazy"
          onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'flex flex-col items-center justify-center h-full bg-gray-200\'><i class=\'fas fa-map-marker-alt text-4xl text-red-600 mb-3\'></i><p class=\'text-gray-500\'>Map loading failed. Please check your connection.</p></div>';">
        </iframe>
      </div>
    </div>
  </div>
</section>

<!-- FAQ Section -->
<section class="py-12 px-4 bg-white">
  <div class="container mx-auto max-w-4xl">
    <div class="text-center mb-10">
      <h2 class="text-3xl font-bold text-red-600 mb-4">Frequently Asked Questions</h2>
      <p class="text-gray-600">Find answers to common questions about our services and policies.</p>
    </div>
    
    <div class="space-y-6">
      <!-- FAQ Item 1 -->
      <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">What are your delivery hours?</h3>
        <p class="text-gray-600">We deliver from 11:00 AM to 10:30 PM every day. Last orders are accepted at 10:00 PM.</p>
      </div>
      
      <!-- FAQ Item 2 -->
      <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Is there a minimum order value for free delivery?</h3>
        <p class="text-gray-600">Yes, orders above Rs. 2000 qualify for free delivery. For orders below this amount, a delivery fee of Rs. 50 is applicable.</p>
      </div>
      
      <!-- FAQ Item 3 -->
      <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Do you offer vegetarian options?</h3>
        <p class="text-gray-600">Absolutely! We have a dedicated vegetarian menu with a variety of delicious burger options.</p>
      </div>
      
      <!-- FAQ Item 4 -->
      <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">How do I place a bulk order for an event?</h3>
        <p class="text-gray-600">For bulk orders or event catering, please contact us at least 24 hours in advance at +91 9876543210 or email us at orders@burgeez.com.</p>
      </div>
    </div>
  </div>
</section>

<!-- Call to Action -->
<section class="py-12 px-4 bg-gradient-to-r from-red-600 to-red-700 text-white">
  <div class="container mx-auto text-center max-w-4xl">
    <h2 class="text-3xl font-bold mb-6">Ready to Order the Best Burgers in Town?</h2>
    <p class="text-xl opacity-90 mb-8">Skip the phone call and order directly from our website!</p>
    <a href="/burgeez/pages/menu.php" class="bg-white text-red-600 hover:bg-gray-100 px-8 py-3 rounded-lg text-lg font-semibold transition transform hover:scale-105 shadow-lg inline-flex items-center">
      <i class="fas fa-hamburger mr-2"></i> View Our Menu
    </a>
  </div>
</section>

<?php 
// Include footer
include_once($_SERVER['DOCUMENT_ROOT'] . '/burgeez/includes/footer.php'); 
?>