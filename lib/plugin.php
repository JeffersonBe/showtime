<?php
/**
 * Plugin file for reForm.
 * 
 * This plugin works if there were no validation errors and before
 * Output is being sent back to user, so there it is _strongly_ recommended to test your
 * plugin file as much as possible before placing it for production, as any error, warning,
 * or notice may cause further processing to stop.
 * 
 * @package     reform
 * @version     1.0.2
 * @author      r0ash.com
 * @link        http://demo.r0ash.com/reform
 * @copyright   Copyright 2011 r0ash.com. All rights reserved
 * 
 */ 

/**
 * This plugin has access to raw and filtered Form-Fields input
 * 
 * Form Fields Data Array:      $aFormData
 * Main-Configuration:          $configs
 * Form-Configuration:          $formConfigs
 *
 * 
 * $aFormData array structure:
 *  array(
 *      any_field_name  => array(
 *                          label           => 'Label of the field, from form-configuration',
 *                          name            => 'Form-Field name',
 *                          value           => 'Filtered Input from user',
 *                          original        => 'Original input from user'
 *                      ),
 *      multi_value-1   => array(
 *                          label           => 'Label of the field, from form-configuration',
 *                          name            => 'Form-Field name',
 *                          value           => 'Filtered Input from user, for first of multiple-input form-field',
 *                          original        => 'Original input from user, for first of multiple-input form-field'
 *                      ),
 *      multi_value-2   => array(
 *                          label           => 'Label of the field, from form-configuration',
 *                          name            => 'Form-Field name',
 *                          value           => 'Filtered Input from user, for second of multiple-input form-field',
 *                          original        => 'Original input from user, for second of multiple-input form-field'
 *                      ),
 *      file_field_1    => array(
 *                          label           => 'Label of the field, from form-configuration',
 *                          name            => 'Form-Field name',
 *                          value           => 'Converted file name for storage',
 *                          original        => 'Original name of file, as uploaded by user'
 *                          size            => 'File size in bytes',
 *                          error           => 'File error from $_FILES',
 *                          type            => 'File type from $_FILES',
 *                          tmp_name        => 'tmp_name of file',
 *                          file_path       => 'file system path including new file-name'
 *                      )
 *  )
 *
 * Usage: Looping array
 * You can loop through $aFormData as follows;
 * 
 * foreach( $aFormData as $field_name => $aFieldData ) {
 *  print "{$field_name}: {$aFieldData['value']}<br />";
 * }
 * 
 * Output:
 * The output will contain all of the form-fields provided in form-configuration, as follows;
 * 
 *  field_1_name: field_1_value<br />
 *  field_2_name: field_2_value<br />
 *  field_3_name: field_3_value<br />
 * 
 * Usage: Individual field data access
 * 
 * print $aFormData['field_1_name']['label'];   // label of the field
 * print $aFormData['field_1_name']['name'];    // field-name
 * print $aFormData['field_1_name']['value'];   // filtered value or new file name from $_FILES
 * print $aFormData['field_1_name']['original'];// un-filtered value or original file name 
 *                                                 from $_FILES
 * 
 * Usage: Individual field data access for a field where multiple inputs are possible
 * 
 * print $aFormData['field_1_name-1']['label'];    // label of the multi-value field
 * print $aFormData['field_1_name-1']['name'];     // field-name of the multi-value field
 * print $aFormData['field_1_name-1']['value'];    // filtered value or new file name from 
 *                                                 // $_FILES of first value from multi-value field
 * print $aFormData['field_1_name-1']['original']; // un-filtered value or original file name 
 *                                                 // for the first input for multi-value field
 *  
 * print $aFormData['field_1_name-1']['value'];    // filtered value or new file name from 
 *                                                 // $_FILES of second value from multi-value field
 * 
 *  
 * $configs & $formConfigs arrays have same structure:
 *  array(
 *         section_1_name => array(
 *                              property_1_1_name   => value,
 *                              property_1_2_name   => value
 *                              ),
 *         section_2_name => array(
 *                              property_2_1_name   => value,
 *                              property_2_2_name   => value,
 *                              property_2_3_name   => value,
 *                              )
 *  )
 * 
 */



