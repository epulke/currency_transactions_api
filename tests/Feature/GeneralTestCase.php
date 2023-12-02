<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\CreatesApplication;
use Tests\TestCase;

class GeneralTestCase extends TestCase {
	use CreatesApplication;

	public function setUp(): void {
		parent::setUp();

		Artisan::call('migrate:refresh --env=testing');
		Artisan::call('db:seed --class=TestDatabaseSeeder');
	}
}
