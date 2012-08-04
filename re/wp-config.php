<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

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
define('DB_NAME', 'db410959788');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'dbo410959788');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'onvagagner');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'db410959788.db.1and1.com');

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
define('AUTH_KEY',         '8M+u]3{;r;.#]t18o+<jxC7!^#xDWFp0[MK1xaXcaQ`FqAQV7#w@a0ie$Ka<.ZI['); 
define('SECURE_AUTH_KEY',  '<*d{_ WGS9~I%[TAtuo;ZW$V?[aOE>z}YJ5|:D6rh543U{K0Cs.Fw-+];n+AQ4rM'); 
define('LOGGED_IN_KEY',    'Xh,K9kYW?Q()rqHb4#-Y+Q6 E?4Djf=>=:joN/kq&mt0Qp9e/td:2F~pXXox/Yx='); 
define('NONCE_KEY',        '=j2WJ1}bP(.a&|aE~MD/,`;Z+F(>nV$IbVLP!G-&cjh<`-mX.ma OHFJt[-wiT:M'); 
define('AUTH_SALT',        '&=&hu>v3-)_Ka&|Miy_yxJ?[9tgs4.{X &COk-WB5<lZ-Q|}M_9f!UmQ_I,GE;Y0'); 
define('SECURE_AUTH_SALT', 'Iu{afz es^rTprG/FI<UH7O+P%khQU!6JS)5>toMosN;OVvX+1~|LDMO-;?#m`4}'); 
define('LOGGED_IN_SALT',   'C_E|Z@=%zL+bn27=A!gXP^%-0~XM=%LI#TWS=EZYa{1uHne&<$~d.PsCIjLQ,^#d'); 
define('NONCE_SALT',       '}/rafMyBf_%:ge.LGKnt/)Y[jLAKj60pBo~G|oXhR-aZK20nR9HDa8*+!b+hy(}z'); 
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