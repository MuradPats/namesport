//TODO:
// * if game ends without player giving an answer - submit "wrong" answer; so the right one gets displayed

var uuid = null,
    answer = null,
    sid = null,
    time = -1,
    interval,
    newQuestionTimer;
    
//DEBUG && cheat
var debug = true,
    hint = true;

$(document).ready(function () {
    
    $.ajax({
        url: "https://ideelabor.ee/api/namesport/v1/session",
        async: false,
        success: function (e) {
            if (debug === true) {
                handleDebug(e, "session");
            }
            if (e.status === "ERROR") {
                handleError(e);
            } else {
                sid = e.data[0].sid;
            }
        },
        error: function (e) {
            console.log(e);
        }
    });

    $('#game_container').hide();
    $('#start_game').click(function () {
        startGame();
        $('#game_container').show();
        $('#start_game_container').hide();
    });
});

function setTime(t) {
    //TODO: make this use javascript Date object instead, for delta time etc.
    //console.log("[IDLAB] setTime");
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
    console.log("[IDLAB] startGame with SID: " + sid );
    $.ajax({
        url: "https://ideelabor.ee/api/namesport/v1/question/start?SID="+sid,
        success: function (e) {
            if (debug === true) {
                handleDebug(e, "startGame");
            }
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
function handleDebug(e,event) {
    console.log("handleDebug");
    $("#insert-json-here").html("<strong>" + event + "</strong><br />" + JSON.stringify(e));
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
            if (debug === true) {
                handleDebug(e, "nextQuestion");
            }
            if (e.status === "ERROR") {
                handleError(e);
            } else {
                gotQuestion(e);
            }
        }
    });
}
function answerQuestion(name, uuid, sid) {
    console.log("SID is "+ sid);
    $.ajax({
        url: "https://ideelabor.ee/api/namesport/v1/answer/" + name + "/" + uuid + "?SID=" + sid,
        success: function (e) {
            if (debug === true) {
                handleDebug(e, "answerQuestion");
            }
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