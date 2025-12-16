let allInputButtons = document.getElementsByTagName("input");
document.getElementById("answerA_input").addEventListener("click", function () {
    pressedBtn(0);
});
document.getElementById("answerB_input").addEventListener("click", function () {
    pressedBtn(1);
});
document.getElementById("answerC_input").addEventListener("click", function () {
    pressedBtn(2);
});

let rightButton = parseInt(document.getElementById("answer").innerHTML);
let resultTitle = document.getElementById("resultAns");
let explanationElement = document.getElementById("explanation");

window.addEventListener('load', init);

function init() {

}

function pressedBtn(btnPressed) {
    document.getElementById('resultAns').innerHTML = "Button pressed";
    buttonCheck(btnPressed);

    //disables all buttons
    for (let i = 0; i < allInputButtons; i++) {
        let changeBtn = document.getElementsByTagName("input")[i].id;
        document.getElementById(changeBtn).disabled = true;
    }
}

function buttonCheck(btn) {
    if (btn === rightButton) {
        result(true, btn);
    } else {
        result(false, btn);
    }
}

function result(result, btnChange) {
    let changeInputBtn = allInputButtons[btnChange].id; //neater way to write it
    if (result) {
        resultTitle.innerHTML = "Correct";
        explanationElement.innerHTML = "Hier is de reden waarom je antwoord goed is";
        document.getElementById(changeInputBtn).style.background = "#16BE00";
    } else if (!result) {
        resultTitle.innerHTML = "Helaas";
        explanationElement.innerHTML = "Dit is niet het goede antwoord. Je hebt wel het kaartje verdiend!";
        document.getElementById(changeInputBtn).style.background = "red";
    }
    document.getElementById('submitBtn').style.visibility = "visible";
}
