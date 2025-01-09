<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class VnpayController extends Controller
{
    private $vnp_TmnCode = "PJCNXYWL";
    private $vnp_HashSecret = "0NVE7YL3N6PKEJMJ8GYFPXDVQN5NABTE";
    private $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    private $vnp_Returnurl = "http://localhost:8081/transaction-result";
    private $vnp_Amount = 500000;

    public function index(Request $request)
    {
        $user_id = $request->user_id;
        $vnp_Locale = "vn";
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        $vnp_TxnRef = time();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_Amount" => $this->vnp_Amount* 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => "Nạp VIP",
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $this->vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $this->vnp_Url = $this->vnp_Url . "?" . $query;

        if (isset($this->vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $this->vnp_HashSecret);
            $this->vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return response()->json(['url' => $this->vnp_Url]);
    }
    
    public function vnpayReturn(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $vnp_ResponseCode = $request->vnp_ResponseCode;

            if ($vnp_ResponseCode == "00") {
                $user = User::where('id', $user_id)->firstOrFail();

                if(!isset($user)){
                    return response()->json(['message' => 'Tài khoản không tồn tại'], 500);
                }

                $user->vip_level += 1;
                $user->save();

                return response()->json(['message' => 'Thanh toán thành công'], 200);
            } else {
                return response()->json(['message' => 'Thanh toán thất bại'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Thanh toán thất bại'], 500);
        }
    }
}
