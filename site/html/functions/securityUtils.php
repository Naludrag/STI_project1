<?php
class SecurityUtils {

    const DUMMY_HASH = '$2y$10$0G8VDQJbsMUg2uv5ATLCNO2MMdby5P7UsYgQ/5LxgeqQCuMa3PcUK';

    /**
     * Strip tags from $data and convert special characters (+ double & single quotes) to HTML entities data to sanitize
     * @param $data String data to sanitize
     * @return String sanitized string
     */
     public static function sanitize_output($data) {
        // Strip tags from $data and convert special characters (+ double & single quotes) to HTML entities
        return htmlspecialchars(strip_tags($data), ENT_QUOTES, 'UTF-8');
     }

    /**
     * Sanitize data for the database to prevent potential XSS on phpliteadmin
     * @param $data string data to sanitize
     * @return string sanitized string
     */
     public static function sanitize_for_db($data) {
         // Strip tags from $data and convert special characters (+ double & single quotes) to HTML entities
         return strip_tags($data);
     }

    /**
     * Will return a const response for authentification of a user
     * @param $password String password of the user
     * @param $userDetails Object user to authenticate
     * @return bool true if the user was authenticated false otherwise
     */
    public static function constant_time_authentication($password, $userDetails){
        // We will make a pass_verify in each case to have a const time in the repsonse
        if ($userDetails) {
            return password_verify($password, $userDetails['passwordHash']);
        } else {
            password_verify("", SecurityUtils::DUMMY_HASH);
        }
        return false;
    }

    /**
     * @param $token
     */
    public static function verify_csrf_token($token){
        if (empty($token) || !SecurityUtils::c_hash_equals($_SESSION['csrf-token'], $token)) {
            header ('location: login.php');
            exit();
        }
    }

    /**
     * Implementation of hash_equals for older version from https://www.php.net/manual/en/function.hash-equals.php#115635.git
     * @param $str1
     * @param $str2
     * @return bool
     */
    private static function c_hash_equals($str1, $str2) {
        if (!function_exists('hash_equals')) {
            if (strlen($str1) != strlen($str2)) {
                return false;
            } else {
                $res = $str1 ^ $str2;
                $ret = 0;
                for ($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
                return !$ret;
            }
        } else {
            return hash_equals($str1, $str2);
        }
    }
}
