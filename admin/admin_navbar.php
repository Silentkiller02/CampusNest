<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Themed Graphic Navigation Bar</title>
    <!-- Load Tailwind CSS from CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Configure Tailwind for Inter font and new Teal/Cyan colors -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['cursive'],
                    },
                    colors: {
                        'hms-primary': '#0f766e', // Teal 700 (Main Bar Color)
                        'hms-dark': '#0d9488',    // Teal 600 (Accent)
                        'hms-light': '#ccfbf1',   // Cyan 100 (Login Button Background)
                    }
                }
            }
        }
    </script>
    <!-- Custom CSS for theme, pattern, and link effects -->
    <style>
        /* Base styles for the entire nav bar */
        .hostel-navbar-container {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            /* --- NEW GRAPHIC PATTERN (Wavy/Scale) --- */
            background-color: #0f766e; /* Primary Teal */
            /* Creates a dynamic, geometric background texture */
            background-image: radial-gradient(circle at 100% 100%, transparent 10px, rgba(255, 255, 255, 0.12) 10px, rgba(255, 255, 255, 0.12) 11px, transparent 11px),
                              radial-gradient(circle at 0% 0%, transparent 10px, rgba(255, 255, 255, 0.12) 10px, rgba(255, 255, 255, 0.12) 11px, transparent 11px);
            background-size: 20px 20px;
            /* ---------------------------- */
        }

        /* Hover effect for links */
        .nav-link-item {
            position: relative;
            padding-bottom: 2px;
            transition: color 0.3s ease;
        }
        .nav-link-item:hover {
            color: #d1d5db; /* Light gray on hover */
        }
        /* Underline effect on hover */
        .nav-link-item::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 0;
            height: 2px;
            background-color: #fbbf24; /* Amber line for high contrast */
            transition: width 0.3s ease;
        }
        .nav-link-item:hover::after {
            width: 100%;
        }

        /* Mobile menu toggle logic */
        #mobile-menu-btn:checked ~ #mobile-menu {
            display: flex;
        }
        #mobile-menu-btn {
            display: none;
        }
    </style>
</head>
<body class="font-sans bg-gray-50">

    <!-- Hostel Management System Navigation Bar (Thematic/Graphic Design) -->
    <nav class="hostel-navbar-container p-4 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between">

            <!-- Left Section: Graphic Logo and System Name -->
            <div class="flex items-center space-x-3 text-white">
                <!-- Stylized Student/Community Icon (Themed Clipart look) -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-hms-light" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <!-- Icon represents a group of students/users -->
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <!-- System Name (Visible on all sizes for strong branding) -->
                <span class="text-xl sm:text-2xl font-extrabold tracking-widest text-hms-light">
                    CampusNest
                </span>
            </div>

            <!-- Right Section: Desktop Navigation Links -->
            <div class="hidden md:flex items-center space-x-8 text-lg font-medium">
                <a href="#" class="nav-link-item text-white hover:text-hms-light">Home</a>
                <a href="#" class="nav-link-item text-white hover:text-hms-light">About</a>
                <a href="#" class="nav-link-item text-white hover:text-hms-light">Contact Us</a>
                <!-- Login/Dashboard button with soft contrast -->
                <button  href="../admin/admin_logout.php"class="ml-4 px-5 py-2.5 font-bold bg-hms-light text-hms-primary rounded-full hover:bg-white transition duration-300 shadow-xl">
                    Logout
                </button>
            </div>

            <!-- Mobile Menu Button (Hamburger Icon) -->
            <label for="mobile-menu-btn" class="md:hidden text-white cursor-pointer p-2 rounded-lg hover:bg-hms-dark transition">
                <!-- Hamburger Icon (Inline SVG) -->
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" y1="12" x2="20" y2="12"></line>
                    <line x1="4" y1="6" x2="20" y2="6"></line>
                    <line x1="4" y1="18" x2="20" y2="18"></line>
                </svg>
            </label>

        </div>

        <!-- Hidden Checkbox to control mobile menu state -->
        <input type="checkbox" id="mobile-menu-btn" class="hidden">

        <!-- Mobile Menu (Drops down) -->
        <div id="mobile-menu" class="hidden md:hidden flex-col mt-4 bg-hms-dark rounded-b-xl py-4 text-center text-lg space-y-3 shadow-inner">
            <a href="#" class="block text-white py-2 hover:bg-hms-primary/70 transition rounded-md mx-4 font-semibold">Home</a>
            <a href="#" class="block text-white py-2 hover:bg-hms-primary/70 transition rounded-md mx-4 font-semibold">About</a>
            <a href="#" class="block text-white py-2 hover:bg-hms-primary/70 transition rounded-md mx-4 font-semibold">Contact Us</a>
            <button class="w-11/12 mx-auto mt-4 px-4 py-2 font-bold bg-hms-light text-hms-primary rounded-full hover:bg-white transition duration-300 shadow-lg">
                Login
            </button>
        </div>

    </nav>

    <!-- Example page content to demonstrate that the nav bar is independent -->
  

</body>
</html>
