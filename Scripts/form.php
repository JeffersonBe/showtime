<?php
// Couleur du texte des champs si erreur saisie utilisateur
$color_font_warn="#f52985";
// Couleur de fond des champs si erreur saisie utilisateur
$color_form_warn="#FFCC66";
// Ne rien modifier ci-dessous si vous n’êtes pas certain de ce que vous faites !
$list['f_7']=array("S&eacute;lectionnez","à la Française"," à l'Anglaise"," à l'Am&eacute;ricaine"," à l'Asiatique");
$list['f_8']=array("S&eacute;lectionnez","Showtimer"," Showtimeuse");
if(isset($_POST['submit'])){
	$erreur="";
	// Nettoyage des entr&eacute;es
	while(list($var,$val)=each($_POST)){
	if(!is_array($val)){
		$$var=strip_tags($val);
	}else{
		while(list($arvar,$arval)=each($val)){
				$$var[$arvar]=strip_tags($arval);
			}
		}
	}
	// Formatage des entr&eacute;es
	$f_1=trim(ucwords(eregi_replace("[^a-zA-Z0-9&eacute;èàäö\ -]", "", $f_1)));
	$f_2=trim(ucwords(eregi_replace("[^a-zA-Z0-9&eacute;èàäö\ -]", "", $f_2)));
	$f_3=trim(ucwords(eregi_replace("[^a-zA-Z0-9&eacute;èàäö\ -]", "", $f_3)));
	$f_4=trim(ucwords(eregi_replace("[^a-zA-Z0-9&eacute;èàäö\ -]", "", $f_4)));
	$f_5=trim(eregi_replace("[^0-9\ +]", "", $f_5));
	$f_6=strip_tags(trim($f_6));
	// Verification des champs
	if(strlen($f_1)<2){
		$erreur.="<li><span class='txterror'>Le champ &laquo; Nom &raquo; est vide ou incomplet.</span>";
		$errf_1=1;
	}
	if(strlen($f_2)<2){
		$erreur.="<li><span class='txterror'>Le champ &laquo; Pr&eacute;nom &raquo; est vide ou incomplet.</span>";
		$errf_2=1;
	}
	if(strlen($f_3)<2){
		$erreur.="<li><span class='txterror'>Le champ &laquo; Batiment &raquo; est vide ou incomplet.</span>";
		$errf_3=1;
	}
	if(strlen($f_4)<2){
		$erreur.="<li><span class='txterror'>Le champ &laquo; N° Chambre &raquo; est vide ou incomplet.</span>";
		$errf_4=1;
	}
	if(strlen($f_5)<2){
		$erreur.="<li><span class='txterror'>Le champ &laquo; T&eacute;l&eacute;phone &raquo; est vide ou incomplet.</span>";
		$errf_5=1;
	}
	if(strlen($f_6)<2){
		$erreur.="<li><span class='txterror'>Le champ &laquo; Email &raquo; est vide ou incomplet.</span>";
		$errf_6=1;
	}else{
		if(!ereg('^[-!#$%&\'*+\./0-9=?A-Z^_`a-z{|}~]+'.
		'@'.
		'[-!#$%&\'*+\/0-9=?A-Z^_`a-z{|}~]+\.'.
		'[-!#$%&\'*+\./0-9=?A-Z^_`a-z{|}~]+$',
		$f_6)){
			$erreur.="<li><span class='txterror'>La syntaxe de votre adresse e-mail n'est pas correcte.</span>";
			$errf_6=1;
		}
	}
	if($f_7==0){
		$erreur.="<li><span class='txterror'>Le champ &laquo; Petit d&eacute;jeuner &raquo; n'a pas &eacute;t&eacute; d&eacute;fini.</span>";
		$errf_7=1;
	}
	if($f_8==0){
		$erreur.="<li><span class='txterror'>Le champ &laquo; Livraison par &raquo; n'a pas &eacute;t&eacute; d&eacute;fini.</span>";
		$errf_8=1;
	}
	if($erreur==""){
		// Cr&eacute;ation du message
		$titre="Message de votre site";
		$tete="From:Site@Showtime2012.fr\n";
		$corps.="Nom : ".$f_1."\n";
		$corps.="Pr&eacute;nom : ".$f_2."\n";
		$corps.="Batiment : ".$f_3."\n";
		$corps.="N° Chambre : ".$f_4."\n";
		$corps.="T&eacute;l&eacute;phone : ".$f_5."\n";
		$corps.="Email : ".$f_6."\n";
		$corps.="Petit d&eacute;jeuner : ".$list['f_7'][$f_7]."\n";
		$corps.="Livraison par : ".$list['f_8'][$f_8]."\n";
		$corps.="Message : ".$f_9."\n";
		if(mail("pdj@showtime2012.fr", $titre, stripslashes($corps), $tete)){
			$ok_mail="true";
		}else{
			$erreur.="<li><span class='txterror'>Une erreur est survenue lors de l'envoi du message, veuillez refaire une tentative.</span>";
		}
	}
}
?>