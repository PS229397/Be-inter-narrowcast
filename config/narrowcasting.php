<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Emails
    |--------------------------------------------------------------------------
    |
    | The admin panel uses the same user table during this sprint. To keep the
    | panel access logic explicit without introducing new schema changes, admin
    | users are identified by email.
    |
    */
    'admin_emails' => collect(explode(',', (string) env('NARROWCAST_ADMIN_EMAILS', '')))
        ->map(fn (string $email): string => str($email)->trim()->lower()->toString())
        ->filter()
        ->values()
        ->all(),
];
