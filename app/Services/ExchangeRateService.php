<?php

namespace App\Services;

use App\Exceptions\ExchangeRateServiceException;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ExchangeRateService {

	protected string $apiKey;
	protected Client $client;

	public function __construct() {
		$this->apiKey = config('services.exchangerate_host.api_key');
		$this->client = new Client();
	}

	public function getCurrencies(): array {
		$url = "http://api.exchangerate.host/list?access_key={$this->apiKey}";
		$response = $this->getResponse($url);

		return array_keys($response['currencies']);
	}

	public function getExchangeRate(string $baseCurrency, string $targetCurrency): float {
		$url = "http://api.exchangerate.host/live?access_key={$this->apiKey}"
			."&source={$baseCurrency}"
			."&currencies={$targetCurrency}";
		$response = $this->getResponse($url);
		$currency_key = $baseCurrency . $targetCurrency;

		if (!array_key_exists('quotes', $response) || !array_key_exists($currency_key, $response['quotes'])) {
			throw new Exception("This currency quote is not available at the moment", 201);
		}

		$quote = $response['quotes'][$currency_key];

		return $quote;
	}

	private function getResponse(string $url): array {
		$attempts = 3;

		while ($attempts > 0) {
			try {
				$response_json = $this->client->get($url, ['timeout' => 10]);
				$response = json_decode($response_json->getBody(), true);

				if ($response) {
					break;
				}
			} catch (RequestException $e) {
				$attempts--;

				if ($attempts === 0) {
					$response = [
						'success' => false,
						'error' => ['info' => $e->getMessage()]
					];
				}

			}
		}

		if (!array_key_exists('success', $response) || !$response['success']) {
			$message = (array_key_exists('error', $response) && array_key_exists('info', $response['error']))
				? $response['error']['info']
				: 'Connection to the exchangerate.host has failed, try again later.';

			throw new ExchangeRateServiceException($message);
		}

		return $response;
	}
}