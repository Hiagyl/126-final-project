console.log("profile.js loaded");

document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM fully loaded");
    const openBtn = document.getElementById('openEditProfileModalBtn');
    const modal = document.getElementById('editProfileModalBackDrop');
    const closeBtn = document.getElementById('closeEditProfileModalBtn');
    const cancelBtn = document.getElementById('cancelEditProfileModalBtn');


    fetch("sessionData.php")
        .then(response => {
            if (!response.ok) {
                throw new Error("User not logged in");
            }
            return response.json();
        })
        .then(data => {
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
        modal.classList.remove("hidden");

    });

    closeBtn.addEventListener("click", () => {
        modal.classList.add("hidden");
    });

    cancelBtn.addEventListener("click", () => {
        modal.classList.add("hidden");
    });
});
