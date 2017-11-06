# Invites

Invites provides a way to limit access to your Laravel applications by using invite codes.

Invite Codes:
* Can be available to anyone (great for sharing on social media).
* Can have a limited number of uses or unlimited.
* Can have an expiry date, or never expire.

## Installation

You can pull in the package using [composer](https://getcomposer.org):

```bash
$ composer require clarkeash/invites
```

Next, register the service provider with Laravel (no need on version 5.5):

```php
// config/app.php
'providers' => [
    ...
    Abstem\Invites\InvitesServiceProvider::class,
];
```

And, register the facade:

```php
// config/app.php
'aliases' => [
    ...
    'Invites'   =>  Abstem\Invites\Facades\Invites::class,
];
```

Finally, migrate the database:

```bash
$ php artisan migrate
```

## Usage

### Generate Invites

Make a single generic invite code with 1 redemption, and no expiry.
```php
Invites::generate()->make();
```

Make 5 generic invite codes with 1 redemption each, and no expiry.
```php
Invites::generate()->times(5)->make();
```

Make an invite with 10 redemptions and no expiry.
```php
Invites::generate()->uses(10)->make();
```

Make an invite that expires on a specific date.
```php
$date = Carbon::now('UTC')->addDays(7);
Invites::generate()->expiresOn($date)->make();
```

Make an invite that expires in 14 days.
```php
Invites::generate()->expiresIn(14)->make();
```


### Redeem Invites

You can redeem an invite by calling the ````redeem```` method. Providing the invite code and optionally an email address.

```php
Invites::redeem('ABCDE');
```

If invites is able to redeem the invite code it will increment the number of redemptions by 1, otherwise it will throw an exception.

* ````InvalidInviteCode```` is thrown if the code does not exist in the database.
* ````ExpiredInviteCode```` is thrown if an expiry date is set and it is in the past.
* ````MaxUsesReached```` is thrown if the invite code has already been used the maximum number of times.

All of the above exceptions extend ````InvitesException```` so you can catch that exception if your application does not need to do anything specific for the above exceptions.

```php
try {
    Invites::redeem(request()->get('code'), request()->get('email'));
} catch (InvitesException $e) {
    return response()->json(['error' => $e->getMessage()], 422);
}
```

### Check Invites without redeeming them

You can check an invite by calling the ````check```` method. Providing the invite code and optionally an email address. (It has the same signature as the ````redeem```` method except it will return ````true```` or ````false```` instead of throwing an exception.

```php
Invites::check('ABCDE');
```

### Change Error Messages (and translation support)

In order to change the error message returned from invites, we need to publish the language files like so:

```bash
$ php artisan vendor:publish --tag=translations
```

The language files will then be in ````/resources/lang/vendor/invites/en```` where you can edit the ````messages.php```` file, and these messages will be used by invites. You can create support for other languages by creating extra folders with a ````messages.php```` file in the ````/resources/lang/vendor/invites```` directory such as ````de```` where you could place your German translations. [Read the localisation docs for more info](https://laravel.com/docs/localization).

### Config - change table name

First publish the package configuration:

```bash
$ php artisan vendor:publish --tag=config
```

In `config/invites.php` you will see:

```php
return [
    'invite_table_name' => 'invites',
];
```
 If you change the table name and then run your migrations Invites will then use the new table name.
 
 ### Console
 
 To remove used and expired invites you can use the `cleanup` command:
 
 ```bash
$ php artisan invites:cleanup
```