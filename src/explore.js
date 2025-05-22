document.addEventListener("DOMContentLoaded", function () {
    const exploreContainer = document.getElementById("exploreContainer");

    fetch("get_public_flashcards.php")
        .then((response) => {
            if (!response.ok) throw new Error("Failed to fetch flashcard sets");
            return response.json();
        })
        .then((sets) => {
            if (sets.length === 0) {
                exploreContainer.innerHTML = "<p>No public flashcard sets found.</p>";
                return;
            }

            sets.forEach((set) => {
                const card = document.createElement("div");
                card.className = "flashcard-set";
                card.innerHTML = `
                    <h3>${set.name}</h3>
                    <p>${set.description}</p>
                    <small>By: ${set.date_created} | ${new Date(set.date_created).toLocaleDateString()}</small>
                `;
                exploreContainer.appendChild(card);
            });
        })
        .catch((error) => {
            console.error("Error:", error);
            exploreContainer.innerHTML = "<p>Error loading flashcard sets.</p>";
        });
});
