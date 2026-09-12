<?php

namespace App\Jobs;

use App\Models\PurchaseCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeneratePurchaseCodes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct(public int $adminId, public int $quantity, public int $durationDays, public ?string $name, public ?string $notes) {}
    public function handle(): void
    {
        for ($index = 0; $index < $this->quantity; $index++) {
            PurchaseCode::generate($this->adminId, $this->durationDays, $this->name, $this->notes);
        }
    }
}
