<?php

namespace App\Http\Controllers;

use App\Http\Requests\RedeemPurchaseCodeRequest;
use App\Services\PurchaseCodeRedemptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RedeemPurchaseCodeController extends Controller
{
    public function __invoke(RedeemPurchaseCodeRequest $request, PurchaseCodeRedemptionService $service): JsonResponse
    {
        try {
            $result = $service->redeem($request->validated('code'), $request->ip());
            Auth::login($result['user']);
            return response()->json(['success' => true, 'email' => $result['user']->email, 'password' => $result['password']]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'The code cannot be redeemed.'], 400);
        }
    }
}
