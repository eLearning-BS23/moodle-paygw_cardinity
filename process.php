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

/*
 * Various helper methods for interacting with the portwallet API
 *
 * @package    paygw_portwallet
 * @copyright  2021 Brain station 23 ltd.
 * @author     Brain station 23 ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_payment\helper;

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->dirroot . '/course/lib.php');
global $CFG, $USER, $DB;

defined('MOODLE_INTERNAL') || die();

require_login();

$status = required_param('status', PARAM_TEXT);
$courseid = required_param("id", PARAM_INT);
$component = required_param('component', PARAM_ALPHANUMEXT);
$paymentarea = required_param('paymentarea', PARAM_ALPHANUMEXT);
$itemid = required_param('itemid', PARAM_INT);
$status = required_param('status', PARAM_TEXT);
// Load Cardinity Configuration.
$config = (object) helper::get_gateway_configuration($component, $paymentarea, $itemid, 'cardinity');

$projectsecret = $config->secretkey;
$message = '';
ksort($_POST);

foreach ($_POST as $key => $value) {
    if ($key == 'signature') {
        continue;
    }
    $message .= $key . $value;
}

$signature = hash_hmac('sha256', $message, $projectsecret);

if ($signature == $_POST['signature']) {
    $paymentrecord = new stdClass();
    $paymentrecord->courseid = $courseid;
    $paymentrecord->itemid = $itemid;
    $paymentrecord->userid = $USER->id;
    $paymentrecord->currency = required_param('currency', PARAM_TEXT);
    $paymentrecord->payment_status = $status;
    $paymentrecord->txn_id = required_param('order_id', PARAM_TEXT);
    $paymentrecord->timeupdated = time();

    if ($status == "approved") {
        redirect($CFG->wwwroot . '/payment/gateway/cardinity/success.php?id=' .
        $courseid . '&component=' . $component . '&paymentarea=' .
        $paymentarea . '&itemid=' . $itemid);
        exit();
    } else {
        redirect($CFG->wwwroot . '/payment/gateway/cardinity/cancel.php?id=' . $courseid);
        exit();
    }
} else {
    redirect($CFG->wwwroot . '/payment/gateway/cardinity/cancel.php?id=' . $courseid);
    exit();
}
