<?php

require 'core/init.php';

$user = new User();
$user->checkIsValidUser('teacher');

$teacherId = $user->data()->id;
$groups = new Groups();
$teacherGroups = $groups->getGroupsForTeacher($teacherId);
$groupsIds = array();
foreach($teacherGroups as $group){
  array_push($groupsIds,$group->id);
}
$blockedStudents = $user->getBlockedStudents($groupsIds);
?>

<!doctype html>
<html class="no-js" lang="en">
<head>
	<title>LSAT | Webs</title>
	<?php include 'includes/templates/headTags.php' ?>
</head>

<body>

	<?php include 'includes/templates/header.php' ?>

  <section class="scroll-container" role="main">

		<div class="row">
			<?php include 'includes/templates/teacherSidebar.php' ?>
			<div class="large-9 medium-8 columns">
				<h3>Desbloquear alumno</h3>
				<h4 class="subheader">Lista de alumnos bloqueados</h4>
				<hr>

        <table>
					<thead>
						<tr>
							<th width="500">Alumno</th>
              <th width="300">Competencia</th>
              <th width="300">Desbloquear</th>
						</tr>
					</thead>

					<tbody>
						<?php

						if($blockedStudents != null){

							foreach ($blockedStudents as $student) {

								echo "<tr id='$student->id'>
								<td> $student->username </td>
                <td> $student->competenceId </td>";
								echo "<td> <button onClick=unlockStudent($student->id, $student->competenceId, $student->groupId) class='tiny button secondary'>Desbloquear</button> </td></tr>";
							}
						}else{
							echo "<tr> <td> No hay alumnos bloqueados </td> </tr>";
						}
						?>

					</tbody>
				</table>

      </div>
    </div>
  </section>


  <?php include 'includes/templates/footer.php' ?>


  <script src="js/vendor/jquery.js"></script>
  <script src="js/foundation.min.js"></script>
  <script>
    $(document).foundation();

    function unlockStudent(studentId, competenceId, groupId){
      console.log(studentId)
    }
  </script>
</body>
</html>
