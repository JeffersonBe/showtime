<?php
/**
 * Library of core functions
 * 
 * @package     reform
 * @version     1.0.2
 * @author      r0ash.com
 * @link        http://demo.r0ash.com/reform
 * @copyright   Copyright 2011 r0ash.com. All rights reserved
 * 
 */ 

/**
 * Check if user's IP is banned or not
 * IP is retrived from $_SERVER['REMOTE_ADDR']
 * 
 * @param boolean $enable_ban_ip setting from main configuration file
 * @param string $banned_ip_data file name from main configuration file
 * @param string $messages_banned_ip message to be displayed
 * 
 * @return none it just display text and halt further processing
 */
function isIPBanned( $enable_ban_ip, $banned_ip_data,  $messages_banned_ip )
{
    if ( $enable_ban_ip ) {
        $content    = strtolower( reform_file_get_contents( $banned_ip_data ) );
        $aIPParts   = explode( ".", $_SERVER['REMOTE_ADDR'] );
        $index      = count( $aIPParts ) - 1;
        $last_part  = '';
        foreach( $aIPParts as $ip_part ) {
            $ip_pattern = $last_part . $ip_part . str_repeat( '.x', $index );
            $index--;
            $last_part  .= $ip_part . '.';
            if ( preg_match( '/\b' . preg_quote( $ip_pattern )  . '\b/si', $content ) ) {
                print $messages_banned_ip;
                exit;
            }
        }
    }
}

/**
 * Prepare form-field meta-data from form's configuration file
 * 
 * @param array $formConfigs form configuration file array
 * 
 * @return array form field meta array
 * 
 */
function getFormFieldMeta( $formConfigs, $badwords )
{
    $ff = array();

    foreach( $formConfigs as $section => $hData ) {
        if ( strtolower( $section ) != "reform" ) {
            
            /**
             * Parsing of validators
             */
            $aRaw           = explode( ",", $hData['validators'] );
            $aValidators    = array();
            foreach( $aRaw as $mixValue ) {
                $mixValue   = trim ( $mixValue );
                $key        = '';
                if ( preg_match( '/:/', $mixValue ) ) {
                    $partValue  = explode( ":", $mixValue );
                    $key        = trim( $partValue[0] );
                    $value      = trim( $partValue[1] );
                    $aValidators[$key]  = str_replace( "|", ",", $value );
                } else {
                    if ( $mixValue == 'bad_words' ) {
                        $aValidators['bad_words']  = $badwords;
                    } else {
                        $aValidators[]  = str_replace( "|", ",", $mixValue );
                    }
                }
            }
            
            /**
             * Parsing of filters
             */
            $aRaw           = explode( ",", $hData['filters'] );
            $aFilters   = array();
            foreach( $aRaw as $mixValue ) {
                $mixValue   = trim ( $mixValue );
                $key        = '';
                if ( preg_match( '/:/', $mixValue ) ) {
                    $partValue  = explode( ":", $mixValue );
                    $key        = trim( $partValue[0] );
                    $value      = trim( $partValue[1] );
                    $aFilters[$key] = str_replace( "|", ",", $value );
                } else {
                    $aFilters[] = str_replace( "|", ",", $mixValue );
                }
            }
            $ff[]   = array( $hData['name'], $hData['main_id'], $hData['label'], $aValidators, $aFilters, $hData['message'] );
        }
    }

    return $ff;
}
/**
 * process()
 * This function handles complete form processing
 * 
 * @return none just display the output
 * 
 */
