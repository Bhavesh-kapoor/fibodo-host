<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\Finance\CreateSecurePaymentToken;
use App\Services\FinanceService;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use Exception;

class FinanceController extends Controller
{
    /**
     * CreatePayment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    /*public function createPayment(Request $request)
    {
        //$lastID = PaymentTransaction::select('id')->max('id');
        //$this->od_number = str_pad($lastID + 1, 4, 0, STR_PAD_LEFT);
        $responseArr = [];
        $responseArr['action'] = config('finance.worldnet_url');
        $responseArr['fields']['TERMINALID'] = config('finance.worldnet_terminal_id');
        $responseArr['fields']['ORDERID'] = 'FB-' . (string) Str::ulid();
        $responseArr['fields']['CURRENCY'] = config('finance.worldnet_currency');
        $responseArr['fields']['AMOUNT'] = '10.00';
        $responseArr['fields']['DATETIME'] = date('d-m-Y H:i:s') . ':' . substr(microtime(), 2, 3);
        $responseArr['fields']['INIFRAME'] = 'Y';
        $responseArr['fields']['SECURECARDMERCHANTREF'] = (string) Str::ulid();

        $responseArr['fields']['HASH'] = $this->generatePaymentHash($responseArr['fields']);
        return response()->json($responseArr);
    }

    public function capturePayment(Request $request)
    {
        $data = $request->all();
        $hash = $this->generateResponseHash($data);
        if ($hash == $data['HASH']) {
            $cardData = [];
            $cardData['user_id'] = '01jws6brtrcm86hfx5v0s43szh';
            $cardData['merchant_ref'] = $data['SECURECARDMERCHANTREF'];
            $cardData['worldnet_ref'] = $data['CARDREFERENCE'] ?? '';
            $cardData['number'] = $data['CARDNUMBER'];
            $cardData['type'] = $data['CARDTYPE'];
            $cardData['expiry'] = $data['CARDEXPIRY'] ?? '0000';
            $cardData['holder_name'] = $data['SECURECARDMERCHANTREF'];
            $cardData['holder_email'] = $data['EMAIL'];
            $cardData['holder_phone'] = $data['PHONE'] ?? '';
            $cardData['is_stored'] = (isset($data['CARDREFERENCE']) && $data['CARDREFERENCE'] != '') ? true : false;
            $cardData['description'] = json_encode($data);
            $card = Card::create($cardData);
            if ($card && $card->id) {
                $payData = [];
                $payData['card_id'] = $card->id;
                $payData['booking_id'] = '';
                $payData['order_id'] = $data['ORDERID'];
                $payData['amount'] = $data['AMOUNT'];
                $payData['response_code'] = $data['RESPONSETEXT'];
                $payData['unique_ref'] = $data['UNIQUEREF'];
                $payData['datetime'] = $data['DATETIME'];
                $payData['description'] = json_encode($data);
                PaymentTransaction::create($payData);
            }
            return response()->success("Payment successful!");
        } else {
            return response()->error("Unable to process the payment");
        }
    }*/

    /*protected function generatePaymentHash($fields)
    {
        //TERMINALID:ORDERID:AMOUNT:DATETIME:SECRET
        $hash = hash('sha512', $fields['TERMINALID'] . ':' . $fields['ORDERID'] . ':' . $fields['AMOUNT'] . ':' . $fields['DATETIME'] . ':' . config('finance.worldnet_secret'));
        return $hash;
    }*/

    protected function generateSecureTokenHashResponse()
    {
        //TERMINALID:RESPONSECODE:RESPONSETEXT:MERCHANTREF:CARDREFERENCE:DATETIME:SECRET
        $hash = hash('sha512', config('finance.worldnet_terminal_id') . ':' . request()->RESPONSECODE . ':' . request()->RESPONSETEXT . ':' . request()->MERCHANTREF . ':' . request()->CARDREFERENCE . ':' . request()->DATETIME . ':' . config('finance.worldnet_secret'));
        return $hash;
    }

    protected function generateSecureTokenHashRequest($fields)
    {
        //TERMINALID:ORDERID:AMOUNT:DATETIME:SECRET
        $hash = hash('sha512', $fields['TERMINALID'] . ':' . $fields['MERCHANTREF'] . ':' . $fields['DATETIME'] . ':' . $fields['ACTION'] . ':' . config('finance.worldnet_secret'));
        return $hash;
    }

    public function createSecurePaymentToken(Request $request)
    {
        $responseArr = [];
        $responseArr['form_action'] = config('finance.worldnet_securetoken_url');
        $responseArr['fields']['ACTION'] = config('finance.worldnet_register_action');
        $responseArr['fields']['TERMINALID'] = config('finance.worldnet_terminal_id');
        $responseArr['fields']['MERCHANTREF'] = time() . '?^' . $request->get('user_id');
        $responseArr['fields']['DATETIME'] = date('d-m-Y:H:i:s') . ':' . substr(microtime(), 2, 3);
        $responseArr['fields']['INIFRAME'] = 'Y';
        $responseArr['fields']['HASH'] = $this->generateSecureTokenHashRequest($responseArr['fields']);
        return response()->json($responseArr);
    }

    public function captureSecureToken(CreateSecurePaymentToken $request, FinanceService $financeService)
    {
        try {

            $resHash = $this->generateSecureTokenHashResponse();
            $wnHash = request()->HASH;
            if (strcmp($resHash, $wnHash) == 0) {
                return Response::success(
                    'messages.success',
                    $financeService->createSecureToken(),
                    null,
                    HttpResponse::HTTP_CREATED
                );
            }
        } catch (Exception $e) {
            // Send error response
            return Response::error($e->getMessage(), null, $e->getCode());
        }
    }
}
