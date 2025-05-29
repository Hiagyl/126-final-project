document.addEventListener("DOMContentLoaded", function () {
    const flashcardContainer = document.getElementById('flashcardContainer');
    const openBtn = document.getElementById('openFlashcardModalBtn');
    const modal = document.getElementById('flashcardModalBackdrop');
    const closeBtn = document.getElementById('closeFlashcardModalBtn');
    const cancelBtn = document.getElementById('cancelFlashcardModalBtn');
    const courseSelect = document.getElementById("courseSelect");
    const hiddenSetIdInput = document.getElementById("hiddenSetId");

    const urlParams = new URLSearchParams(window.location.search);
    const setID = urlParams.get("set_id");

    // Function to load flashcards after session is set
    function loadFlashcards() {
        fetch("get_flashcards.php")
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    flashcardContainer.innerHTML = `<p class="text-red-500">${data.error}</p>`;
                    return;
                }

                const flashcards = data.flashcards || [];

                if (flashcards.length === 0) {
                    flashcardContainer.innerHTML = "<p>No flashcards found.</p>";
                    return;
                }

                flashcardContainer.innerHTML = ""; // Clear previous content

                flashcards.forEach(flashcard => {
                    const flashcardCard = document.createElement('div');
                    flashcardCard.classList.add('bg-white', 'shadow-md', 'rounded-lg', 'p-4', 'relative');
                    flashcardCard.innerHTML = `
                        <div class="flex justify-between items-center">
                            <p class="text-lg font-semibold">Q: ${flashcard.question}</p>
                        </div>
                    `;
                    flashcardContainer.appendChild(flashcardCard);
                });
            })
            .catch(err => {
                flashcardContainer.innerHTML = `<p class="text-red-500">Failed to load flashcards.</p>`;
                console.error(err);
            });
    }

    if (setID) {
        // Save the setID in a hidden input for the form
        hiddenSetIdInput.value = setID;

        // Inform backend to set the current flashcard set in session, then load flashcards
        fetch("set_current_fset.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "set_id=" + encodeURIComponent(setID)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Failed to set current flashcard set");
                }
                return response.text(); // or response.json() if your PHP returns JSON
            })
            .then(() => {
                loadFlashcards(); // Now load flashcards after session is set
            })
            .catch(err => {
                console.error("Error setting current flashcard set:", err);
                // Even if setting session fails, try to load flashcards anyway
                loadFlashcards();
            });
    } else {
        // No setID in URL, just load flashcards normally
        loadFlashcards();
    }

    // Fetch list of course folders for dropdown
    fetch("get_courses.php")
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data)) {
                data.forEach(course => {
                    const option = document.createElement("option");
                    option.value = course.course_id;
                    option.textContent = course.course_name;
                    courseSelect.appendChild(option);
                });
            } else if (data.error) {
                alert("Error: " + data.error);
            }
        })
        .catch(error => {
            console.error("Error fetching course list:", error);
        });

    // Show modal
    openBtn.addEventListener("click", () => {
        modal.classList.remove("hidden");
    });

    // Hide modal
    closeBtn.addEventListener("click", () => {
        modal.classList.add("hidden");
    });

    cancelBtn.addEventListener("click", () => {
        modal.classList.add("hidden");
    });

    modal.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.classList.add("hidden");
        }
    });

    // Back button
    document.getElementById("backBtn").addEventListener("click", () => {
        window.location.href = "explore.html";
    });
});
