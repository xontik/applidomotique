<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=360px, initial-scale=1.0">
  <title>Connexion</title>
  
</head>
<body>

<form method="post" action="<?php echo site_url("core/connect");?>">
    <fieldset>
    <legend>Connexion via <?php echo $_SERVER["REMOTE_ADDR"]; ?></legend>
    <p>
    <label for="login">Pseudo :</label><input name="login" type="text" id="login" /><br />
    <label for="psw">Mot de Passe :</label><input type="password" name="psw" id="psw" />
    </p>
    </fieldset>
    <p><input type="submit" value="Connexion" /></p></form>
    </body><html>