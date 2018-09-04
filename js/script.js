var hash;
var lives = 3;
var selectedquestion = "";
var selectedanswer = "";
var answers = [];
var currentquestion = 0;
var currentpoints = 0;
var currentcategories = [];
var userid = new Date().valueOf();
var correctquestions = 0;
var timestamp;
var consent = false;
var timewatering = 15000;

function init() {
	countPointsCloud();
	updatePagesSize();
	randomTips();
}

init();

function pad(num, size) {
	var s = num + "";
	while (s.length < size) s = "0" + s;
	return s;
}

/** BEGIN: Functions for manipulating playing elements **/
function countPointsCloud() {
	var calcpoints = 0;
	var calcpercentage = (daypoints / maxpoints) * 100;
	var calcpointsend = maxpoints - daypoints;
	changeWaterLevel(calcpercentage);
	var timer = setInterval(function () {
		if (calcpoints < calcpointsend - 50) {
			calcpoints = Math.floor(calcpoints + calcpointsend * 0.021);
		} else {
			if (calcpoints < calcpointsend) {
				calcpoints++;
			} else {
				window.clearInterval(timer);
			}
		}
		$("#points").html(pad(calcpoints, 4));
	}, 50);
	var msg = {
		sendto: 'tv',
		function: 'newgame'
	}
	setTimeout(function () {
		sendWebSocket(JSON.stringify(msg));
	}, 5000);
	$(".page").removeClass("hide");
}

var sensor1 = false;
var sensor2 = false;
var checkingsensor = false;

function checkSensors() {
	if (!checkingsensor) {
		checkingsensor = true;
		$.ajax({
			type: "GET",
			url: "bd.php",
			data: "sensorstatus",
			success: function (response) {
				var sensor = JSON.parse(response);
				changeSensors(Boolean(Number(sensor[0].sensor1)), Boolean(Number(sensor[0].sensor2)));
			},
			complete: function (response) {
				checkingsensor = false;
			}
		});
	}
}

function changeSensors(newsensor1, newsensor2) {
	if (sensor1 != newsensor1 || sensor2 != newsensor2) {
		sensor1 = newsensor1;
		sensor2 = newsensor2;

		if (sensor1) {
			$("#player1").addClass("ok");
		} else {
			$("#player1").removeClass("ok");
		}
		if (sensor2) {
			$("#player2").addClass("ok");
		} else {
			$("#player2").removeClass("ok");
		}
		if (!sensor1 && !sensor2) {
			if (hash === "page2") {
				var msg = ["Sente na cadeira para poder jogar!"];
				changeEmoji("sad", 3000, msg[Math.floor(Math.random() * msg.length)], true)
				$("#startbutton").removeClass("show").addClass("hide");
			}
		} else {
			if (sensor1 && sensor2) {
				if (hash === "page2") {
					var msg = ["Eba! Vamos começar?", "Partiu jogar?", "Vamos começar?", "Legal! Agora é só começar!", "A diversão vai começar!"];
					changeEmoji("happy", 3000, msg[Math.floor(Math.random() * msg.length)], true)
					$("#startbutton").removeClass("hide").addClass("show");
				}
			} else {
				if (hash === "page2") {
					var msg = ["Convide alguém para jogar!", "Chame alguém para jogar com você!"];
					changeEmoji("sad", 3000, msg[Math.floor(Math.random() * msg.length)])
					$("#startbutton").removeClass("show").addClass("hide");
				}
			}
		}
		stillPlaying();
	}
}

var canwater = false;

function calcpointsleft() {
	return maxpoints - (daypoints + currentpoints) % maxpoints;
}

var oldestpoints = maxpoints - (daypoints + currentpoints) % maxpoints;
var lastoldpoints = maxpoints - (daypoints + currentpoints) % maxpoints;