/**
 * Example: Database
 * This example demonstrate how to insert user submitted data in mySQL Database
 * 
 * Requirements:
 * 1)   mySQL Database & Credentials
 * 2)   required mySQL Table already exist in your database (sample table is also provided)
 * 
 * Sample mySQL Table: SQL Command to create sample table
 *  
 * CREATE TABLE contacts (
 *  `contact_id` int(11) AUTO_INCREMENT,
 *  `email` varchar(255),
 *  `message` varchar(255),
 *  `ip` bigint(11),
 *  `created_at` datetime,
 * PRIMARY KEY (`contact_id`)
 * );
 * 
 * 
 * Notes:
 * -    If you use die() function, the output will halt and form will not be further used
 * -    If you use exit anywhere, it will stop further processing
 * 
 */

$link	= mysql_connect( 'MYSQL_SERVER_HOST', 'MYSQL_USERNAME', 'MYSQL_PASSWORD' );
if ( !isset( $link ) ) {
	print "Error while connecting database: " . mysql_error() . ' in ' . __FILE__ . ' line number ' . ( __LINE__ - 2 );
	return false;
}
if ( mysql_select_db( 'DATABASE_NAME' ) == false ) {
	print "Error while selecting database: " . mysql_error() . ' in ' . __FILE__ . ' line number ' . ( __LINE__ - 1 );
	return false;
}

$sql	= "	INSERT INTO `contacts`
				(
					`email`,
					`message`,
					`ip_address`,
					`created_at`
				)
				VALUES
				(
					'" . mysql_escape_string( $aFormData['email']['value'] ) . "',
					'" . mysql_escape_string( isset( $aFormData['message'] ) ? $aFormData['message']['value'] : '' ) . "',
					'" . ip2long( $_SERVER['REMOTE_ADDR'] ) . "',
					NOW()
				)";
if ( mysql_query( $sql ) == false ) {
	print "Error: " . mysql_error() . ' in ' . __FILE__ . ' line number ' . ( __LINE__ - 1 );
}

/**
 * Example: Google Spreadsheet
 * This example demonstrate the use of form submitted data to store in Google Spreadsheet
 * 
 * Requirements:
 * 1) You must meet the requirements as indicated over http://www.farinspace.com/saving-form-data-to-google-spreadsheets/
 * 2) Double check if you have already created the Spreadsheet document and worksheet under it
 * 
 * Notes:
 * 1) Make sure you have included Google_Spreadsheet.php appropriately
 * 2) Make sure you have Zend's GData library in include_path
 * 3) The fields used, actually exist under $aFormData array
 * 
 */
include_once( 'Google_Spreadsheet.php' );
/**
 * Following line to include current directory in PHP's include path.
 * 
 * We included current directory because we have Zend's library exist in same directory
 * 
 */
set_include_path( get_include_path() . PATH_SEPARATOR . '.' );

$u = "your_account@whatever-website.com";
$p = "your_password";
 
$ss = new Google_Spreadsheet($u,$p);
$ss->useSpreadsheet("Your_Spreadsheet");
$ss->useWorksheet("Your_Worksheet");
$id = "z" . md5(microtime(true));
$row = array 
(
	"id"       => $id,
	"email"    => $aFormData['email']['value'],
	"message"  => $aFormData['message']['value']
);
 
if ( !$ss->addRow( $row ) ) {
    echo "Error, unable to store data in google-doc spreadsheet";
    return false;
}
 
/**
 * Example: CSV Export
 * This example demonstrate how to store user submitted data into csv file
 * 
 * The demonstration use uploads directory (as specified under main-configuration path.upload setting)
 * and csv file name is set to export.csv
 * 
 * Notes:
 * The CSV File expects column values separated by comma and enclosed in double-quotes, so double
 * check your code take care of these requirements. This example can be changed to tab-delimited file 
 * as well.
 */
$configs['path']['upload']  = rtrim ( $configs['path']['upload'], DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;
if ( !is_dir( $configs['path']['upload'] ) ) {
    mkdir( $configs['path']['upload'] );
}
$fh = fopen( $configs['path']['upload'] . 'export.csv', 'a');
fwrite($fh, '"' . str_replace( '"', '\"', $aFormData['name']['value'] ) . '","' . str_replace( '"', '\"', $aFormData['email']['value'] ) . "\"\n");
fclose($fh);
 
