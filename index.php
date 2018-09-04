<?php
	require 'bd.php';
	$points = json_decode(getPoints());
	$ranking = getRanking();
	$max_score = getWeekRanking();
?>
	<!doctype html>
	<html lang="pt-br">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta http-equiv="expires" content="Sun, 01 Jan 2014 00:00:00 GMT"/>
        <meta http-equiv="pragma" content="no-cache" />
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/style.css">
		<title>PlayGarden</title>
        <style>
            .ipad{
                display: block;
                width: 94px;
                height: 100px;
                position: absolute;
                top: 36%;
                left: 50%;
                background: url(img/ipad.png) center center no-repeat;
                z-index: 1;
                background-size: contain;
                transform: translate(-50%,0);
            }
        </style>
		<script>
			var maxpoints = <?php echo $points[0]->total; ?>;
			var daypoints = <?php echo $points[1]->total; ?>;
			var objranking = JSON.parse('<?php echo $ranking; ?>');
			var objweekranking = JSON.parse('<?php echo $max_score; ?>');

		</script>
	</head>

	<body class="feeling-thirsty">
		<div class="modal" id="modal_playing" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<div class="modal-title">Tem alguém jogando?</div>
					</div>
					<div class="modal-body" id="modal-countdown">
					</div>
					<div class="modal-footer"><button type="button" class="button big yes" data-dismiss="modal" onclick="stillPlaying();">SIM</button></div>
				</div>
			</div>
		</div>
		<div class="modal" id="modal_watering" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<div class="modal-title">Vamos regar o jardim?</div>
					</div>
					<div class="modal-body" id="msg_watering">
						<div>O nosso regador está bem cheio!</div><div>Clique no botão abaixo para começar a regar o jardim...</div>
						<div>
							<img src="img/watering_off.gif" id="wateringicon">
						</div>
					</div>
					<div class="modal-footer"><button type="button" onclick="startWatering()" class="button big yes" style="width:auto" id="bt_watering">Regar agora</button></div>
				</div>
			</div>
		</div>
		<div class="intro-plant">
			<div class="emoticon cute" id="plantemoji"></div>
			<div class="ballon show" id="ballon">
				<div class="dialog" id="dialog">Estou com sede...</div>
			</div>
		</div>
		<div class="pages">
			<div class="all-pages">
				<div class="page" id="page1">
					<div class="text-center">
						<div class="cloud">
							<div class="t1">Faltam</div>
							<div class="points" id="points">0000</div>
							<div class="t2"><span class="l1">pontos </span><span class="l2">para regar</span><span class="l3">o jardim</span></div>
						</div>
						<div class="thermometer">
							<div class="front"></div>
							<div class="bg-white">
								<div class="water progress-10" id="water">
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
						<div class="intro-button"><button class="button big" id="playbutton">Jogar</button></div>
					</div>
				</div>
				<div class="page gradientbg hide" id="page2">
                    <div class="gardenimg"></div>
					<div class="instructions">
						<div class="title">INSTRUÇÕES</div>
						<div class="desc" id="inst_desc">Pegue o tablet &nbsp;e sente no banco.<BR/>O quiz começará quando dois jogadores sentados forem detectados.</div>
						<div class="bench">
                            <div class="ipad"></div>
							<div class="player1" id="player1"></div>
							<div class="player2" id="player2"></div>
						</div>
						<div><button class="button big hide" id="startbutton">iniciar</button></div>
					</div>

					<div class="modal" id="modal_consenting" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<div class="modal-title">Desejam colaborar com um estudo envolvendo este jogo?</div>
								</div>
								<div class="modal-body">
									<div class="summarized-consent" id="summarized-consent">
										<div>- É anônimo e gratuito;</div>
										<div>- Nenhum cadastro é necessário;</div>
										<div>- Não envolve tarefas extras, stress ou riscos;</div>
										<div>- Você precisa ter 18 anos ou mais;</div>
										<div>- Você pode optar por sair a qualquer momento;</div>
										<div>Leia o <a href="#" class="consent-link"><span>termo de consentimento aqui</span></a></div>
									</div>
									<div class="full-consent hidden" id="full-consent">
										<div>Leia o <a href="#" class="consent-link read"><span>termo de consentimento aqui</span></a></div>
										<div class="full-text scrollbar">
											<p>O projeto PlayGarden visa investigar como uma instalação interativa tecnológica pode estender o engajamento com e dentro de um determinado espaço por um certo tempo. O objetivo deste projeto é reunir informações que possam ajudar a entender como as tecnologias podem transformar um espaço em um período de tempo em um ambiente comunal e mais socialmente ativo. Para isso, o PlayGarden convida as pessoas a contribuírem colaborativamente com a manutenção de um jardim por meio de um jogo de perguntas e respostas.</p>
											<p>Este estudo não envolve riscos para os indivíduos que participam dela. As identidades de todas as pessoas que participam permanecerão anônimas e serão mantidas em sigilo. Os pesquisadores podem tirar fotos e gravar vídeos enquanto você utiliza a instalação. Essas imagens e vídeos coletados serão armazenadas num computador criptografado. No entanto, você terá o direito de pedir para excluí-las.</p>
											<p>Este projeto considera e garante a privacidade dos dados coletados de acordo com as leis e estatutos federais, estaduais e locais, incluindo a Lei 12.965/2014 (Marco Civil da internet). Os dados de interação com a instalação serão usadas apenas para fins de pesquisa e os pesquisadores poderão compartilhar e divulgar as informações desde que anonimizadas em relatórios, artigos científicos, documentos de pesquisa, documentos de teses, apresentações e em sites relacionados a este projeto.</p>
											<p>Pretendemos que sua participação neste projeto seja agradável e livre de estresse. Sua participação é totalmente voluntária e você pode se recusar a participar ou retirar-se do estudo a qualquer momento.</p>
											<p>Somos muito gratos pela sua participação.</p>
											<p>Principais investigadores:</p>
											<p>Profa. Dra. Junia Anacleto<br/> junia@dc.ufscar.br</p>
											<p>MSc. Vinicius Ferreira (Doutorando)<br/> vinicius.ferreira@ufscar.br</p>
											<p>MSc. Andre Bueno (Doutorando)<br/> andre.bueno@ufscar.br</p>
											<p>Marcelo Huffenbaecher (Graduando)<br/> marceloh.huff@gmail.com</p>
											<p>Depto. De Computação, UFSCar - Brasil</p>
										</div>
									</div>
								</div>
								<div class="modal-footer"><button type="button" class="button big yes" data-dismiss="modal" onclick="consent=true">SIM</button>&nbsp;<button type="button" class="button big no" data-dismiss="modal">Não</button></div>
							</div>
						</div>
					</div>
				</div>
				<div class="page hide" id="page3">
					<div class="top">
						<div class="question"><div class="question-inner">Teste seus conhecimentos:</div>Selecione uma ou mais categorias</div>
					</div>
					<div class="answers">
						<div><label class="btn button big category" id="cat_all">Todas as categorias</label></div>
						<div class="line other">
							<div class="column btn-group-toggle" data-toggle="buttons"><label class="btn button big category" catid="1">
    <input type="checkbox" autocomplete="off" name="cat1" id="cat_1">Cultura Nerd</label></div>
							<div class="column btn-group-toggle" data-toggle="buttons"><label class="btn button big category" catid="2">
    <input type="checkbox" autocomplete="off" name="cat2" id="cat_2">Atualidades</label></div>
						</div>
						<div class="line other">
							<div class="column btn-group-toggle" data-toggle="buttons"><label class="btn button big category" catid="3">
    <input type="checkbox" autocomplete="off" name="cat3" id="cat_3">São Carlos</label></div>
							<div class="column btn-group-toggle" data-toggle="buttons"><label class="btn button big category" catid="4">
    <input type="checkbox" autocomplete="off" name="cat4" id="cat_4">UFSCar</label></div>
						</div>
						<div class="line other">
							<div class="column btn-group-toggle" data-toggle="buttons"><label class="btn button big category" catid="5">
    <input type="checkbox" autocomplete="off" name="cat5" id="cat_5">Conhecimento gerais</label></div>
							<div class="column btn-group-toggle" data-toggle="buttons"><label class="btn button big category" catid="6">
    <input type="checkbox" autocomplete="off" name="cat6" id="cat_6">Departamento de computação</label></div>
						</div>
						<div class="line text-center"><br>
							<button class="button big hide" id="startquizbutton">Começar</button>
						</div>
					</div>
				</div>
				<div class="page hide" id="page4">
					<div class="modal" id="modal_tips" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<div class="modal-title">Você sabia?</div>
								</div>
								<div class="modal-body">
									<div class="tips hidden" id="tip1">
										<div>
											O contato com a natureza pode evocar um sentimento de liberdade nas pessoas, melhorando a qualidade de vida e diminuindo o sentimento de ansiedade e nervosismo.
										</div>
										<div class="reference">Masashi Soga, Kevin J. Gaston, and Yuichi Yamaura. (2017). Gardening is beneficial for health: A meta-analysis. Preventive medicine reports 5: 92-99.</div>
									</div>
									<div class="tips hidden" id="tip2">
										<div>
											Um recente estudo mostrou que jardinagem pode ser mais relaxante do que várias outras formas de lazer, impactando positivamente também no humor das pessoas.</div>
										<div class="reference">Chantal Koolhaas, Klodian Dhana, Rajna Golubic, Josje Schoufour, Albert Hofman, Frank van Rooij, and Oscar Franco. (2016). Physical activity types and coronary heart disease risk in middle-aged and elderly persons: the Rotterdam Study. American journal of epidemiology, 183(8), 729-738.</div>
									</div>
									<div class="tips hidden" id="tip3">
										<div>
											Cultivar plantas tem se mostrado um grande aliado para se tratar algumas doenças, tais como depressão, mau humor persistente e transtorno bipolar.</div>
										<div class="reference">Marianne Gonzalez, Terry Hartig, Grete Patil, Martinsen, Egil Martinsen, Marit Kirkevold. (2011). A prospective study of existential issues in therapeutic horticulture for clinical depression. Issues in mental health nursing, 32(1), 73-81.</div>
									</div>
									<div class="tips hidden" id="tip4">
										<div>Participar de um jardim comunitário é um ótimo meio para estabelecer vínculos mais fortes com a sua comunidade.</div>
										<div class="reference">Carrie Draper, and Darcy Freedman. "Review and analysis of the benefits, purposes, and motivations associated with community gardening in the United States." Journal of Community Practice 18.4 (2010): 458-492.</div>
									</div>
									<div class="tips hidden" id="tip5">
										<div>Espaços verdes trazem diversos benefícios a saúde física e mental das pessoas, aumentando a performance cognitivas e o sentimento de bem-estar.</div>
										<div class="reference">Lucy Keniger, Kevin Gaston, Katherine Irvine, and Richard Fuller. (2013). What are the benefits of interacting with nature?. International journal of environmental research and public health, 10(3), 913-935.</div>
									</div>
									<div class="tips hidden" id="tip6">
										<div>Pesquisa recente mostrou que jardins comunitários podem melhorar o desempenho de estudantes, quando os mesmos participam da sua manutenção.</div>
										<div class="reference">Tomomi Murakami. (2015). Educators’ Perspectives Associated with School Garden Programs in Clark County, Nevada: Practices, Resources, Benefits and Barriers.</div>
									</div>
								</div>
								<div class="modal-footer"><button type="button" class="button big yes" data-dismiss="modal" style="    width: auto;">Fechar</button></div>
							</div>
						</div>
					</div>
					<div class="score">
						<div class="lives">
							<div class="heart on" id="heart1"></div>
							<div class="heart on" id="heart2"></div>
							<div class="heart on" id="heart3"></div>
						</div>
						<div class="t1">Sua contribuição</div>
						<div class="yourpoints" id="yourpoints">0000</div>
						<div class="t1">pontos</div>
					</div>
					<div class="list-group" style="display: none" id="list-answer" role="tablist">

					</div>
					<div class="tab-content" id="tab-content">

					</div>
				</div>
				<div class="page hide" id="page5">
					<div class="modal" id="modal_ranking" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<div class="modal-title">Escolha o nome da dupla para o ranking diário</div>
								</div>
								<div class="modal-body">
									<input class="input-team" id="input-team" placeholder="Digite aqui..." autocomplete="off" type="text" value="">
								</div>
								<div class="modal-footer"><button type="button" class="button big yes" id="btSaveRanking" onclick="checkInput()">OK</button>&nbsp;<button type="button" class="button big no" data-dismiss="modal">NÃO</button></div>
							</div>
						</div>
					</div>
					<div class="top">
						<div class="question">Ranking do dia</div>
					</div>
					<div class="ranking-list">

					</div>
					<div class="scoretop">
						<div class="question">Recorde da semana</div>
					</div>
					<div class="ranking-list">
						<div class="weekscore" id="weekscore">0000</div>
					</div>
					<div class="text-center" style="margin-top:35px"><button onclick="window.location.reload(true)" class="button big" style="font-size: 37px;">Jogar novamente</button></div>
				</div>
			</div>
		</div>
		<script src="js/jquery-3.3.1.min.js"></script>
		<script src="js/jquery.easing.min.js"></script>
		<script src="js/popper.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/script.js"></script>
	</body>

	</html>
