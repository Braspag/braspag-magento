<?php

namespace Braspag\Braspag\Pagador\Transaction\Api\AntiFraud\MDD;

interface GeneralRequestInterface
{
    const MDD_KEY_LIMIT_CHARACTERS = 255;
    const MDD_KEY_CUSTOMER_NAME = 1;
    const MDD_KEY_CUSTOMER_IS_LOGGED = 2;
    //const MDD_KEY_PURCHASE_BY_THIRD = 3;
    const MDD_KEY_SALES_ORDER_CHANNEL = 4;
    //const MDD_KEY_PRODUCT_CATEGORY = 5;
    //const MDD_KEY_SHIPPING_METHOD = 7;
    const MDD_KEY_CUSTOMER_FETCH_SELF = 9;
    const MDD_KEY_STORE_CODE = 22;
    const MDD_KEY_COUPON_CODE = 5;
    // const MDD_KEY_HAS_GIFT_CARD = 13;
    // const MDD_KEY_SECOND_PAYMENT_METHOD = 14;
    //const MDD_KEY_PAYMENT_METHOD_QTY = 15;
    const MDD_KEY_SHIPPING_METHOD_AMOUNT = 16;
    // const MDD_KEY_SECOND_PAYMENT_METHOD_AMOUNT = 17;
    // const MDD_KEY_SALES_ORDER_AMOUNT = 18;
    const MDD_KEY_QTY_INSTALLMENTS_ORDER = 3;
    const MDD_KEY_CARD_IS_PRIVATE_LABEL = 20;
    // const MDD_KEY_CUSTOMER_IDENTITY = 21;
    const MDD_KEY_VERTICAL_SEGMENT = 83;
    // const MDD_KEY_STORE_IDENTITY = 23;
    // const MDD_KEY_PROVIDER = 24;
    //const MDD_KEY_CUSTOMER_IS_RISK = 25;
    const MDD_KEY_CUSTOMER_IS_VIP = 26;
    const MDD_KEY_PLATAFORM_NAME = 84;

    public function getCustomerName();

    public function getCustomerIsLogged();

    public function getPurchaseByThird();

    public function getSalesOrderChannel();

    public function getProductCategory();

    public function getShippingMethod();

    public function getCustomerFetchSelf();

    public function getStoreCode();

    public function getCouponCode();

    public function getHasGiftCard();

    public function getSecondPaymentMethod();

    public function getPaymentMethodQTY();

    public function getShippingMethodAmount();

    public function getSecondPaymentMethodAmount();

    public function getSalesOrderAmount();

    public function getQtyInstallmentsOrder();

    public function getCardIsPrivateLabel();

    public function getCustomerIdentity();

    public function getCustomerTelephone();

    public function getStoreIdentity();

    public function getProvider();

    public function getCustomerIsRisk();

    public function getCustomerIsVIP();

    public function getVerticalSegment();

    public function getPlataformName();

    public function getAFType();
    
    public function hasCustomMDD();
        
    public function getCustomMDD85();

    public function getCustomMDD86();

    public function getCustomMDD87();

    public function getCustomMDD88();

    public function getCustomMDD89();

    public function getOrderData($data);
}