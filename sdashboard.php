<?php

require 'core/init.php';

$user = new User();
$user->checkIsValidUser('student');
$studentId = $user->data()->id;
$information = $user->getInformationForStudent($studentId);

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
				<h3>Dashboard estudiante</h3>
				<h4 class="subheader"></h4>
				<hr>  

				<div id="studentCompetences">
					<div id="groups">
						<ul>
							<?php
							foreach ($information as $key => $competence) {
								//Desplegar descripcion del curso
								echo "<li><a href='#' onclick='showCompetence($competence->groupId)'><span><b>$competence->groupName</b></span><span>$competence->professorName</span></a></li>";
							}
							?>
						</ul>
					</div>
					<?php
					foreach ($information as $key => $competence) {
								//Desplegar descripcion del curso
						echo "<div id=$competence->groupId class='competences' style='display: none'><h3>$competence->groupName / <small>$competence->professorName</small></h3></div>";
					}
					?>
				</div>

			</div>
		</div>
	</section>


	<?php include 'includes/templates/footer.php' ?>

	<script src="js/vendor/jquery.js"></script>
	<script src="js/foundation.min.js"></script>
	<script>
		$(document).foundation();

		function showCompetence(groupId) {
			var group = "#"+groupId;
			$(group).show();
		}

	</script>
</body>
</html>
