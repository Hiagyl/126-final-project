document.addEventListener("DOMContentLoaded", function () {
    console.log("file opened");
    const modal = document.getElementById("modalBackdrop");
    const editModal = document.getElementById("modalBackdrop2");
    const openBtn = document.getElementById("openModalBtn");
    const closeBtn = document.getElementById("closeModalBtn");
    const cancelBtn = document.getElementById("cancelModalBtn");
    const closeBtn2 = document.getElementById("closeModalBtn2");
    const cancelBtn2 = document.getElementById("cancelModalBtn2");
    const setContainer = document.getElementById("setContainer");
    const setNameInput = document.getElementById("setName");
    const setDescInput = document.getElementById("setDesc");
    const setIdInput = document.getElementById("setID");
    const urlParams = new URLSearchParams(window.location.search);
    const courseID = urlParams.get("course_id");

    if (courseID) {
        fetch("set_current_course.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "course_id=" + encodeURIComponent(courseID)
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

    modal.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.classList.add("hidden");
        }
    });

    fetch("get_sets.php")
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                setContainer.innerHTML = `<p class="text-red-500">${data.error}</p>`;
                return;
            }

            data.forEach(set => {
                const card = document.createElement("a");
                card.href = `coursePage.html?set_id=${set.set_id}`;
                card.className = "block w-full sm:w-[calc(33.33%-1rem)] bg-white shadow-md rounded-lg p-4 hover:bg-gray-100 transition";
                card.innerHTML = `
            <div class="flex justify-between items-start">
                <h3 class="text-xl font-semibold text-gray-800">${set.name}</h3>
                <div class="relative">
                    <button class="dotsBtn text-2xl p-1 rounded hover:bg-gray-200">&#x22EE;</button>
                    <div class="modalMenu hidden absolute left-0 mt-2 w-40 bg-white border rounded shadow-lg z-10">
                        <button class="editBtn w-full text-left px-4 py-2 hover:bg-gray-100">Edit</button>
                        <form onsubmit="return confirm('Are you sure you want to delete this set?');" action='delete_flashcard_set.php' method='post'>
                            <input type='hidden' name='set_id' value='${set.set_id}'>
                            <button type='submit' class='w-full text-left px-4 py-2 hover:bg-gray-100'>Delete</button>
                        </form>
                    </div>
                </div>
            </div>
            <p class="mt-2 text-gray-600">${set.description}</p>
            <p class="mt-1 text-sm text-gray-500">Owner: ${set.owner_name}</p>
        `;
                setContainer.appendChild(card);

                const dotsBtn = card.querySelector(".dotsBtn");
                const modalMenu = card.querySelector(".modalMenu");
                const editBtn = card.querySelector(".editBtn");

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

                    setIdInput.value = set.set_id;
                    setNameInput.value = set.name;
                    setDescInput.value = set.description;
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
            setContainer.innerHTML = `<p class="text-red-500">Failed to load flashcard sets.</p>`;
            console.error(err);
        });
});
