<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Contains helper class to work with Cardinity REST API.
 *
 * @package    paygw_cardinity
 * @copyright  2021 Brain station 23 ltd.
 * @author     Brain station 23 ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace paygw_cardinity;
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/filelib.php');

class cardinity_helper {

    /**
     * @var string The base API URL
     */
    private $baseurl;

    /**
     * @var string Client consumerkey
     */
    private $consumerkey;

    /**
     * @var string Cardinity secret
     */
    private $consumersecret;

    /**
     * @var string The oath bearer token
     */
    private $token;

    /**
     * @param string $consumerkey
     * @param string $consumersecret
     */
    public function __construct(string $consumerkey, string $consumersecret) {
        $this->consumerkey = $consumerkey;
        $this->consumersecret = $consumersecret;
        $this->baseurl = 'https://api.cardinity.com/';
    }

    /**
     * @param $url
     * @param $method
     * @param $params
     * @return string
     */
    private function build_base_string($url, $method, $params) {
        $paramstrings = array();
        ksort($params);
        foreach ($params as $key => $value) {
            $paramstrings[] = "$key=" . rawurlencode($value);
        }
        return $method . "&" . rawurlencode($this->baseurl . $url) . '&' . rawurlencode(implode('&', $paramstrings));
    }

    /**
     * @param $oauth
     * @return string
     */
    private function build_authorization_header($oauth) {
        $headerstring = 'Authorization: OAuth ';
        $values = array();
        foreach ($oauth as $key => $value) {
            $values[] = "$key=\"" . rawurlencode($value) . "\"";
            $headerstring .= implode(', ', $values);
        }
        return $headerstring;
    }

    /**
     * @param $paymentid
     * @return bool
     */
    public function check_payment_status($paymentid) {
        $requestparam = 'v1/payments/' . $paymentid;
        $timestamp = time();
        $authnonce = $timestamp . '123';
        $oauthparams = [
            'oauth_consumer_key' => $this->consumerkey,
            'oauth_nonce' => $authnonce,
            'oauth_signature_method' => "HMAC-SHA1",
            'oauth_timestamp' => $timestamp,
            'oauth_version' => '1.0',
        ];
        $baseurlstring = $this->build_base_string($requestparam, 'GET', $oauthparams);
        $consumersecret = rawurlencode($this->consumersecret) . '&';
        $hash = hash_hmac('sha1', $baseurlstring, $consumersecret, true);
        $signature = base64_encode($hash);

        $oauthparams['oauth_signature'] = $signature;
        $headers = array(
            'Content-Type: application/json',
            $this->build_authorization_header($oauthparams),
        );

        $curlconnection = curl_init($this->baseurl . $requestparam);

        curl_setopt($curlconnection, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curlconnection, CURLOPT_RETURNTRANSFER, true);

        $apiresponse = curl_exec($curlconnection);

        curl_close($curlconnection);

        $jsonarrayresponse = json_decode($apiresponse, true);

        return $jsonarrayresponse['status'] == 'approved' ?? false;
    }

}
