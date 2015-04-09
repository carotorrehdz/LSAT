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
$nextQuestionId = $q->getNextQuestionId($studentId, $groupId, $competenceId);
var_dump("Id de la siguiente pregunta");
var_dump($nextQuestionId);

$nextQuestion = $q->getQuestion($nextQuestionId);
var_dump($nextQuestion);

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


			</div>
		</div>
	</section>


	<?php include 'includes/templates/footer.php' ?>

	<script src="js/vendor/jquery.js"></script>
	<script src="js/foundation.min.js"></script>
	<script>
		$(document).foundation();


		function x(){
			var username  = $("#username").val();
			var mail      = $("#mail").val();
			var idnumber  = $("#idnumber").val();

			$.post( "controls/doAction.php", { action:"getNextQuestion", sId: $studentId, cId: $competenceId, gId: $groupId })
			.done(function( data ) {
				console.log(data);
				data = JSON.parse(data);
				if(data.message == 'success'){
					alert("The teacher was registered");
					window.location.reload();
				}else{
					alert("There was an error: " + data.message);
				}

			});
		}

	</script>
</body>
</html>
