<?php

defined('ABSPATH') || exit;

use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;

use PayPal\Api\Details;
use PayPal\Api\PaymentExecution;

if (!class_exists('MP_PayPal_Gateway')) :


    class MP_PayPal_Gateway extends MP_Payment_Gateway
    {
        private $testMode;

        private $clientID;
        private $clientSecret;

        private $mode;

        private $apiContext;

        public function __construct()
        {
            $this->id = 'paypal_authorize';
            $this->method_title = 'Payment via PayPal';
            $this->description = 'Pay via PayPal; you can pay with your credit card if you don’t have a PayPal account.';


            $clientID = MTP_OSA()->get_option('client_id', 'meal_prep_paypal_express');
            $clientSecret = MTP_OSA()->get_option('client_secret', 'meal_prep_paypal_express');
            $testMode = MTP_OSA()->get_option('paypal_test_mode', 'meal_prep_paypal_express');

            $this->clientID = $clientID;
            $this->clientSecret = $clientSecret;

            $this->testMode = $testMode ? true : false;

            if ($this->testMode) {
                $this->description .= 'SANDBOX ENABLED. You can use sandbox testing accounts only.';
                $this->mode = 'sandbox';
            } else {
                $this->mode = 'live';
            }

            add_filter('meal_prep_checkout_methods', array($this, 'add_checkout_method'));
        }

        private function getApiContext()
        {
            if (!isset($this->apiContext)) {
                $apiContext = new ApiContext(
                    new OAuthTokenCredential(
                        $this->clientID,        // ClientID
                        $this->clientSecret     // ClientSecret
                    )
                );

                $apiContext->setConfig(
                    array(
                        'mode' => $this->mode
                    )
                );

                $this->apiContext = $apiContext;
            }

            return $this->apiContext;
        }

        public function process_payment($order_id)
        {
            $order = food_to_prep_get_order($order_id);
            $order_total = number_format(($order->get_total()), 2);
            $order_currency = FTP()->settings->get_current_currency();

            $arr_params = array('orderId' => $order_id);

            $revice_order_page = add_query_arg($arr_params, home_url(FTP()->endpoint_revice_order()));
            $error_page = add_query_arg($arr_params, home_url('sorry'));


            $apiContext = $this->getApiContext();

            // Step 2: create order infomation
            $payer = new Payer();
            $payer
                ->setPaymentMethod('paypal');

            $amount = new Amount();
            $amount
                ->setTotal($order_total)
                ->setCurrency($order_currency);

            $transaction = new Transaction();
            $transaction
                ->setAmount($amount);

            $redirectUrls = new RedirectUrls();
            $redirectUrls
                ->setReturnUrl($revice_order_page)
                ->setCancelUrl($error_page);

            $payment = new Payment();
            $payment
                ->setIntent('sale')
                ->setPayer($payer)
                ->setTransactions(array($transaction))
                ->setRedirectUrls($redirectUrls);


            // Step 3: Execute transaction
            try {
                $payment->create($apiContext);

                $approvalUrl = $payment->getApprovalLink();

                return array(
                    'result' => 'success',
                    'messages' => $approvalUrl
                );
            } catch (PayPalConnectionException $ex) {
                // This will print the detailed information on the exception.
                //REALLY HELPFUL FOR DEBUGGING

                error_log($ex->getData());

                return array(
                    'result' => 'failure',
                    'messages' => $ex->getMessage()
                );
            }
        }

        public function executePayment($order_id)
        {

            $apiContext = $this->getApiContext();
            $order = food_to_prep_get_order($order_id);
            $order_total = number_format(($order->get_total()), 2);
            $order_currency = FTP()->settings->get_current_currency();

            $paypal_order_items = array();

            // ### Itemized information
            // (Optional) Lets you specify item wise
            // information
            foreach ($order->get_order_items() as $order_item) {
                $item1 = new Item();
                $item1
                    ->setName($order_item->getName())
                    ->setCurrency($order_currency)
                    ->setQuantity($order_item->getQuality())
                    ->setPrice($order_item->getPrice());

                array_push($paypal_order_items, $item1);
            }

            $itemList = new ItemList();
            $itemList->setItems($paypal_order_items);

            $paymentId = $_GET['paymentId'];
            $payment = Payment::get($paymentId, $apiContext);
            // ### Payment Execute

            $execution = new PaymentExecution();
            $execution->setPayerId($_GET['PayerID']);

            $transaction = new Transaction();
            $amount = new Amount();
    //        $details = new Details();
    //        $details->setShipping(2.2)
    //            ->setTax(1.3)
    //            ->setSubtotal(17.50);
            $amount
                ->setCurrency($order_currency)
                ->setTotal($order_total);
    //        $amount->setDetails($details);
            $transaction
                ->setAmount($amount)
                ->setItemList($itemList);

            $execution->addTransaction($transaction);
            try {
                $result = $payment->execute($execution, $apiContext);
                try {
                    $payment = Payment::get($paymentId, $apiContext);

                    // ### Write order
                    $order->set_transaction_id($payment->getTransactions()[0]->getRelatedResources()[0]->order->id);
                    $order->save();

                    $order->update_status('processing');

                    $msg = 'Your payment has been processed successfully.This transaction has been approved.Transaction ID: ' . $order->get_transaction_id();

                    $order->add_order_note($msg);

                } catch (Exception $ex) {
                    error_log($ex);
                    exit(1);
                }
            } catch (Exception $ex) {
                error_log($ex);

                exit(1);
            }
            error_log($payment);
        }
    }

    new MP_PayPal_Gateway();

endif;