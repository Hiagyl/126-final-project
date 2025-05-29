document.addEventListener("DOMContentLoaded", function () {
    const flashcardContainer = document.getElementById('flashcardContainer');
    const openBtn = document.getElementById('openFlashcardModalBtn');
    const modal = document.getElementById('flashcardModalBackdrop');
    const closeBtn = document.getElementById('closeFlashcardModalBtn');
    const cancelBtn = document.getElementById('cancelFlashcardModalBtn');
    const editModal = document.getElementById('flashcardModalBackdrop2');
    const closeBtn2 = document.getElementById('closeFlashcardModalBtn2');
    const cancelBtn2 = document.getElementById('cancelFlashcardModalBtn2');
    const flashcardIdInput = document.getElementById('flashcardID');
    const questionInput = document.getElementById('flashcardQuestion');
    const answerInput = document.getElementById('flashcardAnswer');

    const takeQuizBtn = document.getElementById('takeQuizBtn');
    const reviewFlashcardsBtn = document.getElementById('reviewFlashcardsBtn');

    const urlParams = new URLSearchParams(window.location.search);
    const setID = urlParams.get("set_id");

    // Function to wrap button with its prompt for better UI layout
    function createButtonWrapper(button, prompt) {
        const wrapper = document.createElement('div');
        wrapper.style.display = 'flex';
        wrapper.style.flexDirection = 'column';
        wrapper.style.alignItems = 'center';  // Center horizontally
        wrapper.style.marginBottom = '1rem';  // spacing between groups
        button.parentNode.insertBefore(wrapper, button);
        wrapper.appendChild(button);
        wrapper.appendChild(prompt);
    }

    // Create prompt elements with consistent styles
    const takeQuizPrompt = document.createElement('p');
    takeQuizPrompt.style.color = 'red';
    takeQuizPrompt.style.margin = '4px 0 0 0';
    takeQuizPrompt.style.fontSize = '0.9rem';
    takeQuizPrompt.style.textAlign = 'center';  // Center prompt text

    const reviewFlashcardsPrompt = document.createElement('p');
    reviewFlashcardsPrompt.style.color = 'red';
    reviewFlashcardsPrompt.style.margin = '4px 0 0 0';
    reviewFlashcardsPrompt.style.fontSize = '0.9rem';
    reviewFlashcardsPrompt.style.textAlign = 'center';  // Center prompt text

    if (takeQuizBtn) createButtonWrapper(takeQuizBtn, takeQuizPrompt);
    if (reviewFlashcardsBtn) createButtonWrapper(reviewFlashcardsBtn, reviewFlashcardsPrompt);

    if (setID) {
        fetch("set_current_fset.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "set_id=" + encodeURIComponent(setID)
        });
    }

    openBtn.addEventListener("click", () => modal.classList.remove("hidden"));
    closeBtn.addEventListener("click", () => modal.classList.add("hidden"));
    cancelBtn.addEventListener("click", () => modal.classList.add("hidden"));

    modal.addEventListener("click", (e) => {
        if (e.target === modal) modal.classList.add("hidden");
    });

    fetch("get_flashcards.php")
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.error) {
                flashcardContainer.innerHTML = `<p class="text-red-500">${data.error}</p>`;
                if (takeQuizBtn) takeQuizBtn.disabled = true;
                if (reviewFlashcardsBtn) reviewFlashcardsBtn.disabled = true;
                takeQuizPrompt.textContent = "";
                reviewFlashcardsPrompt.textContent = "";
                return;
            }

            const flashcardCount = data.flashcards ? data.flashcards.length : 0;

            // Disable or enable buttons according to flashcard count
            if (flashcardCount < 1) {
                if (takeQuizBtn) takeQuizBtn.disabled = true;
                if (reviewFlashcardsBtn) reviewFlashcardsBtn.disabled = true;
                takeQuizPrompt.textContent = "You need at least 8 flashcards to take the quiz.";
                reviewFlashcardsPrompt.textContent = "You need at least 1 flashcard to review.";
            } else {
                if (reviewFlashcardsBtn) {
                    reviewFlashcardsBtn.disabled = false;
                    reviewFlashcardsPrompt.textContent = ""; // Clear prompt
                }

                if (takeQuizBtn) {
                    if (flashcardCount < 8) {
                        takeQuizBtn.disabled = true;
                        takeQuizPrompt.textContent = "You need at least 8 flashcards to take the quiz.";
                    } else {
                        takeQuizBtn.disabled = false;
                        takeQuizPrompt.textContent = "";
                    }
                }
            }

            if (!data.is_owner) {
                openBtn.classList.add("hidden");
            }

            // Clear existing flashcards before appending new ones
            flashcardContainer.innerHTML = "";

            data.flashcards.forEach(flashcard => {
                const flashcardCard = document.createElement('div');
                flashcardCard.classList.add('bg-white', 'shadow-md', 'rounded-lg', 'p-4', 'relative');

                let menuHTML = "";
                if (data.is_owner) {
                    menuHTML = `
                        <div class="relative">
                            <button class="dotsBtn text-2xl p-1 rounded hover:bg-gray-200">&#x22EE;</button>
                            <div class="modalMenu hidden absolute right-0 mt-2 w-40 bg-white border rounded shadow-lg z-10">
                                <button class="editBtn w-full text-left px-4 py-2 hover:bg-gray-100">Edit</button>
                                <form onsubmit="return confirm('Are you sure you want to delete this card?');" action='delete_flashcard.php' method='post'>
                                    <input type='hidden' name='flashcard_id' value='${flashcard.flashcard_id}'>
                                    <button type='submit' class='w-full text-left px-4 py-2 hover:bg-gray-100'>Delete</button>
                                </form>
                            </div>
                        </div>
                    `;
                }

                flashcardCard.innerHTML = `
                    <div class="flex justify-between items-center">
                        <p class="text-lg font-semibold">Q: ${flashcard.question}</p>
                        ${menuHTML}
                    </div>
                    <hr>
                    <p class="text-gray-700">A: ${flashcard.answer}</p>
                `;
                flashcardContainer.appendChild(flashcardCard);

                if (data.is_owner) {
                    const dotsBtn = flashcardCard.querySelector(".dotsBtn");
                    const modalMenu = flashcardCard.querySelector(".modalMenu");
                    const editBtn = flashcardCard.querySelector(".editBtn");

                    dotsBtn.addEventListener("click", (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        document.querySelectorAll(".modalMenu").forEach(menu => {
                            if (menu !== modalMenu) menu.classList.add("hidden");
                        });
                        modalMenu.classList.toggle("hidden");
                    });

                    editBtn.addEventListener("click", (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        flashcardIdInput.value = flashcard.flashcard_id;
                        questionInput.value = flashcard.question;
                        answerInput.value = flashcard.answer;
                        editModal.classList.remove("hidden");
                    });
                }
            });

            closeBtn2.addEventListener("click", () => editModal.classList.add("hidden"));
            cancelBtn2.addEventListener("click", () => editModal.classList.add("hidden"));
        })
        .catch(err => {
            flashcardContainer.innerHTML = `<p class="text-red-500">Failed to load flashcards.</p>`;
            if (takeQuizBtn) takeQuizBtn.disabled = true;
            if (reviewFlashcardsBtn) reviewFlashcardsBtn.disabled = true;
            takeQuizPrompt.textContent = "";
            reviewFlashcardsPrompt.textContent = "";
            console.error(err);
        });
});
