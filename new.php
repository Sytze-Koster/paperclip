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
// maakt een nieuwe record aan
function renderForm($first, $last, $name_prefix, $error)
{
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
			<title>Nieuw Persoon</title>
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

			<form action="" method="post">
				<div>
					<strong>Voornaam: *</strong> <input type="text" name="name_first" value="<?php echo $name_first; ?>" /><br/>
					<strong>tussenvoegsel: </strong> <input type="text" name="name_prefix" value="<?php echo $name_prefix; ?>" /><br/>
					<strong>Achternaam: *</strong> <input type="text" name="name_last" value="<?php echo $name_last; ?>" /><br/>
					<strong>Telefoonnummer: </strong> <input type="text" name="telefoon" value="<?php echo $telephone; ?>" /><br/>
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

// kijkt of er geklikt is op submit
if (isset($_POST['submit'])) { 

// kijkt naar de data, en checkt op valide
	$name_first = mysql_real_escape_string(htmlspecialchars($_POST['name_first']));
	$name_prefix = mysql_real_escape_string(htmlspecialchars($_POST['name_prefix']));
	$name_last = mysql_real_escape_string(htmlspecialchars($_POST['name_last']));
	$telephone = mysql_real_escape_string(htmlspecialchars($_POST['telephone']));
	$email = mysql_real_escape_string(htmlspecialchars($_POST['email']));

		// kijkt of bij de zijn ingevuld
		if ($name_first == '' || $name_last == ''){
			// error display
			$error = 'ERROR: Please fill in all required fields!';

			renderForm($id, $name_first, $name_prefix, $name_last, $telephone, $email, $error);
		}
	else {
		// database saver
		mysql_query("INSERT person SET name_first='$name_first', name_prefix='$name_prefix', name_last='$name_last', telephone='$telephone', email='$email'")
		or die(mysql_error());

		// Als opgeslagen is terug naar view page
		header("Location: view.php");
	}
}
else {
	// als niet is opgeslagen terug naar new.php
	renderForm('','','','','');
}
?> 