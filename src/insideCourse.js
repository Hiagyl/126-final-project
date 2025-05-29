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

    const alertModal = document.getElementById("alertModal");
    const alertContent = document.getElementById("alertContent");
    const alertType = urlParams.get('alert');
    const alertMsg = urlParams.get('msg');

    fetch("get_course_name.php")
        .then(response => response.json())
        .then(data => {
            if (data.course_name) {
                document.getElementById("courseTitle").textContent = data.course_name;
            } else {
                console.error(data.error || "Failed to get course name.");
            }
        })
        .catch(err => console.error("Error fetching course name:", err));

    if (alertType && alertMsg) {
        const decodedMsg = decodeURIComponent(alertMsg);
        alertContent.textContent = decodedMsg;

        if (alertType === 'success') {
            alertContent.classList.add("bg-green-100", "text-green-800", "border-green-300");
        } else {
            alertContent.classList.add("bg-red-100", "text-red-800", "border-red-300");
        }

        alertModal.classList.remove("hidden");

        setTimeout(() => {
            alertModal.classList.add("hidden");
            alertContent.className = "bg-white px-6 py-4 rounded-lg shadow-lg text-sm font-medium text-center max-w-sm w-full border";
        }, 3000);
    }

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

            if (data.length === 0) {
                setContainer.innerHTML = `<p class="text-gray-800 text-center w-full">No Flashcard Sets Yet.</p>`;
                return;
            }
            fetch("get_current_user.php")
                .then(res => res.json())
                .then(userData => {
                    const currentUserID = userData.user_id;

                    data.forEach(set => {
                        const isOwner = set.owner_id == currentUserID;
                        const isPublic = set.is_public == 1;

                        const card = document.createElement("a");
                        card.href = `coursePage.html?set_id=${set.set_id}`;
                        card.className = `relative block w-full sm:w-[calc(33.33%-1rem)] bg-white shadow-md rounded-lg p-4 hover:bg-gray-200 transition`;

                        card.innerHTML = `
                            <div class="flex justify-between items-start">
                                <h3 class="text-xl font-semibold text-gray-800 max-w-[90%] break-words whitespace-pre-wrap">${set.name}</h3>
                                <div class="relative">
                                <button class="dotsBtn text-2xl p-1 rounded hover:bg-gray-200">&#x22EE;</button>
                                <div class="modalMenu hidden absolute right-0 mt-2 w-40 bg-white border rounded shadow-lg z-10">
                                ${isOwner ? `
                                    <button class="editBtn w-full text-left px-4 py-2 hover:bg-gray-100">Edit</button>
                                ` : ''}
                                <form onsubmit="return confirm('Are you sure you want to delete this set?');" action='delete_flashcard_set.php' method='post'>
                                    <input type='hidden' name='set_id' value='${set.set_id}'>
                                    <button type='submit' class='w-full text-left px-4 py-2 hover:bg-gray-100'>Delete</button>
                                </form>
                            </div>
                        </div>

                            </div>
                            <p class="mt-2 text-gray-600 break-words whitespace-pre-wrap">${set.description}</p>
                            <p class="mt-3 text-base font-bold ${isPublic ? 'text-green-600' : 'text-red-600'}">${isPublic ? 'Public' : 'Private'}</p>
                            <span class="absolute bottom-2 right-2 text-xs font-bold px-2 py-1 rounded-full ${isOwner ? 'bg-green-600 text-white' : 'bg-red-600 text-white'}">
                                ${isOwner ? 'Your Set' : 'Saved Set'}
                            </span>
                        `;

                        setContainer.appendChild(card);

                        const dotsBtn = card.querySelector(".dotsBtn");
                        const modalMenu = card.querySelector(".modalMenu");

                        dotsBtn.addEventListener("click", (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            document.querySelectorAll(".modalMenu").forEach(menu => {
                                if (menu !== modalMenu) menu.classList.add("hidden");
                            });
                            modalMenu.classList.toggle("hidden");
                        });

                        document.addEventListener("click", (e) => {
                            const isDotsButton = e.target.closest(".dotsBtn");
                            const isMenu = e.target.closest(".modalMenu");

                            if (!isDotsButton && !isMenu) {
                                document.querySelectorAll(".modalMenu").forEach(menu => {
                                    menu.classList.add("hidden");
                                });
                            }
                        });
                        

                        if (isOwner) {
                            const editBtn = card.querySelector(".editBtn");

                            editBtn.addEventListener("click", (e) => {
                                e.preventDefault();
                                e.stopPropagation();
                                setIdInput.value = set.set_id;
                                setNameInput.value = set.name;
                                setDescInput.value = set.description;

                                // Set the correct radio button checked based on set.is_public
                                const radios = document.getElementsByName("visibility");
                                radios.forEach(radio => {
                                    radio.checked = (radio.value === String(set.is_public));
                                });

                                editModal.classList.remove("hidden");
                            });
                            

                            closeBtn2.addEventListener("click", () => {
                                editModal.classList.add("hidden");
                            });

                            cancelBtn2.addEventListener("click", () => {
                                editModal.classList.add("hidden");
                            });
                        }

                    });
                    
                })
                .catch(err => {
                    setContainer.innerHTML = `<p class="text-red-500">Failed to fetch user information.</p>`;
                    console.error("Error fetching current user:", err);
                });
        })
        .catch(err => {
            setContainer.innerHTML = `<p class="text-red-500">Failed to load flashcard sets.</p>`;
            console.error(err);
        });
});
