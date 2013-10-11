<?php
$pageTitle = 'Списък';
$message = '';
$messageForDel = '';
require_once 'includes'.DIRECTORY_SEPARATOR.'header.php';
require_once 'includes'.DIRECTORY_SEPARATOR.'connection.php';
$query = 'SELECT books.book_title, authors.author_id, authors.author_name FROM	books
		LEFT JOIN books_authors
		INNER JOIN authors
		ON books_authors.author_id = authors.author_id
		ON books_authors.book_id = books.book_id';

$q = mysqli_query($connection, $query);
$result = array();
while($row = mysqli_fetch_assoc($q)) {
	$result[$row['book_title']]['book_title'] = $row['book_title'];
	$result[$row['book_title']]['authors'][$row['author_id']] = $row['author_name'];
}
//echo '<pre>'.print_r($result, true). '</pre>';
	echo '	<div>'."\n";
	echo '			<a href="new_book.php">Добави книга</a>'."\n";
	echo '			<a href="new_author.php">Добави автор</a>'."\n";
	echo '		</div>'."\n";
	echo '		<table border="1">'."\n";
	echo '			<tr><td>Книга</td><td>Автори</td></tr>'."\n";
foreach ($result as $value_book) {
	echo '			<tr><td>'.$value_book['book_title'].'</td><td>';
	$authors = array();
	foreach ($value_book['authors'] as $key => $value_author) {
		$authors[] = '<a href="books_from_author.php?author='.$key.'">'.$value_author.'</a>';
	}
	echo implode(', ', $authors).'</td></tr>'."\n";
}
echo '		</table>'."\n";
require_once 'includes'.DIRECTORY_SEPARATOR.'footer.php';
