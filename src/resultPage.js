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

function goBack() {
    window.location.href = `coursePage.html?set_id=${setId}`;
}

function retakeTest() {
    window.location.href = `test.php`;
}