<?php

namespace JPNut\ExtendedAuth\Contracts;

interface Authenticatable
{
    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName();

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier();

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword();

    /**
     * Get all the "remember me" tokens.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRememberTokens();

    /**
     * Add a new "remember me" token.
     *
     * @param  string  $value
     * @return \JPNut\Contracts\Auth\Tokenable
     */
    public function addRememberToken($value);

    /**
     * Find a "remember me" token.
     *
     * @param  mixed  $identifier
     * @return \JPNut\Contracts\Auth\Tokenable
     */
    public function findRememberToken($identifier);

    /**
     * Remove a "remember me" token.
     *
     * @param  mixed  $identifier
     * @return bool|null
     */
    public function removeRememberToken($identifier);

    /**
     * Remove all "remember me" tokens.
     *
     * @return bool|null
     */
    public function removeAllRememberTokens();

    /**
     * Get the name of the relationship where the tokens can be found.
     *
     * @return string
     */
    public function getRememberTokenRelationshipName();

    /**
     * Get the name of the model for the concrete implementation of "Tokens".
     * 
     * @return string
     */
    public function getRememberTokenModelName();

    /**
     * Determine if the token relationship has been properly defined
     * 
     * @return bool
     */
    public function tokenRelationshipIsDefined();
}
