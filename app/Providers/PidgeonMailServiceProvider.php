<?php

namespace App\Providers;

use App\Mail\Transport\PidgeonTransport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class PidgeonMailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Mail::extend('pidgeon', function () {
            $domain = config('services.pidgeon.domain', 'invoiceflow.local');
            $from = config('mail.from.address') ?: "noreply@{$domain}";

            return new PidgeonTransport(
                config('services.pidgeon.url', 'http://127.0.0.1:3000'),
                $from,
            );
        });
    }
}