function changePoints(points) {
	canwater = false;
	var newpoints = parseInt(currentpoints);
	currentpoints = parseInt(points);
	var calcpointstosend = calcpointsleft();
	lastoldpoints = maxpoints - (daypoints + currentpoints) % maxpoints;
	console.info("oldestpoints: "+oldestpoints + ", lastoldpoints: "+lastoldpoints);
	if (oldestpoints < lastoldpoints) {
		calcpointstosend = 0;
		canwater = true;
		oldestpoints = maxpoints - (daypoints + currentpoints) % maxpoints;
		lastoldpoints = maxpoints - (daypoints + currentpoints) % maxpoints;
	}
    if (oldestpoints < 100 && lastoldpoints > 100) {
		oldestpoints = 100;
    }
	var msgpoints = {
		sendto: 'tv',
		function: 'points',
		totalpoints: calcpointstosend
	}
	sendWebSocket(JSON.stringify(msgpoints));
	console.info(calcpointstosend);
	var timer = setInterval(function () {
		if (newpoints < points) {
			newpoints++;
		} else {
			window.clearInterval(timer);
		}
		$("#yourpoints").html(pad(newpoints, 4));
	}, 20);
}

function changeWaterLevel(percentage) {
	var old_percentage = $("#water").height() / $("#water").parent().height() * 100;
	var timefactor = Math.abs(old_percentage - percentage) * 0.06;
	$("#water").addClass("blinkwater");
	$("#water").animate({
		height: percentage + "%"
	}, 1000 * timefactor, "easeInQuad", function () {
		var timer = setTimeout(function () {
			$("#water").removeClass("blinkwater");
			window.clearTimeout(timer);
		}, 5000);
	});
}

var timerEmoji;

function changeEmoji(feeling, time, msg, send) {
	var coolmsg = ["Vocês são demais!", "Vocês são o máximo!", "Uhulll", "Ebaaa!", "Uauuu...", "Que demais!", "Lá vamos nós!", "Wow", "Yuupi!!", "Super!", "Curti!"];
	var sadmsg = ["Poxa...", "Não foi dessa vez!", "Foi por pouco...", "Não desistam...", "Na próxima vocês acertam!", "Na próxima vocês conseguem!", "Foi quase.."];
	var number;
	var bgclass;
	$("#plantemoji").removeClass("cool").removeClass("cute").removeClass("good").removeClass("haha").removeClass("haha2").removeClass("happy").removeClass("love").removeClass("super").removeClass("tongue").removeClass("wow");
	$("#plantemoji").removeClass("cry").removeClass("humpf").removeClass("meh").removeClass("notcool").removeClass("sad");
	$("#ballon").removeClass("show");
	if (feeling == "happy") {
		var coolarray = ["cool", "cute", "good", "haha", "haha2", "happy", "love", "super", "tongue", "wow"];
		number = Math.floor(Math.random() * coolarray.length);
		bgclass = coolarray[number];
		if (typeof msg == 'string' && msg != "") {
			$("#dialog").html(msg);
		} else {
			$("#dialog").html(coolmsg[Math.floor(Math.random() * coolmsg.length)]);
		}
	} else {
		if (feeling == "sad") {
			var sadarray = ["cry", "humpf", "meh", "notcool", "sad"];
			number = Math.floor(Math.random() * sadarray.length);
			bgclass = sadarray[number];
			if (typeof msg == 'string' && msg != "") {
				$("#dialog").html(msg);
			} else {
				$("#dialog").html(sadmsg[Math.floor(Math.random() * sadmsg.length)]);
			}
		}
	}
	window.clearTimeout(timerEmoji);
	if (typeof send == 'boolean' && send) {
		var msgplantemoji = {
			sendto: 'tv',
			function: 'plantemoji',
			feeling: feeling,
			time: time,
			message: $("#dialog").text(),
			emoji: bgclass
		}
		sendWebSocket(JSON.stringify(msgplantemoji));
	}
	$("#ballon").addClass("show");
	$("#plantemoji").addClass(bgclass);
	if (time > 0) {
		timerEmoji = setTimeout(function () {
			$("#plantemoji").removeClass(bgclass);
			$("#ballon").removeClass("show");
			window.clearTimeout(timerEmoji);
		}, time);
	}
}
/** END: Functions for manipulating playing elements **/

/** BEGIN: Functions for changing pages **/
function updatePagesSize() {
	$(".all-pages").width($(document).width() * ($(".all-pages .page").length + 1)).height($(document).height());
	$(".page").width($(document).width());
}

$(window).resize(function () {
	updatePagesSize();
	if (hash != null) {
		$(".all-pages").css({
			'margin-left': -($('div #' + hash + ':first').position().left)
		});
	}
});

