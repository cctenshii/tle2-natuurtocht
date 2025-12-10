<x-app-layout>
    <?php
    $answersArray = json_decode($data->answers, true);
    shuffle($answersArray);

    $setAnswerId;
    foreach ($answersArray as $question) {
        if ($question['correct'] === true) {
            $setAnswerId = $question['id'];
        }
    }
    ?>

    <x-slot:header>Quiz</x-slot:header>
    <!--I'm so so sorry for the css and Javascript chilling in here but I had no choice ;w; it didn't work otherwise-->
    <body
            style="text-align: center; display: flex; gap: 3vh; flex-direction: column; background: #FCF5EB;">
    <div style="visibility: hidden; position: absolute"><!--Hidden elements-->
        <p id="answerNum">{{$setAnswerId}}</p>
        <!-- -1 isn't best way to solve but works for now-->
        <p id="answerExplanation"></p>
        <!--🔴 Dit moet nog een 'if empty' zodat er of text is of de daadwerkelijke explanation-->
        <!--🔴 Als het goed is geklikt moet de kaart shiny worden (miss different btns)-->
    </div>
    <section style="margin-top: 6vh;">
        <h1 style="font-size: 2rem;">Upload gelukt!</h1>
        <hr style="background: black; margin-top: 2vh; margin-bottom: 2vh;">
        <p style="margin-left: 15vw; margin-right: 15vw;">Beantwoord de vraag juist voor een glimmende kaart!</p>
    </section>
    <section style="margin-top: 3vh;">
        <form action="{{ route('index') }}" method="GET"> <!--Post to page that changes card data-->
            <h2 style="margin-bottom: 2vh;">{{$data->question_text}}</h2>
            <div style="display: flex; flex-direction: column; gap: 2vh;">
                @foreach($answersArray as $question)
                    <input type="button" value="{{$question['text']}}" id="{{$question['id']}}" class="userInput"
                           style="background:#0076A8; color:white; border: none; border-radius: 10%; padding: 10px;">
                @endforeach
            </div>
            <div style="margin-top: 3vh">
                <span id="resultAns"></span>
                <p id="explanation" style="color: dimgrey; font-style: italic;"></p>
                <div style="margin-top: 3vh">
                    <input type="submit" value="Verder" id="submitBtn"
                           style="visibility: hidden; border: #63BFB5 2px solid; padding: 10px; background: #319E88">
                </div>
            </div>
        </form>
    </section>
    <script> <!--src="../js/quiz.js" defer-->
        let allInputButtons = document.getElementsByClassName("userInput");

        //Get data from php
        let rightButton = parseInt(document.getElementById("answerNum").innerHTML);
        let explanation = document.getElementById("answerExplanation").innerHTML;
        //Get the explanation if it exists (check is in init)

        //set data from php
        let resultTitle = document.getElementById("resultAns");
        let explanationElement = document.getElementById("explanation");

        window.addEventListener('load', init);

        function init() {
            for (let i = 0; i < allInputButtons.length; i++) {
                document.getElementsByClassName("userInput")[i].addEventListener("click", function () {
                    pressedBtn(i)
                });
            }

            if (explanation === null || explanation === "") { //null if it's empty
                explanation = "Geen uitleg beschikbaar";
            } else {
                explanation = document.getElementById("answerExplanation").innerHTML;
            }
        }

        function pressedBtn(btnPressed) {
            document.getElementById('resultAns').innerHTML = "Button pressed";
            buttonCheck(btnPressed);

            //disables all buttons
            for (let i = 0; i < allInputButtons.length; i++) {
                document.getElementsByClassName("userInput")[i].disabled = true;
            }
        }

        function buttonCheck(btn) {
            if (parseInt(allInputButtons[btn].id) === rightButton) {
                result(true, btn);
            } else {
                result(false, btn);
            }
        }

        function result(result, btnChange) {
            let changeInputBtn = allInputButtons[btnChange].id;
            if (result) {
                resultTitle.innerHTML = "Correct!";
                explanationElement.innerHTML = "Hier is de reden waarom je antwoord goed is";
                document.getElementById(changeInputBtn).style.background = "#16BE00";
            } else if (!result) {
                resultTitle.innerHTML = "Helaas";
                explanationElement.innerHTML = "Dit is niet het goede antwoord. Je hebt wel het kaartje verdiend!";
                document.getElementById(changeInputBtn).style.background = "red";
            }
            explanationElement.innerHTML = explanation;

            document.getElementById('submitBtn').style.visibility = "visible";
        }
    </script>
    </body>
</x-app-layout>
