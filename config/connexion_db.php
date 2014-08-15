<?php            
            //$connexion = mysql_connect('mysql-tp.svc.enst-bretagne.fr:3306', 'abouraou', '4039146') OR die('Erreur de connexion');
           //mysql_select_db('ABOURAOU') OR die('Erreur de sélection de la base');
			
			
			//Configuration sur mon ordinateur
			 $db_host="localhost";
			 $db_usr="root";
			 $db_password="";
			 $db_name="club-afrique-db";
					  // conexion à  la base de données
					  
				$connexion = mysql_connect($db_host,$db_usr) or die("Impossible de se connecter à  la base de donnée");
			 mysql_select_db($db_name,$connexion);
?>