function animatetoHash(page) {
	hash = page;
	$(".all-pages").animate({
		'margin-left': -($('div #' + page + ':first').position().left)
	}, 800);
};
/** END: Functions for changing pages **/

/** BEGIN: Reset when no user is playing **/
var timer1;
var stillplaying = true;
var countdownplaying;

function stillPlaying() {
	window.clearInterval(timer1);
	stillplaying = true;
	countdownplaying = 180;
	startCountdown();

	function startCountdown() {
		timer1 = setInterval(function () {
			if (countdownplaying >= 0) {
				countdownplaying--;
			}
			if (countdownplaying == 16) {
				stillplaying = false;
				$('#modal_playing').modal('show');
			}
		}, 1000);
	}
	var msgplantemoji = {
		sendto: 'tv',
		function: 'stillplaying'
	}
	sendWebSocket(JSON.stringify(msgplantemoji));
}

$('#modal_playing').on('shown.bs.modal', function (e) {
	stillplaying = false;
	window.clearInterval(timer1);
	timer1 = setInterval(function () {
		if (countdownplaying > 0) {
			countdownplaying--;
			$("#modal-countdown").html("O jogo será reiniciado em " + countdownplaying + "...");
		} else {
			window.clearInterval(timer1);
			window.location.reload(true);
		}
	}, 1000);
});

$('#modal_playing').on('hide.bs.modal', function (e) {
	countdownplaying = 180;
	stillPlaying();
});

$('#modal_ranking').on('hide.bs.modal', function (e) {
	saveRanking();
});

window.addEventListener('touchstart', function () {
	stillPlaying();
}, true);

var a1, b1, c1, a2, b2, c2;
var testing_orientation = false;

if (window.DeviceOrientationEvent) {
	window.addEventListener('deviceorientation', function (e) {
		a1 = Math.floor(e.alpha);
		b1 = Math.floor(e.beta);
		c1 = Math.floor(e.gamma);
		if ((b1 >= 65 && b1 <= 70) && (c1 >= -10 && c1 <= 10) && !testing_orientation) {
			testing_orientation = true;
			a2 = a1;
			b2 = b1;
			c2 = c1;
			setTimeout(function () {
				if (((a1 >= a2 - 3) && (a1 <= a2 + 3)) && ((b1 >= b2 - 3) && (b1 <= b2 + 3)) && ((c1 >= c2 - 3) && (c1 <= c2 + 3))) {
					countdownplaying = 30;
				}
				testing_orientation = false;
			}, 5000);
		}
	}, true);
}
/** END: Reset when no user is playing **/


/** BEGIN: Timer countdown functions **/
var objtimer;
var totaltime = 60;
var i = 360;
var progress;
var timeOut;

function stopTimer() {
	window.clearTimeout(timeOut);
}

function newTimer(time, obje) {
	i = 360;
	totaltime = time;
	objtimer = obje;
	stopTimer();
	setTimer("nc");
}

function setTimer(dir) {
	if (dir == "c")
		i = i + 3.6
	else
		i = i - 3.6;
	if (i < 0)
		i = 0;
	if (i >= 360)
		i = 360;
	progress = (100 * i) / 360;
	$("#" + objtimer + " .timer-text").html(Math.round(progress) + "<div class='timer-points'>PONTOS</div>");
	if (i <= 180) {
		$("#" + objtimer).css('background-image', 'linear-gradient(' + (90 + i) + 'deg, transparent 50%, #eec497 50%),linear-gradient(90deg, #eec497 50%, transparent 50%)');
	} else {
		$("#" + objtimer).css('background-image', 'linear-gradient(' + (i - 90) + 'deg, transparent 50%, #087aff 50%),linear-gradient(90deg, #eec497 50%, transparent 50%)');
	}
	if (progress <= 0) {
		selectedquestion = "";
		selectedanswer = "";
		nextAnswer();
		loseLife();
	}
    if (progress <= 50 && !$("#" + objtimer).hasClass("pulsate")) {
        $("#" + objtimer).addClass("pulsate");
    }
	if (i <= 360) {
		timeOut = window.setTimeout(function () {
			setTimer("nc");
		}, 10 * totaltime);
	}
}
/** END: Timer countdown functions **/

