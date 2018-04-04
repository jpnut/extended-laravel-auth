<?php

return [
	/**
	 * The name of the table which holds the "remember me" tokens. Note that if you overwrite the 
	 * token class' constructor, you should reference this value.
	 */
	'token_table_name' => 'remember_tokens',

	/**
	 * The name of the user provider driver in this package. For any auth configurations which
	 * utilise this package, one should reference this value as the "provider->driver"
	 */
	'provider' => 'extended-eloquent',

	/**
	 * The name of the guard driver in this package. For any auth configurations which utilise
	 * this package, one should reference this value as the "guard->driver"
	 */
	'guard' => 'extended-session',
];