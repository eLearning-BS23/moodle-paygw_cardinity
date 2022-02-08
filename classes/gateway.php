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
namespace paygw_cardinity;

use coding_exception;
use core_payment\form\account_gateway;
use stdClass;

class gateway extends \core_payment\gateway {

    /**
     * @inheritDoc
     */
    public static function get_supported_currencies(): array {
        return ['EUR', 'GBP', 'USD'];
    }

    /**
     * @inheritDoc
     */
    public static function add_configuration_to_gateway_form(account_gateway $form): void {
        $mform = $form->get_mform();
        $mform->addElement('text', 'clientid', get_string('clientid', 'paygw_cardinity'));
        $mform->setType('clientid', PARAM_TEXT);
        $mform->addHelpButton('clientid', 'clientid', 'paygw_cardinity');
        $mform->addElement('text', 'secretkey', get_string('secretkey', 'paygw_cardinity'));
        $mform->setType('secretkey', PARAM_TEXT);
        $mform->addHelpButton('secretkey', 'secretkey', 'paygw_cardinity');
        $mform->addHelpButton('defaulttaxbehavior', 'defaulttaxbehavior', 'paygw_cardinity');
    }

    /**
     * Validates the gateway configuration form.
     *
     * @param account_gateway $form
     * @param stdClass $data
     * @param array $files
     * @param array $errors form errors (passed by reference)
     * @throws coding_exception
     */
    public static function validate_gateway_form(
        account_gateway $form,
        stdClass $data,
        array $files,
        array &$errors
    ): void {
        if ($data->enabled && (empty($data->clientid) || empty($data->secretkey))) {
            $errors['enabled'] = get_string('gatewaycannotbeenabled', 'payment');
        }
    }
}
