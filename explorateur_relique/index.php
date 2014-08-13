<?php 
/******************************************************************
auteur: Geraud LACHENY (2014)
site web: http://www.geraudlacheny.fr
/******************************************************************/

require_once('config/config.inc.php');
require_once('config/settings.inc.php');
require_once('lib/functions.php');

if( AUTHENTIFICATION == 'on' ){

	require_once('classes/User.php');

	session_start();

	// si l'utilisateur n'est pas authentifie, il est renvoye sur la page de login
	if( !isset($_SESSION['auth']) )
		redirect('login.php');
	
}

// par defaut, trier par...
$orderby = 'nom';
  
// par defaut, tri ascendant 
$order = 'asc';

// par defaut, repertoire courant
$repertoire_courant = BASE;
if( isset($_GET['p']) && !empty($_GET['p']) ) {
	$repertoire_courant =  secureDir($_GET['p']);
	
	$pos = strpos($repertoire_courant, BASE);
	if( (0 != $pos) or ($pos === false) ) {
		$repertoire_courant = BASE;
	} 
}	
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8" />
	<title>Les reliques laissés par vos anciens</title>	
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" href="themes/original/css/normalize.css" />
	<link rel="stylesheet" href="themes/original/css/screen.css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	
	<!-- JQUERY -->
	<script src="js/jquery-1.11.0.min.js"></script>	
	
	<!-- SHADOWBOX -->
	<script src="js/shadowbox-3.0.3/shadowbox.js"></script>
	<link rel="stylesheet" type="text/css" href="js/shadowbox-3.0.3/shadowbox.css" />
	<script>
		Shadowbox.init();
	</script>
	
	<!-- SCRIPTS DIVERS -->	
	<script>
		$(document).ready(function(){
		
			$(".element1").hover( 
				function () { $(this).addClass("hover"); }, function () { $(this).removeClass("hover"); }
			);			
			
			$(".repertoire").click(function () { 
				$(".element1").removeClass("stay");
				$(this).addClass("stay");
			});
			$(".image").click(function () { 
				$(".element1").removeClass("stay");
				$(this).addClass("stay");
			});
			$(".fichier").click(function () { 
				$(".element1").removeClass("stay");
				$(this).addClass("stay");
			});
			$(".inconnu").click(function () { 
				$(".element1").removeClass("stay");
				$(this).addClass("stay");
			});

			$(".repertoire").dblclick(function(){		
				window.location.href = 'index.php?p='+$(this).attr("id").slice(8);
			});
			$(".image").dblclick(function(){		
				window.location.href = 'ressource.php?id='+$(this).attr("id").slice(8);
			});
			$(".fichier").dblclick(function(){		
				window.location.href = 'ressource.php?id='+$(this).attr("id").slice(8);
			});
			$(".inconnu").dblclick(function(){		
				window.location.href = 'ressource.php?id='+$(this).attr("id").slice(8);
			});
			
		});
	</script>

</head>
 
