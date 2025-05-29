const setId = window.sessionData?.setId || '';

document.addEventListener("DOMContentLoaded", function () {
    const results = JSON.parse(localStorage.getItem("test_results")) || {
        percentage: 0,
        exp: 0,
        total: 0,
        correct: 0,
        wrong: 0
    };

    document.getElementById("scorePercentage").textContent = `${results.percentage}%`;
    document.getElementById("exp").textContent = results.exp;
    document.getElementById("total").textContent = results.total;
    document.getElementById("correct").textContent = results.correct;
    document.getElementById("wrong").textContent = results.wrong;
});

function saveExpAndRedirect(url) {
    const results = JSON.parse(localStorage.getItem("test_results")) || { exp: 0 };

    fetch("save_exp_log.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            source_id: setId,
            exp_earned: results.exp
        })
    })
    .then(res => res.json())
    .then(data => {
        console.log("EXP log saved:", data);
        window.location.href = url;
    })
    .catch(err => {
        console.error("EXP log failed:", err);
        window.location.href = url;
    });
}

function goBack() {
    saveExpAndRedirect(`coursePage.html?set_id=${setId}`);
}

function retakeTest() {
    saveExpAndRedirect("test.php");
}
