<?php

namespace paygw_cardinity;

use coding_exception;
use core_payment\form\account_gateway;
use stdClass;

class gateway extends \core_payment\gateway
{

    /**
     * @inheritDoc
     */
    public static function get_supported_currencies(): array
    {
        return ['EUR', 'GBP', 'USD'];
    }

    /**
     * @inheritDoc
     */
    public static function add_configuration_to_gateway_form(account_gateway $form): void
    {
        $mform = $form->get_mform();

        $mform->addElement('text', 'apikey', get_string('apikey', 'paygw_cardinity'));
        $mform->setType('apikey', PARAM_TEXT);
        $mform->addHelpButton('apikey', 'apikey', 'paygw_cardinity');

        $mform->addElement('text', 'secretkey', get_string('secretkey', 'paygw_cardinity'));
        $mform->setType('secretkey', PARAM_TEXT);
        $mform->addHelpButton('secretkey', 'secretkey', 'paygw_cardinity');

        $paymentmethods = [
            'card' => get_string('paymentmethod:card', 'paygw_cardinity'),
//            'alipay' => get_string('paymentmethod:alipay', 'paygw_cardinity'),
//            'bancontact' => get_string('paymentmethod:bancontact', 'paygw_cardinity'),
//            'eps' => get_string('paymentmethod:eps', 'paygw_cardinity'),
//            'giropay' => get_string('paymentmethod:giropay', 'paygw_cardinity'),
//            'ideal' => get_string('paymentmethod:ideal', 'paygw_cardinity'),
//            'p24' => get_string('paymentmethod:p24', 'paygw_cardinity'),
//            'sepa_debit' => get_string('paymentmethod:sepa_debit', 'paygw_cardinity'),
//            'sofort' => get_string('paymentmethod:sofort', 'paygw_cardinity'),
//            'upi' => get_string('paymentmethod:upi', 'paygw_cardinity'),
//            'netbanking' => get_string('paymentmethod:netbanking', 'paygw_cardinity')
        ];
        $method = $mform->addElement('select', 'paymentmethods', get_string('paymentmethods', 'paygw_cardinity'), $paymentmethods);
        $mform->setType('paymentmethods', PARAM_TEXT);
        $mform->setDefault('paymentmethods', 'card');
        $method->setMultiple(true);

        $mform->addElement('advcheckbox', 'allowpromotioncodes', get_string('allowpromotioncodes', 'paygw_cardinity'));
        $mform->setDefault('allowpromotioncodes', true);

        $mform->addElement('advcheckbox', 'enableautomatictax', get_string('enableautomatictax', 'paygw_cardinity'),
            get_string('enableautomatictax_desc', 'paygw_cardinity'));

        $mform->addElement('select', 'defaulttaxbehavior', get_string('defaulttaxbehavior', 'paygw_cardinity'), [
            'exclusive' => get_string('taxbehavior:exclusive', 'paygw_cardinity'),
            'inclusive' => get_string('taxbehavior:inclusive', 'paygw_cardinity'),
        ]);
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
    public static function validate_gateway_form(account_gateway $form,
                                                 stdClass $data, array $files, array &$errors): void {
        if ($data->enabled && (empty($data->apikey) || empty($data->secretkey) || empty($data->paymentmethods))) {
            $errors['enabled'] = get_string('gatewaycannotbeenabled', 'payment');
        }
    }
}