/** BEGIN: Quiz functions **/
var pos_answers = 0;
var obj;

function addAnswer() {
	var posend_answers = pos_answers + 3;
	var pos_total = obj.length;
	for (pos_answers = pos_answers; pos_answers < posend_answers; pos_answers++) {
		if (pos_answers < pos_total) {
			var show = "";
			if (pos_answers == 0) {
				show = " show active";
			}
			$("#list-answer").append('<a class="list-group-item list-group-item-action' + show + '" data-toggle="list" href="#answer' + pos_answers + '" role="tab">Answer ' + pos_answers + '</a>');
			$("#tab-content").append('<div class="tab-pane fade' + show + '" id="answer' + pos_answers + '" role="tabpanel"><div class="timer points"><div id="timer-answer' + pos_answers + '" class="progress-circle"><div class="circle"><span class="timer-text" id="timer-text">99<div class="timer-points">PONTOS</div></span></div></div></div><div class="top"></div><div class="top"><div class="question">' + obj[pos_answers].question + '</div></div><div class="answers"><div class="line"><button class="button big option ok" onclick="focusThisAnswer(this); $(\'#review_answer' + pos_answers + '\').html($(\'#btn1_answer' + pos_answers + '\').text()); selectedquestion=' + obj[pos_answers].id + '; selectedanswer=\'A\'" id="btn1_answer' + pos_answers + '" data-toggle="modal" data-target="#modal_answer' + pos_answers + '">' + obj[pos_answers].answer1 + '</button></div><div class="line"><button class="button big option" onclick="focusThisAnswer(this); $(\'#review_answer' + pos_answers + '\').html($(\'#btn2_answer' + pos_answers + '\').text()); selectedquestion=' + obj[pos_answers].id + '; selectedanswer=\'B\'" id="btn2_answer' + pos_answers + '" data-toggle="modal" data-target="#modal_answer' + pos_answers + '">' + obj[pos_answers].answer2 + '</button></div><div class="line"><button class="button big option" onclick="focusThisAnswer(this); $(\'#review_answer' + pos_answers + '\').html($(\'#btn3_answer' + pos_answers + '\').text()); selectedquestion=' + obj[pos_answers].id + '; selectedanswer=\'C\'" id="btn3_answer' + pos_answers + '" data-toggle="modal" data-target="#modal_answer' + pos_answers + '">' + obj[pos_answers].answer3 + '</button></div><div class="line"><button class="button big option" onclick="focusThisAnswer(this); $(\'#review_answer' + pos_answers + '\').html($(\'#btn4_answer' + pos_answers + '\').text()); selectedquestion=' + obj[pos_answers].id + '; selectedanswer=\'D\'" id="btn4_answer' + pos_answers + '" data-toggle="modal" data-target="#modal_answer' + pos_answers + '">' + obj[pos_answers].answer4 + '</button></div></div><div class="modal" id="modal_answer' + pos_answers + '" tabindex="-1" role="dialog"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><div class="modal-title">Confirma a resposta?</div></div><div class="modal-body" id="review_answer' + pos_answers + '"></div><div class="modal-footer"><button type="button" class="button big yes" data-dismiss="modal" id="answer' + pos_answers + '_yes" onclick="answerThis()">SIM</button>&nbsp;<button type="button" class="button big no"  data-dismiss="modal">NÃO</button></div></div></div></div></div>');
			$("#answer" + pos_answers + " .answers").html($("#answer" + pos_answers + " .answers .line").sort(function () {
				return Math.random() - 0.5;
			}));
		}
	}
}

function focusThisAnswer(element) {
	$("#answer" + currentquestion + " .button").removeClass("focus");
	$(element).addClass("focus");
	var msg = {
		sendto: 'tv',
		function: 'selectanswer',
		position: $(element).index("#answer" + currentquestion + " .button")
	}
	sendWebSocket(JSON.stringify(msg));
}

