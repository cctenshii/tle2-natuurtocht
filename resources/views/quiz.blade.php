<x-app-layout>
    <x-slot:header>Quiz</x-slot:header>
    <!--I'm so so sorry for the css and Javascript chilling in here but I had no choice ;w; it didn't work otherwise-->
    <body
        style="text-align: center; display: flex; gap: 3vh; flex-direction: column; background: #FCF5EB;">
    <p id="answer" style="visibility:hidden; position: absolute">2</p>
    <section style="margin-top: 6vh;">
        <h1 style="font-size: 2rem;">Upload gelukt!</h1>
        <hr style="background: black; margin-top: 2vh; margin-bottom: 2vh;">
        <p style="margin-left: 15vw; margin-right: 15vw;">Beantwoord de vraag juist voor een glimmende kaart!</p>
    </section>
    <section style="margin-top: 3vh;">
        <h2 style="margin-bottom: 2vh;">Vraag over de gemaakte kaart</h2>
        <div style="display: flex; flex-direction: column; gap: 2vh;">
            <button id="answerA"
                    style="background:#0076A8; color:white; border: none; border-radius: 10%; padding: 10px;">
                A) Antwoord A
            </button>
            <button id="answerB"
                    style="background:#0076A8; color:white; border: none; border-radius: 10%; padding: 10px;">
                B) Antwoord B
            </button>
            <button id="answerC"
                    style="background:#0076A8; color:white; border: none; border-radius: 10%; padding: 10px;">
                C) Antwoord C
            </button>
        </div>
    </section>
    <section style="margin-top: 3vh;">
        <span id="resultAns"></span>
        <p id="explanation" style="color: dimgrey; font-style: italic;"></p>
    </section>
    <script> <!--src="../js/quiz.js" defer-->
        let allButtons = document.getElementsByTagName("button");
        document.getElementById("answerA").addEventListener("click", function () {
            pressedBtn(0);
        });
        document.getElementById("answerB").addEventListener("click", function () {
            pressedBtn(1);
        });
        document.getElementById("answerC").addEventListener("click", function () {
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

            for (let i = 0; i < allButtons.length; i++) {
                let changeBtn = document.getElementsByTagName("button")[i].id;
                document.getElementById(changeBtn).disabled = true; //need enable again before leaving page?
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
            let changeBtn = document.getElementsByTagName("button")[btnChange].id;
            if (result) {
                resultTitle.innerHTML = "Correct";
                explanationElement.innerHTML = "Hier is de reden waarom je antwoord goed is";
                document.getElementById(changeBtn).style.background = "#16BE00";
            } else if (!result) {
                resultTitle.innerHTML = "Helaas";
                explanationElement.innerHTML = "Dit is niet het goede antwoord. Je hebt wel het kaartje verdiend!";
                document.getElementById(changeBtn).style.background = "red";
            }
        }

    </script>
    </body>
</x-app-layout>
</html>
