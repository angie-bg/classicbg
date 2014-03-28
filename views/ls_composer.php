<?php
	include './inc/header.php';
	echo '<section><article><header>';
	echo '<h1>'.$title.'</h1></header>
		<table>
			<thead><tr>
			<th>Произведение</th>
			</tr></thead><tbody>';
		foreach ($data as $row) {
		    echo '<tr><td>';
			echo $row['opera_title'].'</td><td>';
		}
		echo '</tbody></table>
	</article></section>';
	include './inc/footer.php';
