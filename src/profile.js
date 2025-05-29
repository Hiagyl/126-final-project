console.log("profile.js loaded");

document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM fully loaded");
    const openBtn = document.getElementById('openEditProfileModalBtn');
    const modal = document.getElementById('editProfileModalBackDrop');
    const closeBtn = document.getElementById('closeEditProfileModalBtn');
    const cancelBtn = document.getElementById('cancelEditProfileModalBtn');

    // Input fields inside the edit modal (adjust these IDs to match your form)
    const inputUsername = document.getElementById('editProfileName');
    const inputCollege = document.getElementById('editCollege');
    const inputYearLevel = document.getElementById('editYearLevel');
    const inputAcadOrg = document.getElementById('editAcadOrg');

    let userData = null;  // store data to use later

    fetch("sessionData.php")
        .then(response => {
            if (!response.ok) {
                throw new Error("User not logged in");
            }
            return response.json();
        })
        .then(data => {
            userData = data;

            // Update profile display fields
            document.getElementById('username').textContent = data.username;
            document.getElementById('college').textContent = data.college;
            document.getElementById('year_level').textContent = data.year_level;
            document.getElementById('acad_org').textContent = data.acad_org;
            document.getElementById('current_league').textContent = data.current_league;
            document.getElementById('highest_league').textContent = data.highest_league;
            document.getElementById('streak').textContent = data.streak;
            document.getElementById('exp').textContent = data.exp;
        })
        .catch(error => {
            console.error("Error loading session data:", error);
            alert("You are not logged in. Please log in again.");
            window.location.href = "login.php";
        });

    openBtn.addEventListener("click", () => {
        // Pre-fill inputs when modal opens, if data is loaded
        if (userData) {
            inputUsername.value = userData.username || " ";
            inputCollege.value = userData.college || " ";
            inputYearLevel.value = userData.year_level || " ";
            inputAcadOrg.value = userData.acad_org || " ";
        }
        modal.classList.remove("hidden");
    });

    closeBtn.addEventListener("click", () => {
        modal.classList.add("hidden");
    });

    cancelBtn.addEventListener("click", () => {
        modal.classList.add("hidden");
    });
});
