<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Burgeez - Burger Shop</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- GSAP CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body class="bg-gray-50 font-sans">

  <!-- Navigation Bar -->
  <header class="bg-white shadow-md px-4 py-3 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-red-600">Burgeez</h1>

    <!-- Mobile Menu Button -->
    <button id="nav-toggle" class="md:hidden text-2xl focus:outline-none">
      ☰
    </button>

    <!-- Navigation Links -->
    <nav id="nav-menu" class="hidden md:flex flex-col md:flex-row md:items-center gap-4 md:gap-8 absolute md:static top-16 left-0 w-full md:w-auto bg-white md:bg-transparent px-4 md:px-0 py-4 md:py-0 shadow md:shadow-none z-50">
      <a href="/burgeez/index.php" class="text-gray-700 hover:text-red-600">Home</a>
      <a href="/burgeez/pages/menu.php" class="text-gray-700 hover:text-red-600">Menu</a>
      <a href="/burgeez/pages/cart.php" class="text-gray-700 hover:text-red-600">Cart</a>
      <a href="/burgeez/pages/checkout.php" class="text-gray-700 hover:text-red-600">Checkout</a>
    </nav>
  </header>
