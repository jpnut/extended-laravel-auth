<?php

namespace JPNut\ExtendedAuth;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Support\Str;
use JPNut\ExtendedAuth\Contracts\Authenticatable;
use JPNut\ExtendedAuth\Contracts\UserProvider;

class EloquentUserProvider implements UserProvider
{
	/**
     * The hasher implementation.
     *
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;

    /**
     * The Eloquent user model.
     *
     * @var string
     */
    protected $model;

    /**
     * Create a new database user provider.
     *
     * @param  \Illuminate\Contracts\Hashing\Hasher  $hasher
     * @param  string  $model
     * @return void
     */
    public function __construct(HasherContract $hasher, $model)
    {
        $this->model = $model;
        $this->hasher = $hasher;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \JPNut\ExtendedAuth\Contracts\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        $model = $this->createModel();

        return $model->newQuery()
            ->where($model->getAuthIdentifierName(), $identifier)
            ->first();
    }

	/**
     * Retrieve a user by their unique identifier and "remember me" token consisting of an id and value.
     *
     * @param  mixed  $identifier
     * @param  int  $tokenId
     * @param  string  $token
     * @return \JPNut\ExtendedAuth\Contracts\Authenticatable|null
     */
    public function retrieveByToken($identifier, $tokenId, $tokenValue)
    {
        $model = $this->createModel();

        $model = $model->where($model->getAuthIdentifierName(), $identifier)->first();

        if (! $model) {
            return null;
        }

        $rememberToken = $model->findRememberToken($tokenId);

        if (! $rememberToken) {
            return null;
        }

        $rememberToken = $rememberToken->getTokenValue();

        return $rememberToken && hash_equals($rememberToken, $tokenValue) ? $model : null;
    }

    /**
     * Add the generated token into storage
     * 
     * @param  \JPNut\ExtendedAuth\Contracts\Authenticatable  $user
     * @param  string  $token_value
     * @return \JPNut\Contracts\Auth\Tokenable
     */
    public function addRememberToken(Authenticatable $user, string $token_value)
    {
        return $user->addRememberToken($token_value);
    }

    /**
     * Remove a "remember me" token from storage
     *
     * @param  \JPNut\ExtendedAuth\Contracts\Authenticatable  $user
     * @param  mixed  $token_identifier
     * @return bool|null
     */
    public function removeRememberToken(Authenticatable $user, $token_identifier)
    {
        return $user->removeRememberToken($token_identifier);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \JPNut\ExtendedAuth\Contracts\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
           (count($credentials) === 1 &&
            array_key_exists('password', $credentials))) {
            return;
        }

        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $value) {
            if (! Str::contains($key, 'password')) {
                $query->where($key, $value);
            }
        }

        return $query->first();
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \JPNut\ExtendedAuth\Contracts\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $plain = $credentials['password'];

        return $this->hasher->check($plain, $user->getAuthPassword());
    }

    /**
     * Create a new instance of the model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createModel()
    {
        $class = '\\'.ltrim($this->model, '\\');

        return new $class;
    }

    /**
     * Gets the hasher implementation.
     *
     * @return \Illuminate\Contracts\Hashing\Hasher
     */
    public function getHasher()
    {
        return $this->hasher;
    }

    /**
     * Sets the hasher implementation.
     *
     * @param  \Illuminate\Contracts\Hashing\Hasher  $hasher
     * @return $this
     */
    public function setHasher(HasherContract $hasher)
    {
        $this->hasher = $hasher;

        return $this;
    }

    /**
     * Gets the name of the Eloquent user model.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Sets the name of the Eloquent user model.
     *
     * @param  string  $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }
}