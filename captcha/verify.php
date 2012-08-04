 <?php
  require_once('../includes/recaptchalib.php');
  $privatekey = "6LeQFM0SAAAAAJdk307jCnNELly6ktas8QTwXcL3";
  $resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

  if (!$resp->is_valid) {
    // What happens when the CAPTCHA was entered incorrectly
    die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
         "(reCAPTCHA said: " . $resp->error . ")");
  } else {
    <?php
    // Couleur du texte des champs si erreur saisie utilisateur
    $color_font_warn="#FF0000";
    // Couleur de fond des champs si erreur saisie utilisateur
    $color_form_warn="#FFCC66";
    // Ne rien modifier ci-dessous si vous n’êtes pas certain de ce que vous faites !
    $list['f_6']=array("Sélectionnez","3h-4h"," 4h-5h"," 5h-6h"," 6h-7h"," 7h-8h"," 8h-9h"," 9h-10h"," 10h-11h"," 11h-12h"," 12h-13h"," 13h-14h");
    $list['f_8']=array("Sélectionnez","telecom-em.eu"," it-sudparis.eu");
    $list['f_9']=array("Sélectionnez","A la française avec croissant"," A la française avec pain au chocolat"," A l'Américaine");
    if(isset($_POST['submit'])){
    	$erreur="";
    	// Nettoyage des entrées
    	while(list($var,$val)=each($_POST)){
    	if(!is_array($val)){
    		$$var=strip_tags($val);
    	}else{
    		while(list($arvar,$arval)=each($val)){
    				$$var[$arvar]=strip_tags($arval);
    			}
    		}
    	}
    	// Formatage des entrées
    	$f_1=trim(ucwords(eregi_replace("[^a-zA-Z0-9éèàäö\ -]", "", $f_1)));
    	$f_2=trim(ucwords(eregi_replace("[^a-zA-Z0-9éèàäö\ -]", "", $f_2)));
    	$f_3=trim(ucwords(eregi_replace("[^a-zA-Z0-9éèàäö\ -]", "", $f_3)));
    	$f_4=trim(ucwords(eregi_replace("[^a-zA-Z0-9éèàäö\ -]", "", $f_4)));
    	$f_5=trim(eregi_replace("[^0-9\ +]", "", $f_5));
    	$f_7=trim(ucwords(eregi_replace("[^a-z0-9._ -]", "", $f_7)));
    	// Verification des champs
    	if(strlen($f_1)<2){
    		$erreur.="<li><span class='txterror'>Le champ &laquo; Nom &raquo; est vide ou incomplet.</span>";
    		$errf_1=1;
    	}
    	if(strlen($f_2)<2){
    		$erreur.="<li><span class='txterror'>Le champ &laquo; Prénom &raquo; est vide ou incomplet.</span>";
    		$errf_2=1;
    	}
    	if(strlen($f_3)<2){
    		$erreur.="<li><span class='txterror'>Le champ &laquo; Bâtiment/adresse &raquo; est vide ou incomplet.</span>";
    		$errf_3=1;
    	}
    	if(strlen($f_5)<2){
    		$erreur.="<li><span class='txterror'>Le champ &laquo; N° de Téléphone &raquo; est vide ou incomplet.</span>";
    		$errf_5=1;
    	}
    	if($f_6==0){
    		$erreur.="<li><span class='txterror'>Le champ &laquo; Créneau de livraison &raquo; n'a pas été défini.</span>";
    		$errf_6=1;
    	}
    	if(strlen($f_7)<2){
    		$erreur.="<li><span class='txterror'>Le champ &laquo; Entrez votre adresse mail &raquo; est vide ou incomplet.</span>";
    		$errf_7=1;
    	}
    	if($f_8==0){
    		$erreur.="<li><span class='txterror'>Le champ &laquo; Sélectionnez &raquo; n'a pas été défini.</span>";
    		$errf_8=1;
    	}
    	if($f_9==0){
    		$erreur.="<li><span class='txterror'>Le champ &laquo; Menu &raquo; n'a pas été défini.</span>";
    		$errf_9=1;
    	}
    	if($erreur==""){
    		// Création du message
    		$titre="Commande de ton petit-dejeuner Showtime";
    		$tete="From:petit-dejeuner@Showtime2012.fr\n";
    		$corps.="Bonjour,\n Tu viens de commander un petit-déjeuner pour vendredi matin, tu peux modifier une dernière fois ta commande au besoin, sinon répond nous juste un ok avec ta commande.\n A vendredi!\n\n L'équipe Showtime\n\n ";
    		
    		$corps.="Commande définitive:\n";		
    		$corps.="Nom : ".$f_1."\n";
    		$corps.="Prénom : ".$f_2."\n";
    		$corps.="Bâtiment/adresse : ".$f_3."\n";
    		$corps.="Numéro de Chambre si Maisel : ".$f_4."\n";
    		$corps.="N° de Téléphone : ".$f_5."\n";
    		$corps.="Créneau de livraison : ".$list['f_6'][$f_6]."\n";
    		$corps.="Menu : ".$list['f_9'][$f_9]."\n";
    		$corps.="Remarques / Autres commandes : : ".$f_10."\n";
    		if(mail($f_7."@".$list['f_8'][$f_8], $titre, stripslashes($corps), $tete)){
    			$ok_mail="true";
    		}else{
    			$erreur.="<li><span class='txterror'>Une erreur est survenue lors de l'envoi du message, veuillez refaire une tentative.</span>";
    		}
    	}
    }
    // Your code here to handle a successful verification
  }
  ?>