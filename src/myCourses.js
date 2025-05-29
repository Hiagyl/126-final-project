document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("modalBackdrop");
    const editModal = document.getElementById("modalBackdrop2");
    const openBtn = document.getElementById("openModalBtn");
    const closeBtn = document.getElementById("closeModalBtn");
    const cancelBtn = document.getElementById("cancelModalBtn");
    const closeBtn2 = document.getElementById("closeModalBtn2");
    const cancelBtn2 = document.getElementById("cancelModalBtn2");
    const courseContainer = document.getElementById("courseContainer");
    const courseNameInput = document.getElementById("courseName");
    const courseDescInput = document.getElementById("courseDesc");
    const courseIdInput = document.getElementById("courseID");
    // const modalTitle = document.getElementById("modalTitle");
    const alertModal = document.getElementById("alertModal");
    const alertContent = document.getElementById("alertContent");
    const urlParams = new URLSearchParams(window.location.search);
    const alertType = urlParams.get('alert');
    const alertMsg = urlParams.get('msg');

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

    
    fetch("get_courses.php")
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                courseContainer.innerHTML = `<p class="text-red-500">${data.error}</p>`;
                return;
            }

            data.forEach(course => {
                const card = document.createElement("a");
                card.href = `insideCourse.html?course_id=${course.course_id}`;
                card.className = "block w-full sm:w-[calc(50%-1rem)] bg-white shadow-md rounded-lg p-4 hover:bg-gray-100 transition";
                card.innerHTML = `
            <div class="flex justify-between items-start">
                <h3 class="text-xl font-semibold text-gray-800 max-w-[90%] break-words whitespace-pre-wrap">${course.course_name}</h3>
                <div class="relative">
                    <button class="dotsBtn text-2xl p-1 rounded hover:bg-gray-200">&#x22EE;</button>
                    <div class="modalMenu hidden absolute right-0 mt-2 w-40 bg-white border rounded shadow-lg z-10">
                    <button class="editBtn w-full text-left px-4 py-2 hover:bg-gray-100">Edit</button>
                    <form onsubmit=\"return confirm('Are you sure you want to delete this course?');\" action='delete_course.php'  method='post'>
                    <input type='text' style='display:none' name='course_id' value='${course.course_id}'>
                    <button type='submit' class='w-full text-left px-4 py-2 hover:bg-gray-100'>Delete</button>
                    </form>
                    </div>
                </div>
            </div>
            <p class="mt-2 text-gray-600 break-words whitespace-pre-wrap">${course.course_description}</p>
        `;
                courseContainer.appendChild(card);

                const dotsBtn = card.querySelector(".dotsBtn");
                const modalMenu = card.querySelector(".modalMenu");
                const editBtn = card.querySelector(".editBtn");
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

                    courseIdInput.value = course.course_id;
                    courseNameInput.value = course.course_name;
                    courseDescInput.value = course.course_description;
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
            courseContainer.innerHTML = `<p class="text-red-500">Failed to load courses.</p>`;
            console.error(err);
        });
    
    document.addEventListener("click", (e) => {
        const isDotsBtn = e.target.closest(".dotsBtn");
        const isModalMenu = e.target.closest(".modalMenu");

        if (!isDotsBtn && !isModalMenu) {
            document.querySelectorAll(".modalMenu").forEach(menu => {
                menu.classList.add("hidden");
            });
        }
    });
        
});