<?php
/**
 * Captcha rendering script
 * 
 * @package     reform
 * @version     1.0.2
 * @author      r0ash.com
 * @link        http://demo.r0ash.com/reform
 * @copyright   Copyright 2011 r0ash.com. All rights reserved
 * 
 */ 
session_start();
/**
 * Find out the file system path to retrieve main & form configuration files and functions.php
 */
$aPathInfo      = pathinfo( $_SERVER['SCRIPT_FILENAME'] );
$base_path      = rtrim( $aPathInfo['dirname'], DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR ;

$main_ini       = $base_path . '../reform.ini';
$form_ini       = $base_path . '../reform_action.ini';

$configs        =  parse_ini_file( $main_ini, true );
$formConfigs    =  parse_ini_file( $form_ini, true );
$session_name   = $formConfigs['reform']['session_name'];

$dir            = rtrim( $configs['path']['captcha'], DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;

/**
 * Loop through captcha images directory
 */
$aFiles	= array();
if ( is_dir( $dir ) ) {
    if ( $dh = opendir( $dir ) ) {
        while ( ( $file = readdir( $dh ) ) !== false ) {
            $fileExtension	= getFileExtension( $file ) ;
            /**
             * Only specified image types will be taken care of
             */
			if ( is_file( $dir . $file ) &&  ( $fileExtension == 'gif' || $fileExtension == 'jpg' || $fileExtension == 'png' ) ) {
				$aFiles[]	= $file;
			}
        }
        closedir($dh);
    }
}

$file	= $aFiles[ array_rand( $aFiles, 1 ) ];
unset( $aFiles );

$aFileParts      = explode( ".", $file );
$fileExtension	 = strtolower( trim ( array_pop( $aFileParts ) ) );

/**
 * Clean file name to store word ( file name without extension ) in $_SESSION
 */
$word		     = implode( ".", $aFileParts );
$word		     = str_replace( '.', ' ', $word );
$word		     = str_replace( '_', ' ', $word );
$_SESSION[$session_name]	= ( $configs['application']['captcha_ignore_case'] ) ? trim( $word ) : strtolower( trim( $word ) );
ob_end_clean();
header( 'Content-Type: ' . getMimeType( $fileExtension ) );
header(	'Expires: Wed, 1 Jan 1976 00:00:00 GMT' );
header(	'Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT' );
header(	'Cache-Control: no-store, no-cache, must-revalidate' );
header(	'Pragma: no-cache' );
$fh = fopen( $dir . $file, 'rb');
echo fread( $fh, filesize( $dir . $file ) );
fclose( $fh );

/**
 * Utility function to get file extension out of a file name
 * 
 * @param string $file_name Name of the file
 * 
 * @return string lower-case extension name of the file
 * 
 */
function getFileExtension( $file_name )
{
    $aFileParts = explode( '.', $file_name );
    return strtolower( trim( $aFileParts[ count( $aFileParts ) - 1 ] ) );
}

/**
 * Get Mime Type based upload file extension
 * 
 * @param string $file_extension Extension name of a file
 * 
 * @return string Mime Type
 * 
 */
function getMimeType( $file_extension )
{
    $mime_type  = 'image/jpeg';
    switch( $file_extension ) {
        case 'jpe':
        case 'jpeg':
        case 'jpg':
            $mime_type = 'image/jpeg';
            break;
        case 'gif':
            $mime_type = 'image/gif';
            break;
        case 'png':
            $mime_type = 'image/png';
            break;
        case 'tif':
        case 'tiff':
            $mime_type = 'image/tiff';
            break;
    }
    return $mime_type;
}