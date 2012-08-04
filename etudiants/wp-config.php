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
define('WP_CACHE', true); //Added by WP-Cache Manager
define('DB_NAME', 'db411075213');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'dbo411075213');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', '972showtime972');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'db411075213.db.1and1.com');

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
define('AUTH_KEY',         'S`-B=X3R$1-yqbuaqk=zhT-s/@POuKm)MtznzhS:mq[oHb cS[:+1|$/&od9`.3t');
define('SECURE_AUTH_KEY',  '+FSf&1`5%SZGsoAdL>!e|-9Y+hZ6|+Gx<e.;o0a{Du=S8GiKi3KrR#-xDt+!g+u4');
define('LOGGED_IN_KEY',    'ryxt#!zCAzYFhtkSaEoG{}||W0+5a$!t5 iI?+JY+1J4-U?TUI+L6Bf!7RV%1^Z`');
define('NONCE_KEY',        'WyiP%-EN[W[ xGB3MiV<ydDCL3?KLqa+2C?@Tn;2dml7A>(_oiPu.*fR+-8bXo$j');
define('AUTH_SALT',        '{Z/ ^[hVzsJi9 gme4>$GGWxeT{4r0/;duj2uvn0hs#C>n@(jO*?egw)ST<[&v++');
define('SECURE_AUTH_SALT', '9d6 xi&6+%MhcY{==p:|alQ/S>1-h^4+K+$mtK~+!ZQ[:9r](@U.5JbV;;v[|WT-');
define('LOGGED_IN_SALT',   'J</Tb|r&Q#^W8fjK@zswNy(9Meg_oSO]Va[dK3X7VouIml<n+vSR(l71$-!4>&eY');
define('NONCE_SALT',       '2|ROSwOo0?fJ@mL%lOlFC[HUe_fym)i(]|{mtSZ%ejyCksL$|y+OiBZ#nfqFKGH]');
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
