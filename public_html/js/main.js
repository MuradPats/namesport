$(document).ready(function () {

    /*$('#submit').click(function () {
        answerQuestion(answer, uuid, sid);
    });*/
    $('#game_container').hide();
    $('#start_game').click(function () {
        startGame();
        $('#game_container').show();
        $('#start_game_container').hide();
    });
});

var uuid = null;
var answer = null;
var sid = null;
var time = -1;
var interval;
var newQuestionTimer;
//DEBUG
var hint = false;

function setTime(t) {
    //TODO: make this use javascript Date object instead, for delta time etc.
    console.log("[IDLAB] setTIme");
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
    console.log("[IDLAB] gotQuestion");
    uuid = e.data[0].uuid;
    sid = e.data[0].sid;
    $('img').attr('src', 'https://ideelabor.ee/api/namesport/v1/image/' + uuid + '?SID=' + sid);
    //Generate table
    var clicked = false;
    console.log("got question");
    $('#answers').empty();
    e.data[0].answers.forEach(function (name) {
        var lia = $("<a href='#' class='btn btn-default btn-block'>" + name + "</a>");
        var liel = $("<li></li>").append(lia);
        var liclick = lia.click(function (event) {
            event.preventDefault();
            if(!clicked) {
                clicked = true;
                $(this).addClass("human-answer");
                console.log("selected " + name);
                answer = name;
                answerQuestion(answer, uuid, sid);
            }
        });
        if(hint && e.data[0].hasOwnProperty("hint")) {
            if (name === e.data[0].hint) {
                liel.append("<div id='hint'><img src='img/HINT.png' alt='HINT' height='24' /></div>");
            }
        }
        $('#answers').append(liel);
    });
}
function startGame() {
    console.log("[IDLAB] startGame");
    $.ajax({
        url: "https://ideelabor.ee/api/namesport/v1/question/start",
        success: function (e) {
            if (e.status === "ERROR") {
                handleError(e);
            } else {
                interval = setInterval(function () {
                    setTime(time - 0.1);
                }, 100);
                setTime(e.data[0].gamelength);
                //console.log(e)
                gotQuestion(e);
            }
        }
    });
}
function handleError(e) {
    console.log("[IDLAB] handleError", e);
    clearInterval(interval);
    clearTimeout(newQuestionTimer);
    if (e.reason === "Game over") {
        gameOver();
    }
}
function gameOver() {
    console.log("[IDLAB] gameOver");
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
            console.log(e.status);
            if (e.status === "ERROR") {
                handleError(e);
            } else {
                console.log(e);
                $('#score span').text(e.data[0].score);
                var tlength = 3000;
                if (e.data[0].answer === "true") {
                    $("a.human-answer").removeClass("btn-primary").addClass("btn-success");
                    tlength = 100;
                } else {
                    $("a.human-answer").removeClass("btn-primary").addClass("btn-warning");
                    $("#answers li > a").each(function (index, element) {
                        var nm = $(element).text();
                        if(e.data[0].rightanswer === nm) {
                            $(element).removeClass("btn-primary").addClass("btn-success");
                        }
                    });
                }
                newQuestionTimer = setTimeout(function () {
                    nextQuestion(sid);
                }, tlength);
            }

        }
    })
}
function reset() {
    
    $('#game_container').hide();
    $('#start_game_container').show();
}