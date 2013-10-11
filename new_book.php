<?php
$pageTitle = 'Добавяне на книга';
$message = '';
$messageForDel = '';
$selectTitle = 'Избери';
$group = '0';
require_once 'includes'.DIRECTORY_SEPARATOR.'header.php';
require_once 'includes'.DIRECTORY_SEPARATOR.'conection.php';
$result = mysqli_query($connection,"SELECT * FROM groups");
while($row = mysqli_fetch_array($result))
{
	$groups [$row['groups_id']]=$row['group'];
}
$query = 'SELECT user_id FROM users WHERE username="'.$username.'"';
$result = mysqli_query($connection, $query);
$user_id=mysqli_fetch_array($result)['user_id'];
if ($_POST) {
	if (isset($_POST['title']) && isset($_POST['message'])) {
		$error = false;
		$title = trim($_POST['title']);
		if (mb_strlen($title, 'UTF-8') < 1 || mb_strlen($title, 'UTF-8') > 50) {
			echo 'Заглавието трябва да е между 1 и 50 символа</br>'."\n";
			$error = true;
		}
		else {
			mysqli_real_escape_string($connection, $title);
		}
		$message = trim($_POST['message']);
		if (mb_strlen($message, 'UTF-8') < 1 || mb_strlen($message, 'UTF-8') > 250) {
			echo 'Съобщението трябва да е между 1 и 250 символа</br>'."\n";
			$error = true;
		}
		else {
			mysqli_real_escape_string($connection, $message);
		}
		$group = trim($_POST['group']);
	}
	if (!$error) {
		$group = (int)$group;
		$user_id = (int)$user_id;
		echo $user_id.' '.$group.' '.$title.' '.$message;
		var_dump($user_id);
		var_dump($group);
		echo 'shte prepare';
		if (!($stmt = mysqli_prepare($connection, 'INSERT INTO messages(user_id, `group`, title, message) VALUES (?, ?, ?, ?)'))) {
			//echo mysqli_error($connection);
			//echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			header('error.php?message=databaseerror');
			exit;
		}
		echo 'shte bindwam';
		if (!$stmt->bind_param("iiss", $user_id, $group, $title, $message)) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		echo 'shte zapiswam';
		if (!$stmt->execute()) {
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		echo 'Записа е успешен';
		header('Location: messages.php');
		exit;
	}
}

?>
	<div>
		<a href="destroy.php">Изход</a>
	</div>
	<div>
		<a href="messages.php">Обратно към съобщенията</a>
	</div>
	<form method="POST" action="new_message.php" id="new_message">
		<div>Тема: <select name="group">
				<?php 
					echo'<option value="0">'.$selectTitle.'</option>'."\n";
					foreach ($groups as $key=>$value) {
						echo'				<option value="'.$key.'"';
						if ($group==$key){
							echo 'selected';
						}
						echo '>'.$value.'</option>'."\n";
					}
				?>
			</select></div>
		<div>Заглавие:<input type="text" name="title" value="<?= (isset($title)) ? $title : '';?>"/></div>
		<textarea rows="4" cols="50" name="message" form="new_message"><?= (isset($message)) ? $message : '';?></textarea>
		<div><input type="submit" name="submit" value="Запис" /></div>
	</form>
<?php
require_once 'includes'.DIRECTORY_SEPARATOR.'footer.php';
