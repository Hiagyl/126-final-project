document.addEventListener("DOMContentLoaded", function () {
    const exploreContainer = document.getElementById("exploreContainer");
    const searchForm = document.getElementById("searchForm");
    const searchInput = document.getElementById("searchInput");
    const paginationContainer = document.getElementById("pagination");

    let currentPage = 1;

    function displaySets(sets) {
        exploreContainer.innerHTML = '';

        if (sets.length === 0) {
            exploreContainer.innerHTML = "<p>No public flashcard sets found.</p>";
            return;
        }

        sets.forEach((set) => {
            const card = document.createElement("div");
            card.className = "block w-full sm:w-[calc(33.33%-1rem)] bg-white shadow-md rounded-lg p-4 hover:bg-gray-100 transition";
            card.innerHTML = `
                <h3 class="text-xl font-semibold text-gray-800 mb-2 max-w-[90%] break-words whitespace-pre-wrap">${set.name}</h3>
                <p class="text-gray-700 mb-2 break-words whitespace-pre-wrap">${set.description || ''}</p>
                <p class="text-gray-600 mb-2 break-words whitespace-pre-wrap">Created by: ${set.owner_name}</p>
                <p class="text-gray-500 text-sm mb-4">Date: ${new Date(set.date_created).toLocaleDateString()}</p>
                <a href="viewCourse.html?set_id=${set.set_id}" 
                   class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    View Set
                </a>
            `;
            exploreContainer.appendChild(card);
        });
    }

    function updatePagination(totalPages, currentPage, search) {
        paginationContainer.innerHTML = '';

        if (currentPage > 1) {
            const prevButton = createPaginationButton('Previous', currentPage - 1, search);
            paginationContainer.appendChild(prevButton);
        }

        for (let i = 1; i <= totalPages; i++) {
            const pageButton = createPaginationButton(i, i, search);
            if (i === currentPage) {
                pageButton.classList.add('bg-indigo-600', 'text-white');
            }
            paginationContainer.appendChild(pageButton);
        }

        if (currentPage < totalPages) {
            const nextButton = createPaginationButton('Next', currentPage + 1, search);
            paginationContainer.appendChild(nextButton);
        }
    }

    function createPaginationButton(text, page, search) {
        const button = document.createElement('button');
        button.className = 'px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-100';
        button.textContent = text;
        button.addEventListener('click', () => {
            currentPage = page;
            fetchFlashcardSets(page, search);
        });
        return button;
    }

    function fetchFlashcardSets(page = 1, search = '') {
        fetch(`search_explore.php?page=${page}&search=${encodeURIComponent(search)}`)
            .then((response) => {
                if (!response.ok) throw new Error("Failed to fetch flashcard sets");
                return response.json();
            })
            .then((data) => {
                displaySets(data.sets);
                updatePagination(data.totalPages, data.currentPage, search);
            })
            .catch((error) => {
                console.error("Error:", error);
                exploreContainer.innerHTML = "<p>Error loading flashcard sets.</p>";
            });
    }

    searchForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const searchTerm = searchInput.value.trim();
        currentPage = 1;
        fetchFlashcardSets(currentPage, searchTerm);
    });

    fetchFlashcardSets(); // Load initial sets
});
