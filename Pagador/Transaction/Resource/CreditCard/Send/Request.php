<?php

/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Braspag\Braspag\Pagador\Transaction\Resource\CreditCard\Send;

use Braspag\Braspag\Factories\AntiFraudRequestFactory;
use Braspag\Braspag\Factories\CreditCardAvsRequestFactory;
use Braspag\Braspag\Factories\PaymentSplitRequestFactory;
use Braspag\Braspag\Pagador\Transaction\Api\AntiFraud\RequestInterface as AntiFraudRequest;
use Braspag\Braspag\Pagador\Transaction\Resource\RequestAbstract;
use Braspag\Braspag\Pagador\Transaction\Api\CreditCard\Send\RequestInterface as Data;

class Request extends RequestAbstract
{
    /**
     * @param Data $data
     */
    public function __construct(Data $data)
    {
        $this->data = $data;
        $this->prepareParams();
    }

    /**
     * @return $this
     */
    protected function prepareParams()
    {
        $creditCardParamKey = 'creditCard';
        if ($this->data->getPaymentType() === 'PrivateLabelCard') {
            $creditCardParamKey = 'PrivateLabelCard';
        }

        $this->params = [
            'headers' => [
                'Content-Type' => self::CONTENT_TYPE_APPLICATION_JSON,
                'MerchantId' => $this->data->getMerchantId(),
                'MerchantKey' => $this->data->getMerchantKey()
            ],
            'body' => [
                'merchantOrderId' => $this->data->getMerchantOrderId(),
                'customer' => [
                    'name' => $this->data->getCustomerName(),
                    'identity' => $this->data->getCustomerIdentity(),
                    'identityType' => $this->data->getCustomerIdentityType(),
                    'email' => $this->data->getCustomerEmail(),
                    'birthDate' => $this->data->getCustomerBirthDate(),
                    'phone' => $this->data->getCustomerAddressPhone(),
                    'address' => [
                        'street' => (substr($this->data->getCustomerAddressStreet(), 0, 53)),
                        'number' => (substr($this->data->getCustomerAddressNumber(), 0, 4)),
                        'complement' => (substr($this->data->getCustomerAddressComplement(), 0,10)),
                        'zipCode' => $this->data->getCustomerAddressZipCode(),
                        'district' => (substr($this->data->getCustomerAddressDistrict(), 0, 44)),
                        'city' => $this->data->getCustomerAddressCity(),
                        'state' => $this->data->getCustomerAddressState(),
                        'country' => $this->data->getCustomerAddressCountry(),
                    ],
                    'deliveryAddress' => [
                        'street' => (substr($this->data->getCustomerDeliveryAddressStreet(), 0, 53)),
                        'number' => (substr($this->data->getCustomerDeliveryAddressNumber(), 0, 4)),
                        'complement' =>(substr($this->data->getCustomerDeliveryAddressComplement(), 0,10)),
                        'zipCode' => $this->data->getCustomerDeliveryAddressZipCode(),
                        'district' => (substr($this->data->getCustomerDeliveryAddressDistrict(), 0, 44)),
                        'city' => $this->data->getCustomerDeliveryAddressCity(),
                        'state' => $this->data->getCustomerDeliveryAddressState(),
                        'country' => $this->data->getCustomerDeliveryAddressCountry(),
                    ]
                ],
                'payment' => [
                    'type' => ($this->data->getPaymentType()) ?: Data::PAYMENT_TYPE,
                    'amount' => $this->data->getPaymentAmount(),
                    'currency' => $this->data->getPaymentCurrency(),
                    'country' => $this->data->getPaymentCountry(),
                    'provider' => $this->data->getPaymentProvider(),
                    'serviceTaxAmount' => $this->data->getPaymentServiceTaxAmount(),
                    'installments' => $this->data->getPaymentInstallments(),
                    'interest' => $this->data->getPaymentInterest(),
                    'capture' => $this->data->getPaymentCapture(),
                    'doSplit' => $this->data->getPaymentDoSplit(),
                    'authenticate' => $this->data->getPaymentAuthenticate(),
                    'returnUrl' => $this->data->getReturnUrl(),
                    'softDescriptor' => $this->data->getPaymentSoftDescriptor(),
                    'Partner' => 'MAG',
                    $creditCardParamKey => $this->getCreditCardParams(),
                    'extraDataCollection' => $this->getPaymentExtraDataCollection()
                ]
            ]
        ];

        if (($antiFraudRequest = $this->data->getAntiFraudRequest()) && !$this->data->getPaymentAuthenticate()) {
            $antiFraud = AntiFraudRequestFactory::make($antiFraudRequest);
            $this->params['body']['payment']['FraudAnalysis'] = $antiFraud->getParams();
            $this->params['body']['payment']['FraudAnalysis']['Shipping']['Identity'] = $this->data->getCustomerIdentity();
            $this->params['body']['payment']['FraudAnalysis']['Shipping']['IdentityType'] = $this->data->getCustomerIdentityType();
            $this->params['body']['payment']['FraudAnalysis']['Shipping']['Street'] = $this->data->getCustomerDeliveryAddressStreet();
            $this->params['body']['payment']['FraudAnalysis']['Shipping']['Number'] = $this->data->getCustomerDeliveryAddressNumber();
            $this->params['body']['payment']['FraudAnalysis']['Shipping']['Complement'] = $this->data->getCustomerDeliveryAddressComplement();
            $this->params['body']['payment']['FraudAnalysis']['Shipping']['Neighborhood'] = $this->data->getCustomerDeliveryAddressDistrict();
            $this->params['body']['payment']['FraudAnalysis']['Shipping']['City'] = $this->data->getCustomerDeliveryAddressCity();
            $this->params['body']['payment']['FraudAnalysis']['Shipping']['State'] = $this->data->getCustomerDeliveryAddressState();
            $this->params['body']['payment']['FraudAnalysis']['Shipping']['Country'] = $this->data->getCustomerDeliveryAddressCountry();
            $this->params['body']['payment']['FraudAnalysis']['Shipping']['ZipCode'] = $this->data->getCustomerDeliveryAddressZipCode();
            $this->params['body']['payment']['FraudAnalysis']['Shipping']['Email'] = $this->data->getCustomerEmail();

            // Formatacoes especificas ClearSale
            if (isset($this->params['body']['payment']['FraudAnalysis']['Provider'])
                && $this->params['body']['payment']['FraudAnalysis']['Provider'] === 'ClearSale'
            ) {
                $this->params['body']['customer']['phone'] = $this->formatPhoneClearSale(
                    $this->params['body']['customer']['phone']
                );
                $this->params['body']['customer']['address']['zipCode'] = $this->formatZipCode(
                    $this->params['body']['customer']['address']['zipCode']
                );
                $this->params['body']['customer']['deliveryAddress']['zipCode'] = $this->formatZipCode(
                    $this->params['body']['customer']['deliveryAddress']['zipCode']
                );
                $this->params['body']['payment']['FraudAnalysis']['Shipping']['ZipCode'] = $this->formatZipCode(
                    $this->params['body']['payment']['FraudAnalysis']['Shipping']['ZipCode']
                );
            }
        }


        if ($avsRequest = $this->data->getAvsRequest()) {
            $avs = CreditCardAvsRequestFactory::make($avsRequest);
            $this->params['body']['payment']['creditCard']['Avs'] = $avs->getParams();
        }

        $paymentSplitRequest = $this->data->getPaymentSplitRequest();

        if (
            $paymentSplitRequest && $this->data->getPaymentCapture()
        ) {
            $splitData = PaymentSplitRequestFactory::make($paymentSplitRequest)->getParams();
            $this->params['body']['payment']['SplitPayments'] = $splitData['body']['SplitPayments'];
            $this->params['body']['Payment']['SplitTransaction'] = $splitData['body']['SplitTransaction'];
        }

        if ($this->data->getPaymentAuthenticate()) {
            $this->params['body']['payment']['externalAuthentication'] = $this->getExternalAuthenticationParams();
        }

        $device = $this->data->getFromDevice();
        //if (isset($device)  && strtoupper($this->data->getPaymentProvider()) == 'MASTERCARD') {
        if (isset($device) ) {
            $this->params['body']['payment']['InitiatedTransactionIndicator.Category'] = 'C1';
            $this->params['body']['payment']['InitiatedTransactionIndicator.Subcategory'] = 'CredentialsOnFile';
        }

        return $this;
    }

