<?php


function age($naiss)  {
  list($annee, $mois, $jour) = preg_split('[-.]', $naiss);
  $today['mois'] = date('n');
  $today['jour'] = date('j');
  $today['annee'] = date('Y');
  $annees = $today['annee'] - $annee;
  if ($today['mois'] <= $mois) {
    if ($mois == $today['mois']) {
      if ($jour > $today['jour'])
        $annees--;
      }
    else
      $annees--;
    }
  return $annees;
  }


function type_age($parama){
        if ((is_numeric($parama))===true && $parama <140)
            echo $parama;
        else {
            echo '<span style="color:red;">champ incoherent</span>';
            }
    }

function test($param) {
	   if (($param=="") || (!(preg_match("#^[a-zA-Z-]+$#",$param)))){
            echo '<span style="color:red;">champs incoherent </span>';
            
            }
        else{ 
            echo $param	;}

       
	}


function test2($param) {
       $erreur = 0;
	   if (($param=="") || (!(preg_match("#^[a-zA-Z-]+$#",$param)))) {
            $erreur = 1 ;
                        }
       return $erreur;
	}


function type_age_new($parama){
        $erreur = 0;
        if ((is_numeric($parama)===true) && ($parama <140))
               $erreur = 0;
        else 
                $erreur = 1;
           
        return $erreur;
    }
function adressemail($par,$attribut){
     echo $par.".".$attribut."@enst-bretagne.fr";
 
    }

?>
