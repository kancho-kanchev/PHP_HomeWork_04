<?php
$pageTitle = 'Списък';
$message = '';
$messageForDel = '';
require_once 'includes'.DIRECTORY_SEPARATOR.'header.php';
require_once 'includes'.DIRECTORY_SEPARATOR.'conection.php';
$query = 'SELECT * FROM books';
$result = mysqli_query($connection, $query);
?>
	<div>
			<a href="new_book.php">Добави книга</a>
			<a href="new_author.php">Добави автор</a>
		</div>
		<table border="1">
			<tr>
				<td>Книга</td>
				<td>Автори</td>
			</tr>
<?php
while($row = mysqli_fetch_array($result))
{
	echo '<tr><td>'.$row['book_title'].'</td><td>'.$row['book_id'].'</td></tr>';
}
echo '</table>';
require_once 'includes'.DIRECTORY_SEPARATOR.'footer.php';
