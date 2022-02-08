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
 * Redirects to the cardinity checkout for payment
 *
 * @package    paygw_cardinity
 * @copyright  2021 Brain station 23 ltd.
 * @author     Brain station 23 ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core\uuid;
use core_payment\helper;

require_once(__DIR__ . '/../../../config.php');

require_login();
global $CFG, $USER, $DB;

$component   = required_param('component', PARAM_ALPHANUMEXT);
$paymentarea = required_param('paymentarea', PARAM_ALPHANUMEXT);
$itemid      = required_param('itemid', PARAM_INT);
$description = required_param('description', PARAM_TEXT);
$courseid   = $DB->get_field('enrol', 'courseid', ['enrol' => 'fee', 'id' => $itemid]);
$config     = (object) helper::get_gateway_configuration($component, $paymentarea, $itemid, 'cardinity');
$payable    = helper::get_payable($component, $paymentarea, $itemid);
$surcharge  = helper::get_gateway_surcharge('cardinity');
$cost       = helper::get_rounded_cost($payable->get_amount(), $payable->get_currency(), $surcharge);

$amount = number_format((float)$cost, 2, '.', '');
$cancelurl = $CFG->wwwroot . '/payment/gateway/cardinity/cancel.php?id=' . $courseid . '&component=' . $component .
  '&paymentarea=' . $paymentarea . '&itemid=' . $itemid;
$country = !empty($USER->country) ? $USER->country : 'BD';
$language = "EN";
$currency = $payable->get_currency();
$description = $description;
$orderid = uniqid();
$returnurl = $CFG->wwwroot . '/payment/gateway/cardinity/process.php?id=' .
  $courseid . '&component=' . $component .
  '&paymentarea=' . $paymentarea . '&itemid=' . $itemid;

$projectid = $config->clientid;
$projectsecret = $config->secretkey;

$attributes = [
  "amount" => $amount,
  "currency" => $currency,
  "country" => $country,
  "language" => $language,
  "order_id" => $orderid,
  "description" => $description,
  "project_id" => $projectid,
  "cancel_url" => $cancelurl,
  "return_url" => $returnurl,
];

ksort($attributes);

$message = '';
foreach ($attributes as $key => $value) {
    $message .= $key . $value;
}

$signature = hash_hmac('sha256', $message, $projectsecret);

?>
<html>
<head>
  <title>Cardiniy Hosted Payment Page</title>
  </style>
</head>
<body onload="document.forms['checkout'].submit()">
  <div class="loader"></div>
  <form name="checkout" method="POST" action="https://checkout.cardinity.com">
    <input type="hidden" name="amount" value="<?php $amount; ?>" />
    <input type="hidden" name="cancel_url" value="<?php $cancelurl; ?>" />
    <input type="hidden" name="country" value="<?php $country; ?>" />
    <input type="hidden" name="language" value="<?php $language; ?>" />
    <input type="hidden" name="currency" value="<?php $currency; ?>" />
    <input type="hidden" name="description" value="<?php $description; ?>" />
    <input type="hidden" name="order_id" value="<?php $orderid; ?>" />
    <input type="hidden" name="project_id" value="<?php $projectid; ?>" />
    <input type="hidden" name="return_url" value="<?php $returnurl; ?>" />
    <input type="hidden" name="signature" value="<?php $signature; ?>" />
  </form>
</body>
</html>