function answerThis() {
	if (selectedquestion != "" && selectedanswer != "") {
		stopTimer();
		duration.push(selectedquestion + " - " + ((new Date().getTime() - durationaswer) / 1000) + "s");
		$("#answer" + currentquestion + " .button").click(false).attr("onclick", "");
		$("#btn1_answer" + currentquestion).addClass("blink");
		setTimeout(function () {
			$("#btn1_answer" + currentquestion).removeClass("blink");
		}, 4500);
		if (selectedanswer != "A") {
			changeEmoji("sad", 6000, "", true);
		} else {
			correctquestions++;
			changePoints(Math.ceil(currentpoints + progress));
			changeEmoji("happy", 6000, "", true);
		}
		answers.push(selectedquestion + " - " + selectedanswer);
		if (selectedanswer != "A") {
			loseLife();
		}
		var msg = {
			sendto: 'tv',
			function: 'showanswer'
		}
		sendWebSocket(JSON.stringify(msg));
		saveRanking();
	}


	var timer = setTimeout(function () {
		if (canwater) {
			$('#modal_watering').modal({
				keyboard: false,
				backdrop: 'static'
			});
			$('#modal_watering').on('hide.bs.modal', function (e) {
				nextAnswer();
				canwater = false;
			});
			$('#modal_watering').on('hidden.bs.modal', function (e) {
				$('#bt_watering').attr("onclick","startWatering();");
				$('#bt_watering').html("Regar agora");
			});
		} else {
			if ((Math.abs(currentquestion % 2) == 1) && lives > 0) {
				showTip();
			} else {
				nextAnswer();
			}
		}
		window.clearTimeout(timer);
	}, 6000);
}

function nextAnswer() {
	currentquestion++;
	var next = currentquestion;
	if (parseInt(next) >= 3) {
		addAnswer();
		if ($("#answer" + (next - 3)).length) {
			$("#answer" + (next - 3)).remove();
		}
	}
	if (parseInt(next) < obj.length) {
		$('#list-answer a[href="#answer' + next + '"]').tab('show');
		newTimer(60, "timer-answer" + next);
		durationaswer = new Date().getTime();
		if (lives > 0) {
			var msg = {
				sendto: 'tv',
				function: 'newquestion',
				question: $('#answer' + next + ' .question:first').text(),
				answer1: $('#answer' + next + ' .option:eq(0)').text(),
				answer2: $('#answer' + next + ' .option:eq(1)').text(),
				answer3: $('#answer' + next + ' .option:eq(2)').text(),
				answer4: $('#answer' + next + ' .option:eq(3)').text(),
				correct: $('#btn1_answer' + next).index('#answer' + next + ' .option'),
				time: totaltime
			}
			sendWebSocket(JSON.stringify(msg));
		}
	} else {
		if (lives > 0) {
			gotoRanking();
		}
	}
	if (next == 1) {
		$('#page3').empty();
	}
}

function loseLife() {
	if (lives >= 1) {
		switch (lives) {
			case 1:
				$("#heart1").removeClass("on");
				break;
			case 2:
				$("#heart2").removeClass("on");
				break;
			case 3:
				$("#heart3").removeClass("on");
				break;
			default:
				$("#heart3").removeClass("on");
		}
		lives--;
	}
	if (lives == 0 && hash != "page5") {
		changeEmoji("sad", 2000, "Tente novamente...", true);
		setTimeout(function () {
			gotoRanking();
		}, 6000);
	}
}

function randomTips() {
	$("#modal_tips .modal-body").html($("#modal_tips .modal-body .tips").sort(function () {
		return Math.random() - 0.5;
	}));
	$('#modal_tips').on('hide.bs.modal', function (e) {
		nextAnswer();
	});
}
var lastTip = 0;

function showTip() {
	if ($("#modal_tips .modal-body .tips").length > 0) {
		$("#modal_tips .modal-body .tips:eq(0)").removeClass("hidden");
		$('#modal_tips').on('hidden.bs.modal', function (e) {
			$("#modal_tips .modal-body .tips:eq(0)").remove();
			lastTip++;
		});
		$('#modal_tips').modal('show');
	} else {
		nextAnswer();
	}
}
/** END: Quiz functions **/
var timerchecksensors;
$("#playbutton").click(function (event) {
	event.preventDefault();
	timestamp = new Date();
	startedat = new Date().getTime();

	$('#page1').animate({
		'opacity': 0
	}, 600);

	$(".intro-plant .ballon").removeClass("show");
	setTimeout(function () {
		animatetoHash("page2");
		$(".intro-plant .ballon").addClass('ballon2');
		timerchecksensors = setInterval(function () {
			checkSensors();
		}, 100);
		$(".intro-plant").animate({
			'left': '13%',
			'top': '85%',
			'height': '200px',
			'width': '120px'
		}, 800, function () {
			$('#page1').empty();
            changeEmoji("happy", 3000, "Jogue e ajude a regar o jardim!");
		});
	}, 1000);
});

