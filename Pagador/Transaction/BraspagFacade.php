<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */
namespace Webjump\Braspag\Pagador\Transaction;

use Webjump\Braspag\Factories\Auth3Ds20TokenCommandFactory;
use Webjump\Braspag\Factories\OAuth2TokenCommandFactory;
use Webjump\Braspag\Factories\PaymentSplitTransactionPostCommandFactory;
use Webjump\Braspag\Factories\Auth3Ds20TokenRequestFactory;
use Webjump\Braspag\Factories\OAuth2TokenRequestFactory;
use Webjump\Braspag\Factories\BilletRequestFactory;
use Webjump\Braspag\Factories\PaymentRequestFactory;
use Webjump\Braspag\Factories\CreditCardRequestFactory;
use Webjump\Braspag\Factories\CreditCardPaymentSplitRequestFactory;
use Webjump\Braspag\Factories\DebitCardPaymentSplitRequestFactory;
use Webjump\Braspag\Factories\CaptureCommandFactory;
use Webjump\Braspag\Factories\DebitCardRequestFactory;
use Webjump\Braspag\Factories\GetCommandFactory;
use Webjump\Braspag\Factories\VoidCommandFactory;
use Webjump\Braspag\Pagador\Transaction\Api\Billet\Send\RequestInterface as BilletRequest;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\RequestInterface as CreditCardRequest;
use Webjump\Braspag\Pagador\Transaction\Api\Actions\RequestInterface as ActionsPaymentRequest;
use Webjump\Braspag\Pagador\Transaction\Api\Auth3Ds20\Token\RequestInterface as Auth3Ds20TokenRequest;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\PaymentSplit\RequestInterface as PaymentSplitCreditCardTransactionPostRequest;
use Webjump\Braspag\Pagador\Transaction\Api\Debit\PaymentSplit\RequestInterface as PaymentSplitDebitCardTransactionPostRequest;
use Webjump\Braspag\Pagador\Transaction\Api\OAuth2\Token\RequestInterface as OAuth2TokenRequest;
use Webjump\Braspag\Pagador\Transaction\Api\Debit\Send\RequestInterface as DebitRequest;
use Webjump\Braspag\Factories\SalesCommandFactory;
use Webjump\Braspag\Pagador\Transaction\Command\Sales\CaptureCommand;
use Webjump\Braspag\Pagador\Transaction\Command\Sales\GetCommand;
use Webjump\Braspag\Pagador\Transaction\Command\Sales\VoidCommand;
use Webjump\Braspag\Pagador\Transaction\Command\SalesCommand;

class BraspagFacade implements FacadeInterface
{
    /**
     * @param ActionsAuthenticateRequest $request
     * @return AuthenticateCommand
     */
    public function getToken(Auth3Ds20TokenRequest $request)
    {
        $request = Auth3Ds20TokenCommandFactory::make(Auth3Ds20TokenRequestFactory::make($request))->getResult();

        return $request;
    }

    /**
     * @param ActionsAuthenticateRequest $request
     * @return AuthenticateCommand
     */
    public function getOAuth2Token(OAuth2TokenRequest $request)
    {
        $request = OAuth2TokenCommandFactory::make(OAuth2TokenRequestFactory::make($request))->getResult();

        return $request;
    }

    /**
     * @param BilletRequest $request
     * @return SalesCommand
     */
    public function sendBillet(BilletRequest $request)
    {
        $request = SalesCommandFactory::make(BilletRequestFactory::make($request))->getResult();
        return $request;
    }

    /**
     * @param CreditCardRequest $request
     * @return SalesCommand
     */
    public function sendCreditCard(CreditCardRequest $request)
    {
        $request = SalesCommandFactory::make(CreditCardRequestFactory::make($request))->getResult();
        return $request;
    }

    /**
     * @param CreditCardRequest $request
     * @return SalesCommand
     */
    public function sendCreditCardSplitPaymentTransactionPost(PaymentSplitCreditCardTransactionPostRequest $request)
    {
        $request = PaymentSplitTransactionPostCommandFactory::make(CreditCardPaymentSplitRequestFactory::make($request))->getResult();
        return $request;
    }

    /**
     * @param PaymentSplitDebitCardTransactionPostRequest $request
     * @return PaymentSplitDebitCardTransactionPostRequest|SalesCommand
     */
    public function sendDebitCardSplitPaymentTransactionPost(PaymentSplitDebitCardTransactionPostRequest $request)
    {
        $request = PaymentSplitTransactionPostCommandFactory::make(DebitCardPaymentSplitRequestFactory::make($request))->getResult();
        return $request;
    }

    /**
     * @param ActionsPaymentRequest $request
     * @return CaptureCommand
     */
    public function captureCreditCard(ActionsPaymentRequest $request)
    {
        $request = CaptureCommandFactory::make(PaymentRequestFactory::make($request))->getResult();
        return $request;
    }

    /**
     * @param DebitRequest $request
     * @return SalesCommand
     */
    public function sendDebit(DebitRequest $request)
    {
        $request = SalesCommandFactory::make(DebitCardRequestFactory::make($request))->getResult();
        return $request;
    }

    /**
     * @param ActionsPaymentRequest $request
     * @param $type
     * @return GetCommand
     */
    public function checkPaymentStatus(ActionsPaymentRequest $request, $type)
    {
        $request = GetCommandFactory::make(PaymentRequestFactory::make($request, $type))->getResult();
        return $request;
    }

    /**
     * @param ActionsPaymentRequest $request
     * @return VoidCommand
     */
    public function voidPayment(ActionsPaymentRequest $request)
    {
        $request = VoidCommandFactory::make(PaymentRequestFactory::make($request))->getResult();
        return $request;
    }
}
