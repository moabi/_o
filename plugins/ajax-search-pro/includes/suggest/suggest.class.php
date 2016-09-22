<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

/**
 * A sample and parent class for keyword suggestion and autocomplete
 */

if (!class_exists('wpd_keywordSuggest')) {
    class wpd_keywordSuggest {

        protected  $maxCount;

        protected  $maxCharsPerWord;

        function getKeywords($q) {
            return array();
        }

        function can_get_file() {
            if (function_exists('curl_init')){
                return 1;
            } else if (ini_get('allow_url_fopen')==true) {
                return 2;
            }
            return false;
        }

        function url_get_contents($Url, $method) {
            if ($method==2) {
                return file_get_contents($Url);
            } else if ($method==1) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $Url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $output = curl_exec($ch);
                curl_close($ch);
                return $output;
            }
        }

        /**
         * Performs a full escape
         *
         * @uses wd_mysql_escape_mimic()
         * @param $string
         * @return array|mixed
         */
        protected function escape( $string ) {
            global $wpdb;

            // recursively go through if it is an array
            if ( is_array($string) ) {
                foreach ($string as $k => $v) {
                    $string[$k] = $this->escape($v);
                }
                return $string;
            }

            if ( is_float( $string ) )
                return $string;

            // Escape support for WP < 4.0
            if ( method_exists( $wpdb, 'esc_like' ) )
                return esc_sql( $wpdb->esc_like($string) );

            return esc_sql( wd_mysql_escape_mimic($string) );
        }

    }
}