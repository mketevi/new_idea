<?php
define("INCLUDE_CHECK", true);
require "../config/connexion_db.php";
require "../config/fonction.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <!-- En-tête de la page -->
        <meta charset="utf-8" />
        <title>Rejoignez le club Afrique de Telecom Bretagne</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
        <script type='text/javascript' src='./js/jquery.min.js'></script>
    </head>

    <body >
      <header class="header">
        <div class="header_content">
          <div class ="header_logo">Africa</div><div class ="header_logo" id="logo2">TB</div>
          <div class="header_icon"></div>
              <nav class="menu">
                <div class="menu_logo"><a href="#">Gallerie</a></div>
                <div class="menu_logo"><a href="#">Anciens</a></div>
                <div class="menu_logo"><a href="./explorateur_relique">Reliques</a></div>
                <div class="menu_logo"><a href="#">Contacts</a></div>
              </nav>
        </div>
      </header>

        <div class="momoglobal-container">
          <form class="adminform22">
            <input type="text" class="nameam" name="nameam" placeholder="adminname">
            <input type="text" class="prenomam" name="prenomam" placeholder="password">
            
            <div class="select">
                <select name="role_admin">
                    <?php $query = "SELECT * FROM club_school_admins"; 
                          $resultat = mysql_query($query);
                          echo $resultat;
                          while ($categorie = mysql_fetch_array($resultat)) { 
                                echo '<option value="' . $categorie['role_admin'] . '</option>' . "\n"; } ?> 
                 </select>
              </div>

            <input type="submit" class="button" value="soumis" placeholder="ok">            
          </form>
            
        </div>
    </body>
</html>