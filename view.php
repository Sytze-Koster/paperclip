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
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>personen</title>
		<link rel="stylesheet" type="text/css" href="general.css">
	</head>
	<body>
		<div id="container">
			<?php


				// connect naar de database
				include('connectcheck.php');

				// mysql_query om de gegevens optehalen.
				$result = mysql_query("SELECT * FROM person") 
					or die(mysql_error());  
					
				// laten zien in table
				
				echo "<table border='1' cellpadding='5'>";
				echo "<tr> <th>Voornaam</th> <th>tussenvoegsel</th> <th>Achternaam</th> <th>Telefoon</th><th>Email</tr>";

				// loop van database gegevens
				while($row = mysql_fetch_array( $result )) {
					
					// laat de gegevens uit de database zien in een table
					echo "<tr>";
					echo '<td>' . $row['name_first'] . '</td>';
					echo '<td>' . $row['name_prefix'] . '</td>';
					echo '<td>' . $row['name_last'] . '</td>';
					echo '<td>' . $row['telephone'] . '</td>'; 
					echo '<td>' . $row['email'] . '</td>';
					echo '<td><a href="edit.php?id=' . $row['id'] . '">Wijzig</a></td>';
					echo '<td><a href="delete.php?id=' . $row['id'] . '">Verwijder</a></td>';
					echo "</tr>"; 
				}

				// sluit table>
				echo "</table>";
			?>
			<p><a href="new.php">Nieuw persoon toevoegen</a></p>
			<p><a href="logout.php">Logout</a></p>
			<p><a href="editProfile.php">Mijn Profiel</a></p>
			<p><a href="contact.php">Contact de beheerder</a></p>
		</div>
	</body>
</html> 