<body>

	<header id="header">
        <div class="header_content">
          <div class ="header_logo">Africa</div><div class ="header_logo" id="logo2">TB</div>
              <nav class="menu">
                <div class="menu_logo"><a href="#">Gallerie</a></div>
                <div class="menu_logo"><a href="#">Anciens</a></div>
                <div class="menu_logo"><a href="./explorateur_relique">Reliques</a></div>
                <div class="menu_logo"><a href="#">Contacts</a></div>
              </nav>
        </div>
			<!--<?php if(AUTHENTIFICATION === 'on') { ?>
				<div class="menu_logo">
					<span style="color:#AAA;"><?php echo $_SESSION['auth'] ?></span>
					<a title="Se d&eacute;connecter" href="deconnexion.php" onclick="return confirm('Etes-vous sur de vous d\351connecter ?')"><img alt="se deconnecter" src="themes/original/images/logout.png" /></a>
				</div><!--fin logout -->
			<?php } ?>
		</div><!-- fin contenu -->
	</header>
	
	<nav id="fildariane">
		<div class="contenu">
			<ul>
				<?php 
				$noeud = 0;
				$t_fildariane = array();
				foreach(getBreadcrumb($repertoire_courant) as $element) { 
					$noeud++;
					$t_fildariane[] = $element;
					$p = implode("/", $t_fildariane);
					// j'affiche la racine
					if($noeud == 1) { ?>
						<li class="racine"><a href="index.php"><img alt="racine" src="themes/original/images/repertoire.png" />&nbsp;racine</a></li>
						<?php $nb_elements = count(getBreadcrumb($repertoire_courant));
							if($nb_elements > 0) { ?>
								<li><img alt="&gt;" src="themes/original/images/arrow.gif" /></li>
							<?php } ?>						
					<?php }
					else {
						echo "<li><a href=\"index.php?p=" , rawurlencode($p) , "&amp;orderby=nom&amp;order=". $order ."\">" , normalizeString($element) , "</a></li>";
						// s'il n'y a pas d'elements enfants, on n'affiche pas le marqueur
						if(hasChildren($p)) { ?>
							<li><img alt="&gt;" src="./themes/original/images/arrow.gif" /></li>
						<?php }
					}
				} ?>
			</ul>
		</div><!-- fin contenu -->
	</nav>
	
	<div id="vues">
		<div class="contenu">
			<ul>
				<li id="vue_courante"><a href="#">Mosa&iuml;que</a></li>
				<li><a href="index2.php?p=<?php echo rawurlencode($repertoire_courant) ?>">D&eacute;tail</a></li>
				<li><a href="index3.php?p=<?php echo rawurlencode($repertoire_courant) ?>">Grandes ic&ocirc;nes</a></li>
			</ul>
		</div><!-- fin contenu -->
	</div><!-- fin vues -->

	
	<div id="corps">
		
		<div class="contenu">		
		
			<div id="arborescence">
			
				<ul>
					<li class="racine"><a href="index.php"><img alt="racine" src="themes/original/images/repertoire.png" />racine</a></li>
					<?php foreach(getDir(BASE) as $element) { ?>
						<li>
							<a href="index.php?p=<?php echo rawurlencode(BASE . "/" . $element['nom']) ?>">
								<img alt="repertoire" src="themes/original/images/repertoire.png" />
								<span <?php echo rawurlencode(BASE . "/" . $element['nom']) == rawurlencode($repertoire_courant) ? 'style="font-weight:bold"' : '' ?> title="<?php echo normalizeString($element['nom']) ?>"><?php echo shortenString(normalizeString($element['nom']), 18) ?></span>
							</a>
						</li>
					<?php } ?>
				</ul>
			
			</div><!-- fin arborescence -->
	
			<div id="contenu_repertoire">
		
				<?php 
				$contenu_repertoire = listDir($repertoire_courant);
				
				if(isset($contenu_repertoire) && !empty($contenu_repertoire) ){
				
					foreach($contenu_repertoire as $element) {
					
						switch($element['type']) {
						
							case 'repertoire': ?>
								<div class="element1 repertoire" id="element_<?php echo rawurlencode($repertoire_courant."/".$element['nom']), "&amp;orderby=nom&amp;order=", $order ?>" title="<?php echo $element['nom.extension'] ?>">
									<div class="element1_1"><img alt="repertoire" src="themes/original/images/48/repertoire.png" /></div>				
									<div class="element1_2"><span> <?php echo normalizeString($element['nom']) ?></span></div>								
									<div class="element1_3"><span>Dossier de fichiers</span></div>
									<div class="element1_4">&nbsp;</div>
								</div>
								<?php break;
								
							case 'fichier':
								if( in_array(strtolower($element['extension']), $t_extensions_reconnues) ) { ?>
									<div class="element1 fichier" id="element_<?php echo rawurlencode($repertoire_courant."/".$element['nom.extension']), "&amp;orderby=nom&amp;order=", $order ?>" title="<?php echo $element['nom.extension'] ?>">
										<div class="element1_1"><img alt="fichier" src="themes/original/images/48/<?php echo strtolower($element['extension']) ?>.png" /></div>				
										<div class="element1_2"><span><?php echo shortenString(normalizeString($element['nom.extension']), 35) ?></span></div>
										<div class="element1_3"><span><?php echo $t_extensions[strtolower($element['extension'])]?></span></div>										
										<div class="element1_4"><span><?php echo formatSize($element['taille'])?></span></div>				
									</div>
								<?php }
								else { ?>
									<div class="element1 inconnu" id="element_<?php echo rawurlencode($repertoire_courant."/".$element['nom.extension']), "&amp;orderby=nom&amp;order=", $order ?>" title="<?php echo $element['nom.extension'] ?>">
										<div class="element1_1"><img alt="inconnu" src="themes/original/images/48/inconnu.png" /></div>
										<div class="element1_2"><span><?php echo shortenString(normalizeString($element['nom.extension']), 35) ?></span></div>	
										<div class="element1_3">&nbsp;</div>									
										<div class="element1_4"><span><?php echo formatSize($element['taille'])?></span></div>										
									</div>							
								<?php }
								break;
								
							case 'image': ?>
								<div class="element1 image" id="element_<?php echo rawurlencode($repertoire_courant."/".$element['nom.extension']), "&amp;orderby=nom&amp;order=", $order ?>" title="<?php echo $element['nom.extension'] ?>">
									<div class="element1_1"><img alt="image" src="themes/original/images/48/<?php echo strtolower($element['extension']) ?>.png" /></div>				
									<div class="element1_2"><span><?php echo shortenString(normalizeString($element['nom.extension']), 35) ?></span></div>						
									<div class="element1_3"><span><?php echo $t_extensions[strtolower($element['extension'])]?></span></div>	
									<div class="element1_4"><span><?php echo formatSize($element['taille'])?></span></div>										
								</div>								
								<?php break;			
					
						}
					
					}
					
				}
				else { ?>
				
					<img id="info" alt="[i]" src="themes/original/images/info.png" /> R&eacute;pertoire vide
					
				<?php } ?>
			
			</div><!-- fin contenu_repertoire -->
	
		</div><!-- fin contenu -->
		
	</div><!-- fin corps -->
	
	<footer id="pieddepage">
		<div class="contenu">
			<ul>
				<li><img src="http://i.creativecommons.org/l/by-nc/3.0/fr/88x31.png" alt="Licence Creative Commons" /><a href="#">g&eacute;raud lacheny 2014</a></li>
				<li><a href="mailto: contact@geraudlacheny.fr">contact&nbsp;&raquo;</a></li>
				<li><a onclick="window.open(this.href); return false;" href="humans.txt">humans.txt&nbsp;&raquo;</a></li>
			</ul>			
		</div><!-- fin contenu -->
	</footer>

</body>
</html>