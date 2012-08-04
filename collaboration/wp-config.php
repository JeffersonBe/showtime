<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/Editing_wp-config.php Modifier
 * wp-config.php} (en anglais). C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'db418345342');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'dbo418345342');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', '972showtime972');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'db418345342.db.1and1.com');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8');

/** Type de collation de la base de données.
  * N'y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '/3n@Ox}e vi0;|m}vEt>|.XgpV<!wM&O?/Oya89PlLP|UDfslOb-X7&A 2eHCxB?');
define('SECURE_AUTH_KEY',  'V^,4,R;-,*EhyNG)%~-Rb@D<2dxA_O-w++UVyTPHr&!wr[l>{]_f-pN%6PqH:6Cp');
define('LOGGED_IN_KEY',    'hbol|eAc3801d4-&e{`,KBE+3i@~|ybH.)g-j(FWB:8O+uFgTQO*5vJ5z?--Uu5R');
define('NONCE_KEY',        'ajh>+_*<rldZ9F?W;D*+:-7]@DcB=&Fq3Qjpn~8RF$~M-jE]$,o:WK]*/UWdD[w;');
define('AUTH_SALT',        'O;HYN-=Ta1*&+%Q(Cz E`H+di5WauO||VT=VJSR*.+131<Lt(G~KX=6V8284pto_');
define('SECURE_AUTH_SALT', 'y7J@q*V84jw4JAs+Lfy*Q@KWA@!fY-Io,fOhl}`jEtCNQ*Ie[uo[Z%n:jR5hs2^+');
define('LOGGED_IN_SALT',   ',TY[OkT(ufIWO.-bn3Z!92vN2tZ X:&1N+m>:QE=[ujA!2)g.)u>d=_u^>9dO2@n');
define('NONCE_SALT',       '&$j0W_)cPb[RuYz@SR8;crDk:YK|()I@qO=0%f(;L/||4XRlhFTg&6wuBG=?|9#+');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/**
 * Langue de localisation de WordPress, par défaut en Anglais.
 *
 * Modifiez cette valeur pour localiser WordPress. Un fichier MO correspondant
 * au langage choisi doit être installé dans le dossier wp-content/languages.
 * Par exemple, pour mettre en place une traduction française, mettez le fichier
 * fr_FR.mo dans wp-content/languages, et réglez l'option ci-dessous à "fr_FR".
 */
define('WPLANG', 'fr_FR');

/**
 * Pour les développeurs : le mode deboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant votre essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 */
define('WP_DEBUG', false);

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');