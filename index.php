<?php
// Start alle openstaande sessies van andere pagina's
session_start();
//kijk of de sessies bestaan als je bent ingelogd
if(isset($_SESSION['myusername']) || isset($_SESSION['mypassword'])){
	header("location:view.php");
}
include 'header.php';

				// mysql_query om de gegevens optehalen.
				$result = mysql_query("SELECT * FROM person") 
					or die(mysql_error());  
					
				// laten zien in table
				
				echo "<table border='1' cellpadding='5'>";
				echo "<tr> <th>Voornaam</th> <th>tussenvoegsel</th> <th>Achternaam</th> <th>Telefoon</th><th>Email</tr>";

				// loop van resultaten
				while($row = mysql_fetch_array( $result )) {
					
					// laten zien in table
					echo "<tr>";
					echo '<td>' . $row['name_first'] . '</td>';
					echo '<td>' . $row['name_prefix'] . '</td>';
					echo '<td>' . $row['name_last'] . '</td>';
					echo '<td>' . $row['telephone'] . '</td>'; 
					echo '<td>' . $row['email'] . '</td>';
					echo "</tr>"; 
				} 

				// sluit table>
				echo "</table>";
			?>
			<p><a href="login.php">Inloggen</a></p>
			<p><a href="contact.php">Contact</a></p>
		</div>
	</body>
</html> 