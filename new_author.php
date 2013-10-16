<?php
$pageTitle = 'Добавяне на автор';
require_once 'includes'.DIRECTORY_SEPARATOR.'header.php';
require_once 'includes'.DIRECTORY_SEPARATOR.'connection.php';
if ($_POST) {
	if (isset($_POST['name'])) {
		$error = false;
		$name = trim($_POST['name']);
		if (mb_strlen($name, 'UTF-8') < 3 || mb_strlen($name, 'UTF-8') > 250) {
			echo '	<p>Името трябва да е между 3 и 250 символа</p>'."\n";
			$error = true;
		}
		else {
			$name = mysqli_real_escape_string($connection, $name);
		}
		$stmt = mysqli_prepare($connection, 'SELECT author_name FROM authors WHERE author_name =?');
		if (!$stmt) {
			echo mysqli_error($connection);
			exit;
		}
		else {
			mysqli_stmt_bind_param($stmt, 's', $name);
			mysqli_stmt_execute($stmt);
			$rows = mysqli_stmt_result_metadata($stmt);
			while ($field = mysqli_fetch_field($rows)) {
				$fields[] = &$row[$field->name];
			}
			call_user_func_array(array($stmt, 'bind_result'), $fields);
			while (mysqli_stmt_fetch($stmt)) {
				echo '	<p>Автора вече съществува.</p>'."\n";
				$error = true;
			}
		}
	}
	if (!$error) {
		if (!($stmt = mysqli_prepare($connection, 'INSERT INTO authors(author_name) VALUES (?)'))) {
			echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			require_once 'includes'.DIRECTORY_SEPARATOR.'footer.php';
			exit;
		}
		if (!$stmt->bind_param("s", $name)) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			require_once 'includes'.DIRECTORY_SEPARATOR.'footer.php';
			exit;
		}
		if (!$stmt->execute()) {
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			require_once 'includes'.DIRECTORY_SEPARATOR.'footer.php';
			exit;
		}
		echo '	<p>Записа е успешен</p>'."\n";
		$name = '';
	}
}
echo '	<div>'."\n";
echo '			<a href="index.php">Обратно към списъка с книгите</a>'."\n";
echo '		</div>'."\n";
?>
		<form method="POST" action="new_author.php">
			<div>Име:<input type="text" name="name" value="<?= (isset($name)) ? $name : '';?>"/></div>
			<div><input type="submit" name="submit" value="Запис" /></div>
		</form>
<?php
$query = 'SELECT * FROM authors';
$q = mysqli_query($connection, $query);
while($row = mysqli_fetch_assoc($q)) {
	$result[$row['author_id']]= $row['author_name'];
}
echo '		<table border="1">'."\n";
echo '			<tr><td>Автори</td></tr>'."\n";
foreach ($result as $key => $value) {
	echo '			<tr><td><a href="books_from_author.php?author='.$key.'">'.$value.'</a></td></tr>'."\n";
}
echo '		</table>'."\n";
require_once 'includes'.DIRECTORY_SEPARATOR.'footer.php';
