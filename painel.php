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
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<title>PlayGarden</title>
		<style>
			.material-switch>input[type="checkbox"] {
				display: none;
			}

			.material-switch>label {
				cursor: pointer;
				height: 0px;
				position: relative;
				width: 40px;
				background-color: cornflowerblue;
			}

			.material-switch>label::before {
				background: rgb(0, 0, 0);
				box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
				border-radius: 8px;
				content: '';
				height: 16px;
				margin-top: -8px;
				position: absolute;
				opacity: 0.3;
				transition: all 0.4s ease-in-out;
				width: 40px;
			}

			.material-switch>label::after {
				background: rgb(255, 255, 255);
				border-radius: 16px;
				box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
				content: '';
				height: 24px;
				left: -4px;
				margin-top: -8px;
				position: absolute;
				top: -4px;
				transition: all 0.3s ease-in-out;
				width: 24px;
			}

			.material-switch>input[type="checkbox"]:checked+label::before {
				background: inherit;
				opacity: 0.5;
			}

			.material-switch>input[type="checkbox"]:checked+label::after {
				background: inherit;
				left: 20px;
			}
            .error{
                color:red;
                font-weight: bold;
            }
		</style>
	</head>

	<body>
		<div class="container mt-3">
			<h1>Painel de Controle</h1>
			<div class="row mt-2">
				<button type="button" class="btn btn-primary btn-lg mr-1" id="bt_reload_tablet">Reload Tablet</button>
				<button type="button" class="btn btn-primary btn-lg" id="bt_reload_tv">Reload TV</button>
			</div>
			<div class="row mt-3">
				<div class="material-switch">
					<input type="checkbox" id="sensor1">
					<label for="sensor1"></label>
				</div>
				<div class="col-2">
					Sensor 1
				</div>
				<div class="material-switch">
					<input type="checkbox" id="sensor2">
					<label for="sensor2"></label>
				</div>
				<div class="col-2">
					Sensor 2
				</div>
			</div>
			<div class="row mt-2">
				<button type="button" class="btn btn-primary btn-lg mr-1" id="bt_active_questions_TV">Activate questions TV</button>
				<button type="button" class="btn btn-primary btn-lg" id="bt_desactive_questions_TV">Desactivate questions TV</button>
			</div>

			<div class="row mt-2">
				<button type="button" class="btn btn-primary btn-lg mr-1" id="bt_active_watering">Activate watering</button>
			</div>
            <div class="row mt-2" id="statussensor">
				
			</div>
		</div>

		<!-- Optional JavaScript -->
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="js/jquery-3.3.1.min.js"></script>
		<script src="js/jquery.easing.min.js"></script>
		<script src="js/popper.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script>
			$("#bt_reload_tablet").click(function() {
				var msg = {
					sendto: 'tablet',
					function: 'reload'
				}
				sendWebSocket(JSON.stringify(msg));
			});

			$("#bt_reload_tv").click(function() {
				var msg = {
					sendto: 'tv',
					function: 'reload'
				}
				sendWebSocket(JSON.stringify(msg));
			});

			$(".material-switch input").change(function() {
				var sensors = {
					savesensorstatus: "",
					sensor1: document.getElementById('sensor1').checked,
					sensor2: document.getElementById('sensor2').checked,
					blocked: true
				}
				$.ajax({
					type: "POST",
					url: "bd.php",
					data: sensors
				});
			});

			$("#bt_active_watering").click(function() {
				var obj = {
					savewateringstatus: "",
					status: true
				}
				$.ajax({
					type: "POST",
					url: "bd.php",
					data: obj,
					success: function(response) {
						alert("Sent!");
					}
				});
			});
            
            setInterval(function(){
				$.ajax({
					type: "GET",
					url: "bd.php",
					data: "getlastwateringstatus",
					success: function(response) {
                        $("#statussensor").html("Last time water sensor checked the status: "+response);
					}
				});
                $.ajax({
					type: "GET",
					url: "bd.php",
					data: "geterrorstatus",
					success: function(response) {
                        if (response){
                            $("#statussensor").addClass("error"); 
                        } else {
                            $("#statussensor").removeClass("error"); 
                        }
					}
				});
            },1000);

			$("#bt_active_questions_TV").click(function() {
				var msg = {
					sendto: 'tv',
					function: 'activequestions',
					status: true
				}
				sendWebSocket(JSON.stringify(msg));
			});

			$("#bt_desactive_questions_TV").click(function() {
				var msg = {
					sendto: 'tv',
					function: 'desactivequestions',
					status: false
				}
				sendWebSocket(JSON.stringify(msg));
			});
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

				ws.onerror = function(e) {
					alert('Erro: ' + e.data);
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
