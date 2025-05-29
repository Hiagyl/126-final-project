document.addEventListener("DOMContentLoaded", function () {
    const flashcard = document.getElementById("flashcard");
    const questionText = document.getElementById("questionText");
    const answerText = document.getElementById("answerText");
    const setName = document.getElementById("setName");
    const counter = document.getElementById("flashcardCounter");
    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");
    const closeBtn = document.getElementById("closeBtn");
    const setId = window.sessionData?.setId || '';

    let flashcards = [];
    let currentIndex = 0;
    let flipped = false;

    function renderFlashcard() {
        if (flashcards.length === 0) {
            questionText.textContent = "No flashcards available.";
            answerText.textContent = "";
            counter.textContent = "0 / 0";
            prevBtn.disabled = true;
            nextBtn.disabled = true;
            flashcard.classList.remove("flipped");
            flipped = false;
            return;
        }

        const card = flashcards[currentIndex];
        questionText.textContent = card.question;
        answerText.textContent = card.answer;
        counter.textContent = `${currentIndex + 1} / ${flashcards.length}`;
        flashcard.classList.remove("flipped");
        flipped = false;

        prevBtn.disabled = currentIndex === 0;
        nextBtn.disabled = currentIndex === flashcards.length - 1;
    }

    fetch('get_flashcards.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }

            if (!Array.isArray(data.flashcards)) {
                throw new Error("Invalid flashcard data.");
            }

            flashcards = data.flashcards;
            setName.textContent = "My Flashcards";
            renderFlashcard();
        })
        .catch(error => {
            console.error('Error fetching flashcards:', error);
            questionText.textContent = "Failed to load flashcards.";
            answerText.textContent = "";
            counter.textContent = "0 / 0";
        });

    flashcard.addEventListener("click", () => {
        if (flashcards.length === 0) return;
        flipped = !flipped;
        flashcard.classList.toggle("flipped", flipped);
    });

    prevBtn.addEventListener("click", () => {
        if (currentIndex > 0) {
            currentIndex--;
            renderFlashcard();
        }
    });

    nextBtn.addEventListener("click", () => {
        if (currentIndex < flashcards.length - 1) {
            currentIndex++;
            renderFlashcard();
        }
    });

    closeBtn.addEventListener("click", () => {
        window.location.href = `coursePage.html?set_id=${setId}`;
    });
});
