<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseCodeRedemptionAttempt extends Model
{
    protected $fillable = ['code_fingerprint', 'ip_address', 'result', 'reason'];
}
