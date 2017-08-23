//TODO:
// * if game ends without player giving an answer - submit "wrong" answer; so the right one gets displayed

var uuid = null,
    answer = null,
    sid = null,
	timertime = null,
	endscore = 0,
    time = -1,
    interval,
    newQuestionTimer;
    
//DEBUG && cheat
var debug = false,
    hint = false;

jQuery.extend({
getValues: function(url) {
    var result = null;
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        async: false,
        success: function(data) {
            dito = JSON.stringify(data);
			result = dito.replace(/\[/g,"").replace(/\]/g,"").replace(/\"/g,"").split("},{");
        }
    });
   return result;
}
});


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
	$('#countdownrow').hide();
	showPlay();
    $('#startgame_easy').click(function () {
        preGame();
        
    });
	$('#startgame_medium').click(function () {
		preGame();
        
    });
	$('#startgame_hard').click(function () {
		preGame();
        
    });
	//Menu clickListeners
	$('#menu_play').click(function () {
        
		showPlay();
        
    });
	$('#menu_about').click(function () {
        
		showAbout();
        
    });
	$('#menu_leaderboards').click(function () {
		
        showLeaderboards();
		
    });
	
});

function preGame(){
	$('#playrow').hide();
	$('#countdownrow').show();
	$('#counterelement').show();
	var counterElement = document.getElementById("counterelement");
	counterElement.innerHTML = "3";
	$('#counterelement').fadeOut();
	var counter = 2;
	id = setInterval(function() {
    if(counter < 1) {
		$('#countdownrow').hide();
        startGame();
		showGame();
        clearInterval(id);
    } else {
		$('#counterelement').show();
        counterElement.innerHTML = counter.toString();
		$('#counterelement').fadeOut();
		
    }
	counter--;
}, 500);
}

function showPlay(){
    $('#gamerow').hide();
	$('#aboutrow').hide();
	$('#leaderboardsrow').hide();
	$('#submitrow').hide();
	$('#playrow').fadeIn();
}

function showGame(){
	$('#playrow').hide();
	$('#aboutrow').hide();
	$('#leaderboardsrow').hide();
	$('#submitrow').hide();
	$('#gamerow').fadeIn();
}

function showAbout(){
	$('#playrow').hide();
    $('#gamerow').hide();
	$('#leaderboardsrow').hide();
	$('#submitrow').hide();
	$('#aboutrow').fadeIn();
}

function showLeaderboards(){
	$('#playrow').hide();
    $('#gamerow').hide();
	$('#aboutrow').hide();
	$('#submitrow').hide();
	$('#leaders').empty();
	var counter1= 0;
	var testData = $.getValues("fromdb.php");
	if(testData!=null){
		console.log("wasnt null");
		testData.forEach(function (index){
			temp = testData[counter1].replace(/\{/g,"").replace(/\}/g,"").replace(/name:/g,"").replace(/score:/g,"").split(",");
			var lia = $("<p >" + temp[0]+ "		" + temp[1] + "<br>"+"</p>");
			var liel = $("<li></li>").append(lia);
			$('#leaders').append(liel);
			counter1++;
		})
	}else{
		console.log("was null");
		var lia = $("<p >" + "No Highscores Yet!" + "<br>"+"</p>");
		var liel = $("<li></li>").append(lia);
		$('#leaders').append(liel);
	}
	$('#leaderboardsrow').fadeIn();
}


function showSubmit(){
	$('#playrow').hide();
    $('#gamerow').hide();
	$('#aboutrow').hide();
	$('#leaderboardsrow').hide();
	$('#submitrow').fadeIn();
}

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
        var lia = $("<a href='#' >" + name + "</a>");
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
		
		//class='btn btn-default btn-block'
    });
}

function starttimer(timer){
	$('#demo').pietimer({
	seconds: timer,
	color: 'rgba(132, 0, 0, 1)',
	height: 100,
	width: 100,
	is_reversed: false
	},
	function(){
		
	});
	$('#demo').pietimer('start');
}

function startGame() {
    console.log("[IDLAB] startGame with SID: " + sid );
    $.ajax({
        url: "https://ideelabor.ee/api/namesport/v1/question/start?SID="+sid,
        success: function (e) {
			timertime = e.data[0].gamelength;			
			starttimer(timertime);
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
	/*
    var submit = confirm("Game over, submit your score: "+$('#score span').text());
    //TODO: Mart 
    if (submit === true) {
        //Submit score somewhere
    }
	*/
	showSubmit();
    //reset();

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
				endscore = e.data[0].score;
                $('#score span').text(e.data[0].score);
                var tlength = 3000;
                if (e.data[0].answer === "true") {
                    $("li.human-answer").removeClass("btn-primary").addClass("btn-success");
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

function onSignIn(googleUser) {
  var profile = googleUser.getBasicProfile();
  console.log('Name: ' + profile.getName());
  console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
  console.log(endscore);
  $.post("intodb.php",
    {
        name: profile.getName(),
        score: endscore,
		mail: profile.getEmail
    },
    function(data, status){
		console.log("");
    });
  signOut();
  showLeaderboards();
  
}

function signOut() {
	var auth2 = gapi.auth2.getAuthInstance();
	auth2.signOut().then(function () {
	console.log('User signed out.');
	auth2.disconnect();
	console.log('disconnected');
	});
}



function reset() {
    
    showPlay();
}