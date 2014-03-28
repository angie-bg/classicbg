<?php
	if (isset($_GET['composer_id'])) {
	    $composer_id = (int) $_GET['composer_id'];
	    /* проверка дали композиторът е известен и под други имена */
	    $q = mysqli_query($db, 'SELECT * FROM composers LEFT JOIN 
	     aliases ON composers.composer_id=aliases.id1 WHERE composers.composer_id='.$composer_id.' and aliases.type="c"');
	    while ($row = mysqli_fetch_assoc($q)) {
	    	$result[] = $row['id2'];
	    } 
	    $query='SELECT * FROM composers LEFT JOIN 
	    operas_composers ON composers.composer_id=operas_composers.composer_id LEFT JOIN operas 
	     ON operas.opera_id=operas_composers.opera_id WHERE composers.composer_id='.$result[0];
	    if(count($result>1)) {
	    	for ($i=1;$i<count($result);$i++){
	    		$query=$query.' OR composers.composer_id='.$result[$i];
	    	}
	    }
	    /* първоначален вариант
		$q = mysqli_query($db, 'SELECT * FROM composers LEFT JOIN 
	    operas_composers ON composers.composer_id=operas_composers.composer_id LEFT JOIN operas 
	     ON operas.opera_id=operas_composers.opera_id WHERE composers.composer_id='.$composer_id); 
	     */
	} else {
	   	/* първоначален вариант
		$q = mysqli_query($db, 'SELECT * FROM operas INNER JOIN 
	    operas_composers ON operas.opera_id=operas_composers.opera_id INNER JOIN composers 
	     ON composers.composer_id=operas_composers.composer_id');
	    */
	    $query='SELECT * FROM operas INNER JOIN 
	    operas_composers ON operas.opera_id=operas_composers.opera_id INNER JOIN aliases on operas_composers.composer_id=aliases.id1 INNER Join composers 
	     ON composers.composer_id=aliases.id2 where aliases.type="c"'; 
	}
	$q = mysqli_query($db,$query);
	while ($row = mysqli_fetch_assoc($q)) {
		/* добавяме линкове към името на всяко произведение */
	    $result[$row['opera_id']]['opera_title'] = array('<a href="index.php?opera_id='.$row['opera_id']. '">' .$row['opera_title'] . '</a>');
	    /* добавяме линкове към името на всеки композитор */
	    $result[count($result)]['composers'][$row['composer_id']] = '<a href="index.php?composer_id=' .$row['composer_id']. '">' . $row['composer_name'] . '</a>';
	}
	$query='SELECT * FROM operas LEFT JOIN `aliases` ON operas.opera_id = aliases.id1 WHERE aliases.id1 != aliases.id2
		AND aliases.type = "o"';
	$q = mysqli_query($db,$query);
	while ($row = mysqli_fetch_assoc($q)) {
	    $res[][$row['id1']] = $row['id2']; 
	}
	$r=asort($res);
	/* обединяване на дублиращи се опери и композитори */
	for ($i=0;$i<count($res);$i++){
		$keys=array_keys($res[$i]);
		/* съответстващите си индекси в aliases*/
		$id1=$keys[0];
		$id2=$res[$i][$id1];
		$keys=array_keys($result[$id2]['composers']);
		$result[$id1]['opera_title'][]=$result[$id2]['opera_title'][0];
		$result[$id1]['composers']=array_merge_recursive($result[$id1]['composers'],$result[$id2]['composers']);
		unset($result[$id2]);
		$i++;
	}
	
	render('Списък',$viewDir.'ls_operas',$result);
?>
 
