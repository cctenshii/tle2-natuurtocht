document.getElementById("answerA").addEventListener("click", pressedBtn);
document.getElementById("answerB").addEventListener("click", pressedBtn);
document.getElementById("answerC").addEventListener("click", pressedBtn);

function pressedBtn() {
    document.getElementById('result_ans').innerHTML = "Button pressed";
    alert("pressed");
}
