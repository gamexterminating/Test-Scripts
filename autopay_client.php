<?php
require_once dirname(dirname(__FILE__)) . "/wp-load.php";
// static data for test
$input = file_get_contents("https://gamexterminating.kloudexpert.com/tekcard_json.txt");
$pay_data = json_decode($input, true);

// Check if event type exists from the service 
if (isset($pay_data['event_type']) && !empty($pay_data['event_type']) && !empty($pay_data)) {

    // time to grab event body from response
    $pay_body = $pay_data['event_body'];

    // check request from autopay transaction 
    if (!empty($pay_body['action']['source']) && !empty($pay_body['action']['api_method']) && $pay_body['action']['source'] == 'virtual_terminal' && $pay_body['action']['api_method'] == 'virtual_terminal') {

        // time to grab email from response and fetch invoice id
        //$client_email = (!empty($pay_body['billing_address']['email']) ? $pay_body['billing_address']['email'] : $pay_body['shipping_address']['email']);

        $trans_data = [];

        // transaction date
        $trans_date  = $pay_body['action']['date'];

        // static data to fetch invoice
        $client_email = "kbg.upwork.php@gmail.com";

        // transaction month and year
        $trans_month = date('m', strtotime($trans_date));
        $trans_year = date('Y', strtotime($trans_date));

        // Begin transaction
        //$this->beginTransaction();
        try {

            // firstly fetch invoice id based on transaction date month and year
            $invoice_with_month = (new TekCard)->fetchInvoiceIdByDate([
                'email' => $client_email,
                'method' =>  'client_on_autopay',
                'month' => $trans_month,
                'year' => $trans_year
            ]);

            // return invoice id if found with transaction month and year
            if (!empty($invoice_with_month)) {
                $invoice_id = (!empty($invoice_with_month->id) ? $invoice_with_month->id : '');
            } else {
                $last_invoice_id = (new TekCard)->fetchLastInvoiceID($client_email);
                $invoice_id = (!empty($last_invoice_id->id) ? $last_invoice_id->id : '');
            }

            // fetch data from transaction response
            $event_type  = $pay_data['event_type'];
            $transaction_id  = $pay_body['transaction_id'];
            $api_method  = $pay_body['action']['api_method'];
            $source  = $pay_body['action']['source'];
            $status  = $pay_body['action']['success'];
            $amount  = $pay_body['action']['amount'];
            $payment_data  = serialize($pay_data['event_body']);
            $payment_date  = date('Y-m-d H:i:s', strtotime($trans_date));
            $payment_date_note  = date('m/d/y', strtotime($trans_date));

            // transaction data to be used later for save/update transaction
            $trans_data['data'] = [
                'transaction_id'    =>    $transaction_id,
                'email'             =>    $client_email,
                'event_type'        =>    $event_type,
                'api_method'        =>    $api_method,
                'source'            =>    $source,
                'status'            =>    $status,
                'amount'            =>    $amount,
                'payment_data'      =>    $payment_data,
                'payment_date'      =>    $payment_date,
            ];

            /* if invoice id is empty means transaction is completed on tekcard but there is
             no invoice generated yet and marked as unbilled transaction */
            if (empty($invoice_id)) {
                // pass extra data in table
                $trans_data['data']['payment_status'] = 0;
                $response = (new TekCard)->saveTransactionWithoutInvoiceID($data);
                if (!$response) throw new Exception(sprintf("Error on record insert if no invoice id found: %s", $wpdb->last_error));
            } else {
                // pass extra data in table
                $trans_data['data']['invoice_id'] = $invoice_id;
                $trans_data['data']['payment_status'] = 1;

                // fetch Transactions is already Done or Not in Autopay Tekcard table
                $trans_already_exist = (new TekCard)->fetchTransactionWithEmailExist([
                    'email' => $client_email,
                    'pay_status' => 0
                ]);

                // admin note data in invoice table about payment
                $trans_data['note_data'] = [
                    'invoice_id' => $invoice_id,
                    'note' => $payment_date_note,
                    'amt' => $amount,
                    'month' => date('M', strtotime($trans_date))
                ];

                /* if transaction already exist in db then just add extra 
                method key to update record in DB*/
                if (!empty($trans_already_exist)) {
                    $trans_data['method'] = 'update';
                }

                // save transaction data in DB
                list($res,$msg) = (new TekCard)->saveClientTransactionInDb($trans_data);
                if (!$res) throw new Exception(sprintf("Query execute failed : %s", $msg));
            }
            echo $invoice_id;
        } catch (\Exception $ex) {

            // rollback the transaction
            //$this->rollbackCommand();

            // log error message
            file_put_contents('gamex_autopay_failed.txt', $ex->getMessage() . PHP_EOL, FILE_APPEND);
        }

        // commit the transaction
        //$this->commitTransaction();
    }
}