$("#summarized-consent a:first").click(function (event) {
	$("#summarized-consent").addClass("hidden");
	$("#full-consent").removeClass("hidden");
});

$("#full-consent a:first").click(function (event) {
	$("#full-consent").addClass("hidden");
	$("#summarized-consent").removeClass("hidden");
});
var timercategories;
$("#startbutton").click(function (event) {
	event.preventDefault();
	$('#modal_consenting').on('hide.bs.modal', function (e) {
		changeEmoji("happy", 5000, "Escolham as categorias!");
		animatetoHash("page3");
		window.clearInterval(timerchecksensors);

		timercategories = setInterval(function () {
			if ($("#page3 .btn.button.big.category.active").length > 0) {
				$("#startquizbutton").removeClass("hide").addClass("show");
			} else {
				$("#startquizbutton").removeClass("show").addClass("hide");
			}
		}, 100);
	});
	$('#modal_consenting').modal({
		keyboard: false,
		backdrop: 'static'
	});
});
$("#startquizbutton").click(function (event) {
	event.preventDefault();

	if ($("#page3 .btn.active").length > 0) {
		clearInterval(timercategories);
		currentcategories = [];
		$("#page3 .btn.active").each(function () {
			currentcategories.push($(this).attr("catid") + "");
		});
		$.ajax({
			type: "GET",
			url: "bd.php",
			data: {
				"questions": true,
				categories: JSON.stringify(currentcategories)
			},
			success: function (response) {
				obj = JSON.parse(response);
				addAnswer();
				animatetoHash("page4");
				$('#page2').empty();
				changeEmoji("happy", 4000, "Agora é com vocês!");
				newTimer(60, "timer-answer0");
				durationaswer = new Date().getTime();
				var msg = {
					sendto: 'tv',
					function: 'newquestion',
					question: $('#answer' + currentquestion + ' .question:first').text(),
					answer1: $('#answer' + currentquestion + ' .option:eq(0)').text(),
					answer2: $('#answer' + currentquestion + ' .option:eq(1)').text(),
					answer3: $('#answer' + currentquestion + ' .option:eq(2)').text(),
					answer4: $('#answer' + currentquestion + ' .option:eq(3)').text(),
					correct: $('#btn1_answer' + currentquestion).index('#answer' + currentquestion + ' .option'),
					time: totaltime
				}
				sendWebSocket(JSON.stringify(msg));
			}
		});
	}
});

$("#cat_all").click(function (event) {
	event.preventDefault();
	$("#cat_all .other input").prop("checked");
	$("#page3 .answers .other .btn.button.big.category").addClass("active");
	currentcategories = [];
});

$(".answers .other .btn.button.big.category").click(function (event) {
	$("#cat_all .other input").removeAttr("checked");
	$("#cat_all").removeClass("active");
});

/** BEGIN: Ranking functions **/
addRanking(false);

function addRanking(ajax) {
	$(".ranking-list:first").html("");
	if (ajax) {
		$.ajax({
			type: "GET",
			url: "bd.php",
			data: "ranking",
			success: function (response) {
				objranking = JSON.parse(response);
				fillRanking();
			}
		});
		$.ajax({
			type: "GET",
			url: "bd.php",
			data: "weekranking",
			success: function (response) {
				objweekranking = JSON.parse(response);
				$("#weekscore").html(pad(objweekranking[0].total, 4));
			}
		});
	} else {
		fillRanking();
		$("#weekscore").html(pad(objweekranking[0].total, 4));
	}

	function fillRanking() {
		$(".ranking-list:first").empty();
		if (objranking.length > 0) {
			for (var i = 0; i < objranking.length; i++) {
				$(".ranking-list:first").append('<div class="line"><div class="ranking-name">' + objranking[i].name + '</div><div class="ranking-score">' + objranking[i].points + '</div></div>');
			}
		} else {
			$(".ranking-list:first").append('<div class="line"><div class="ranking-name" style="width:100%; text-align:center">Ninguém no ranking ainda...</div></div>');
		}
	}
}

