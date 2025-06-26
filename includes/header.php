<?php
// Start with PHP code and no text content before opening tag
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set up cart count
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$cart_count = 0;
foreach ($_SESSION['cart'] as $item) {
    $cart_count += $item['qty'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Burgeez - Burger Shop</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              50: '#fff1f2',
              100: '#ffe4e6',
              200: '#fecdd3',
              300: '#fda4af',
              400: '#fb7185',
              500: '#f43f5e',
              600: '#e11d48',
              700: '#be123c',
              800: '#9f1239',
              900: '#881337',
            },
          },
          fontFamily: {
            sans: ['Poppins', 'sans-serif'],
          },
          animation: {
            'float': 'float 6s ease-in-out infinite',
          },
          keyframes: {
            float: {
              '0%, 100%': { transform: 'translateY(0)' },
              '50%': { transform: 'translateY(-10px)' },
            }
          }
        }
      }
    }
  </script>

  <!-- GSAP CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
    
    .animate-float {
      animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }
  </style>
</head>
<body class="bg-gray-50 font-sans">

  <!-- Navigation Bar -->
  <header class="bg-white shadow-md sticky top-0 z-50 transition-shadow duration-300">
    <!-- Promo Bar -->
    <div class="bg-gradient-to-r from-red-600 to-red-700 text-white text-center text-sm py-1.5">
      <div class="container mx-auto px-4">
        <p class="flex items-center justify-center gap-1">
          <i class="fas fa-fire-alt"></i> 
          <span>Limited Time: Free delivery on orders above Rs. 2000!</span>
          <a href="/burgeez/pages/menu.php" class="underline font-medium hover:text-yellow-200 ml-1">Order now</a>
        </p>
      </div>
    </div>
    
    <!-- Main Navigation -->
    <div class="container mx-auto px-4 py-3">
      <div class="flex items-center justify-between">
        <!-- Logo -->
        <a href="/burgeez/index.php" class="flex items-center space-x-2">
          <div class="bg-gradient-to-r from-red-600 to-red-700 text-white h-10 w-10 rounded-lg flex items-center justify-center shadow-md">
            <i class="fas fa-burger text-lg"></i>
          </div>
          <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-red-700">Burgeez</span>
        </a>
        
        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center space-x-6">
          <a href="/burgeez/index.php" class="font-medium text-gray-700 hover:text-red-600 py-2 border-b-2 border-transparent hover:border-red-600 transition-colors">Home</a>
          <a href="/burgeez/pages/menu.php" class="font-medium text-gray-700 hover:text-red-600 py-2 border-b-2 border-transparent hover:border-red-600 transition-colors">Menu</a>
          <a href="/burgeez/pages/about.php" class="font-medium text-gray-700 hover:text-red-600 py-2 border-b-2 border-transparent hover:border-red-600 transition-colors">About</a>
          <a href="/burgeez/pages/contact.php" class="font-medium text-gray-700 hover:text-red-600 py-2 border-b-2 border-transparent hover:border-red-600 transition-colors">Contact</a>
        </nav>
        
        <!-- Right Section: Search, User, Cart -->
        <div class="flex items-center space-x-4">
          <!-- Search -->
          <div class="hidden md:block relative">
            <button id="desktop-search-button" class="text-gray-600 hover:text-red-600 transition-colors">
              <i class="fas fa-search text-lg"></i>
            </button>
            <!-- Desktop Search Dropdown -->
            <div id="desktop-search-dropdown" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg py-2 px-3 z-50">
              <form action="/burgeez/pages/search-results.php" method="GET" class="flex items-center">
                <input type="text" name="query" placeholder="Search menu items..." class="w-full pl-8 pr-4 py-2 bg-gray-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <div class="absolute left-3">
                  <i class="fas fa-search text-gray-400"></i>
                </div>
                <button type="submit" class="ml-2 bg-red-600 text-white p-2 rounded-lg hover:bg-red-700">
                  <i class="fas fa-arrow-right text-sm"></i>
                </button>
              </form>
            </div>
          </div>
          
          <!-- User Menu -->
          <div class="relative">
            <button id="user-menu-button" class="text-gray-600 hover:text-red-600 transition-colors focus:outline-none">
              <i class="fas fa-user-circle text-lg"></i>
            </button>
            <!-- Dropdown -->
            <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50">
              <a href="/burgeez/login.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50">
                <i class="fas fa-sign-in-alt w-5 text-red-500"></i> Login
              </a>
              <a href="/burgeez/register.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50">
                <i class="fas fa-user-plus w-5 text-red-500"></i> Register
              </a>
              <hr class="my-1">
              <a href="/burgeez/pages/profile.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50">
                <i class="fas fa-user w-5 text-red-500"></i> My Profile
              </a>
              <a href="/burgeez/pages/orders.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50">
                <i class="fas fa-clipboard-list w-5 text-red-500"></i> My Orders
              </a>
              <a href="/burgeez/logout.php" class="block px-4 py-2 text-gray-700 hover:bg-red-50">
                <i class="fas fa-sign-out-alt w-5 text-red-500"></i> Logout
              </a>
            </div>
          </div>
          
          <!-- Cart -->
          <a href="/burgeez/pages/cart.php" class="relative group">
            <div class="p-1.5 bg-white group-hover:bg-red-50 rounded-full transition-colors">
              <i class="fas fa-shopping-bag text-gray-600 group-hover:text-red-600 transition-colors"></i>
            </div>
            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full"><?= $cart_count ?></span>
          </a>
          
          <!-- Mobile Menu Button -->
          <button id="nav-toggle" class="md:hidden text-gray-600 hover:text-red-600 focus:outline-none">
            <i class="fas fa-bars text-xl"></i>
          </button>
        </div>
      </div>
    </div>
    
    <!-- Mobile Navigation Menu -->
    <div id="nav-menu" class="md:hidden hidden bg-white border-t border-gray-100">
      <div class="container mx-auto px-4 py-3">
        <!-- Mobile Search -->
        <form action="/burgeez/pages/search-results.php" method="GET" class="relative mb-4">
          <input type="text" name="query" placeholder="Search menu items..." class="w-full pl-10 pr-4 py-2 bg-gray-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
          <button type="submit" class="absolute left-0 top-0 mt-2 ml-3 text-gray-400">
            <i class="fas fa-search"></i>
          </button>
        </form>
        
        <!-- Mobile Navigation Links -->
        <div class="flex flex-col space-y-2">
          <a href="/burgeez/index.php" class="flex items-center py-2 px-3 rounded-lg hover:bg-red-50">
            <i class="fas fa-home w-6 text-red-500"></i>
            <span>Home</span>
          </a>
          <a href="/burgeez/pages/menu.php" class="flex items-center py-2 px-3 rounded-lg hover:bg-red-50">
            <i class="fas fa-utensils w-6 text-red-500"></i>
            <span>Menu</span>
          </a>
          <a href="/burgeez/pages/cart.php" class="flex items-center py-2 px-3 rounded-lg hover:bg-red-50">
            <i class="fas fa-shopping-bag w-6 text-red-500"></i>
            <span>Cart</span>
          </a>
          <a href="/burgeez/pages/checkout.php" class="flex items-center py-2 px-3 rounded-lg hover:bg-red-50">
            <i class="fas fa-credit-card w-6 text-red-500"></i>
            <span>Checkout</span>
          </a>
          <a href="/burgeez/pages/about.php" class="flex items-center py-2 px-3 rounded-lg hover:bg-red-50">
            <i class="fas fa-info-circle w-6 text-red-500"></i>
            <span>About Us</span>
          </a>
          <a href="/burgeez/pages/contact.php" class="flex items-center py-2 px-3 rounded-lg hover:bg-red-50">
            <i class="fas fa-envelope w-6 text-red-500"></i>
            <span>Contact</span>
          </a>
        </div>
      </div>
    </div>
  </header>

  <script>
    // Toggle user dropdown
    const userMenuButton = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-dropdown');
    
    if (userMenuButton && userDropdown) {
      userMenuButton.addEventListener('click', () => {
        userDropdown.classList.toggle('hidden');
      });
      
      // Hide dropdown when clicking outside
      document.addEventListener('click', (event) => {
        if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
          userDropdown.classList.add('hidden');
        }
      });
    }

    // Mobile menu toggle
    const navToggle = document.getElementById('nav-toggle');
    const navMenu = document.getElementById('nav-menu');
    
    if (navToggle && navMenu) {
      navToggle.addEventListener('click', () => {
        navMenu.classList.toggle('hidden');
      });
    }

    // Add shadow on scroll
    window.addEventListener('scroll', () => {
      const header = document.querySelector('header');
      if (window.scrollY > 10) {
        header.classList.add('shadow-lg');
      } else {
        header.classList.remove('shadow-lg');
      }
    });
    
    // Desktop search functionality
    const desktopSearchButton = document.getElementById('desktop-search-button');
    const desktopSearchDropdown = document.getElementById('desktop-search-dropdown');
    
    if (desktopSearchButton && desktopSearchDropdown) {
      desktopSearchButton.addEventListener('click', (e) => {
        e.stopPropagation();
        desktopSearchDropdown.classList.toggle('hidden');
        // Focus on input when dropdown is shown
        if (!desktopSearchDropdown.classList.contains('hidden')) {
          desktopSearchDropdown.querySelector('input').focus();
        }
      });
      
      // Hide search dropdown when clicking outside
      document.addEventListener('click', (event) => {
        if (!desktopSearchButton.contains(event.target) && !desktopSearchDropdown.contains(event.target)) {
          desktopSearchDropdown.classList.add('hidden');
        }
      });
      
      // Prevent dropdown from closing when clicking inside it
      desktopSearchDropdown.addEventListener('click', (e) => {
        e.stopPropagation();
      });
    }
  </script>