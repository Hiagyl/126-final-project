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
    <script src="test.js" defer></script>
    <title>Flashcard Test</title>
    <style>
        .card {
            transform-style: preserve-3d;
            transition: transform 0.6s;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-r from-sky-100 to-indigo-100 flex flex-col items-center justify-start p-6">
    <!-- Header -->
    <div class="w-full max-w-4xl flex justify-between items-center mb-6">
        <h2 id="setName" class="text-xl font-semibold text-gray-700">Flashcard Quiz</h2>
        <button id="closeBtn" aria-label="Close"
            class="text-3xl font-bold text-gray-500 hover:text-red-500 transition">&times;</button>
    </div>

    <!-- Flashcard counter -->
    <div id="flashcardCounter" class="text-center text-gray-600 mb-4 font-medium text-lg">0 / 0</div>

    <!-- Question Card -->
    <div class="w-full max-w-4xl mb-4">
    <div
        class="card relative w-full min-h-[200px] bg-white rounded-3xl shadow-2xl p-8 flex items-center justify-center text-center text-2xl font-semibold text-gray-800">
        <div id="questionText" class="w-full break-words whitespace-pre-wrap overflow-x-auto">
        Loading question...
        </div>
    </div>
    </div>


    <!-- Answer Input Card -->
    <div class="w-full max-w-4xl mb-4">
        <div class="card relative w-full min-h-[150px] bg-white rounded-3xl shadow-lg p-6">
            <input id="answerInput" autocomplete="off" type="text" class="w-full border-b-2 border-gray-300 focus:outline-none text-xl p-2"
                placeholder="Type your answer here...">
        </div>
    </div>

    <div class="mb-8">
        <button id="submitBtn"
            class="bg-indigo-600 text-white px-8 py-3 rounded-xl shadow-md transition hover:bg-indigo-700">Submit</button>
    </div>

    <div id="feedback" class="text-xl font-medium text-center text-gray-700"></div>

    
</body>

</html>