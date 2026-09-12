# Purchase-code flow

Administrators generate prepaid codes from **Admin → Purchase Codes**. Every new code is formatted as `CBT-XXXX-XXXX-XXXX` and is issued with a unique CBTWise student email plus an encrypted, generated password.

Students redeem a code at `/redeem` or by posting `{ "code": "CBT-XXXX-XXXX-XXXX" }` to `POST /redeem-code`. Redemption locks the code row, creates the assigned account, assigns the `user` role, creates an active subscription, marks the code used, records an admin notification, and writes a non-sensitive redemption audit entry.

Run queued large batches with:

```bash
php artisan queue:work
```

Codes generated before this upgrade have no assigned credentials and should be cancelled or replaced with newly generated codes before public redemption.
