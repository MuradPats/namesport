$(document).ready(function () {
    var uuid = null;
    var answer = null;
    function gotQuestion(e) {
        uuid = e.data[0].uuid;
        $('img').attr('src', 'https://ideelabor.ee/api/namesport/v1/image/' + uuid);
        //Generate table
        console.log("got question");
        $('#answers').empty();
        e.data[0].answers.forEach(function (name) {
            var li = $("<li>" + name + "</li>").click(function (event) {
                var target = $(event.target);
                $('li').removeClass("selected");
                target.addClass("selected");
                console.log("selected" + name);
                answer = name;
            });
            if (name === e.data[0].hint) {
                li.addClass("selected");
                answer = name;

            }
            $('#answers').append(li);
        });
    }
    function startGame() {
        $.ajax({
            url: "https://ideelabor.ee/api/namesport/v1/question/start",
            success: function (e) {
                console.log(e)
                gotQuestion(e);
            }
        });
    }
    function handleError() {
        $('#overlay').show();
        setTimeout(function () {
            $('#overlay').hide();
            $('#score span').text(0);
            startGame();
        }, 2000)

    }
    function nextQuestion() {
        $.ajax({
            url: "https://ideelabor.ee/api/namesport/v1/question?hint=true",
            success: function (e) {
                console.log(e);
                if (e.status === "ERROR") {
                    handleError();
                } else {
                    gotQuestion(e);
                }
            }
        });
    }
    function answerQuestion(name, uuid) {
        $.ajax({
            url: "https://ideelabor.ee/api/namesport/v1/answer/" + name + "/" + uuid,
            success: function (e) {
                if (e.status === "ERROR") {
                    handleError();
                } else {
                    console.log(e);
                    $('#score span').text(e.data[0].score);
                    nextQuestion();
                }

            }
        })
    }
    $('button').click(function () {
        answerQuestion(answer, uuid);
    })
    //startGame();
    nextQuestion();
})