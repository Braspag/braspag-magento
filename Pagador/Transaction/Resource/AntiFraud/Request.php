<?php

/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Braspag\Braspag\Pagador\Transaction\Resource\AntiFraud;

use Braspag\Braspag\Pagador\Transaction\Api\AntiFraud\Items\RequestInterface as AntiFraudItemsRequest;
use Braspag\Braspag\Pagador\Transaction\Api\AntiFraud\MDD\GeneralRequestInterface;
use Braspag\Braspag\Pagador\Transaction\Resource\RequestAbstract;
use Braspag\Braspag\Pagador\Transaction\Api\AntiFraud\RequestInterface as Data;

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
        $this->params = [
            'Sequence' => $this->data->getSequence(),
            'SequenceCriteria' => $this->data->getSequenceCriteria(),
            'FingerPrintId' => $this->data->getFingerPrintId(),
            'CaptureOnLowRisk' => $this->data->getCaptureOnLowRisk(),
            'VoidOnHighRisk' => $this->data->getVoidOnHighRisk(),
            'Browser' => [
                'CookiesAccepted' => $this->data->getBrowserCookiesAccepted(),
                'Email' => $this->data->getBrowserEmail(),
                'HostName' => $this->data->getBrowserHostName(),
                'IpAddress' => $this->data->getBrowserIpAddress(),
                'Type' => $this->data->getBrowserType()
            ],
            'Cart' => [
                'IsGift' => $this->data->getCartIsGift(),
                'ReturnsAccepted' => $this->data->getCartReturnsAccepted(),
                'Items' => $this->getItems($this->data->getCartItems())
            ],
                'Shipping' => [
                'Addressee' => $this->data->getCartShippingAddressee(),
                'Method' => $this->data->getCartShippingMethod(),
                'Phone' => $this->data->getCartShippingPhone()
            ],
        ];

        $hasMDDS =  $this->getMDDs($this->data->getMerchantDefinedFields());
        if ($hasMDDS) {
            $this->params['MerchantDefinedFields'] = $this->getMDDs($this->data->getMerchantDefinedFields());
        }

        return $this;
    }

    /**
     * @param array $items
     * @return array
     * @throws \Exception
     */
    private function getItems(array $items = [])
    {
        $result  = [];
        /** @var AntiFraudItemsRequest $item */
        foreach ($items as $item) {
            if (! $item instanceof AntiFraudItemsRequest) {
                throw new \Exception('items params not valid, is have must instance of "\Braspag\Braspag\Pagador\Transaction\Api\AntiFraud\Items\RequestInterface"');
            }

            $result[] = [
                'GiftCategory' => $item->getGiftCategory(),
                'HostHedge' => $item->getHostHedge(),
                'NonSensicalHedge' => $item->getNonSensicalHedge(),
                'ObscenitiesHedge' => $item->getObscenitiesHedge(),
                'PhoneHedge' => $item->getPhoneHedge(),
                'Name' => $item->getName(),
                'Quantity' => preg_replace('/[^0-9]/', '', $item->getQuantity()),
                'Sku' => $item->getSku(),
                'UnitPrice' => $item->getUnitPrice(),
                'Risk' => $item->getRisk(),
                'TimeHedge' => $item->getTimeHedge(),
                'Type' => $item->getType(),
                'VelocityHedge' => $item->getVelocityHedge(),
                'Passenger' => [
                    'Email' => $item->getPassengerEmail(),
                    'Identity' => $item->getPassengerIdentity(),
                    'Name' => $item->getPassengerName(),
                    'Rating' => $item->getPassengerRating(),
                    'Phone' => $item->getPassengerPhone(),
                    'Status' => $item->getPassengerStatus()
                ]
            ];
        }

        return $result;
    }

    private function getMDDs(GeneralRequestInterface $data)
    {

        $storeCode = $data->getStoreCode();

        $mddCollection = [
            [
                'Id' => GeneralRequestInterface::MDD_KEY_CUSTOMER_NAME,
                'Value' => substr($data->getCustomerName(), 0, GeneralRequestInterface::MDD_KEY_LIMIT_CHARACTERS)
            ],
            [
                'Id' => GeneralRequestInterface::MDD_KEY_SALES_ORDER_CHANNEL,
                'Value' => substr($data->getSalesOrderChannel(), 0, GeneralRequestInterface::MDD_KEY_LIMIT_CHARACTERS)
            ],
            [
                'Id' => GeneralRequestInterface::MDD_KEY_CUSTOMER_FETCH_SELF,
                'Value' => substr($data->getCustomerFetchSelf(), 0, GeneralRequestInterface::MDD_KEY_LIMIT_CHARACTERS)
            ],
            [
                'Id' => GeneralRequestInterface::MDD_KEY_QTY_INSTALLMENTS_ORDER,
                'Value' => substr($data->getQtyInstallmentsOrder(), 0, GeneralRequestInterface::MDD_KEY_LIMIT_CHARACTERS)
            ],
            [
                'Id' => GeneralRequestInterface::MDD_KEY_PLATAFORM_NAME,
                'Value' => substr($data->getPlataformName(), 0, GeneralRequestInterface::MDD_KEY_LIMIT_CHARACTERS)
            ]
        ];

        if ($data->getStoreCode() && $data->getCustomerFetchSelf() == 'Sim') {
            $mddCollection[] = [
                'Id' => GeneralRequestInterface::MDD_KEY_STORE_CODE,
                'Value' => substr($data->getStoreCode(), 0, GeneralRequestInterface::MDD_KEY_LIMIT_CHARACTERS)
            ];
        }

       // if ($data->getCustomerIdentity()) {
          //  $mddCollection[] =  [
            //    'Id' => GeneralRequestInterface::MDD_KEY_CUSTOMER_IDENTITY,
            //    'Value' => substr($data->getCustomerIdentity(), 0, GeneralRequestInterface::MDD_KEY_LIMIT_CHARACTERS)
           // ];
       // }

        // if ($data->getStoreIdentity()) {
        //    $mddCollection[] =  [
        //        'Id' => GeneralRequestInterface::MDD_KEY_CUSTOMER_IDENTITY,
        //       'Value' => substr($data->getStoreIdentity(), 0, GeneralRequestInterface::MDD_KEY_LIMIT_CHARACTERS)
        //   ];
        //  }


        //  if ($data->getProvider()) {
           // $mddCollection[] = [
            //    'Id' => GeneralRequestInterface::MDD_KEY_PROVIDER,
            //    'Value' => substr((($data->getProvider()) ? $data->getProvider() : 'Braspag'), 0, GeneralRequestInterface::MDD_KEY_LIMIT_CHARACTERS)
            // ];
         // }

        // if ($data->getCustomerIsRisk()) {
         //   $mddCollection[] = 
         //   [
          //      'Id' => GeneralRequestInterface::MDD_KEY_CUSTOMER_IS_RISK,
          //      'Value' => substr($data->getCustomerIsRisk(), 0, GeneralRequestInterface::MDD_KEY_LIMIT_CHARACTERS)
          //  ];
         //  }

        //if ($data->getCustomerIsVIP()) {
        //    $mddCollection[] = 
         //   [
         //       'Id' => GeneralRequestInterface::MDD_KEY_CUSTOMER_IS_VIP,
          //      'Value' => substr($data->getCustomerIsVIP(), 0, GeneralRequestInterface::MDD_KEY_LIMIT_CHARACTERS)
          //  ];
         // }
       
        if ($data->getCouponCode()) {
            $mddCollection[] = 
            [
                'Id' => GeneralRequestInterface::MDD_KEY_COUPON_CODE,
                'Value' => substr($data->getCouponCode(), 0, GeneralRequestInterface::MDD_KEY_LIMIT_CHARACTERS)
            ];
        }

        if ($data->getVerticalSegment()) {
            $mddCollection[] = 
            [
                'Id' => GeneralRequestInterface::MDD_KEY_VERTICAL_SEGMENT,
                'Value' => substr($data->getVerticalSegment(), 0, GeneralRequestInterface::MDD_KEY_LIMIT_CHARACTERS)
            ];
        }


        $result = [];
        foreach ($mddCollection as $mdd) {
            if ($mdd['Value']) {
                $result[] = $mdd;
            }
        }

        return $result;
    }
}
