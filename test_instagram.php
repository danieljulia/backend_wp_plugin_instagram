<?php

//mostrar errors
error_reporting(E_ALL);
ini_set('display_errors', 1);


//configuration data
require "config.php"; 
require "instagram-api.php";


$username="instagram";

?>
<!doctype html>
<html>
<meta charset="utf-8">
<body>
<h1>Fotos per l'usuari <?php print $username?></h1>

<?php
$fotos=instagram_get_photos($username);
?>

<?php
foreach($fotos->data as $foto):
?>
<li>
<a href='<?php print $foto->link?>'>
<img src='<?php print $foto->images->thumbnail->url?>'>
</a>
<!-- print_r($foto) -->
<?php print $foto->caption->text?>
</li>
<?php
endforeach;
?>



</body>
</html>