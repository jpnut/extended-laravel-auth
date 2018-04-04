<?php

namespace JPNut\ExtendedAuth;

trait Tokenable
{
	/**
	 * Set the token value.
	 * 
	 * @param  string $token_value
	 * @return  static
	 */
	public function setTokenValue(string $token_value)
	{
		$this->value = $token_value;
		return $this;
	}

	/**
	 * Get the token value.
	 * 
	 * @return  string
	 */
	public function getTokenValue()
	{
		return $this->value;
	}

	/**
	 * Get the token ID.
	 * 
	 * @return  mixed
	 */
	public function getTokenId()
	{
		return $this->id;
	}
}