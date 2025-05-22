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
  <script src="learnFlashcard.js" defer></script>
  <title>Learn Flashcards</title>
  <style>
    .card {
      transform-style: preserve-3d;
      transition: transform 0.6s;
    }

    .flipped {
      transform: rotateY(180deg);
    }

    .card-front,
    .card-back {
      backface-visibility: hidden;
    }

    .card-back {
      transform: rotateY(180deg);
    }
  </style>
</head>

<body class="min-h-screen bg-gradient-to-r from-sky-100 to-indigo-100 flex flex-col items-center justify-start p-6">
  <!-- Header -->
  <div class="w-full max-w-4xl flex justify-between items-center mb-6">
    <h2 id="setName" class="text-xl font-semibold text-gray-700">Flashcard Set Name</h2>
    <button id="closeBtn" aria-label="Close" class="text-3xl font-bold text-gray-500 hover:text-red-500 transition">&times;</button>
  </div>

  <!-- Flashcard counter -->
  <div id="flashcardCounter" class="text-center text-gray-600 mb-4 font-medium text-lg">0 / 0</div>

  <!-- Flashcard container -->
<div class="w-full max-w-4xl">
    <div id="flashcard"
        class="card relative w-full h-[300px] bg-white rounded-3xl shadow-2xl cursor-pointer select-none p-10 flex items-center justify-center text-center text-3xl font-semibold text-gray-800">
        <div class="card-front absolute w-full h-full flex flex-col items-center justify-center px-8">
            <span class="text-base text-indigo-500 font-medium mb-4">Question</span>
            <div id="questionText" class="text-gray-800 text-2xl font-semibold text-center">Loading...</div>
        </div>
        <div class="card-back absolute w-full h-full flex flex-col items-center justify-center px-8">
            <span class="text-base text-green-500 font-medium mb-4">Answer</span>
            <div id="answerText" class="text-gray-800 text-2xl font-semibold text-center">Loading...</div>
        </div>
    </div>
</div>

  <!-- Navigation buttons -->
  <div class="w-full max-w-4xl flex justify-center gap-10 mt-8">
    <button id="prevBtn" class="flex items-center justify-center gap-2 bg-white border border-gray-300 text-gray-700 px-8 py-3 rounded-xl shadow-sm transition disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
      </svg>
      Previous
    </button>
    <button id="nextBtn" class="flex items-center justify-center gap-2 bg-indigo-600 text-white px-8 py-3 rounded-xl shadow-md transition hover:bg-indigo-700 disabled:bg-gray-400 disabled:text-gray-200 disabled:cursor-not-allowed">
      Next
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
      </svg>
    </button>
  </div>
</body>

</html>
