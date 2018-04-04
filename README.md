# Multiple "Remember Me" tokens per user

This package extends the default Laravel Authentication scaffolding to allow the creation of multiple "Remember Me" tokens per user.

With Laravel's default Auth, only one "Remember Me" token can be associated with a user at a time. This presents an issue for users of an application across multiple devices. For example, consider a user who has logged in using the "Remember Me" funcionality on two devices (e.g. on desktop and on mobile). If this user were to log out on one device (e.g. mobile), then the "Remember Me" token will be regenerated, and the user will not be logged in automatically on the other device (desktop in this example). We solve this problem my allowing multiple "Remember Me" tokens to be stored per user.

This process also has the added advantage of enabling an application to invalidate specific "Remember Me" tokens. For example, if a user were to accidentally click "Remember Me" whilst on a public device, the default process only allows a user to invalidate the token across all devices. With this package, we can selectively revoke tokens.

## Installation

TODO

You can publish the migration with:

```bash
php artisan vendor:publish --provider="JPNut\ExtendedAuth\AuthServiceProvider" --tag="migrations"
```

One should note that the removal of the "remember_token" field in the users table is not currently handled by this package. After the migration has been published you can create the token table by running the migrations:

```bash
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="JPNut\ExtendedAuth\AuthServiceProvider" --tag="config"
```

## Usage

Since the default auth implementation of "Remember Me" tokens is baked into the `"Illuminate\Contracts\Auth\Authenticatable"` contract, this package overwrites this, and provides an alternative to the `"Illuminate\Foundation\Auth\User"` provided by Laravel.

You must ensure that your `User` model(s) implement `"JPNut\ExtendedAuth\Contracts\Authenticatable"`. The easiest way to accomplish this is to extend `"JPNut\ExtendedAuth\User"`:

```php
use JPNut\ExtendedAuth\User as Authenticatable;

class User extends Authenticatable
{
	// ...
}
```

To enable the guard, you will need to modify `config/auth.php` as follows:

```php
...
    'guards' => [
        'extended-web' => [
            'driver' => 'extended-session',
            'provider' => 'extended-users',
        ],
    ],
...
    'extended-users' => [
        'driver' => 'extended-eloquent',
        'model' => JPNut\ExtendedAuth\User::class,
    ],
...
```

You should also make sure that the guard is defined on the routes where you wish to make use of this guard. For most cases this involves changing the default guard to `extended-web`. Note that `extended-session` and `extended-eloquent` can be modified in the `config/extended-auth` file.

## Extending

You need to make sure: 

- Any `User` model(s) implement "JPNut\ExtendedAuth\Contracts\Authenticatable"
- Your `Token` model implements "JPNut\ExtendedAuth\Contracts\Tokenable"

## Testing

```bash
phpunit
```

## License

MIT