<?php

namespace App\Services;

use GuzzleHttp\Client;

class ExchangeRateService {

	protected string $apiKey;
	protected Client $client;

	public function __construct() {
		$this->apiKey = config('services.exchangerate_host.api_key');
		$this->client = new Client();
	}

	public function getCurrencies(): array {
		$url = "http://api.exchangerate.host/list?access_key={$this->apiKey}";
		$response_json = $this->client->get($url);
		$response = json_decode($response_json->getBody(), true);

		return array_keys($response['currencies']);
	}

	public function getExchangeRate(string $baseCurrency, string $targetCurrency): float {
		$url = "http://api.exchangerate.host/live?access_key={$this->apiKey}"
			."&source={$baseCurrency}"
			."&currencies={$targetCurrency}";
		$response_json = $this->client->get($url);
		$response = json_decode($response_json->getBody(), true);

		return array_values($response['quotes'])[0];
	}
}