    protected function getCreditCardParams()
    {
        if ($this->data->getPaymentCreditCardCardToken()) {
            return $this->getCreditCardTokenParams();
        }

        if ($this->data->getPaymentCreditSoptpaymenttoken()) {
            return $this->getCreditCardSilentOrderPostParams();
        }

        return [
            'cardNumber' => $this->data->getPaymentCreditCardCardNumber(),
            'holder' => $this->data->getPaymentCreditCardHolder(),
            'expirationDate' => $this->data->getPaymentCreditCardExpirationDate(),
            'securityCode' => $this->data->getPaymentCreditCardSecurityCode(),
            'saveCard' => $this->data->getPaymentCreditCardSaveCard(),
            'brand' => $this->data->getPaymentCreditCardBrand(),
        ];
    }

    protected function getExternalAuthenticationParams()
    {
        return [
           "Cavv" => $this->data->getPaymentExternalAuthenticationCavv(),
           "Xid" => $this->data->getPaymentExternalAuthenticationXid(),
           "Eci" => $this->data->getPaymentExternalAuthenticationEci(),
           "Version" => $this->data->getPaymentCardExternalAuthenticationVersion(),
           "ReferenceID" => $this->data->getPaymentExternalAuthenticationReferenceId()
        ];
    }

    protected function getCreditCardSilentOrderPostParams()
    {
        return [
            'paymentToken' => $this->data->getPaymentCreditSoptpaymenttoken(),
            'brand' => $this->data->getPaymentCreditCardBrand(),
            'saveCard' => $this->data->getPaymentCreditCardSaveCard(),
        ];
    }

