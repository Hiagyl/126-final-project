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
    // const saveFlashcardBtn = document.getElementById('saveFlashcardBtn');
    const questionInput = document.getElementById('flashcardQuestion');
    const answerInput = document.getElementById('flashcardAnswer');
    // const setTitle = document.getElementById('setTitle');

    const urlParams = new URLSearchParams(window.location.search);
    const setID = urlParams.get("set_id");

    if (setID) {
        fetch("set_current_fset.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "set_id=" + encodeURIComponent(setID)
        });
    }

    openBtn.addEventListener("click", () => {
        modal.classList.remove("hidden");

    });

    closeBtn.addEventListener("click", () => {
        modal.classList.add("hidden");
    });

    cancelBtn.addEventListener("click", () => {
        modal.classList.add("hidden");
    });

    // Optional: Close modal when clicking outside the form
    modal.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.classList.add("hidden");
        }
    });

    fetch("get_flashcards.php")
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                setContainer.innerHTML = `<p class="text-red-500">${data.error}</p>`;
                return;
            }

            data.forEach(flashcard => {
                const flashcardCard = document.createElement('div');
                flashcardCard.classList.add('bg-white', 'shadow-md', 'rounded-lg', 'p-4', 'relative');
                flashcardCard.innerHTML = `
            <div class="flex justify-between items-center">
                <p class="text-lg font-semibold">Q: ${flashcard.question}</p>
                <div class="relative">
                    <button class="dotsBtn text-2xl p-1 rounded hover:bg-gray-200">&#x22EE;</button>
                    <div class="modalMenu hidden absolute right-0 mt-2 w-40 bg-white border rounded shadow-lg z-10">
                        <button class="editBtn w-full text-left px-4 py-2 hover:bg-gray-100">Edit</button>
                    <form onsubmit=\"return confirm('Are you sure you want to delete this set?');\" action='delete_flashcard.php'  method='post'>
                    <input type='text' style='display:none' name='flashcard_id' value='${flashcard.flashcard_id}'>
                    <button type='submit' class='w-full text-left px-4 py-2 hover:bg-gray-100'>Delete</button>
                    </form>
                    </div>
                </div>
            </div>
            <hr>
            <p class="text-gray-700">A: ${flashcard.answer}</p>
        `;
                flashcardContainer.appendChild(flashcardCard);

                const dotsBtn = flashcardCard.querySelector(".dotsBtn");
                const modalMenu = flashcardCard.querySelector(".modalMenu");
                const editBtn = flashcardCard.querySelector(".editBtn");
                // const deleteBtn = card.querySelector(".deleteBtn");

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
                    // modalTitle.textContent = "Edit Course";
                    editModal.classList.remove("hidden");
                });

                closeBtn2.addEventListener("click", () => {
                    editModal.classList.add("hidden");
                });

                cancelBtn2.addEventListener("click", () => {
                    editModal.classList.add("hidden");
                });
            });
        })
        .catch(err => {
            flashcardContainer.innerHTML = `<p class="text-red-500">Failed to load flashcards.</p>`;
            console.error(err);
        });
});