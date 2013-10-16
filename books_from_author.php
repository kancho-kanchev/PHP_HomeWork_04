<?php
$pageTitle = 'Книги от автор';
require_once 'includes'.DIRECTORY_SEPARATOR.'header.php';
require_once 'includes'.DIRECTORY_SEPARATOR.'connection.php';
if ($_GET && isset($_GET['author'])) {
	$author_id = trim($_GET['author']);
	$error = true;
	$author_id = mysqli_real_escape_string($connection, $author_id);
	$stmt = mysqli_prepare($connection, 'SELECT author_id FROM authors WHERE author_id =?');
	if (!$stmt) {
		echo mysqli_error($connection);
		exit;
	}
	else {
		mysqli_stmt_bind_param($stmt, 'i', $author_id);
		mysqli_stmt_execute($stmt);
		$rows = mysqli_stmt_result_metadata($stmt);
		while ($field = mysqli_fetch_field($rows)) {
			$fields[] = &$row[$field->name];
		}
		call_user_func_array(array($stmt, 'bind_result'), $fields);
		while (mysqli_stmt_fetch($stmt)) {
			$error = false;
		}
	}
	if (!$error) {
		$query = 'SELECT b.book_title, a.author_name as author_name, a.author_id
			FROM `authors` as a
			LEFT JOIN books_authors as ba
			ON ba.author_id = a.author_id
			LEFT JOIN books as b
			ON b.book_id = ba.book_id
			LEFT JOIN books_authors as ba2
			ON ba2.book_id = b.book_id
			WHERE ba2.author_id = '.$author_id;
		$q = mysqli_query($connection, $query);

?>	<div>
			<a href="index.php">Обратно към списъка с книгите</a>
		</div>
		<div>
			<a href="new_book.php">Добави книга</a>
			<a href="new_author.php">Добави автор</a>
		</div>
<?= '		<table border="1">';?>
			<tr>
				<td>Книга</td>
				<td>Автори</td>
			</tr>
<?php
		$result = array();
		while($row = mysqli_fetch_assoc($q)) {
			$result[$row['book_title']]['book_title'] = $row['book_title'];
			$result[$row['book_title']]['authors'][$row['author_id']] = $row['author_name'];
		}
		//echo '<pre>'.print_r($result, true). '</pre>';
		foreach ($result as $value_book) {
			echo '			<tr><td>'.$value_book['book_title'].'</td><td>';
			$authors = array();
			foreach ($value_book['authors'] as $key => $value_author) {
				$authors[] = '<a href="books_from_author.php?author='.$key.'">'.$value_author.'</a>';
			}
			echo implode(', ', $authors).'</td></tr>'."\n";
		}
		echo '</table>';
	}
	else {
?>	<div>
			<a href="index.php">Обратно към списъка с книгите</a>
		</div>
	<div>Невалиден индентификатор за автор!</div>
<?php 
	}
}
else {
	header('Location: index.php');
	exit;
}
require_once 'includes'.DIRECTORY_SEPARATOR.'footer.php';
