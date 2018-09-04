<?php
	require 'bd.php';
	$points = json_decode(getPoints());

?>
	<!doctype html>
	<html lang="pt-br">

	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="expires" content="Sun, 01 Jan 2014 00:00:00 GMT"/>
        <meta http-equiv="pragma" content="no-cache" />
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/tv.css">

		<title>PlayGarden</title>
	</head>

	<body>
		<canvas></canvas>
		<div class="text-center">
			<div class="intro-msg fade" id="intro-msg">

			</div>
			<div class="questions fade" id="questions">
				<div class="desc" id="desc_question"></div>
				<div class="question" id="question"></div>
				<div class="answers" id="answers">
					<div class="answer" id="answer1"></div>
					<div class="answer" id="answer2"></div>
					<div class="answer" id="answer3"></div>
					<div class="answer" id="answer4"></div>
				</div>
			</div>
			<div class="cloud">
				<div class="t1">Faltam</div>
				<div class="points" id="points">
					<?php echo $points[0]->total - $points[1]->total; ?>
				</div>
				<div class="t2"><span class="l1">pontos </span><span class="l2">para regar</span><span class="l3">o jardim</span></div>
			</div>
			<div class="thermometer">
				<div class="front"></div>
				<div class="bg-white">
					<div class="water" id="water">
						<div class="bubble x1"></div>
						<div class="bubble x2"></div>
						<div class="bubble x3"></div>
						<div class="bubble x4"></div>
						<div class="bubble x5"></div>
						<div class="bubble x6"></div>
						<div class="bubble x7"></div>
						<div class="bubble x8"></div>
						<div class="bubble x9"></div>
						<div class="bubble x10"></div>
					</div>
				</div>
			</div>
			<div class="tv-plant">
				<div class="emoticon" id="plantemoji"></div>
				<div class="ballon hide" id="ballon">
					<div class="dialog" id="dialog"></div>
				</div>
			</div>
		</div>
		<div class="bg dry" id="bg">
		</div>
        <div class="hidden">
            <audio id="audio_selectanswer">
              <source src="sounds/penclick_selectanswer.mp3" type="audio/mpeg">
            </audio>
            <audio id="audio_waterup">
              <source src="sounds/pourdrink_waterup.mp3" type="audio/mpeg">
            </audio>
            <audio id="audio_raining">
              <source src="sounds/waterfall_raining.mp3" type="audio/mpeg">
            </audio>
            <audio id="audio_rightanswer1">
              <source src="sounds/bubbles_rightanswer1.mp3" type="audio/mpeg">
            </audio>
            <audio id="audio_rightanswer2">
              <source src="sounds/bubbles_rightanswer2.mp3" type="audio/mpeg">
            </audio>
        </div>
		<!-- Optional JavaScript -->
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="js/jquery-3.3.1.min.js"></script>
		<script src="js/jquery.easing.min.js"></script>
		<script src="js/popper.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script>
			var maxpoints = <?php echo $points[0]->total; ?>;
			var daypoints = <?php echo $points[1]->total; ?>;
			var currentpoints = <?php echo $points[0]->total; ?>;
			var canshowquestions = false;
			var lives = 3;
			var showingquestion = false;
            var playsounds = true;

			init();

			function init() {
				countPointsCloud();
				changeIntroMsg("Jogue e ajude a regar o jardim");
			}

			function pad(num, size) {
				var s = num + "";
				while (s.length < size) s = "0" + s;
				return s;
			}
            function countPointsCloud() {
                var calcpoints = 0;
                var daypointsmod = parseInt(daypoints % maxpoints);
                var calcpercentage = (daypointsmod / maxpoints) * 100;
                var calcpointsend = maxpoints - daypointsmod;
                changeWaterLevel(calcpercentage);
                document.getElementById("audio_waterup").pause();
                playSound("waterup");
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
                $(".page").removeClass("hide");
            }

			/** BEGIN: Functions for manipulating playing elements **/
			function changePoints(points) {
				var calcpoints = parseInt(currentpoints);
				currentpoints = parseInt(points % maxpoints);
				var calcpointsend = maxpoints - currentpoints;
				var calcpercentage = (calcpointsend / maxpoints) * 100;
				changeWaterLevel(calcpercentage);
				stillPlaying();
				var timer = setInterval(function() {
					if (calcpoints > points + 50) {
						calcpoints = Math.floor(calcpoints - calcpointsend * 0.021);
					} else {
						if (calcpoints > points && calcpoints > 0) {
							calcpoints--;
						} else {
							window.clearInterval(timer);
						}
					}
					$("#points").html(pad(calcpoints, 4));
				}, 50);
			}

			function startWatering() {
				$("#intro-msg").removeClass("show").addClass("hide");
				$("#questions").removeClass("show").addClass("hide");
				$("#bg").addClass("rain").removeClass("dry");
                playSound("raining");
				var calcpoints = 0;
                var timerwater = setInterval(function() {
					if (calcpoints < maxpoints) {
						calcpoints = calcpoints + (maxpoints / 20);
						changePoints(calcpoints);
					}
				}, 750);
				
				setTimeout(function() {
					$("#bg").addClass("ok").removeClass("rain");
                    $("#audio_raining").animate({volume: 0}, 1000);
                    window.clearInterval(timerwater);
					/*
					$.ajax({
						type: "GET",
						url: "bd.php",
						data: "getpoints",
						success: function(response) {
							var getpoints = JSON.parse(response);
							changePoints(getpoints[0].total - (getpoints[1].total % getpoints[0].total));
						}
					});*/
				}, 1500);
			}

			function changeIntroMsg(msg) {
				if (!showingquestion) {
					$("#intro-msg").removeClass("show").addClass("hide");
					setTimeout(function() {
						$("#intro-msg").html(msg);
						$("#intro-msg").removeClass("hide").addClass("show");
					}, 500);
				}
			}

			var timer1, timer2, timer3;

			function changeQuestion(question, answer1, answer2, answer3, answer4, correct, time) {
				window.clearTimeout(timer1);
				window.clearTimeout(timer2);
				window.clearTimeout(timer3);
				showingquestion = true;
				$("#intro-msg").removeClass("show").addClass("hide");
				timer1 = setTimeout(function() {
					$("#questions").removeClass("show").addClass("hide");
					timer2 = setTimeout(function() {
						$("#desc_question").html("Ajude-nos a responder:");
						$("#question").html(question);
						$(".answer").removeClass("active").removeClass("correct");
						$("#answer1").html(answer1);
						$("#answer2").html(answer2);
						$("#answer3").html(answer3);
						$("#answer4").html(answer4);
						$("#answer" + (correct + 1)).addClass("correct");
						$("#questions").removeClass("hide").addClass("show");
						timer3 = setTimeout(function() {
							$("#questions").removeClass("show").addClass("hide");
						}, time * 1000);
					}, 500);
				}, 500);
			}

			function selectAnswer(position) {
				$(".answer").removeClass("active");
				$("#answer" + (position + 1)).addClass("active");
                playSound("select");
			}

			function showAnswer() {
				window.clearTimeout(timer);
				$(".answer.correct").removeClass("blink").addClass("blink");
                if ($(".answer.correct.active").length>0){
                    playSound("rightanswer");
                    }
				var timer = setTimeout(function() {
					$(".answer.correct").removeClass("blink");
					window.clearTimeout(timer);
				}, 3000);
			}
            
            function playSound(sound) {
                if (playsounds){
                    if (sound == "select"){
                        var objaudio = "audio_selectanswer";
                         if (document.getElementById(objaudio).paused) {
                            document.getElementById(objaudio).pause();
                            document.getElementById(objaudio).currentTime = 0;
                            document.getElementById(objaudio).play();
                        }
                    }
                    if (sound == "waterup"){
                        var objaudio = "audio_waterup";
                        $("#audio_waterup").prop("volume",1);
                        if (document.getElementById(objaudio).paused) {
                            document.getElementById(objaudio).pause();
                            document.getElementById(objaudio).currentTime = 0;
                            document.getElementById(objaudio).play();
                        }
                        
                    }
                    if (sound == "raining"){
                        var objaudio = "audio_raining";
                        $("#audio_raining").prop("volume",1);
                        if (document.getElementById(objaudio).paused) {
                            document.getElementById(objaudio).pause();
                            document.getElementById(objaudio).currentTime = 0;
                            document.getElementById(objaudio).play();
                        }
                        
                    }
                    if (sound == "rightanswer"){
                        var objaudio = ["audio_rightanswer1","audio_rightanswer2"];
                        objaudio = objaudio[Math.floor((Math.random() * objaudio.length))];
                        if (document.getElementById(objaudio).paused) {
                            document.getElementById(objaudio).pause();
                            document.getElementById(objaudio).currentTime = 0;
                            document.getElementById(objaudio).play();
                        }
                        
                    }
                }
                
            }

			var timerEmoji;

			function changePlantemoji(feeling, time, msg, emoji) {
				var coolmsg = ["Vocês são demais!", "Vocês são o máximo!", "Uhulll", "Ebaaa!", "Uauuu...", "Que demais!", "Lá vamos nós!", "Wow", "Yuupi!!", "Super!", "Curti!"];
				var sadmsg = ["Poxa...", "Não foi dessa vez!", "Foi por pouco...", "Não desitam...", "Na próxima vocês acertam!", "Na próxima vocês conseguem!", "Foi quase.."];
				var number;
				var bgclass;
				$("#plantemoji").removeClass("cool").removeClass("cute").removeClass("good").removeClass("haha").removeClass("haha2").removeClass("happy").removeClass("love").removeClass("super").removeClass("tongue").removeClass("wow");
				$("#plantemoji").removeClass("cry").removeClass("humpf").removeClass("meh").removeClass("notcool").removeClass("sad");
				$("#ballon").removeClass("show");
				if (feeling == "happy") {
					if (typeof msg == 'string') {
						$("#dialog").html(msg);
					} else {
						$("#dialog").html(coolmsg[Math.floor((Math.random() * coolmsg.length))]);
					}
					if (typeof emoji == 'string') {
						bgclass = emoji;
					} else {
						var coolarray = ["cool", "cute", "good", "haha", "haha2", "happy", "love", "super", "tongue", "wow"];
						number = Math.floor(Math.random() * coolarray.length);
						bgclass = coolarray[number];
					}

				} else {
					if (feeling == "sad") {

						if (typeof msg == 'string') {
							$("#dialog").html(msg);
						} else {
							$("#dialog").html(sadmsg[Math.floor((Math.random() * sadmsg.length))]);
						}
						if (typeof emoji == 'string') {
							bgclass = emoji;
						} else {
							var sadarray = ["cry", "humpf", "meh", "notcool", "sad"];
							number = Math.floor(Math.random() * sadarray.length);
							bgclass = sadarray[number];
						}
					}
				}
				window.clearTimeout(timerEmoji);
				$("#ballon").addClass("show");
				$("#plantemoji").addClass(bgclass);
				timerEmoji = setTimeout(function() {
					$("#plantemoji").removeClass(bgclass);
					$("#ballon").removeClass("show");
					window.clearTimeout(timerEmoji);
				}, time);
			}

			function changeWaterLevel(percentage) {
				var startimer;
				window.clearInterval(startimer);
				var old_percentage = $("#water").height() / $("#water").parent().height() * 100;
				var timefactor = Math.abs(old_percentage - percentage) * 0.06;
				$("#water").addClass("blinkwater");
				$("#water").animate({
					height: percentage + "%"
				}, 1000 * timefactor, "easeInQuad", function() {
					setTimeout(function() {
						window.clearInterval(startimer);
                        $("#audio_waterup").animate({volume: 0}, 1000);
					}, 300);
					setTimeout(function() {
						$("#water").removeClass("blinkwater");
					}, 5000);
				});

				startimer = setInterval(function() {
					for (var i = 0; i < 50; i++) {
						var water_left = $("#water").offset().left + ((Math.random() + 0) * 100);
						var water_top = $("#water").offset().top - 10 + ((Math.random() + 0) * 20);
						stars.push(new Star(water_left, water_top, true));
					}
				}, 100);
			}

			var sensor1 = false;
			var sensor2 = false;

			function changeSensors(newsensor1, newsensor2) {
				if (sensor1 != newsensor1 || sensor2 != newsensor2) {
					sensor1 = newsensor1;
					sensor2 = newsensor2;

					if (newsensor1 && newsensor2) {
						var msg = ["Eba! Vamos começar?", "Partiu jogar?", "Vamos começar?", "Legal! Agora é só começar!", "A diversão vai começar!"];
						$("#dialog").html(msg[Math.floor(Math.random() * msg.length)]);
						$("#startbutton").removeClass("hide").addClass("show");
					} else {
						var msg = ["Convide alguém para jogar!", "Chame alguém para jogar com você!"];
						$("#dialog").html(msg[Math.floor(Math.random() * msg.length)]);
					}
					stillPlaying();
				}
			}
			/** END: Functions for manipulating playing elements **/

			var timer1, timer2;
			var stillplaying = false;
			var countdownplaying;

			function stillPlaying() {
				window.clearInterval(timer1);
				window.clearInterval(timer2);
				stillplaying = true;
				countdownplaying = 150;
				startCountdown();

				function startCountdown() {
					timer1 = setInterval(function() {
						if (countdownplaying > 0) {
							countdownplaying--;
						} else {
							stillplaying = false;
							if (Math.floor(Math.random() * 2) == 0) {
								var msg = ["Vamos jogar?", "Vem jogar!", "Vem jogar... vem!", "Vamos jogar... vai!", "Quer jogar!? Só vem!"];
								changePlantemoji("happy", 10000, msg[Math.floor(Math.random() * msg.length)]);

							} else {
								var msg = ["Estou com sede...", "Nunca te pedi nada!", "Todos jogaram menos você!", "Só falta você jogar!", "Vai me deixar com sede?", "Estou esperando você!"];
								changePlantemoji("sad", 10000, msg[Math.floor(Math.random() * msg.length)]);
							}
							countdownplaying = 25;
						}
					}, 1000);
				}
			}

			/** BEGIN: Stars canvas functions **/
			var canvas = document.querySelector("canvas");
			var ctx = canvas.getContext("2d");
			var ww, wh, clipSize;
			var stars = [];

			function Star(x, y, kaboom) {
				this.scale = (Math.random() + 0.2) * 20;
				this.scaleSpeed = Math.random() / 5 + 0.01;
				this.x = x || Math.random() * ww;
				this.y = y || Math.random() * wh;
				this.vx = (Math.random() - 0.5) * (kaboom ? 10 : 4);
				if (kaboom) {
					this.vy = (Math.random() - 0.5) * 10;
				} else {
					this.vy = Math.random() * 3;
				}
				this.opacity = 1;
				this.opacitySpeed = Math.random() / 10;
				this.rotate = Math.random() * Math.PI;
				this.rotateSpeed = (Math.random() - 0.5) * 0.1;
				this.color = "hsl(205, 60%,60%)";
				this.out = [];
				this.in = [];
				for (var i = 0; i < 5; i++) {
					var x = Math.cos(i / 5 * Math.PI * 2) * this.scale;
					var y = Math.sin(i / 5 * Math.PI * 2) * this.scale;
					this.out.push([x, y]);

					var x = Math.cos((i + 0.5) / 5 * Math.PI * 2) * this.scale * 0.5;
					var y = Math.sin((i + 0.5) / 5 * Math.PI * 2) * this.scale * 0.5;
					this.in.push([x, y]);
				}

				this.image = document.createElement('canvas');
				this.image.width = this.scale * 4;
				this.image.height = this.scale * 4;
				this.ctx = this.image.getContext('2d');
				this.ctx.translate(this.scale * 2, this.scale * 2);
				this.ctx.beginPath();
				this.ctx.moveTo(this.in[0][0], this.in[0][1]);
				for (var i = 0; i < 5; i++) {
					this.ctx.bezierCurveTo(this.out[i][0], this.out[i][1], this.out[i][0], this.out[i][1], this.in[i][0], this.in[i][1]);
				}
				this.ctx.bezierCurveTo(this.out[0][0], this.out[0][1], this.out[0][0], this.out[0][1], this.in[0][0], this.in[0][1]);
				this.ctx.closePath();
				this.ctx.fillStyle = this.color;
				this.ctx.shadowColor = this.color;
				this.ctx.shadowBlur = 0;
				this.ctx.fill();

			}

			Star.prototype.draw = function(i) {
				this.rotate += this.rotateSpeed;
				this.scale = Math.max(0, this.scale - this.scaleSpeed);
				this.vy = Math.min(10, this.vy + 0.005);
				this.x += this.vx;
				this.y += this.vy;
				this.opacity -= this.opacitySpeed;
				ctx.save();
				ctx.globalAlpha = Math.max(this.opacity, 0);
				ctx.translate(this.x, this.y);
				ctx.scale(this.scale / 20, this.scale / 20);
				ctx.rotate(this.rotate);
				ctx.drawImage(this.image, -this.scale, -this.scale)
				ctx.restore();
			}

			window.addEventListener("resize", onResize);

			function onResize() {
				ww = canvas.width = window.innerWidth;
				wh = canvas.height = window.innerHeight;
				clipSize = Math.min(ww, wh);
				ctx.shadowBlur = 0;
				if (navigator.userAgent.toLowerCase().indexOf('firefox') === -1) {
					ctx.globalCompositeOperation = "screen";
				}
				ctx.rect(0, 0, ww, wh);
				ctx.clip();
			}

			function render(a) {
				requestAnimationFrame(render);
				ctx.clearRect(0, 0, ww, wh);
				ctx.beginPath();
				ctx.rect(0, 0, ww, wh);
				ctx.fillStyle = 'rgba(255, 0, 0, 0)';
				ctx.fill();
				for (var i = 0, j = stars.length; i < j; i++) {
					var star = stars[i];
					star.draw(i);
					if (
						star.x - star.scale > ww ||
						star.x + star.scale < 0 ||
						star.y - star.scale > wh ||
						star.y + star.scale < 0 ||
						star.opacity <= 0
					) {
						stars.splice(i, 1);
						i--;
						j--;
					}
				}

			}
			onResize();
			requestAnimationFrame(render);
			/** END: Stars canvas functions **/

			/** BEGIN: WebSocket functions **/
			var ws;
			var connected = false;

			function connect() {
				ws = new WebSocket('ws://www.egocriativo.com.br:8080');
				ws.onopen = function() {
					document.title = 'PlayGarden ●';
					connected = true;
				};

				ws.onclose = function() {
					document.title = 'PlayGarden ○';
					connected = false;
					setTimeout(function() {
						connect();
					}, 1000);
				};
				ws.onmessage = function(e) {
					var message = JSON.parse(e.data);
					if (message.hasOwnProperty("sendto") && message.sendto === "tv") {
						console.info(message);
						if (message.function == "plantemoji") {
							changePlantemoji(message.feeling, message.time, message.message, message.emoji);
						} else {
							if (message.function == "newgame") {
								$("#questions").removeClass("show").addClass("hide");
								setTimeout(function() {
									$("#intro-msg").removeClass("hide").addClass("show");
								}, 500);
							}
							if (message.function == "points") {
								changePoints(message.totalpoints);
							}
							if (message.function == "stillplaying") {
								stillPlaying();
							}
							if (canshowquestions) {
								if (message.function == "newquestion") {
									changeQuestion(message.question, message.answer1, message.answer2, message.answer3, message.answer4, message.correct, message.time);
								}
								if (message.function == "showanswer") {
									showAnswer();
								}
								if (message.function == "selectanswer") {
									selectAnswer(message.position);
								}
							}

							if (message.function == "watering") {
								startWatering();
							}
							if (message.function == "reload") {
								window.location.reload(true);
							}
							if (message.function == "intromsg") {
								changeIntroMsg(message.message);
							}
							
							if (message.function == "activequestions") {
								canshowquestions = true;
							}
							
							if (message.function == "desactivequestions") {
								canshowquestions = false;
							}
						}
					}
				};
				ws.onerror = function(e) {
					//alert('Erro: ' + e.data);
				};
			}
			connect();

			function sendWebSocket(msg) {
				ws.send(msg);
			}
			/** END: WebSocket functions **/

		</script>
	</body>

	</html>