function process( )
{
    global $configs, $formConfigs;

    /*
     * Check if user's IP is banned or not
     */
    isIPBanned( $configs['enable']['ban_ip'],
                $configs['path']['banned_ip_data'],
                $configs['messages']['banned_ip'] );

    $ff     = getFormFieldMeta( $formConfigs, $configs['application']['badwords'] );

    /**
     * Form values will be validated and filtered while files ($_FILES) data will be
     * prepared for further processing
     */
    $aValidations   = array();
    $aFiltered      = array();
    $aFiles         = array();
    foreach( $ff as $aField ) {
        list( $field_name, $main_id, $field_label, $aValidators, $aFilters, $customMessage ) = $aField;
        list ( $is_valid, $reason, $extra, $field_value ) = validateField( $field_name,
                                                            $aValidators,
                                                            $customMessage );
        if (!$is_valid) {
            $aValidations[] = array( 'label' => $field_label,
                                    'field_name' => $main_id,
                                    'reason' => _t( $reason, 
                                                    $field_label,
                                                    $field_value,
                                                    $extra) );
                                                    
        } else {
            /**
             * Files ($_FILES) validations & filters processing
             */
            if ( isset( $_FILES[$field_name] ) ) {
                $total_files    = count( $_FILES[$field_name]['name'] );
                $hFiles         = array();
                if ( $total_files > 1) {
                    /**
                     * Convert to appropriate data structure for similar processing of files and non-files
                     * form fields
                     */
                    for( $nCounter = 0; $nCounter < $total_files; $nCounter++ ) {
                        $hFiles[] = array(
                                        'name'      => $_FILES[$field_name]['name'][$nCounter],
                                        'type'      => $_FILES[$field_name]['type'][$nCounter],
                                        'tmp_name'  => $_FILES[$field_name]['tmp_name'][$nCounter],
                                        'error'     => $_FILES[$field_name]['error'][$nCounter],
                                        'size'      => $_FILES[$field_name]['size'][$nCounter]
                                        );
                    }
                } else {
                    $hFiles[]     = $_FILES[$field_name];
                }
    
                $nMultipleValueIndex = 1;
                foreach ( $hFiles as $hFile ) {
                    $field_value    = $hFile['name'];
                    $fieldSequence  = ( $total_files == 1 ) ? "" : $nMultipleValueIndex;
                    list ( $is_valid, $reason, $extra ) = validateField( $field_name,
                                                                        $hFile,
                                                                        $aValidators,
                                                                        $customMessage,
                                                                        $fieldSequence );
                    if ( !is_dir( $configs['path']['upload'] ) ) {
                        mkdir( $configs['path']['upload'], 0777 );
                    }
                    if ( !empty( $hFile['name'] ) ) {
                        $postFix        = ( $total_files == 1 ) ? "" : "-" . $nMultipleValueIndex;
                        $file_name      = getNewFileName( $hFile['name'], $field_name, $postFix );
                        $uploaded_file  = rtrim( $configs['path']['upload'], DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . $file_name; 
                        $aFiles[]   = array( 'label'        => $field_label,
                                            'name'          => $field_name,
                                            'value'         => $file_name,
                                            'original'      => $hFile['name'],
                                            'size'          => $hFile['size'],
                                            'error'         => $hFile['error'],
                                            'type'          => $hFile['type'],
                                            'tmp_name'      => $hFile['tmp_name'],
                                            'file_path'     => $uploaded_file,
                                            'array_key'     => $field_name.$postFix );
                    }
                    $nMultipleValueIndex++;
                }
            } elseif ( isset( $_POST[$field_name] ) ) {
                /**
                 * Form-fields (other than files $_FILES) handling
                 */
                $aFieldValues    = $_POST[$field_name];
                if ( !is_array( $aFieldValues ) ) {
                    $aFieldValues    = array( $aFieldValues );
                }
                $nMultipleValueIndex = 0;
                foreach( $aFieldValues as $field_value ) {
                    $postFix        = ( $nMultipleValueIndex == 0 ) ? "" : "-" . $nMultipleValueIndex;
                    $aFiltered[$field_name.$postFix]   = array( 'label'      => $field_label,
                                                        'name'      => $field_name,
                                                        'value'     => filterField( $field_value, $aFilters ),
                                                        'original'  => htmlentities( $field_value ) );
                    $nMultipleValueIndex++;
                }
            }
        }
    }

    /**
     * reCaptcha handling
     */
    if ( $configs['recaptcha']['public_key'] && $configs['recaptcha']['private_key'] ) {
        require_once ( $configs['path']['recaptchalib'] );
        $resp = recaptcha_check_answer( $configs['recaptcha']['private_key'],
                                    $_SERVER["REMOTE_ADDR"],
                                    $_POST["recaptcha_challenge_field"],
                                    $_POST["recaptcha_response_field"] );
        if ( !$resp->is_valid ) {
            $aValidations[] = array( 'field_name' => 'recaptcha_challenge_field',
                                    'reason' => $configs['messages']['captcha'] );
        }
    }

    /**
     * Further file ($_FILES) processing
     */
    if ( count( $aValidations ) == 0 ) {
        foreach ( $aFiles as $hFile ) {
            $status = move_uploaded_file( $hFile['tmp_name'], $hFile['file_path'] );
            if ( $status ) {
                $array_key   = $hFile['array_key'];
                unset( $hFile['array_key'] );
                $aFiltered[$array_key] = $hFile;
            } else {
                $aValidations[] = array( 'label'        => $hFile['label'],
                                        'field_name'    => $hFile['name'],
                                        'reason'        => $configs['messages']['upload'] );
                /**
                 * Dont proceed as single errorneous upload invalidates whole form submit
                 */
                break;
            }
        }
    }

    $message  = $formConfigs['reform']['failure'];
    $processing_result  = 0;
    if ( count( $aValidations ) == 0 ) {
        $processing_result  = handleSubmittedData( $aFiltered );
    }

    $result = false;
    
    /**
     * Check if form contains no validation issues
     */
    if ( ( count( $aValidations ) == 0 ) && ( $processing_result == 1 ) ) {
        $result = true;
        $message= $formConfigs['reform']['success'];

        /**
         * We are good to unset session for reForm captcha now
         */
        $session_name   = $formConfigs['reform']['session_name'];
        if ( isset( $_SESSION[$session_name] ) ) {
            unset( $_SESSION[$session_name] );
        }

        /**
         * Delete uploaded files (if any)
         */
        if ( $configs['application']['delete_files_upload'] ) {
            foreach ( $aFiles as $hFile ) {
                $file_name  = rtrim( $configs['path']['upload'], DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . $hFile['value'];
                if ( file_exists( $file_name ) ) {
                    unlink( $file_name );
                }
            }
        }
        
    } else {
        $message= ( $processing_result != 1 && !empty( $processing_result ) ) ? ( preg_replace( "/\n|\r/", "", $processing_result ) ) : $message;
    }

    /**
     * Check if output beautifier file exist, then include it here
     */
    if ( file_exists( $formConfigs['reform']['file_output_beautifier'] ) ) {
        include_once( $formConfigs['reform']['file_output_beautifier'] );
    }


    /**
     * Display output
     */
    $is_ajax    = false;
    if ( ( isset( $_POST['is_ajax'] ) ) && ( !empty( $_POST['is_ajax'] ) ) ) {
        $is_ajax    = true;
    }

    if ( $is_ajax ) {
        /**
         * Display output as JSON encoded string
         */
        header( "Content-Type: text/plain;charset=utf-8");
        $aOutput    = array( 'result' => $result, 'message' => $message, 'fields' => $aValidations );
        echo reform_json_encode( $aOutput, true );
        exit;
    }

    /**
     * Display output as HTML via no-javascript file from form-configuration file
     */
    if ( file_exists( $formConfigs['reform']['file_nojavascript'] ) ) {
        require_once ( $formConfigs['reform']['file_nojavascript'] );
    }
    
}

/**
 * Turn form data value to array
 * 
 * This will be helpful in handling multi or single value form field a like.
 * Additionally $_FILES and $_POST will be taken care of equally
 * 
 * @param string $field_name
 * @param boolean $return_file_array If true it will return $_FILES complete hash otherwise just name field from $_FILES and usual value from $_POST array
 * 
 * @return array List of form-field value(s) 
 */
function getFieldValueArray( $field_name, $return_file_array = false )
{
    $aFieldValues   = array();
    /*if ( is_array( $field_name ) ) {
        return $field_name['error'];
    } else {
        return $field_name;
    }*/
    if ( isset( $_FILES[$field_name] ) ) {
        /**
         * $_FILES handling
         */
        /**
         * Convert to appropriate data structure for similar processing of files and non-files
         * form fields
         */
        $total_files    = count( $_FILES[$field_name]['name'] );
        if ( $total_files > 1) {
            /**
             * Convert to appropriate data structure for similar processing of files and non-files
             * form fields
             */
            for( $nCounter = 0; $nCounter < $total_files; $nCounter++ ) {
                if ( $return_file_array ) {
                    $aFieldValues[] = array(
                                        'name'      => $_FILES[$field_name]['name'][$nCounter],
                                        'type'      => $_FILES[$field_name]['type'][$nCounter],
                                        'tmp_name'  => $_FILES[$field_name]['tmp_name'][$nCounter],
                                        'error'     => $_FILES[$field_name]['error'][$nCounter],
                                        'size'      => $_FILES[$field_name]['size'][$nCounter]
                                    );
                } else {
                    $aFieldValues[] = $_FILES[$field_name]['name'][$nCounter];
                }
            }
        } else {
            $aFieldValues     = array( ( $return_file_array ) ? $_FILES[$field_name] : $_FILES[$field_name]['name'] );
        }
    } elseif ( isset( $_POST[$field_name] ) ) {
        /**
         * $_POST handling
         */
        $aFieldValues    = $_POST[$field_name];
        if ( !is_array( $aFieldValues ) ) {
            $aFieldValues    = array( $aFieldValues );
        }
    }
    return $aFieldValues;
}

/**
 * Create new file name for uploaded file
 * 
 * @param string $original_file_name The original file name
 * @param string $field_name Form field name
 * @param string $postFix String containing form-field name and possible digit representing sequence number if form-field is multi-value form-field
 * 
 * @return string new name of the uploaded file
 * 
 */
function getNewFileName( $original_file_name, $field_name, $postFix )
{
    return $field_name.$postFix . '_'. time() . '_' . $original_file_name;
}

 
/**
 * Convert internal code for messages here
 * All the messages from main-configuration file are replaced here
 * 
 * @param string $source internal codes for replacement with appropriate main-configuration message
 * @param string $field_name name of the field as used in form-configuration file
 * @param string $field_value data entered in the form field by user
 * @param string $extra Extra data that might replacement in message
 *                      for example, if a message has %s, its value will be 
 *                      contained in this parameter
 * 
 * @return string formatted message with field name or extra information
 */
function _t( $source, $field_name, $field_value, $extra )
{
    global $configs;
    $message    = '';
    switch( $source ) {
        case 'custom':
            $message    = $extra;
            break;
        case 'required':
            $message    = $configs['messages']['required'];
            break;
        case 'captcha':
            $message    = $configs['messages']['captcha'];
            break;
        case 'invalid':
            $message    = $configs['messages']['invalid'];
            break;
        case 'min':
            $message    = $configs['messages']['min'];
            break;
        case 'max':
            $message    = $configs['messages']['max'];
            break;
        case 'gt':
            $message    = $configs['messages']['gt'];
            break;
        case 'lt':
            $message    = $configs['messages']['lt'];
            break;
        case 'gte':
            $message    = $configs['messages']['gte'];
            break;
        case 'lte':
            $message    = $configs['messages']['lte'];
            break;
        case 'length':
            $message    = $configs['messages']['length'];
            break;
        case 'file_size':
            $message    = $configs['messages']['file_size'];
            break;
        case 'file_type_allowed':
            $aAllowedFileTypes  = explode( ",", trim( $extra ) );
            /**
             * We need to clean the values from form-configuration file
             * because may be it contains spaces or something that needs
             * to be trimmed and turn lower-case so that, the 
             * file extensions remain case-insensitive
             */
            array_walk( $aAllowedFileTypes, 'filter_array_item' );
            $extra  = implode( ",", $aAllowedFileTypes );
            $message    = $configs['messages']['file_type'];
            break;
        case 'bad_words':
            $message    = $configs['messages']['badword'];
            break;
        case 'not_matched':
            $message    = $configs['messages']['not_matched'];
            break;
        default:
            $message    = $source;
    }
    $message    = str_replace( '%field_name%', $field_name, $message );
    $message    = str_replace( '%field_value%', $field_value, $message );
    return sprintf( $message, $extra );
}

/**
 * Validate a field and tells if it meets the validator requirements or not
 * Additionally it prepares data for filter-processing (_t() function)
 * 
 * @param string $field_name Name of the form-field
 * @param array $aValidators All of the validators specified in form-configuration 
 *                              file for a give field
 * @param string $customMessage Custom message that will override any message
 * 
 * @return array it contains whether field-value is true or false
 *                  reason of failure internal-code (used by _t() function )
 *                  extra data that will be used for replacement in _t() function
 *                  field's input
 * 
 */
function validateField( $field_name, $aValidators, $customMessage = '' )
{
    global $configs, $formConfigs;
    $is_valid   = true;
    $reason     = '';
    $extra      = '';
    $field_value= '';
    
    /**
     * If no validator is provided just return true
     */
    if ( empty( $aValidators[0] ) ) {
        return array( true, $reason, $extra, $field_value );
    }

    foreach( $aValidators as $k => $v ) {
        $validator  = ( is_int( $k ) ) ? $v : strtolower( trim( $k ) );
        switch( $validator ) {

/**
* It checks if form-field input is empty or not
*
* Usage:
* validators = "required"
*
* Note:
* If required validator is used with a file form-field, it will check if name of file exist or not
**/
 
			case 'required':
                $aFiledValues   = getFieldValueArray( $field_name );
                foreach( $aFiledValues as $field_value ) {
                    if ( strlen( $field_value ) == 0 ) {
                        $is_valid   = false;
                        $reason     = 'required';
                        break 3;
                    }
                }
                break;

/**
* It works with only reForm Captcha and checks if the data entered in reForm Captcha field is same as the file name of captcha image
*
* Usage:
* validators = "captcha"
*
* Note:
* Only ISO-8859-1 encoded file names could be used on Windows based hostings, due to lack of UTF-8 support in PHP.
**/

            case 'captcha':
                $session_name   = $formConfigs['reform']['session_name'];
                if ( isset( $_POST[$field_name] ) ) {
                    $field_value    = $_POST[$field_name];
                }

                $field_value    = ( $configs['application']['captcha_ignore_case'] ) ? trim( $field_value ) : strtolower( trim( $field_value ) ) ;
                if ( strlen( $field_value ) != 0 ) {
                    if ( isset( $_SESSION[$session_name] ) && $_SESSION[$session_name] != $field_value  ) {
                        $is_valid   = false;
                        $reason     = 'captcha';
                        break 2;
                    }
                }
                break;

/**
* It will pass validation check for any form-input that matches data entered after semi-colon
*
* Usage:
* validators = "exactly:yes"
*
* Note:
* Any thing after exactly: is compared with the form-field value in question
**/

            case 'exactly':
                $aFiledValues   = getFieldValueArray( $field_name );
                foreach( $aFiledValues as $field_value ) {
                    if ( strlen( $field_value ) != 0 ) {
                        if ( $field_value != $v  ) {
                            $is_valid   = false;
                            $reason     = 'invalid';
                            break 3;
                        }
                    }
                }
                break;

/**
* This validator works with two form-fields, one that is attached to it and another one that is provided after semi-colon
*
* Usage:
* validators = "paired:field_2"
*
* Note:
* If this validator is used, its necessary to provide second field name
**/

            case 'paired':
                $aFiledValues   = getFieldValueArray( $field_name );
                foreach( $aFiledValues as $field_value ) {
                    if ( strlen( $field_value ) != 0 ) {
                        if ( ( isset( $_POST[$v] ) ) && ( $_POST[$v] != $field_value ) ) {
                            $is_valid   = false;
                            $reason     = 'not_matched';
                            $extra      = $v;
                            break 3;
                        }
                    }
                }
                break;

/**
* Checks if email address format is correct
*
* Usage:
* validators = "email"
*
* Note:
* It checks email address against regular expression pattern ^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$
**/

            case 'email':
                $aFiledValues   = getFieldValueArray( $field_name );
                foreach( $aFiledValues as $field_value ) {
                    if ( ( !empty( $field_value ) ) && ( !preg_match( '/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/si', $field_value ) ) ) {
                        $is_valid   = false;
                        $reason     = 'invalid';
                        break 3;
                    }
                }
                break;

/**
* Checks if a name is provided
*
* Usage:
* validators = "name"
*
* Note:
* It will return true if name field _do not_ contain any character except to alphabets or single-quote ' or dash - or space
**/

            case 'name':
                $aFiledValues   = getFieldValueArray( $field_name );
                foreach( $aFiledValues as $field_value ) {
                    if ( ( !empty( $field_value ) ) && ( preg_match( '/[^ a-z\\\'\-]/i', trim( $field_value ) ) ) ) {
                        $is_valid   = false;
                        $reason     = 'invalid';
                        break 3;
                    }
                }
                break;

/**
* Checks if a number is provided
*
* Usage:
* validators = "num"
*
* Note:
* It will check if input contain only digits
**/

            case 'num':
                $aFiledValues   = getFieldValueArray( $field_name );
                foreach( $aFiledValues as $field_value ) {
                    if ( ( strlen( $field_value ) != 0 ) && ( preg_match( '/[^0-9]/i', trim( $field_value ) ) ) ) {
                        $is_valid   = false;
                        $reason     = 'invalid';
                        break 3;
                    }
                }
                break;

/**
* Check if form-field contains number of characters less than what is specifid after colon
*
* Usage:
* validators = "min:23"
*
* Note:
* The number of characters in the input must be less than to the specified number provided after colon
**/

            case 'min':
                $aFiledValues   = getFieldValueArray( $field_name );
                foreach( $aFiledValues as $field_value ) {
                    $field_length   = strlen( $field_value );
                    if ( $field_length < $v ) {
                        $is_valid   = false;
                        $reason     = 'min';
                        $extra      = $v;
                        break 3;
                    }
                }
                break;

/**
* Check if form-field contains excat number of characters
*
* Usage:
* validators = "length:10"
*
* Note:
* 
**/

            case 'length':
                $aFiledValues   = getFieldValueArray( $field_name );
                foreach( $aFiledValues as $field_value ) {
                    $field_length   = strlen( $field_value );
                    if ( ( $field_length > 0 ) && ( $field_length != $v ) ) {
                        $is_valid   = false;
                        $reason     = 'length';
                        $extra      = $v;
                        break 3;
                    }
                }
                break;

/**
* Check if form-field contains characters more than specified number of characters after colon
*
* Usage:
* validators = "max:20"
*
* Note:
* The form-field will pass test if it contains less than 20 characters data
**/

            case 'max':
                $aFiledValues   = getFieldValueArray( $field_name );
                foreach( $aFiledValues as $field_value ) {
                    $field_length   = strlen( $field_value );
                    if ( $field_length > $v ) {
                        $is_valid   = false;
                        $reason     = 'max';
                        $extra      = $v;
                        break 3;
                    }
                }
                break;

/**
* Check if form-field input contains only alphabets and digits
*
* Usage:
* validators = "alphanum"
*
* Note:
* Returns false if input contains anything other than alphabets and digits
**/

            case 'alphanum':
                $aFiledValues   = getFieldValueArray( $field_name );
                foreach( $aFiledValues as $field_value ) {
                    if ( ( strlen( $field_value ) != 0 ) && ( preg_match( '/[^0-9a-z]/i', trim( $field_value ) ) ) ) {
                        $is_valid   = false;
                        $reason     = 'invalid';
                        break 3;
                    }
                }
                break;

/**
* Check if form-field input contains only alphabets
*
* Usage:
* validators = "alpha"
*
* Note:
* Returns true if input contains only alphabets. It will fail even if there is a space in between characters
**/

            case 'alpha':
                $aFiledValues   = getFieldValueArray( $field_name );
                foreach( $aFiledValues as $field_value ) {
                    if ( ( strlen( $field_value ) != 0 ) && ( preg_match( '/[^a-z]/i', trim( $field_value ) ) ) ) {
                        $is_valid   = false;
                        $reason     = 'invalid';
                        break 3;
                    }
                }
                break;

/**
* Check if form-field input value matched with regular expression
*
* Usage:
* validators = "regex:\d{3}\-\d{3}\-\d{4}"
*
* Note:
* The regular expression in usage is basically USA Telephone number format.
For example it will pass the validation if the telephone provided is like 212-424-4356. Do not use double-quotes in regular expression.
**/

            case 'regex':
                $aFiledValues   = getFieldValueArray( $field_name );
                foreach( $aFiledValues as $field_value ) {
                    $v  = trim( $v );
                    if ( !empty( $v ) ) {
                        if ( !preg_match( '/' . $v . '/i', trim( $field_value ) ) ) {
                            $is_valid   = false;
                            $reason     = ( empty( $customMessage ) ) ? 'invalid' : $customMessage;
                            break 3;
                        }
                    }
                }
                break;

/**
* Check if form-field input is greater than specified value
*
* Usage:
* validators = "gt:12"
*
* Note:
* It should be applied to form-fields, expecting inputs being number(s)
**/

            case 'gt':
                $aFiledValues   = getFieldValueArray( $field_name );
                foreach( $aFiledValues as $field_value ) {
                    $field_value_t  = ( integer ) trim( $field_value );
                    if ( $field_value_t > $v ) {
                        $is_valid   = false;
                        $reason     = 'gt';
                        $extra      = $v;
                        break 3;
                    }
                }
                break;

/**
* Check if form-field input is less than specified value
*
* Usage:
* validators = "lt:10"
*
* Note:
* same as above
**/

            case 'lt':
                $aFiledValues   = getFieldValueArray( $field_name );
                foreach( $aFiledValues as $field_value ) {
                    $field_value_t  = ( integer ) trim( $field_value );
                    if ( $field_value_t < $v ) {
                        $is_valid   = false;
                        $reason     = 'lt';
                        $extra      = $v;
                        break 3;
                    }
                }
                break;

/**
* Check if form-field input is greater than specified value
*
* Usage:
* validators = "gt:12"
*
* Note:
* It should be applied to form-fields, expecting inputs being number(s)
**/

            case 'gte':
                $aFiledValues   = getFieldValueArray( $field_name );
                foreach( $aFiledValues as $field_value ) {
                    $field_value_t  = ( integer ) trim( $field_value );
                    if ( $field_value_t < $v ) {
                        $is_valid   = false;
                        $reason     = 'gte';
                        $extra      = $v;
                        break 3;
                    }
                }
                break;

/**
* Check if form-field input is less than specified value
*
* Usage:
* validators = "lt:10"
*
* Note:
* same as above
**/

            case 'lte':
                $aFiledValues   = getFieldValueArray( $field_name );
                foreach( $aFiledValues as $field_value ) {
                    $field_value_t  = ( integer ) trim( $field_value );
                    if ( $field_value_t > $v ) {
                        $is_valid   = false;
                        $reason     = 'lte';
                        $extra      = $v;
                        break 3;
                    }
                }
                break;

/**
* Check if form-field contains any word from main-configuration's path.banned_ip_data file
*
* Usage:
* validators = "bad_words"
*
* Note:
* It will match words only (not everything that resembles listed bad-word), for instance it will pass validation if the bad-word is 'one' and form-field input is 'oneness', while it will fail validation if string is 'one day'
**/

            case 'bad_words':
                $aFiledValues   = getFieldValueArray( $field_name );
                foreach( $aFiledValues as $field_value ) {
                    $v          = trim( $v );
                    if ( !empty( $v ) ) {
                        $aBadwords  = explode( ",", $v );
                        array_walk( $aBadwords, 'filter_array_item' );
                        foreach ( $aBadwords as $badword ) {
                            if ( preg_match( '/\b' . preg_quote( $badword ) . '\b/si', $field_value ) ) {
                                $is_valid   = false;
                                $reason     = 'bad_words';
                                $extra      = $v;
                                break 3;
                            }
                        }
                    }
                }
                break;

/**
* Checks if user's uploaded file meets the specified file size (in bytes) requirement
*
* Usage:
* validators = "file_size:3072"
*
* Note:
* Demonstrated usage will restrict any file upload that exceeds 3072 bytes (or 3KB)
**/

            case 'file_size':
                $aFiledValues   = getFieldValueArray( $field_name, true );
                foreach( $aFiledValues as $hFile ) {
                    if ( strlen( $hFile['name'] ) != 0 ) {
                        if ( !empty( $hFile['size'] ) && ( $hFile['size'] > $v ) ) {
                            $is_valid   = false;
                            $reason     = 'file_size';
                            $extra      = $v;
                            break 3;
                        }
                    }
                }
                break;

/**
* Checks if uploaded file's extension is under allowed file-extensions list
*
* Usage:
* validators = "file_type_allowed:jpg|jpeg|gif"
*
* Note:
* The example will restrict any file upload that is not a JPEG or GIF image file
**/

            case 'file_type_allowed':
                $aFiledValues   = getFieldValueArray( $field_name );
                foreach( $aFiledValues as $field_value ) {
                    if ( ( strlen( $field_value ) != 0  ) ) {
                        $aFileNameParts     = explode( ".", $field_value );
                        $file_extension     = strtolower( trim( $aFileNameParts[ count( $aFileNameParts ) - 1 ] ) );
                        $aAllowedFileTypes  = explode( ",", trim( $v ) );
                        /**
                         * Clean file types value because it can contain spaces or not in 
                         * lower-case
                         */
                        array_walk( $aAllowedFileTypes, 'filter_array_item' );
                        if ( !in_array( $file_extension, $aAllowedFileTypes ) ) {
                            $is_valid   = false;
                            $reason     = 'file_type_allowed';
                            $extra      = $v;
                            break 3;
                        }
                    }
                }
                break;
        }
    }
    /**
     * If custom message is provided it will be just replaced
     * Watch out if you have provided multiple validators every validator will get same message
     */
    if ( strlen( $customMessage ) != 0 ) {
        $reason = 'custom';
        $extra  = $customMessage;
    }
    return array( $is_valid, $reason, $extra, $field_value );
}

/**
 * Filter form-field input with specified filter in form-configuration file
 * 
 * @param string $field_value Form-field's input, provded by user
 * @param array $aFilters All of the filters attached with form-field
 * 
 * @return string filterer value
 * 
 */
function filterField( $field_value, $aFilters )
{
    $new_value  = $field_value;
    foreach( $aFilters as $k => $v ) {
        $filter = ( is_int( $k ) ) ? $v : $k;
        switch( $filter ) {

/**
* Removes the spaces from begining and end
*
* Usage:
* filters = "trim"
*
* Note:
* Watch UTF-8 strings
**/

            case 'trim':
                $new_value  = trim( $field_value );
                break;

/**
* Removes spaces from just begining
*
* Usage:
* filters = "ltrim"
*
* Note:
* Watch UTF-8 strings
**/

            case 'ltrim':
                $new_value  = ltrim( $field_value, $v );
                break;

/**
* Removes spaces from end only
*
* Usage:
* filters = "rtrim"
*
* Note:
* Watch UTF-8 strings
**/

            case 'rtrim':
                $new_value  = rtrim( $field_value, $v );
                break;

/**
* Removes any html element
*
* Usage:
* filters = "tags"
*
* Note:
* Removes any form-element-tag that is provided as input, for example if user enter a value like &lt;h1&gt;Meeting&lt;/h1&gt; it will be filtered as 'Meeting'
**/

            case 'tags':
                $new_value  = strip_tags( $field_value );
                break;

/**
* Removes everything other than digits
*
* Usage:
* filters = "num"
*
* Note:
* Watch UTF-8 strings
**/

            case 'num':
                $new_value  = preg_replace( '/[^0-9]/', '', $field_value );
                break;

/**
* Removes everything other than alphabets
*
* Usage:
* filters = "alpha"
*
* Note:
* Will not work with UTF-8 encoded inputs
**/

            case 'alpha':
                $new_value  = preg_replace( '/[^a-zA-Z]/', '', $field_value );
                break;

/**
* Removes everything that matches regular expression
*
* Usage:
* filters = "regex:a{4}"
*
* Note:
* Regular expression in ussage will remove trailing a form-field input, for instance if user enters'aBaaCaaaDaaaaEaaaaaD' then this regular expression will filter it to 'aBaaCaaaDEaD'
**/

            case 'regex':
                $new_value  = preg_replace( '/' . $v . '/', '', $field_value );
                break;
        }
    }
    return $new_value;
}

/**
 * Internally used for trimming out and then changing to lower-case, the items of an array
 * 
 * @param reference &$item value of an array item. This will be reference to a mixed-type value
 * 
 * @return mixed the trimmed and lower-case value
 */
function filter_array_item ( &$item )
{
    $item   = strtolower( trim( $item ) );
    return $item;
}

/**
 * The further form-fields processing require sending data as email and/or auto-response 
 * to sender and/or process the data additionally via plugins, if enabled
 * 
 * This function will be executed if and only if there is no validation-issues in form-fields
 * 
 * @param array $aData filtered list of all form fields with additional information like field name
 * 
 * @return mixed returns true if there is no error otherwise an error message
 * 
 */
function handleSubmittedData( $aFormData )
{
    global $configs, $formConfigs;
    
    /**
     * Retriving IP Address of user via $_SERVER['REMOTE_ADDR'] variable and then preparing it
     * similar to how we prepare form-fields
     */
    if ( $configs['email']['include_ip'] ) {
        $aFormData['reform_ip_address']    = array( 'label' => $configs['messages']['label_ip'],
                                                'value' => $_SERVER['REMOTE_ADDR'] );
    }

    /**
     * Send email to administrator and user as auto-response
     */
    $error  = "";
    if ( $configs['enable']['email'] ) {
        if ( sendDataAsEmail( $aFormData ) ) {
            
            /**
             * Auto response will be send to user as email if main-configuration's setting 
             * enable.ar is set to 1
             */
            if ( $configs['enable']['ar'] && ( !empty( $formConfigs['reform']['email_field_name'] ) ) ) {
                sendAutoresponder( $aFormData );
            }
        } else {
            $error  = $configs['messages']['email_error_generic'];
        }
    }

    /**
     * If main-configuration setting enable.plugin is set to 1, then include the plugin file
     * (as specified in form-configuration setting reform.file_plugin) for further processing
     */
    if ( $configs['enable']['plugin'] ) {
        if ( file_exists( $formConfigs['reform']['file_plugin'] ) ) {
            ob_start();
            
            require_once( $formConfigs['reform']['file_plugin'] );

            /**
             * Gather any error from plugin file
             */
            $error_plugin   = ob_get_contents();
            ob_end_clean();
            $error          .= $error_plugin;
        }
    }
    return ( empty( $error ) ) ? true : $error;
}

/**
 * Prepares files and sender email addresses for sending email
 * 
 * @param array $aFormData  Filtered list of form-fields inputs
 * 
 * @return mixed Returns mixed (boolean or string) from sendMail() function
 * 
 */
function sendDataAsEmail( $aFormData )
{
    global $configs, $formConfigs;
    ob_start();

    $message	= require_once( $formConfigs['reform']['file_email_template'] );
    $message    = ob_get_contents();
    ob_end_clean();
    $aFiles = array();
    foreach( $aFormData as $k => $v ) {
        if ( isset( $v['tmp_name'] ) ) {
            $aFiles[]   = $v;
        }
    }
    /**
     * Prepare list of email addreses to whom this email is going to be sent
     */
    $tos    = $configs['email']['to_email'];
    $conditionalEmailField  = $formConfigs['reform']['multiple_tos_field_name'];
    $aMultipleTosKeyEmails  = explode( ",", $formConfigs['reform']['multiple_tos_key_emails'] );
    foreach( $aMultipleTosKeyEmails as $aMultipleTosKeyEmail ) {
        $aKeyValues = explode( ":", trim( $aMultipleTosKeyEmail ) );
        foreach ( $aKeyValues as $field_value => $emails ) {
            $hConditionalEmails[trim( $field_value )]   = trim ( $emails );
        }
    }
    if ( ( count( $hConditionalEmails ) ) && ( !empty( $conditionalEmailField ) ) ) {
        if ( ( isset( $_POST[$conditionalEmailField] ) ) && ( !empty( $_POST[$conditionalEmailField] ) ) ) {
            if ( isset( $hConditionalEmails[$_POST[$conditionalEmailField]] ) && !empty( $hConditionalEmails[$_POST[$conditionalEmailField]] ) ) {
                $tos    = trim( $tos , ';' );
                if ( !empty( $tos ) ) {
                    $tos    .= ';' . $hConditionalEmails[$_POST[$conditionalEmailField]];
                } else {
                    $tos    .= $hConditionalEmails[$_POST[$conditionalEmailField]];
                }
            }
        }
    }
    /**
     * Send email now
     */
    return sendEmail( $tos, $formConfigs['reform']['subject_email'], $message, $configs['email']['html_email'], $aFiles );
}

/**
 * Send auto response email to user who submitted the form
 * 
 * @param array $aFormData  Filtered list of form-fields inputs
 * 
 * @return mixed Returns mixed (boolean or string) from sendMail() function
 * 
 */
function sendAutoresponder( $aFormData )
{
    global $configs, $formConfigs;

    $to             = $aFormData[$formConfigs['reform']['email_field_name']]['value'];

    if ( !empty( $to ) ) {
        /**
         * Call auto response email template
         */
        ob_start();
        require_once( $formConfigs['reform']['file_email_template_ar'] );
        $message    = ob_get_contents();
        ob_end_clean ();
    
        /**
         * Send email now
         */
        return sendEmail( $to, $formConfigs['reform']['subject_ar'], $message, $configs['email']['ar_html_email'], array() );
    }
}

/**
 * Send email via SMTP or sendmail program
 * 
 * @param string $tos Email addresses to whom email is going to be sent
 * @param string $subject Subject of the email
 * @param string $message Message or body of the email
 * @param boolean $is_html If true send email as HTML otherwise as plain-text
 * @param array $aFiles Array of files to attach
 * 
 * @return mixed Returns mixed (boolean or string) from sendMail() function
 * 
 */
function sendEmail( $tos, $subject, $message, $is_html, $aFiles = array() )
{
    global $configs;
    
    /**
     * Turn to-email addresses string to array
     */
    if ( !preg_match( '/;/', $tos ) ) {
        $tos = array( $tos );
    } else {
        $tos    = explode( ";", $tos );
    }

    /**
     * Send email via SMTP if it is enabled via main-configuration setting enable.smtp
     */ 
    if ( $configs['enable']['smtp'] ) {
        /**
         * If this application runs under PHP 5.3, then phpMailer will throw Deprecated message
         * we should handle it here, otherwise application will send the email but display
         * Internal Server Error to user
         */
        if (strnatcmp(phpversion(),'5.3') >= 0) {
            error_reporting( ~E_DEPRECATED );
        }

        require_once( $configs['path']['lib'] . 'phpmailer/class.phpmailer.php' );
        $mail               = new PHPMailer();
        $mail->IsSMTP();
        $mail->Host         = $configs['smtp']['host'];
        $mail->Port         = $configs['smtp']['port'];
        $mail->SMTPSecure   = $configs['smtp']['ssl_type'];
        $mail->SMTPAuth     = $configs['smtp']['require_auth'];
        $mail->Username     = $configs['smtp']['username'];
        $mail->Password     = $configs['smtp']['password'];
        
        $mail->IsHTML( $is_html );
        $mail->From         = $configs['email']['from_email'];
        $mail->FromName     = $configs['email']['from_name'];
        $mail->Subject      = $subject;
        $mail->Body         = $message;
        
        /**
         * Add recipients of the email
         */
        foreach( $tos as $to ) {
            if ( !empty( $to ) ) {
                $mail->AddAddress( $to );
            }
        }

        /**
         * Attach files if main configuration setting email.attach_files is set to 1
         */
        if ( $configs['email']['attach_files'] ) {
            foreach ( $aFiles as $hFile ) {
                $mail->AddAttachment( $hFile['file_path'],
                                    $hFile['value'],
                                    'base64',
                                    getMimeType( getFileExtension( $hFile['value'] ) ) );
            }
        }

        $status = $mail->Send();
        
        /**
         * Reset PHP setting error_reporting, that we changed above to handle deprecated message
         */
        error_reporting( ini_get( 'error_reporting' ) );
        if( !$status ) {
            return false;
        }
    } else {
        $aHeaders   = array();
        /**
         * Make first email-address from $tos array as main email address that will 
         * be sent email to (in To field), rest of the to email-addresses will receieve
         * email being in CC field
         */
        $to     = $tos[0];
        $aToCC  = array();
        unset($tos[0]);
        foreach( $tos as $to_cc ) {
            $aToCC[]    = $to_cc;
        }
        if ( count( $aToCC ) ) {
            $aHeaders[] = "CC: " . implode( ";", $aToCC );
        }
        $aHeaders[] = "From: \"" . $configs['email']['from_name'] . "\"<" . $configs['email']['from_email'] . ">";
        $aHeaders[] = "Reply-To: " . $configs['email']['from_email'];
        $aHeaders[] = "Return-Path: <" . $configs['email']['from_email'] . ">";
        
        // Attachments
        if ( count( $aFiles ) ) {

            if ( $configs['email']['attach_files'] ) {
                /**
                 * Email attachement preparation
                 */
                $semi_rand      = md5( time() * count( $aFiles ) );
                $mime_boundary  = "==Multipart_Boundary_x{$semi_rand}x";
                $aHeaders[]     = "MIME-Version: 1.0";
                $aHeaders[]     = "Content-Type: multipart/mixed; boundary=\"{$mime_boundary}\"";
    
                /**
                 * Actual message being in MIME part
                 */
                $att_message        = "--{$mime_boundary}" . PHP_EOL;
                $att_message        .= "Content-Type: " . ( ( $is_html ) ? 'text/html' : 'text/plain' ) . "; charset=\"utf-8\"". PHP_EOL;
                $att_message        .=  "Content-Transfer-Encoding: 8bit". PHP_EOL;
                $att_message        .=  $message . PHP_EOL;
    
                /**
                 * Attach uploaded files
                 */
                foreach ( $aFiles as $hFile ) {
                    $att_message    .= "--{$mime_boundary}" . PHP_EOL;
                    $data           = chunk_split( base64_encode( reform_file_get_contents( $hFile['file_path'] ) ) );
                    $att_message    .= "Content-Type: " . getMimeType( getFileExtension( $hFile['value'] ) ) . "; name=\"".basename( $hFile['value'] )."\"" . PHP_EOL . 
                    "Content-Description: ".basename( $hFile['value'] ). PHP_EOL .
                    "Content-Disposition: attachment;" . PHP_EOL . " filename=\"" . basename( $hFile['value'] ) . "\"; size=".filesize( $hFile['file_size'] ).";" . PHP_EOL .
                    "Content-Transfer-Encoding: base64". PHP_EOL . $data . PHP_EOL;
                }
                $att_message        .= "--{$mime_boundary}--";
                $message            = $att_message;
            }
        } else {
            if ( $is_html ) {
                $aHeaders[] = "MIME-Version: 1.0";
                $aHeaders[] = "Content-Type: text/html; charset=\"utf-8\"";
            } else {
                $aHeaders[] = "Content-Type: text/plain; charset=\"utf-8\"";
            }
        }

        /**
         * This could be common issue, that is why, we are going to disable any
         * warnings, notices. If mail is not sent, then the reason will be some
         * server side issue
         */
        error_reporting( E_ERROR );
        if ( !mail( $to, $subject, $message, implode( PHP_EOL, $aHeaders ) ) ) {
            error_reporting( ini_get( 'error_reporting' ) );
            return false;
        }
    }

    return true;
}

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
    $mime_type  = 'application/octet-stream';
    switch( $file_extension ) {
        case 'ai':
        case 'eps':
        case 'ps':
            $mime_type = 'application/postscript';
            break;
        case 'aif':
        case 'aifc':
        case 'aiff':
            $mime_type = 'audio/x-aiff';
            break;
        case 'asc':
        case 'txt':
        case 'php':
        case 'php5':
        case 'cgi':
        case 'pl':
        case 'py':
        case 'aspx':
        case 'asp':
        case 'java':
            $mime_type = 'text/plain';
            break;
        case 'atom':
            $mime_type = 'application/atom+xml';
            break;
        case 'au':
        case 'snd':
            $mime_type = 'audio/basic';
            break;
        case 'avi':
            $mime_type = 'video/x-msvideo';
            break;
        case 'bcpio':
            $mime_type = 'application/x-bcpio';
            break;
        case 'bin':
        case 'class':
        case 'dmg':
        case 'dms':
        case 'dll':
        case 'exe':
        case 'lha':
        case 'lzh':
        case 'so':
            $mime_type = 'application/octet-stream';
            break;
        case 'bmp':
            $mime_type = 'image/bmp';
            break;
        case 'cdf':
        case 'nc':
            $mime_type = 'application/x-netcdf';
            break;
        case 'cgm':
            $mime_type = 'image/cgm';
            break;
        case 'cpio':
            $mime_type = 'application/x-cpio';
            break;
        case 'cpt':
            $mime_type = 'application/mac-compactpro';
            break;
        case 'csh':
            $mime_type = 'application/x-csh';
            break;
        case 'css':
            $mime_type = 'text/css';
            break;
        case 'dcr':
        case 'dir':
        case 'dxr':
            $mime_type = 'application/x-director';
            break;
        case 'dif':
        case 'dv':
            $mime_type = 'video/x-dv';
            break;
        case 'djv':
        case 'djvu':
            $mime_type = 'image/vnd.djvu';
            break;
        case 'doc':
        case 'docx':
            $mime_type = 'application/msword';
            break;
        case 'dtd':
            $mime_type = 'application/xml-dtd';
            break;
        case 'dvi':
            $mime_type = 'application/x-dvi';
            break;
        case 'etx':
            $mime_type = 'text/x-setext';
            break;
        case 'ez':
            $mime_type = 'application/andrew-inset';
            break;
        case 'gif':
            $mime_type = 'image/gif';
            break;
        case 'gram':
            $mime_type = 'application/srgs';
            break;
        case 'grxml':
            $mime_type = 'application/srgs+xml';
            break;
        case 'gtar':
            $mime_type = 'application/x-gtar';
            break;
        case 'hdf':
            $mime_type = 'application/x-hdf';
            break;
        case 'hqx':
            $mime_type = 'application/mac-binhex40';
            break;
        case 'htm':
        case 'html':
            $mime_type = 'text/html';
            break;
        case 'ice':
            $mime_type = 'x-conference/x-cooltalk';
            break;
        case 'ico':
            $mime_type = 'image/x-icon';
            break;
        case 'ics':
        case 'ifb':
            $mime_type = 'text/calendar';
            break;
        case 'ief':
            $mime_type = 'image/ief';
            break;
        case 'iges':
        case 'igs':
            $mime_type = 'model/iges';
            break;
        case 'jnlp':
            $mime_type = 'application/x-java-jnlp-file';
            break;
        case 'jp2':
            $mime_type = 'image/jp2';
            break;
        case 'jpe':
        case 'jpeg':
        case 'jpg':
            $mime_type = 'image/jpeg';
            break;
        case 'js':
            $mime_type = 'application/x-javascript';
            break;
        case 'kar':
        case 'mid':
        case 'midi':
            $mime_type = 'audio/midi';
            break;
        case 'latex':
            $mime_type = 'application/x-latex';
            break;
        case 'm3u':
            $mime_type = 'audio/x-mpegurl';
            break;
        case 'm4a':
        case 'm4b':
        case 'm4p':
            $mime_type = 'audio/mp4a-latm';
            break;
        case 'm4u':
        case 'mxu':
            $mime_type = 'video/vnd.mpegurl';
            break;
        case 'm4v':
            $mime_type = 'video/x-m4v';
            break;
        case 'mac':
        case 'pnt':
        case 'pntg':
            $mime_type = 'image/x-macpaint';
            break;
        case 'man':
        case 'me':
            $mime_type = 'application/x-troff-me';
            break;
        case 'mathml':
            $mime_type = 'application/mathml+xml';
            break;
        case 'mesh':
        case 'msh':
        case 'silo':
            $mime_type = 'model/mesh';
            break;
        case 'mif':
            $mime_type = 'application/vnd.mif';
            break;
        case 'mov':
        case 'qt':
            $mime_type = 'video/quicktime';
            break;
        case 'movie':
            $mime_type = 'video/x-sgi-movie';
            break;
        case 'mp2':
        case 'mp3':
        case 'mpe':
        case 'mpeg':
        case 'mpg':
        case 'mpga':
            $mime_type = 'audio/mpeg';
            break;
        case 'mp4':
            $mime_type = 'video/mp4';
            break;
        case 'ms':
            $mime_type = 'application/x-troff-ms';
            break;
        case 'oda':
            $mime_type = 'application/oda';
            break;
        case 'ogg':
            $mime_type = 'application/ogg';
            break;
        case 'pbm':
            $mime_type = 'image/x-portable-bitmap';
            break;
        case 'pct':
        case 'pic':
        case 'pict':
            $mime_type = 'image/pict';
            break;
        case 'pdb':
            $mime_type = 'chemical/x-pdb';
            break;
        case 'pdf':
            $mime_type = 'application/pdf';
            break;
        case 'pgm':
            $mime_type = 'image/x-portable-graymap';
            break;
        case 'pgn':
            $mime_type = 'application/x-chess-pgn';
            break;
        case 'png':
            $mime_type = 'image/png';
            break;
        case 'pnm':
            $mime_type = 'image/x-portable-anymap';
            break;
        case 'ppm':
            $mime_type = 'image/x-portable-pixmap';
            break;
        case 'ppt':
            $mime_type = 'application/vnd.ms-powerpoint';
            break;
        case 'qti':
        case 'qtif':
            $mime_type = 'image/x-quicktime';
            break;
        case 'ra':
        case 'ram':
            $mime_type = 'audio/x-pn-realaudio';
            break;
        case 'ras':
            $mime_type = 'image/x-cmu-raster';
            break;
        case 'rdf':
            $mime_type = 'application/rdf+xml';
            break;
        case 'rgb':
            $mime_type = 'image/x-rgb';
            break;
        case 'rm':
            $mime_type = 'application/vnd.rn-realmedia';
            break;
        case 'roff':
        case 't':
            $mime_type = 'application/x-troff';
            break;
        case 'rtf':
            $mime_type = 'text/rtf';
            break;
        case 'rtx':
            $mime_type = 'text/richtext';
            break;
        case 'sgm':
        case 'sgml':
            $mime_type = 'text/sgml';
            break;
        case 'sh':
            $mime_type = 'application/x-sh';
            break;
        case 'shar':
            $mime_type = 'application/x-shar';
            break;
        case 'sit':
            $mime_type = 'application/x-stuffit';
            break;
        case 'skd':
        case 'skm':
        case 'skp':
        case 'skt':
            $mime_type = 'application/x-koan';
            break;
        case 'smi':
        case 'smil':
            $mime_type = 'application/smil';
            break;
        case 'spl':
            $mime_type = 'application/x-futuresplash';
            break;
        case 'src':
            $mime_type = 'application/x-wais-source';
            break;
        case 'sv4cpio':
            $mime_type = 'application/x-sv4cpio';
            break;
        case 'sv4crc':
            $mime_type = 'application/x-sv4crc';
            break;
        case 'svg':
            $mime_type = 'image/svg+xml';
            break;
        case 'swf':
            $mime_type = 'application/x-shockwave-flash';
            break;
        case 'tar':
            $mime_type = 'application/x-tar';
            break;
        case 'tcl':
            $mime_type = 'application/x-tcl';
            break;
        case 'tex':
            $mime_type = 'application/x-tex';
            break;
        case 'texi':
            $mime_type = 'application/x-texinfo';
            break;
        case 'texinfo':
            $mime_type = 'application/x-texinfo';
            break;
        case 'tif':
        case 'tiff':
            $mime_type = 'image/tiff';
            break;
        case 'tr':
            $mime_type = 'application/x-troff';
            break;
        case 'tsv':
            $mime_type = 'text/tab-separated-values';
            break;
        case 'ustar':
            $mime_type = 'application/x-ustar';
            break;
        case 'vcd':
            $mime_type = 'application/x-cdlink';
            break;
        case 'vrml':
        case 'wrl':
            $mime_type = 'model/vrml';
            break;
        case 'vxml':
            $mime_type = 'application/voicexml+xml';
            break;
        case 'wav':
            $mime_type = 'audio/x-wav';
            break;
        case 'wbmp':
            $mime_type = 'image/vnd.wap.wbmp';
            break;
        case 'wbmxl':
            $mime_type = 'application/vnd.wap.wbxml';
            break;
        case 'wml':
            $mime_type = 'text/vnd.wap.wml';
            break;
        case 'wmlc':
            $mime_type = 'application/vnd.wap.wmlc';
            break;
        case 'wmls':
            $mime_type = 'text/vnd.wap.wmlscript';
            break;
        case 'wmlsc':
            $mime_type = 'application/vnd.wap.wmlscriptc';
            break;
        case 'xbm':
            $mime_type = 'image/x-xbitmap';
            break;
        case 'xht':
        case 'xhtml':
            $mime_type = 'application/xhtml+xml';
            break;
        case 'xls':
            $mime_type = 'application/vnd.ms-excel';
            break;
        case 'xml':
        case 'xsl':
            $mime_type = 'application/xml';
            break;
        case 'xpm':
            $mime_type = 'image/x-xpixmap';
            break;
        case 'xslt':
            $mime_type = 'application/xslt+xml';
            break;
        case 'xul':
            $mime_type = 'application/vnd.mozilla.xul+xml';
            break;
        case 'xwd':
            $mime_type = 'image/x-xwindowdump';
            break;
        case 'xyz':
            $mime_type = 'chemical/x-xyz';
            break;
        case 'zip':
            $mime_type = 'application/zip';
            break;
    }
    return $mime_type;
}

/**
 * Get contents of a file (if exists), this is a replacement function of file_get_contents()
 * 
 * Note: Because file_get_contents() introduced in PHP 4.3.0 and above, we have to write 
 * our own to make PHP version requirement as much as least possible
 * 
 * @param string $path_file Complete file name with path
 * @param string $mode Mode of the file to read. By default its read-text
 * 
 * @return string content of the file 
 */
function reform_file_get_contents( $path_file, $mode='r' )
{
    if ( file_exists( $path_file ) ) {
        $fh         = fopen( $path_file, $mode );
        $content    = fread( $fh, filesize( $path_file ) );
        fclose( $fh );
        return $content;
    }
    
}
/**
 * Encode array as JSON encoded string. UTF-8 and HTML handling is also in place
 * Its a recursive function.
 * 
 * @param array $array The array that needs to be converted into JSON output
 * @param boolean $convert_to_htmlentities Convert HTML code to html-entities if 1
 * 
 * @return string JSON encoded string
 * 
 */
function reform_json_encode( $array = array(), $convert_to_htmlentities = true )
{
    $output = "{";
    $aOutput= array();
    foreach( $array as $key => $value ) {
        if ( !is_array( $value ) ) {
            if ( is_bool( $value ) ) {
                $value  = ( $value ) ? "true" : "false";
            } else {
                /**
                 * Dont use html-entities when value is UTF-8 encoded and it contains HTML
                 */
                if ( ( mb_detect_encoding( $value, mb_detect_order() ) == "UTF-8" ) 
                    || ( preg_match( '/<[^>]+?>/', $value ) ) ) {
                    $convert_to_htmlentities    = false;
                } else {
                    $convert_to_htmlentities    = true;
                }
                $value    = "\"" . ( ( $convert_to_htmlentities ) ? htmlentities( $value ) : $value ) . "\"";
            }
            if ( !is_integer( $key ) ) {
                $aOutput[]  = "\"". $key . "\": {$value}";
            } else {
                $aOutput[]  = "{$value}";
            }
            
        } else {
            if ( !is_integer( $key ) ) {
                $temp       = "\"$key\":[" . reform_json_encode( $value, $convert_to_htmlentities ) . "]";
                $temp       = str_replace( '{{', '{', $temp );
                $temp       = str_replace( '}}', '}', $temp );
                $aOutput[]  = $temp;
            } else {
                $aOutput[]  = reform_json_encode( $value );
            }
        }
    }
    if ( count( $aOutput ) ) {
        $output .= implode( ",", $aOutput );
    }
    $output .= "}";
    return $output;
}

/**
 * Get value from form values
 * Note: It can be used only after validation.
 * Usage: In files like plugin, email templates &output beautifier
 * 
 * @param array $aFormData Form Values array
 * @param string $field_name Name of the field
 * @param string $separator If its empty, the array will be return other wise
 *                          a string separated by this character be it comma etc
 * @param boolean $filtered_data Filtered data or original value
 * 
 * @return mixed array if no separator provided or a string or false if $aFormData 
 *                     contains nothing
 */
function get( $aFormData, $field_name, $separator = '', $filtered_data = true )
{
    $value_key  = ( $filtered_data ) ? 'value' : 'original';
    $aMultipleField = array();
    foreach( $aFormData as $key => $aFieldData ) {
        if ( $aFieldData['name'] == $field_name ) {
            $aMultipleField[]   = $aFieldData[$value_key];
        }
    }
    if ( empty( $separator ) ) {
        return $aMultipleField;
    }
    return implode( $separator, $aMultipleField);
}