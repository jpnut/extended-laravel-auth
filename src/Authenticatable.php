<?php

namespace JPNut\ExtendedAuth;

use JPNut\ExtendedAuth\Token;

trait Authenticatable
{
    /**
     * The name of the relationship where the tokens can be found.
     * 
     * @var string
     */
    protected $rememberTokenRelationshipName = 'tokens';

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return $this->getKeyName();
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get all the "remember me" tokens.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRememberTokens()
    {
        if ($this->tokenRelationshipIsDefined()) {
            return $this->{$this->getRememberTokenRelationshipName()};
        }
    }

    /**
     * Add a new "remember me" token.
     *
     * @param  string  $value
     * @return \JPNut\Contracts\Auth\Tokenable
     */
    public function addRememberToken($value)
    {
        if ($this->tokenRelationshipIsDefined()) {
            return $this->{$this->getRememberTokenRelationshipName()}()->save(
                app()->make($this->getRememberTokenModelName())
                    ->setTokenValue($value)
            );
        }
    }

    /**
     * Find a "remember me" token.
     *
     * @param  mixed  $identifier
     * @return \JPNut\Contracts\Auth\Tokenable
     */
    public function findRememberToken($identifier)
    {
        if ($this->tokenRelationshipIsDefined()) {
            $key = app()->make($this->getRememberTokenModelName())->getKeyName();

            return $this->{$this->getRememberTokenRelationshipName()}()
                ->where($key, $identifier)
                ->first();
        }
    }

    /**
     * Remove a "remember me" token.
     *
     * @param  mixed  $identifier
     * @return bool|null
     */
    public function removeRememberToken($identifier)
    {
        if ($this->tokenRelationshipIsDefined()) {
            $key = app()->make($this->getRememberTokenModelName())->getKeyName();

            return $this->{$this->getRememberTokenRelationshipName()}()
                ->where($key, $identifier)
                ->delete();
        }
    }

    /**
     * Get the name of the relationship where the tokens can be found.
     *
     * @return string
     */
    public function getRememberTokenRelationshipName()
    {
        return $this->rememberTokenRelationshipName;
    }

    /**
     * Get the name of the model for the concrete implementation of "Tokens".
     * 
     * @return string
     */
    public function getRememberTokenModelName()
    {
        return Token::class;
    }

    /**
     * The default relationship where the tokens can be found
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tokens()
    {
        return $this->morphMany($this->getRememberTokenModelName(), 'auth');
    }

    /**
     * Determine if the token relationship has been properly defined
     * 
     * @return bool
     */
    public function tokenRelationshipIsDefined()
    {
        return (! empty($this->getRememberTokenRelationshipName()) && ! empty($this->getRememberTokenModelName()));
    }
}