    protected function getCreditCardTokenParams()
    {
        return [
            'cardToken' => $this->data->getPaymentCreditCardCardToken(),
            'securityCode' => $this->data->getPaymentCreditCardSecurityCode(),
            'brand' => $this->data->getPaymentCreditCardBrand(),
        ];
    }

    protected function getPaymentExtraDataCollection()
    {
        $extDataCollection = array(array('Name' => 'Plataforma', 'Value' => 'Magento'));
        return $extDataCollection;
    }

    /**
     * @param string $zipCode
     * @return string
     */
    private function formatZipCode($zipCode)
    {
        $digits = preg_replace('/[^0-9]/', '', $zipCode);
        if (strlen($digits) === 8) {
            return substr($digits, 0, 5) . '-' . substr($digits, 5, 3);
        }
        return $zipCode;
    }

    /**
     * @param string $phone
     * @return string
     */
    private function formatPhoneClearSale($phone)
    {
        $digits = substr(preg_replace('/[^0-9]/', '', $phone), 0, 13);

        if (strlen($digits) >= 12 && substr($digits, 0, 2) === '55') {
            $ddi = substr($digits, 0, 2);
            $ddd = substr($digits, 2, 2);
            $number = substr($digits, 4);
        } elseif (strlen($digits) >= 10) {
            $ddi = '55';
            $ddd = substr($digits, 0, 2);
            $number = substr($digits, 2);
        } else {
            return $phone;
        }

        if (strlen($number) === 9) {
            $part1 = substr($number, 0, 5);
            $part2 = substr($number, 5);
        } else {
            $part1 = substr($number, 0, 4);
            $part2 = substr($number, 4);
        }

        return '+' . $ddi . ' ' . $ddd . ' ' . $part1 . '-' . $part2;
    }
}
