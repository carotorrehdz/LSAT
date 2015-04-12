<?php

require 'core/init.php';

$user = new User();
$user->checkIsValidUser('student');

//Id estudiante
$studentId = $user->data()->id;

//Id competencia
$competenceId = Input::get("c");

//Id del grupo
$groupId = Input::get("g");

$c = new Competence();
$canAnswer = $c->validateStudentCanAnswer($studentId, $groupId, $competenceId);

if(!$canAnswer){
	Redirect::to('sdashboard.php');
}

//Ver el estado de esta competencia para este alumno
//   -No comenzado, -medio  terminado

//No ha comenzado
$competenceStarted = $c->isCompetenceStarted($studentId, $groupId, $competenceId);
if (!$competenceStarted) {
	//Llenar todas las tablas para comenzar esa competencia
	$c->startCompetence($studentId, $groupId, $competenceId);
}else{
	//Competencia esta empezada

	//Competencia fue terminada
	//Redirigir a una nueva pagina que muestre
	//un mensaje de que la competencia fue termianda y tal vez mostrar su puntaje, dar link para navegar de regreso al dashboard
}

$competence = $c->getCompetence($competenceId);
$q = new Question();

$nextQuestionForStudentResponse = $q->getNextQuestion($studentId, $groupId, $competenceId);

$competenceId = $nextQuestionForStudentResponse['competenceId'];
$webId = $nextQuestionForStudentResponse['webId'];
$sp = $nextQuestionForStudentResponse['studentProgressId'];
$nextQuestionForStudent = $nextQuestionForStudentResponse['nextQuestion'];

var_dump($nextQuestionForStudentResponse);

$questionForStudentId = $nextQuestionForStudent->id;
$questionId = $nextQuestionForStudent->questionId;

$nextQuestionId = $nextQuestionForStudent->questionId;
//var_dump("Id de la siguiente pregunta");
//var_dump($nextQuestionId);

$nextQuestion = $q->getQuestion($nextQuestionId);
$nextQuestion = $nextQuestion[0];
//var_dump("Info Pregunta");
//var_dump($nextQuestion);

$a = new Answer();
$answersIds = array($nextQuestion->optionA, $nextQuestion->optionB, $nextQuestion->optionC, $nextQuestion->optionD);
shuffle($answersIds);
$answersInfo = array();
foreach ($answersIds as $answerId){
	$answersText = $a->getAnswer($answerId);
	array_push($answersInfo, $answersText);
}
//var_dump("Info Respuesta");
//var_dump($answersInfo);

?>

<!doctype html>
<html class="no-js" lang="en">
<head>
	<title>LSAT | Contestar competencia</title>
	<?php include 'includes/templates/headTags.php' ?>
</head>

<body>

	<?php include 'includes/templates/header.php' ?>

	<section class="scroll-container" role="main">

		<div class="row">
			<?php include 'includes/templates/studentSidebar.php' ?>
			<div class="large-9 medium-8 columns">
				<br/>
				<h3><?php echo "$competence->name"?> </h3>
				<h4 class="subheader">Contestar competencia</h4>
				<hr>

				<div id="questionDetail" class="panel">
					<!-- Default panel contents -->
					<h4 id="text">
						<?php
						echo "$nextQuestion->text"
						?>
					</h4>

					<ul style='list-style:none'>
						<?php
						foreach($answersInfo as $a){
							$text = $a[0]->text;
							$answerId = $a[0]->id;
							echo "<li><input id=$answerId type='radio' name='answer'> <span> $text </span> </input></li>";
						}
						?>
					</ul>

				</div>
				<a href="#" onclick="answerQuestion()" class="button round small right">Siguiente</a>


			</div>
		</div>
	</section>


	<?php include 'includes/templates/footer.php' ?>

	<script src="js/vendor/jquery.js"></script>
	<script src="js/foundation.min.js"></script>
	<script>
		$(document).foundation();


		function answerQuestion(){
			var c = <?php
			if (isset($competenceId)) {
				echo "$competenceId";
			}else{
				echo "-1";
			}
			?>;
			var qfs = <?php
			if (isset($questionForStudentId)) {
				echo "$questionForStudentId";
			}else{
				echo "-1";
			}
			?>;
			var w = <?php
			if (isset($webId)) {
				echo "$webId";
			}else{
				echo "-1";
			}
			?>;

			var sp = <?php
			if (isset($sp)) {
				echo "$sp";
			}else{
				echo "-1";
			}
			?>;

			var a = $("input[name=answer]:checked").attr("id");
			console.log(a);

			if(a == undefined){
				alert("Selecciona una respuesta");
			}else{
				$.post( "controls/doAction.php", { action:"answerQuestion", c:c, qfs:qfs , w:w , a:a, sp:sp  })
				.done(function( data ) {
					console.log(data);
					data = JSON.parse(data);
					if(data.message == 'success'){
						console.log(data);
					//window.location.reload();

				}else{
					alert("There was an error: " + data.message);
				}

			});
			}
			
			
			
		}

	</script>
</body>
</html>
