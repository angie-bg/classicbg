<?php
	include './inc/header.php';
	echo '<p><a href="index.php?page=operas">
	Списък</a></p><p><form method="post" action="">
	Композитори: <select name="composers[]" multiple style="width: 300px">';
	 	foreach ($data as $row) {
	 		echo '<option value="' . $row['composer_id'] . '">
	                    ' . $row['composer_name'] . '</option>';
	   	}
	echo '</select>
	<div>Име: <input type="text" name="opera_name" /></div>';
	if(count($data1)>0) {
		echo 'Известна и като: <select name="operas[]" multiple style="width: 300px">';
	 	foreach ($data1 as $row) {
	 		echo '<option value="' . $row['opera_id'] . '">
	                    ' . $row['opera_title'] . '</option>';
	 		dump($row);
	   	}  	

		echo '</select>';
	}	
	echo '<div><input type="submit" value="Добави" /> 
	    <input type="reset" value="Изтрий" /> </div></div>
		</form>';


	include './inc/footer.php';
	