document.addEventListener("DOMContentLoaded", function () {
    const setNameEl = document.getElementById("setName");
    const counterEl = document.getElementById("flashcardCounter");
    const questionText = document.getElementById("questionText");
    const answerInput = document.getElementById("answerInput");
    const submitBtn = document.getElementById("submitBtn");
    const feedbackEl = document.getElementById("feedback");
    const closeBtn = document.getElementById("closeBtn");
    const setId = window.sessionData?.setId || '';

    let flashcards = [];
    let currentIndex = 0;
    let correctCount = 0;
    let incorrectCount = 0;

    function showQuestion() {
        if (currentIndex >= flashcards.length) {
            const percentage = Math.round((correctCount / flashcards.length) * 100);
            const exp = correctCount * 10;
            localStorage.setItem("test_results", JSON.stringify({
                percentage,
                total: flashcards.length,
                correct: correctCount,
                wrong: incorrectCount,
                exp
            }));
            window.location.href = "resultPage.php";
            return;
        }

        counterEl.textContent = `${currentIndex + 1} / ${flashcards.length}`;
        questionText.textContent = flashcards[currentIndex].question;
        answerInput.value = "";
        feedbackEl.textContent = "";
    }

    submitBtn.addEventListener("click", () => {
        const userAnswer = answerInput.value.trim().toLowerCase();
        const correctAnswer = flashcards[currentIndex].answer.trim().toLowerCase();

        if (userAnswer === correctAnswer) {
            feedbackEl.textContent = "✅ Correct!";
            feedbackEl.classList.remove("text-red-600");
            feedbackEl.classList.add("text-green-600");
            correctCount++;
        } else {
            feedbackEl.textContent = `❌ Wrong! Correct answer: ${flashcards[currentIndex].answer}`;
            feedbackEl.classList.remove("text-green-600");
            feedbackEl.classList.add("text-red-600");
            incorrectCount++;
        }

        setTimeout(() => {
            currentIndex++;
            showQuestion();
        }, 1500);
    });

    closeBtn.addEventListener("click", () => {
        window.location.href = `coursePage.html?set_id=${setId}`; // 
    });

    // Fetch flashcards from backend PHP
    fetch('get_flashcards.php')
        .then(res => res.json())
        .then(data => {
            if (!Array.isArray(data)) throw new Error("Invalid data format");
            flashcards = data;
            setNameEl.textContent = "Flashcard Test";
            showQuestion();
        })
        .catch(err => {
            questionText.textContent = "Error loading flashcards.";
            console.error(err);
        });
});