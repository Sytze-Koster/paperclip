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

// Funtion die aangeroepen wordt als die nodig is.
function renderForm($id, $name_first, $name_prefix, $name_last, $telephone, $email, $error)
{
?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="utf-8">
		<title>Edit Record</title>
		<link rel="stylesheet" type="text/css" href="general.css">
	</head>
	<body>
		<div id="container">
			<?php 
			// error laten zien
			
				if ($error != ''){
					echo '<div style="padding:4px; border:1px solid red; color:red;">'.$error.'</div>';
				}
			?>
			<!-- toon het formulier -->
			<form action="" method="post">
				<input type="hidden" name="id" value="<?php echo $id; ?>"/>
				<div>
					<p><strong>ID:</strong> <?php echo $id; ?></p>
					<strong>Voornaam: *</strong> <input type="text" name="name_first" value="<?php echo $name_first; ?>" /><br/>
					<strong>tussenvoegsel: </strong> <input type="text" name="name_prefix" value="<?php echo $name_prefix; ?>" /><br/>
					<strong>Achternaam: *</strong> <input type="text" name="name_last" value="<?php echo $name_last; ?>" /><br/>
					<strong>Telefoonnummer: </strong> <input type="text" name="telephone" value="<?php echo $telephone; ?>" /><br/>
					<strong>Email: </strong> <input type="text" name="email" value="<?php echo $email; ?>" /><br/>
					<p>* Verplicht</p>
					<input type="submit" name="submit" value="Submit">
				</div>
			</form>
		</div>
	</body>
</html> 
<?php
}

// connect naar de database
include('connectcheck.php');

// checkt of er is gesubmit
if (isset($_POST['submit'])){ 
// checht of id is valide
	if (is_numeric($_POST['id'])){
		// display data en checkt op valide en om deze goed optehalen en in de nieuwe variable neer te zetten
		$id = $_POST['id'];
		$name_first = mysql_real_escape_string(htmlspecialchars($_POST['name_first']));
		$name_prefix = mysql_real_escape_string(htmlspecialchars($_POST['name_prefix']));
		$name_last = mysql_real_escape_string(htmlspecialchars($_POST['name_last']));
		$telephone = mysql_real_escape_string(htmlspecialchars($_POST['telephone']));
		$email = mysql_real_escape_string(htmlspecialchars($_POST['email']));

		// kijkt of de verplichte velden niet leeg zijn
		if ($name_first == '' || $name_last == ''){
			// error weergave
			$error = 'ERROR: Please fill in all required fields!';

			renderForm($id, $name_first, $name_prefix, $name_last, $telephone, $email, $error);
		}
		else {
			// mysql_query om de nieuwe gegevens te updaten.
			mysql_query("UPDATE person SET name_first='$name_first', name_prefix='$name_prefix', name_last='$name_last', telephone='$telephone', email='$email' WHERE id='$id'")
			or die(mysql_error()); 

			// als is opgeslagen terug naar view.php
			header("Location: view.php"); 
		}
	}
	else {
	// Toon een error als er ergens iets fout gaat bijvoorbeeld als het id niet nummeriek is
	echo 'Error!';
	}
}

else {
	// checkt of id bestaat en of het niet lager dan 0 is.
	if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
		// mysql_query om de gegevens optehalen.
		$id = $_GET['id'];
		$result = mysql_query("SELECT * FROM person WHERE id=$id")
		or die(mysql_error()); 
		$row = mysql_fetch_array($result);

		// check of het id klopt met de database
		if($row) {
			// Haal de gegevens uit de database.
			$name_first = $row['name_first'];
			$name_last = $row['name_last'];
			$name_prefix = $row['name_prefix'];
			$telephone = $row['telephone'];
			$email = $row['email'];

			// toon in het formulier
			renderForm($id, $name_first, $name_prefix, $name_last, $telephone, $email, '');
		}
		else {
			// als er geen match is dislay:
			echo "No results!";
		}
	}
	else {
		// als het niet valide is display error
		echo 'Error!';
	}
}
?>