<!-- resources/views/layouts/nologin.blade.php -->

<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - xBilling</title>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

    @yield('styles')
</head>
<body class="h-full">
    <div class="min-h-full flex items-center justify-center  sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full space-y-8">
            @yield('content')
        </div>
    </div>

    @yield('scripts')
</body>
</html>