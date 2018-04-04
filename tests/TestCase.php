<?php

namespace JPNut\ExtendedAuth\Test;

use JPNut\ExtendedAuth\AuthServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
	protected function getPackageProviders($app)
	{
		return [
			AuthServiceProvider::class
		];
	}
}