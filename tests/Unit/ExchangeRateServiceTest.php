<?php

namespace Tests\Unit;

use App\Services\ExchangeRateService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExchangeRateServiceTest extends TestCase {

	public function setUp(): void {
		parent::setUp();

		Http::fake([
			'http://api.exchangerate.host/live*' => Http::response(['success' => true, 'quotes' => ['USDEUR' => 1.2]], 200),
		]);
	}

	public function testGetExchangeRate() {
		$exchangeRateService = new ExchangeRateService();
		$exchangeRate = $exchangeRateService->getExchangeRate('USD', 'EUR');

		$this->assertEquals(1.2, $exchangeRate);
	}
}
