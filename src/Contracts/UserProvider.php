<?php

namespace JPNut\ExtendedAuth\Contracts;

use JPNut\ExtendedAuth\Contracts\Authenticatable;

interface UserProvider
{
    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \JPNut\ExtendedAuth\Contracts\Authenticatable|null
     */
    public function retrieveById($identifier);

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  mixed  $token_identifier
     * @param  string  $token_value
     * @return \JPNut\ExtendedAuth\Contracts\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token_identifier, $token_value);

    /**
     * Add the generated token into storage
     * 
     * @param  \JPNut\ExtendedAuth\Contracts\Authenticatable  $user
     * @param  string  $token_value
     * @return \JPNut\Contracts\Auth\Tokenable
     */
    public function addRememberToken(Authenticatable $user, string $token_value);

    /**
     * Remove a "remember me" token from storage
     *
     * @param  \JPNut\ExtendedAuth\Contracts\Authenticatable  $user
     * @param  mixed  $token_identifier
     * @return bool|null
     */
    public function removeRememberToken(Authenticatable $user, $token_identifier);

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \JPNut\ExtendedAuth\Contracts\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials);

    /**
     * Validate a user against the given credentials.
     *
     * @param  \JPNut\ExtendedAuth\Contracts\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials);
}
