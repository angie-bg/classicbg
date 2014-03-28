<?include './inc/header.php';?>
<p><a href="index.php?page=operas">Списък</a>&nbsp&nbsp&nbsp<a href="index.php?page=add_opera">Ново произведение</a></p>
<p><form method="post" action="">
	Име: <input type="text" name="composer_name" /><p>
	<?if(count($data)>0) {
	echo 'Известен и като: <select name="composers[]" multiple style="width: 300px">';
	 	foreach ($data as $row) {
	 		echo '<option value="' . $row['composer_id'] . '">
	                    ' . $row['composer_name'] . '</option>';
	   	}  	

		echo '</select>';
	}	
	echo '<div><input type="submit" value="Добави" /> 
	    <input type="reset" value="Изтрий" /> </div>
		</form>';


include './inc/footer.php';
