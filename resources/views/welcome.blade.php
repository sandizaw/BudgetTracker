<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Laravel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl p-8 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        
        <!-- Left Side Content -->
        <div class="text-center md:text-left">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Welcome to Budget Tracker</h1>
            <p class="text-gray-600 mb-6">WORK OUT WHERE YOUR MONEY IS GOING.............</p>
            <div class="flex justify-center gap-4">
                    <a href="{{ url('/login') }}" class="px-6 py-3 bg-red-500 text-white font-semibold rounded-xl shadow hover:bg-red-600 transition-all">
                        Login
                    </a>
                    <a href="{{ url('/register') }}" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl shadow hover:bg-gray-100 transition-all">
                        Register
                    </a>
            </div>
        </div>

        <!-- Right Side Image -->
<div>
    <img src="https://static.vecteezy.com/system/resources/previews/011/976/274/non_2x/stick-figures-welcome-free-vector.jpg"
         alt="Laravel Illustration" class="w-full rounded-lg">
</div>


    </div>

</body>
</html>
