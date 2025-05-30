<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Sign Up - MyApp</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">

<div id="alertModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div id="alertContent"
            class="bg-white px-6 py-4 rounded-lg shadow-lg text-sm font-medium text-center max-w-sm w-full border">
            <!-- Alert message will be injected here -->
        </div>
    </div>

  <div class="flex max-w-4xl bg-white shadow-lg rounded-lg overflow-hidden">

    <!-- Left side: Logo placeholder -->
    <div class="hidden md:flex w-1/2 bg-white items-center justify-center">
      <!-- <div class="text-white text-4xl font-bold">
        StudySphere
      </div> -->
      <img src="../assets/images/logo.png" alt="StudySphere logo" class="mb-8" />
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
 <script>const alertModal = document.getElementById("alertModal");
    const alertContent = document.getElementById("alertContent");
    const urlParams = new URLSearchParams(window.location.search);
    const alertType = urlParams.get('alert');
    const alertMsg = urlParams.get('msg');

    if (alertType && alertMsg) {
        const decodedMsg = decodeURIComponent(alertMsg);
        alertContent.textContent = decodedMsg;

        if (alertType === 'success') {
            alertContent.classList.add("bg-green-100", "text-green-800", "border-green-300");
        } else {
            alertContent.classList.add("bg-red-100", "text-red-800", "border-red-300");
        }

        alertModal.classList.remove("hidden");

        setTimeout(() => {
            alertModal.classList.add("hidden");
            alertContent.className = "bg-white px-6 py-4 rounded-lg shadow-lg text-sm font-medium text-center max-w-sm w-full border";
        }, 3000);
    }</script>
</body>
</html>
