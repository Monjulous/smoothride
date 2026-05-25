<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class PhonePeHelper
{
    /**
     * Initiate a PhonePe Payment
     *
     * @param string $orderId
     * @param float $amount (in rupees)
     * @param string $redirectUrl
     * @param string $callbackUrl
     * @return array
     */
    public static function initiatePayment($orderId, $amount, $redirectUrl, $callbackUrl,$merchantUserId)
    {

         $merchantId = env('PHONEPE_MERCHANT_ID');
        $saltKey = env('PHONEPE_SALT_KEY');
        $saltIndex = env('PHONEPE_SALT_INDEX');
        $baseUrl = 'https://api.phonepe.com/apis/hermes/pg/v1';

       

        $payload = [
            'merchantId' => $merchantId,
            'merchantTransactionId' => $orderId,
            'merchantUserId' => $merchantUserId,
            'amount' => (int) ($amount * 100), // Convert rupees to paise
            'redirectUrl' => $redirectUrl,
            'redirectMode' => 'POST',
            'callbackUrl' => $callbackUrl,
            'paymentInstrument' => [
                'type' => 'PAY_PAGE'
            ]
        ];

      


        $encodedPayload = base64_encode(json_encode($payload));
        $stringToHash = $encodedPayload . "/pg/v1/pay" . $saltKey;
        $checksum = hash('sha256', $stringToHash) . "###" . $saltIndex;

       
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-VERIFY' => $checksum,
            'accept' => 'application/json'
        ])->post($baseUrl . '/pay', [
            'request' => $encodedPayload
        ]);

        \Log::info('PhonePe Request 1:', $payload);
        \Log::info('PhonePe Encoded 2:', [$encodedPayload]);
        \Log::info('PhonePe Response 3:', [$response->body()]);
        return $response->json();
    }

    /**
     * Verify Payment Status by Order ID
     *
     * @param string $orderId
     * @return array
     */
    public static function verifyPaymentStatus($orderId)
    {
        // $merchantId = env('PHONEPE_MERCHANT_ID');
        // $saltKey = env('PHONEPE_SALT_KEY');
        // $saltIndex = env('PHONEPE_SALT_INDEX');
        // $baseUrl = env('PHONEPE_BASE_URL');

        $merchantId ='TEST-M23AZYQZIIP1X_25082';
        $saltKey = 'd0d8418c-72b2-4612-8831-fbf2a22a45a9';
        $saltIndex =1;
        $baseUrl = 'https://api.phonepe.com/apis/hermes/pg/v1';

        $apiPath = "/pg/v1/status/{$merchantId}/{$orderId}";
        $stringToHash = $apiPath . $saltKey;
        $checksum = hash('sha256', $stringToHash) . "###" . $saltIndex;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-VERIFY' => $checksum,
            'X-MERCHANT-ID' => $merchantId,
        ])->get($baseUrl . $apiPath);

        return $response->json();
    }
}
