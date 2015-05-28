<?php
// Start alle openstaande sessies van andere pagina's
session_start();
//kijk of de sessies niet bestaan dat betekend dat je niet bent ingelogd dus wordt je terug gestuurd naar index.php
if(!isset($_SESSION['myusername']) || !isset($_SESSION['mypassword'])){
	header("location:index.php");
}

//Dit is de code voor het automatisch laten verlopen van de release. Hij kijkt naar de tijd die huidig is en de tijd waarop 
//de session ['last_acted_on'] is aangemaakt. als dit langer dan 60*2 (2 minuten) is zal hij de sessie laten verwijderen.
//Is dit binnen de 2 minuten dan zet hij de sessie tijd van ['last_acted_on'] op de huidige tijd zodat deze weer later opnieuw
//na getrokken kan worden
if( isset($_SESSION['last_acted_on']) && (time() - $_SESSION['last_acted_on'] > 60*2) ){
	session_unset();
	session_destroy();
	header("location:index.php");
}
else{
	session_regenerate_id(true);
	$_SESSION['last_acted_on'] = time();
}

// connect naar de database
include('connectcheck.php');

// kijkt op validitijd
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
	// of id valide is
	$id = $_GET['id'];

	// mysql_query die de rij met huidige id ($id) verwijderd uit de tabel person
	$result = mysql_query("DELETE FROM person WHERE id=$id")
	or die(mysql_error()); 

	// gaat terug naar view.php
	header("Location: view.php");
}
else {
	// als niets is gebeurd ga dan ook terug naar view.php
	header("Location: view.php");
}

?>