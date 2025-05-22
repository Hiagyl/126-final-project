<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Make the session data available globally in JS
        window.sessionData = {
            setId: "<?php echo $_SESSION['current_set_id'] ?? ''; ?>"
        };
    </script>
    <script src="resultPage.js"></script>
    <title>Test Results</title>
</head>

<body class="min-h-screen bg-gradient-to-r from-sky-100 to-indigo-100 flex flex-col items-center justify-center p-6">

    <!-- Close Button -->
    <button onclick="goBack()"
        class="absolute top-4 right-6 text-3xl font-bold text-gray-500 hover:text-red-500 transition">&times;</button>

    <!-- Score -->
    <div class="text-5xl font-extrabold text-gray-800 mb-6 text-center">
        <span id="scorePercentage">0%</span>
    </div>

    <!-- Results Card -->
    <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-md text-center space-y-4 text-lg text-gray-700">
        <div><strong>EXP Earned:</strong> <span id="exp">0</span></div>
        <div><strong>Flashcards Completed:</strong> <span id="total">0</span></div>
        <div><strong>Correct Answers:</strong> <span id="correct">0</span></div>
        <div><strong>Wrong Answers:</strong> <span id="wrong">0</span></div>
    </div>

    <!-- Retake Test Button -->
    <button onclick="retakeTest()"
        class="mt-8 bg-indigo-600 text-white px-6 py-3 rounded-xl shadow hover:bg-indigo-700">
        Retake Test
    </button>
</body>

</html>