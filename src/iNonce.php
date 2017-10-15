<?php
/**
 * iNonce: Simple nonce class
 *
 * @link https://github.com/MahdiY/iNonce
 * @author MahdiY
 * @since 2017-06-05
 * @copyright MahdiY (MahdiY.IR)
 * @version 1.0
 * @license BSD http://www.opensource.org/licenses/bsd-license.php
 */

namespace iNonce;
 
class iNonce {

    /*
     * Nonce Salt
     *
     * @var string Some salt to create secure nonce. Default ''.
     */
    private static $salt = '';

    /*
     * lifespan of nonces in seconds.
     *
     * @var int Lifespan of nonces in seconds. Default 86,400 seconds, or one day.
     */
    private static $time = 86400;

    /*
     * Name of nonce
     *
     * @var string Nonce name. Default '_nonce'.
     */
    private static $key = '_nonce';

    /*
     * Initialize nonce options
     *
     * Check whether nonce options is standard or not
     */
    private static function init() {
        if( empty( self::$salt ) ) {
            die( "iNonce: Salt can't be empty!" );
        }

        if( ! self::$time ) {
            self::$time = 86400;
        }

        if( is_null( self::$key ) ) {
            self::$key = '_nonce';
        }
    }

    /**
     * @param string $salt
     */
    public static function setSalt( $salt ) {
        self::$salt = $salt;
    }

    /**
     * @param int $time
     */
    public static function setTime( $time ) {
        if( $time ) {
            selft::$time = intval( $time );
        }
    }

    /**
     * @param string $key
     */
    public static function setKey( $key ) {
        self::$key = $key;
    }

    /**
     * @return int
     */
    public static function getTime() {
        return self::$time;
    }

    /**
     * @return string
     */
    public static function getKey() {
        return self::$key;
    }

    /*
     * Retrieve nonce query
     *
     * @param string $action Action name.
     * @param string $user Optional. nonce-owning user. Default ''.
     * @return string Nonce query
     */
    public static function query( $action, $user = '' ) {
        return self::$key . '=' . self::nonce( $action, $user );
    }

    /*
     * Retrieve or display nonce hidden field for forms.
     *
     * @param string $action Action name.
     * @param string $user Optional. nonce-owning user. Default ''.
     * @param bool $echo Optional. Whether to display or return hidden form field. Default true.
     * @return string Nonce field HTML markup.
     */
    public static function input( $action, $user = '', $echo = true ) {
        $function = $echo ? 'printf' : 'sprintf';

        return $function( '<input type="hidden" id="%1$s" name="%1$s" value="%2$s" />', self::$key, self::nonce( $action, $user ) );
    }

    /*
     * Verify that correct nonce was used with time limit.
     *
     * @param string $nonce Nonce that was used in the form to verify.
     * @param string $action Action name.
     * @param string $user Optional. nonce-owning user. Default ''.
     * @return bool False if the nonce is invalid, true if the nonce is valid and generated between 0 - self::$time seconds ago.
     */
    public static function verify( $nonce, $action, $user = '' ) {
        return self::nonce( $action, $user ) == $nonce;
    }

    /*
     * Creates a cryptographic token tied to below list:
     *
     * - action
     * - user
     * - user IP
     * - user agent
     * - session id
     * - __FILE__
	 * - some salt!
     * - window of time
     *
     * @param string $action Action name.
     * @param string $user nonce-owning user.
     * @return string The nonce.
     */
    public static function nonce( $action, $user ) {
        self::init();

        $i = ceil( time() / ( self::$time / 2 ) );

        $token = md5( $i . $_SERVER['HTTP_USER_AGENT'] . $action . self::IP() . $user . self::$salt . __FILE__ . session_id() );

        return substr( $token, -12, 10 );
    }

    /*
     * Get current user IP
     *
     * @return string IP
     */
    public static function IP() {
        if( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        if( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return $_SERVER['REMOTE_ADDR'];
    }
}
