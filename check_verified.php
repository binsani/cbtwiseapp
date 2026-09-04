<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'admin@cbtwise.com')->first();
echo "Admin Email Verified At: " . ($user->email_verified_at ? $user->email_verified_at->toDateTimeString() : 'NULL') . "\n";
