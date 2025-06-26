<?php
// Start session
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

// Get search query
$searchQuery = isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '';

// Complete burger menu data
$burgers = [
    [
        'id' => 1, 
        'name' => 'Volcano Crunch Burger', 
        'price' => 1050.00,
        'description' => 'Ignite your taste buds with this fiery sensation! Our Volcano Crunch Burger features a crispy, chili-marinated chicken patty, layered with jalapeños, spicy sauce, and melted cheese.'
    ],
    [
        'id' => 2, 
        'name' => 'SeaMeat Fusion Blaze Burger', 
        'price' => 3490.00,
        'description' => 'A powerhouse of smoky, oceanic, and spicy flavors — the SeaMeat Fusion Blaze is a Sri Lankan-inspired masterpiece! It brings together smoked baked chicken and seafood delight.'
    ],
    [
        'id' => 3, 
        'name' => 'Cheese Overload Burger', 
        'price' => 969.00,
        'description' => 'Cheese lovers, this one\'s for you! A juicy beef patty loaded with layers of melted cheddar, mozzarella, and creamy cheese sauce. Finished with crispy lettuce and tomato.'
    ]
];

// Filter burgers based on search query
$results = [];
if (!empty($searchQuery)) {
    $searchQueryLower = strtolower($searchQuery);
    foreach ($burgers as $burger) {
        if (strpos(strtolower($burger['name']), $searchQueryLower) !== false || 
            strpos(strtolower($burger['description']), $searchQueryLower) !== false) {
            $results[] = $burger;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Search Results - Burgeez</title>

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
                    }
                }
            }
        }
    </script>

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
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Header/Navigation -->
    <header>
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
        <nav class="bg-white shadow-md">
            <div class="container mx-auto px-4 py-3">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <a href="/burgeez/index.php" class="flex items-center space-x-2">
                        <div class="bg-red-600 text-white h-10 w-10 rounded-lg flex items-center justify-center shadow-md">
                            <i class="fas fa-burger text-lg"></i>
                        </div>
                        <span class="text-2xl font-bold text-red-600">Burgeez</span>
                    </a>
                    
                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="/burgeez/index.php" class="font-medium text-gray-700 hover:text-red-600">Home</a>
                        <a href="/burgeez/pages/menu.php" class="font-medium text-gray-700 hover:text-red-600">Menu</a>
                        <a href="/burgeez/pages/about.php" class="font-medium text-gray-700 hover:text-red-600">About</a>
                        <a href="/burgeez/pages/contact.php" class="font-medium text-gray-700 hover:text-red-600">Contact</a>
                    </div>
                    
                    <!-- Right Section -->
                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <div class="relative">
                            <button id="search-button" class="text-gray-600 hover:text-red-600">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        
                        <!-- User Icon -->
                        <button class="text-gray-600 hover:text-red-600">
                            <i class="fas fa-user-circle"></i>
                        </button>
                        
                        <!-- Cart -->
                        <a href="/burgeez/pages/cart.php" class="relative">
                            <i class="fas fa-shopping-bag text-gray-600 hover:text-red-600"></i>
                            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full"><?= $cart_count ?></span>
                        </a>
                        
                        <!-- Mobile Menu Toggle -->
                        <button id="mobile-menu-toggle" class="md:hidden text-gray-600 hover:text-red-600">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    
    <main class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold">Search Results</h1>
            <p class="text-gray-600 mt-2">Showing results for: "<?= $searchQuery ?>"</p>
        </div>
        
        <!-- Search Form -->
        <div class="mb-8 max-w-lg">
            <form action="/burgeez/pages/search-results.php" method="GET" class="flex">
                <div class="relative flex-grow">
                    <input type="text" name="query" value="<?= $searchQuery ?>" placeholder="Search for burgers..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <div class="absolute left-0 top-0 h-full flex items-center pl-3 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-r-lg hover:bg-red-700">
                    Search
                </button>
            </form>
        </div>
        
        <?php if (empty($results)): ?>
            <!-- No Results Found -->
            <div class="text-center py-12">
                <div class="text-6xl text-gray-300 mb-4">
                    <i class="fas fa-search"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-700">No results found</h2>
                <p class="text-gray-500 mt-3 mb-6">Try searching with different keywords or browse our menu</p>
                <a href="/burgeez/pages/menu.php" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 inline-flex items-center">
                    <i class="fas fa-utensils mr-2"></i> Browse Menu
                </a>
            </div>
        <?php else: ?>
            <!-- Results Grid - Resembling your menu page layout -->
            <?php foreach ($results as $burger): ?>
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow mb-6 p-4">
                    <div class="flex flex-col md:flex-row">
                        <!-- Generate a placeholder text image based on burger name -->
                        <div class="md:w-1/3 md:pr-4 mb-4 md:mb-0">
                            <div class="bg-gray-200 rounded-lg w-full aspect-[4/3] flex items-center justify-center">
                                <span class="text-gray-500 text-lg font-medium"><?= $burger['name'] ?></span>
                            </div>
                        </div>
                        
                        <div class="md:w-2/3">
                            <div class="flex justify-between items-start">
                                <h2 class="text-xl font-bold"><?= $burger['name'] ?></h2>
                                <span class="text-red-600 font-bold">Rs. <?= number_format($burger['price'], 2) ?></span>
                            </div>
                            <p class="text-gray-600 mt-2"><?= $burger['description'] ?></p>
                            <div class="mt-4">
                                <button class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
    
    <script>
        // Add relevant JavaScript here for menu toggle, etc.
        document.getElementById('mobile-menu-toggle')?.addEventListener('click', function() {
            // Mobile menu toggle logic
        });
    </script>
</body>
</html>