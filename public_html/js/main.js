$(document).ready(function () {

    $('#submit').click(function () {
        answerQuestion(answer, uuid, sid);
    });
    $('#game_container').hide();
    $('#start_game').click(function () {
        startGame();
        $('#game_container').show();
        $('#start_game').hide();
    });
});

var uuid = null;
var answer = null;
var sid = null;
var time = -1;
var interval;

function setTime(t) {
    //TODO: make this use javascript Date object instead, for delta time etc.
    time = t;
    if (t < 0) {
        time = 0;
        answerQuestion(name, uuid, sid);
        clearInterval(interval);
        console.log("Stopped timer");
    }
    var renderedTime = Math.round(time * 10);
    renderedTime = renderedTime % 10 === 0 ? renderedTime / 10 + '.0' : renderedTime / 10;
    $("#time").text(renderedTime);
}
function gotQuestion(e) {
    uuid = e.data[0].uuid;
    sid = e.data[0].sid;
    $('img').attr('src', 'https://ideelabor.ee/api/namesport/v1/image/' + uuid + '?SID=' + sid);
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
            interval = setInterval(function () {
                setTime(time - 0.1);
            }, 100);
            setTime(120);
            console.log(e)
            gotQuestion(e);
        }
    });
}
function handleError(e) {
    if (e.reason === "Game over") {
        gameOver();
    }
}
function gameOver() {
    var submit = confirm("Game over, submit your score: "+$('#score span').text());
    //TODO: Mart 
    if (submit === true) {
        //Submit score somewhere
    }
    reset();

    //Hide the game, show the start game button
}
function nextQuestion(sid) {
    $.ajax({
        url: "https://ideelabor.ee/api/namesport/v1/question?hint=true&SID=" + sid,
        success: function (e) {
            console.log(e);
            if (e.status === "ERROR") {
                handleError(e);
            } else {
                gotQuestion(e);
            }
        }
    });
}
function answerQuestion(name, uuid, sid) {
    $.ajax({
        url: "https://ideelabor.ee/api/namesport/v1/answer/" + name + "/" + uuid + "?SID=" + sid,
        success: function (e) {
            if (e.status === "ERROR") {
                handleError(e);
            } else {
                console.log(e);
                $('#score span').text(e.data[0].score);
                nextQuestion(sid);
            }

        }
    })
}
function reset() {
    $('#game_container').hide();
    $('#start_game').show();
}