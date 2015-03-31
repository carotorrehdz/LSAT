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
}
?>

<!doctype html>
<html class="no-js" lang="en">
<head>
	<title>LSAT | Dashboard</title>
	<?php include 'includes/templates/headTags.php' ?>
</head>

<body>

	<?php include 'includes/templates/header.php' ?>

	<section class="scroll-container" role="main">

		<div class="row">
			<?php include 'includes/templates/studentSidebar.php' ?>  
			<div class="large-9 medium-8 columns">
				<br/>
				<h3>Contestar competencia </h3>
				<h4 class="subheader"></h4>
				<hr>  

			
			</div>
		</div>
	</section>


	<?php include 'includes/templates/footer.php' ?>

	<script src="js/vendor/jquery.js"></script>
	<script src="js/foundation.min.js"></script>
	<script>
		$(document).foundation();

	</script>
</body>
</html>
