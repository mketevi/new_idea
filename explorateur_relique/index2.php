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
if( isset($_GET['orderby']) && in_array($_GET['orderby'], array('nom', 'date', 'taille', 'type')) )
	$orderby = $_GET['orderby'];
  
// par defaut, tri ascendant 
$order = 'asc';
if( isset($_GET['order']) && in_array($_GET['order'], array('asc', 'desc')) ) 
	$order = $_GET['order'];


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
	<meta charset="utf-8" />
	<title>Explorateur de fichiers PHP</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />	
	<meta name="robots" content="noindex,nofollow" />
	
	<!-- CSS -->
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
			$(".element2").hover( 
				function () { $(this).addClass("hover"); }, 
				function () { $(this).removeClass("hover"); }
			);

			$(".element2").click(function () { 
				$(".element2").removeClass("stay");
				$(this).addClass("stay");
			});
			$(".image").click(function () { 
				$(".element2").removeClass("stay");
				$(this).parent().addClass("stay");
			});
			$(".fichier").click(function () { 
				$(".element2").removeClass("stay");
				$(this).parent().addClass("stay");
			});			
			$(".inconnu").click(function () { 
				$(".element2").removeClass("stay");
				$(this).parent().addClass("stay");
			});

			$(".repertoire").dblclick(function(){		
				window.location.href = 'index2.php?p='+$(this).attr("id").slice(8);
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

	<header id="entete">
		<div class="contenu">
			<div id="logo">
				<img alt="logo" src="themes/original/images/logo.png" />
			</div><!-- fin logo -->
			<?php if(AUTHENTIFICATION == 'on') { ?>
			<div id="logout">
				<span style="color:#AAA;"><?php echo $_SESSION['auth'] ?></span>
				<a title="Se d&eacute;connecter" href="deconnexion.php" onclick="return confirm('Etes-vous sur de vous d\351connecter ?')"><img alt="se deconnecter" src="themes/original/images/logout.png" /></a></div><!--fin logout -->
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
						<li class="racine"><a href="index2.php"><img alt="racine" src="themes/original/images/repertoire.png" />racine</a></li>
						<?php $nb_elements = count(getBreadcrumb($repertoire_courant));
							if($nb_elements > 0) { ?>
								<li><img alt="&gt;" src="themes/original/images/arrow.gif" /></li>
							<?php } ?>						
					<?php }
					else {
						echo "<li><a href=\"index2.php?p=" , rawurlencode($p) , "&amp;orderby=nom&amp;order=". $order ."\">" , normalizeString($element) , "</a></li>";
						// s'il n'y a pas d'elements enfants, on n'affiche pas le marqueur
						if(hasChildren($p)) { ?>
							<li><img alt="&gt;" src="themes/original/images/arrow.gif" /></li>
						<?php }
					}
				} ?>
			</ul>
		</div><!-- fin contenu -->
	</nav>
	
	<div id="vues">
		<div class="contenu">
			<ul>
				<li><a href="index.php?p=<?php echo rawurlencode($repertoire_courant) ?>">Mosa&iuml;que</a></li>
				<li id="vue_courante"><a href="#">D&eacute;tail</a></li>
				<li><a href="index3.php?p=<?php echo rawurlencode($repertoire_courant) ?>">Grandes ic&ocirc;nes</a></li>
			</ul>
		</div><!-- fin contenu -->
	</div><!-- fin vues -->
	
	
	<div id="corps">
		
		<div class="contenu">		
		
			<div id="arborescence">
			
				<ul>
					<li class="racine"><a href="index2.php"><img alt="racine" src="themes/original/images/repertoire.png" />racine</a></li>
					<?php foreach(getDir(BASE) as $element) { ?>
						<li>
							<a href="index2.php?p=<?php echo rawurlencode(BASE . "/" . $element['nom']) ?>">
								<img alt="repertoire" src="themes/original/images/repertoire.png" />
								<span <?php echo rawurlencode(BASE . "/" . $element['nom']) == rawurlencode($repertoire_courant) ? 'style="font-weight:bold"' : '' ?> title="<?php echo normalizeString($element['nom']) ?>"><?php echo shortenString(normalizeString($element['nom']), 18) ?></span>
							</a>
						</li>
					<?php } ?>
				</ul>
			
			</div><!-- fin arborescence -->
	
	
			<div id="contenu_repertoire">
		
				<table id="tab_element2">
					<thead>
						<tr>
							<th style="width:49%;">
								<a href="index2.php?p=<?php echo rawurlencode($p) ?>&amp;orderby=nom&amp;order=<?php echo $order=='asc' ? 'desc' : ($order=='desc' ? 'asc' : 'asc') ?>">Nom</a>
								<a href="index2.php?p=<?php echo rawurlencode($p) ?>&amp;orderby=nom&amp;order=asc"><img alt="asc" src="themes/original/images/<?php echo $orderby=='nom' && $order=='asc' ? 'asc.png' : 'asc2.png' ?>" />
								<a href="index2.php?p=<?php echo rawurlencode($p) ?>&amp;orderby=nom&amp;order=desc"><img alt="desc" src="themes/original/images/<?php echo $orderby=='nom' && $order=='desc' ? 'desc.png' : 'desc2.png' ?>" />
							</th>
							<th style="width:20%;">
								<a href="index2.php?p=<?php echo rawurlencode($p) ?>&amp;orderby=type&amp;order=<?php echo $order=='asc' ? 'desc' : ($order=='desc' ? 'asc' : 'asc') ?>">Type</a>
								<a href="index2.php?p=<?php echo rawurlencode($p) ?>&amp;orderby=type&amp;order=asc"><img alt="asc" src="themes/original/images/<?php echo $orderby=='type' && $order=='asc' ? 'asc.png' :  'asc2.png'  ?>" />
								<a href="index2.php?p=<?php echo rawurlencode($p) ?>&amp;orderby=type&amp;order=desc"><img alt="desc" src="themes/original/images/<?php echo $orderby=='type' && $order=='desc' ? 'desc.png' :  'desc2.png' ?>" />
							</th>
							<th style="width:15%;">
								<a href="index2.php?p=<?php echo rawurlencode($p) ?>&amp;orderby=taille&amp;order=<?php echo $order=='asc' ? 'desc' : ($order=='desc' ? 'asc' : 'asc') ?>">Taille</a>
								<a href="index2.php?p=<?php echo rawurlencode($p) ?>&amp;orderby=taille&amp;order=asc"><img alt="asc" src="themes/original/images/<?php echo $orderby=='taille' && $order=='asc' ? 'asc.png' : 'asc2.png'  ?>" />
								<a href="index2.php?p=<?php echo rawurlencode($p) ?>&amp;orderby=taille&amp;order=desc"><img alt="desc" src="themes/original/images/<?php echo $orderby=='taille' && $order=='desc' ? 'desc.png' : 'desc2.png' ?>" />
							</th>
							<th style="width:16%;">
								<a href="index2.php?p=<?php echo rawurlencode($p) ?>&amp;orderby=date&amp;order=<?php echo $order=='asc' ? 'desc' : ($order=='desc' ? 'asc' : 'asc') ?>">Modifi&eacute; le</a>
								<a href="index2.php?p=<?php echo rawurlencode($p) ?>&amp;orderby=date&amp;order=asc"><img alt="asc" src="themes/original/images/<?php echo $orderby=='date' && $order=='asc' ? 'asc.png' : 'asc2.png' ?>" />
								<a href="index2.php?p=<?php echo rawurlencode($p) ?>&amp;orderby=date&amp;order=desc"><img alt="desc" src="themes/original/images/<?php echo $orderby=='date' && $order=='desc' ? 'desc.png' : 'desc2.png' ?>" />
							</th>
						</tr>
					</thead>
					
					<tbody>
						
					<?php 
					$contenu_repertoire = listDir($repertoire_courant);
					
					if( isset($contenu_repertoire) && !empty($contenu_repertoire) ){ ?>
						
							<?php foreach($contenu_repertoire as $element) { ?>
							
								<tr class="element2">
					
								<?php switch($element['type']) {
								
									case 'repertoire': ?>
										<td class="element2_1 repertoire" id="element_<?php echo rawurlencode($repertoire_courant."/".$element['nom']), "&amp;orderby=nom&amp;order=", $order ?>" title="<?php echo $element['nom.extension'] ?>">
											<img alt="repertoire" src="themes/original/images/24/repertoire.png" />
											<?php echo normalizeString($element['nom']) ?>
										</td>
										<td class="element2_2"><span>Dossier de fichiers</span></td>
										<td class="element2_3">&nbsp;</td>
										<td class="element2_4">&nbsp;</td>
										<?php break;
										
									case 'fichier':
										if( in_array(strtolower($element['extension']), $t_extensions_reconnues) ) { ?>
											
											<td class="element2_1 fichier" id="element_<?php echo rawurlencode($repertoire_courant."/".$element['nom.extension']), "&amp;orderby=nom&amp;order=", $order ?>" title="<?php echo $element['nom.extension'] ?>">
												<img alt="fichier" src="themes/original/images/24/<?php echo strtolower($element['extension']) ?>.png" />
												<?php echo shortenString(normalizeString($element['nom.extension']), 44) ?>
											</td>
											<td class="element2_2"><span><?php echo $t_extensions[strtolower($element['extension'])]?></span></td>
											<td class="element2_3"><?php echo formatSize($element['taille']) ?></td>
											<td class="element2_4"><?php echo date('d/m/Y', $element['date']) ?></td>
								
										
										<?php }
										else { ?>
										
											<td class="element2_1 inconnu" id="element_<?php echo rawurlencode($repertoire_courant."/".$element['nom.extension']), "&amp;orderby=nom&amp;order=", $order ?>" title="<?php echo $element['nom.extension'] ?>">
												<img alt="inconnu" src="themes/original/images/24/inconnu.png" />
												<?php echo shortenString(normalizeString($element['nom.extension']), 44) ?>
											</td>
											<td class="element2_2"><span>&nbsp;</span></td>
											<td class="element2_3"><?php echo formatSize($element['taille'])?></td>
											<td class="element2_4"><?php echo date('d/m/Y', $element['date']) ?></td>
								
										<?php }
										break;
										
									case 'image': ?>
									
										<td class="element2_1 image" id="element_<?php echo rawurlencode($repertoire_courant."/".$element['nom.extension']), "&amp;orderby=nom&amp;order=", $order ?>" title="<?php echo $element['nom.extension'] ?>">
											<img alt="image" src="themes/original/images/24/<?php echo strtolower($element['extension']) ?>.png" />
											<?php echo shortenString(normalizeString($element['nom.extension']), 44) ?>
										</td>
										<td class="element2_2"><span><?php echo $t_extensions[strtolower($element['extension'])]?></span></td>
										<td class="element2_3"><?php echo formatSize($element['taille'])?></td>
										<td class="element2_4"><?php echo date('d/m/Y', $element['date']) ?></td>
										<?php break;			
							
								} ?>
								
								</tr>
							
							<?php } ?>
						
						<?php }
						else { ?>
						
							<tr><td><img id="info" alt="[i]" src="themes/original/images/info.png" />R&eacute;pertoire vide</td></tr>
							
						<?php } ?>
						
						</tbody>
						
						<tfoot></tfoot>
					
					</table>

					
				
			
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