var startedat;
var duration = [];
var durationtotal = 0;
var durationaswer = 0;

function checkInput() {
	if ($("#input-team").val().length > 0) {
		$('#modal_ranking').modal('hide');
	} else {
		$("#input-team").removeClass("shake");
		setTimeout(function () {
			$("#input-team").addClass("shake");
		}, 200);
	}
}

function gotoRanking() {
	stopTimer();
	animatetoHash("page5");
	durationtotal = (new Date().getTime() - startedat) / 1000;
	if (currentpoints > 0) {
		$('#modal_ranking').on('hide.bs.modal', function (e) {
			addRanking(true);
			changeEmoji("happy", 12000, "Obrigado por colaborar com o jardim!");
			setTimeout(function () {
				changeEmoji("happy", 17000, "O jogo será reiniciado em instantes");
				setTimeout(function () {
					window.location.reload(true);
				}, 20000);
			}, 12000);
		});
		$('#modal_ranking').modal({
			keyboard: false,
			backdrop: 'static'
		});
	}

	var msg = {
		sendto: 'tv',
		function: 'newgame'
	}
	sendWebSocket(JSON.stringify(msg));
}

function saveRanking() {
	var objsaveranking = {
		'userid': userid,
		'name': $("#input-team").val(),
		'points': currentpoints,
		'startedat': {
			'year': new Date(timestamp).getFullYear(),
			'month': new Date(timestamp).getMonth() + 1,
			'day': new Date(timestamp).getDate(),
			'hours': new Date(timestamp).getHours(),
			'minutes': new Date(timestamp).getMinutes(),
			'seconds': new Date(timestamp).getSeconds()
		},
		'duration': durationtotal,
		'questions': duration.toString(),
		'categories': currentcategories.toString(),
		'corrects': correctquestions,
		'consent': consent,
		'saveranking': true
	}
	$.ajax({
		type: "POST",
		url: "bd.php",
		data: objsaveranking,
		success: function (response) {
			addRanking(true);
		}
	});
}
/** END: Ranking functions **/
var countwatering = 3;
var timerwatering;

function startWatering() {
	$("#bt_watering").attr("onclick", "");
	timerwatering = setInterval(function () {
		if (countwatering > 0) {
			$("#bt_watering").html('Regando em ' + countwatering + '...');
			if (countwatering == 1) {
				$.ajax({
					type: "POST",
					url: "bd.php",
					data: "savewatering"
				});
			}
			countwatering--;
		} else {
			$("#wateringicon").attr("src", "img/watering.gif");
			$("#bt_watering").html('Regando...');
			var msgpoints = {
				sendto: 'tv',
				function: 'watering'
			}
			sendWebSocket(JSON.stringify(msgpoints));
			window.clearInterval(timerwatering);
			setTimeout(function () {
				$("#wateringicon").attr("src", "img/watering_off.gif");
				$("#bt_watering").html('Continuar jogando');
				$("#bt_watering").attr("onclick", "$('#modal_watering').modal('hide')");
				var msgpoints = {
					sendto: 'tv',
					function: 'points',
					totalpoints: calcpointsleft()
				}
				sendWebSocket(JSON.stringify(msgpoints));
			}, timewatering + 1000);
		}
	}, 1000);
}
/** BEGIN: WebSocket functions **/
var ws;
var connected = false;

function connect() {
	ws = new WebSocket('ws://www.egocriativo.com.br:8080');
	ws.onopen = function () {
		document.title = 'PlayGarden ●';
		connected = true;
	};

	ws.onclose = function () {
		document.title = 'PlayGarden ○';
		connected = false;
		setTimeout(function () {
			connect();
		}, 1000);
	};
	ws.onmessage = function (e) {
		var message = JSON.parse(e.data);
		if (message.hasOwnProperty("sendto") && message.sendto === "tablet") {
			console.info(message);
			if (message.function == "reload") {
				window.location.reload(true);
			}
		}
	};
	ws.onerror = function (e) {
		//alert('Erro: ' + e.data);
	};
}
connect();

function sendWebSocket(msg) {
	if (connected)
		ws.send(msg);
}
/** END: WebSocket functions **/
