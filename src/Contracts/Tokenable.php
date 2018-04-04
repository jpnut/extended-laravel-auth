<?php

namespace JPNut\ExtendedAuth\Contracts;

interface Tokenable
{
	/**
	 * The authenticatable user to which the token belongs.
	 * 
	 * @return \JPNut\ExtendedAuth\Contracts\Authenticatable
	 */
	public function auth();

	/**
	 * Set the token value.
	 * 
	 * @param  string $token_value
	 * @return  static
	 */
	public function setTokenValue(string $token_value);

	/**
	 * Get the token value.
	 * 
	 * @return  string
	 */
	public function getTokenValue();

	/**
	 * Get the token ID.
	 * 
	 * @return  mixed
	 */
	public function getTokenId();
}