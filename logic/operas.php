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
	    unset($result);
	} else {
	    $query='SELECT * FROM operas INNER JOIN 
	    operas_composers ON operas.opera_id=operas_composers.opera_id INNER JOIN aliases on operas_composers.composer_id=aliases.id1 INNER Join composers 
	     ON composers.composer_id=aliases.id2 where aliases.type="c"'; 
	}
	$q = mysqli_query($db,$query);
	$titles=array();
	while ($row = mysqli_fetch_assoc($q)) {
		/* добавяме линкове към името на всяко произведение и на всеки композитор */
	    $result[]['opera_title'][$row['opera_id']] = '<a href="index.php?page=spectacles&opera_id='.$row['opera_id']. '">' .$row['opera_title'] . '</a>';
	    /* добавяме линкове към името на всеки композитор */
	    $result[count($result)-1]['composers'][$row['composer_id']] = '<a href="index.php?page=operas&composer_id=' .$row['composer_id']. '">' . $row['composer_name'] . '</a>';
		$titles[]=$row['composer_name'];  
	}
	/* проверка за две и повече имена на едно и също произведение */
	$query='SELECT * FROM operas LEFT JOIN `aliases` ON operas.opera_id = aliases.id1 WHERE aliases.id1 != aliases.id2
		AND aliases.type = "o"';
	$q = mysqli_query($db,$query);
	while ($row = mysqli_fetch_assoc($q)) {
	    $res[$row['id1']] = $row['id2']; 
	}	
	/* обединяване на имената на композиторите на едно и също произведение, вкл. и известен като... */
	$resul=array();
	if (count($result)>0){
		$resul[0]['opera_title']=$result[0]['opera_title'];
		$resul[0]['composers']=$result[0]['composers'];
		if (count($result)>1){
			$j=0;
			for ($i=1;$i<count($result);$i++){
				$k1=key($result[$i]['opera_title']);
				$k2=key($resul[$j]['opera_title']);
				if($k1==$k2){
					$key=key($result[$i]['composers']);
					$resul[$j]['composers'][$key]=current($result[$i]['composers']);
				} else {
					$j++;
					$resul[$j]['opera_title']=$result[$i]['opera_title'];
					$resul[$j]['composers']=$result[$i]['composers'];
				}
			}
			unset($result);
		}
	}
	/* ако има произведения с две имена, премахваме обратната връзка */
	ksort($res);
	if (count($res)>0){
		for ($i=0;$i<count($res);$i++){
			unset ($res[current($res)]);
			next ($res);
		}
		/* само в случай, че размерът на масива е > 1 */
		if (count($resul)>1){
			/* обединяване на заглавия, известни като... */
			$k1=$k2=0;		
			$set1=$set2=false;
			foreach ($res as $k=>$v) {
				for($j=0;$j<count($resul);$j++){
					$key=key($resul[$j]['opera_title']);
					if ($key==$k){
						$k1=$j;
						$set1=true;
					}
					if ($key==$v){
						$k2=$j;
						$set2=true;
					}
				}
				/* само ако и двата ключа имат стойност */
				if($set1 and $set2){
					/* заглавието от втория ключ отива при първия */
					$resul[$k1]['opera_title'][]=current($resul[$k2]['opera_title']);
							foreach ($resul[$k2]['composers'] as $k => $v){
								$resul[$k1]['composers'][$k]=$v;
							}
							/* изтриване на записа със втория ключ */
							unset($resul[$k2]);
							/* необходимо сортиране за изчистване на „дупките“ в масива */
							sort ($resul);
							/* нулиране на ключовете за следващия цикъл */
							$k1=$k2=0;
							$set1=$set2=false;
				}
			}			
		}
	}
	/* преобразуване за визуализация */
	$result=array();
	$i=0;
	foreach($resul as $v){
		$result[$i]['opera_title']=implode('; ', $v['opera_title']);
		$result[$i]['composers']=implode('; ', $v['composers']);
		$i++;
	}
	/* изпращане към визуализацията */
	if (!isset($_GET['composer_id'])) {
		$title='Списък';
		render($title,$viewDir.'ls_operas',$result);
	}
	else {
		$title=implode('; ', array_unique($titles));
		render($title,$viewDir.'ls_composer',$result);
	}
