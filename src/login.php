<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Login - MyApp</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="flex max-w-4xl bg-white shadow-lg rounded-lg overflow-hidden">

        <!-- Left side: Logo placeholder -->
        <div class="hidden md:flex w-1/2 bg-blue-600 items-center justify-center">
            <div class="text-white text-4xl font-bold">
                StudySphere
            </div>
        </div>

        <!-- Right side: Login form -->
        <div class="w-full md:w-1/2 p-8">
            <h2 class="text-3xl font-semibold text-gray-700 mb-6">Login to your account</h2>
            <?php if (isset($_GET['error'])): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                    Invalid username or password.
                </div>
            <?php endif; ?>

            <form action="login_process.php" method="POST" class="space-y-6">

                <div>
                    <label for="username" class="block text-gray-600 mb-2">Username</label>
                    <input type="text" id="username" name="username" required
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>

                <div>
                    <label for="password" class="block text-gray-600 mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition">
                    Login
                </button>

            </form>

            <p class="mt-4 text-gray-600">
                Don't have an account?
                <a href="signup.php" class="text-blue-600 hover:underline">Sign Up</a>
            </p>
        </div>

    </div>

</body>

</html>