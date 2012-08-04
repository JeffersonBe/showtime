<form method="post" action="ptitdej/creat_commande.php">
	<p>
	<label>Nom : </label>
	<input type="text" maxlength="40" id="nom" name="nom" size="40"/>
	</p>
	<p>
	<label>Prénom : </label>
	<input type="text" maxlength="40" id="prenom" name="prenom" size="40"/>
	</p>
	<p>
	<label>E-mail INT : </label>
	<input type="text" maxlength="45" size="40" name="mail"/>
	</p>
	<p>
	<label>Tél. : </label>
	<input type="text" maxlength="40" name="tel" size="40" />
	</p>
	<p>
	<label>Adresse ou numéro de chambre :</label>
	<textarea name="adresse" rows="5" cols="45" onkeyup="limite(this, 'textadresse');" onkeydown="limite(this, 'textadresse');"></textarea>
	</p>
	<p>Menu choisi : <select name="menu"><option value="americain">Menu américain</option><option value="francais">Menu français</option></select></p>
	<p><br/></p>
	<label>Message : <span id="textextras">(1200 caractères max.)</span></label>
		<textarea name="extras" rows="5" cols="45"></textarea>
	</p>
	
	<div id="recaptcha_widget">
	<label>Vérification : recopiez les deux mots<br/></label>
	<?php
		require_once('ptitdej/recaptchalib.php');
		$publickey = "6LfyIc0SAAAAAAkcLEZ9137mQtP2hMOKyK5fCI0b";
		echo '<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k='.$publickey.'"></script>';
		echo '</div>';
	?>	
	<input type="submit" value="Enregistrer la commande" />
</form>