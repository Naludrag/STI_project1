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

}
