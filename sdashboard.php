<?php

require 'core/init.php';

$user = new User();
$user->checkIsValidUser('student');
$studentId = $user->data()->id;

//$groups = new Groups();
//$teacherGroups = $groups->getGroupsForTeacher($teacherId);
//var_dump($teacherGroups);

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
							<li>
								<span><b>TC01</b></span>
								<span>Administracion de proyectos</span>
							</li>

							<li>
								<span><b>TC02</b></span>
								<span>Estructura de datos</span>
							</li>
						</ul>
					</div>


					<div id="competences">
						<h3>Algoritmos / <small>Luis Humberto</small></h3>
					</div>

				</div>

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
