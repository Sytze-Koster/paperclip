<?php
// Start alle openstaande sessies van andere pagina's
session_start();

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

//kijkt of de submit button is ingedrukt
if($_POST["submit"]) {

	//zet variablen
	$recipient="me@sytzekoster.nl";
	$subject="Form to email message";
	$sender=$_POST["sender"];
	$senderEmail=$_POST["senderEmail"];
	$message=$_POST["message"];

	$mailBody="Name: $sender\nEmail: $senderEmail\n\n$message";

	//php mail funtion die daadwerkelijk ook de mail verstuurd
	mail($recipient, $subject, $mailBody, "From: $sender <$senderEmail>");

	//bedankt bericht die in beeld komt als er op submit gedruikt is
	$thankYou="<p>Thank you! Your message has been sent.</p>";
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Contact me</title>
		<link rel="stylesheet" type="text/css" href="general.css">
	</head>

	<body>
		<div id="container">
			<?php echo $thankYou; ?>
			<form method="post" action="contact.php">
				<label>Name:</label>
				<input name="sender">

				<label>Email address:</label>
				<input name="senderEmail">

				<label>Message:</label>
				<textarea rows="5" cols="20" name="message"></textarea>

				<input type="submit" name="submit">
			</form>
			<p><a href="view.php">Overzichts pagina</a></p>
		</div>
	</body>
</html>