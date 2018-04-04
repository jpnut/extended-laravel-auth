<?php

namespace JPNut\ExtendedAuth;

use Illuminate\Database\Eloquent\Model;
use JPNut\ExtendedAuth\Contracts\Tokenable as TokenableInterface;
use JPNut\ExtendedAuth\Tokenable;

class Token extends Model implements TokenableInterface
{
	use Tokenable;

    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value'
    ];

	public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('extended-auth.token_table_name'));
    }

    /**
	 * The authenticatable user to which the token belongs.
	 * 
	 * @return \JPNut\ExtendedAuth\Contracts\Authenticatable
	 */
    public function auth()
    {
    	return $this->morphTo();
    }
}