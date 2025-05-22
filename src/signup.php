<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Sign Up - MyApp</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">

  <div class="flex max-w-4xl bg-white shadow-lg rounded-lg overflow-hidden">

    <!-- Left side: Logo placeholder -->
    <div class="hidden md:flex w-1/2 bg-green-600 items-center justify-center">
      <div class="text-white text-4xl font-bold">
        LOGO<br/>
        PLACEHOLDER
      </div>
    </div>

    <!-- Right side: Sign Up form -->
    <div class="w-full md:w-1/2 p-8">
      <h2 class="text-3xl font-semibold text-gray-700 mb-6">Create your account</h2>
      <form action="signup_process.php" method="POST" class="space-y-6">

        <div>
          <label for="username" class="block text-gray-600 mb-2">Username</label>
          <input type="text" id="username" name="username" required
            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-400"/>
        </div>

        <div>
          <label for="password" class="block text-gray-600 mb-2">Password</label>
          <input type="password" id="password" name="password" required
            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-400"/>
        </div>

        <button type="submit" 
          class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 transition">
          Sign Up
        </button>

      </form>

      <p class="mt-4 text-gray-600">
        Already have an account? 
        <a href="login.php" class="text-green-600 hover:underline">Login</a>
      </p>
    </div>

  </div>

</body>
</html>
