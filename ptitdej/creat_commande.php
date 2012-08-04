<?php require("fonctions.php");

// validation captcha
require_once('recaptchalib.php');
$privatekey = "6LfyIc0SAAAAAKf8zktQQ9pz8St8p4c8O5KgAyjK";
$resp = recaptcha_check_answer ($privatekey,
							$_SERVER["REMOTE_ADDR"],
							$_POST["recaptcha_challenge_field"],
							$_POST["recaptcha_response_field"]);

if ($resp->is_valid) {
// Bon captcha, on continue...

// on controle maintenant le contenu des variables transmises
$gros_inputs = array("recaptcha_challenge_field", "recaptcha_response_field", "adresse", "extras");
foreach($_POST as $element_input => $valeur)
{
	if((strlen($valeur) > 50 AND !in_array($element_input, $gros_inputs)) OR strlen($valeur) > 1250)
	{
		die("Trop de caractères utilisés dans un des champs" . "<br><a href='http://www.showtime2012.com/pdj.php'>Retour</a>");		
	}
}
// nettoyage de $_POST :
$post = array();
while (list($key, $val) = each($_POST)){
	$post["$key"] = utf8_decode(stripslashes($val));
}

// test si le mail est valide
if(!mailValide($post['mail']) OR !mailINT($post['mail']))
{
	die("ce mail est invalide" . "<br><a href='http://www.showtime2012.com/pdj.php'>Retour</a>");
}

// différents tests de l'user input
if($post["menu"] != "francais" && $post["menu"] != "americain")
	die("Le menu choisi est invalide" . "<br><a href='http://www.showtime2012.com/pdj.php'>Retour</a>");

$demande = "";
$demande .= "<br>\n" . "Nom : ".$post["nom"];
$demande .= "<br>\n" . "Prenom : ".$post["prenom"];
$demande .= "<br>\n" . "Mail : ".$post["mail"];
$demande .= "<br>\n" . "Tel : ".$post["tel"];
$demande .= "<br>\n" . "Adresse/chambre : ".$post["adresse"];
$demande .= "<br>\n" . "Menu : ".$post["menu"];
$demande .= "<br>\n" . "Message : ".$post["message"];

echo "Votre commande a bien été prise en compte.<br><a href='/'>Retour au site</a>";


	
envoyerMail($demande);

$_SESSION['message'] = 'attente_confirmation';

} else {
// Mauvais Captcha
die("Le captcha a mal été rempli");
}


function envoyerMail($demande)
{
	$mail = 'petit-dejeuner@showtime2012.fr'; // Déclaration de l'adresse de destination.
	$passage_ligne = "\r\n";
	
	//=====Déclaration des messages au format texte et au format HTML.
	$message_html = "<html><head></head><body>
Une commande a été faite.<br>\n
Voici ce qui a été demandé :<br>\n
$demande
</body></html>";
	
	$message_txt = strip_tags($message_html);
	//==========
	 
	//=====Création de la boundary
	$boundary = "-----=".md5(rand());
	//==========
	 
	//=====Définition du sujet.
	$sujet = "Une commande a ete effectuee sur le site";
	//=========
	 
	//=====Création du header de l'e-mail.
	$header = "From: \"Showtime!\"<petit-dejeuner@showtime2012.fr>".$passage_ligne;
	$header.= "Reply-to: \"Showtime!\" <ptitdej@wakup2011.com>".$passage_ligne;
	$header.= "MIME-Version: 1.0".$passage_ligne;
	$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
	//==========
	 
	//=====Création du message.
	$message = $passage_ligne.$boundary.$passage_ligne;
	//=====Ajout du message au format texte.
	$message.= "Content-Type: text/plain; charset=\"UTF-8\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$message_txt.$passage_ligne;
	//==========
	$message.= $passage_ligne."--".$boundary.$passage_ligne;
	//=====Ajout du message au format HTML
	$message.= "Content-Type: text/html; charset=\"UTF-8\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$message_html.$passage_ligne;
	//==========
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	//==========
	 
	//=====Envoi de l'e-mail.
	mail($mail,$sujet,$message,$header);
	//==========
}

?>