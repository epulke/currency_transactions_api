<?php

namespace App\Services;

use App\Exceptions\ExchangeRateServiceException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;

class ExchangeRateService {

	protected string $apiKey;
	protected Client $client;

	public function __construct() {
		$this->apiKey = config('services.exchangerate_host.api_key');
		$this->client = new Client();
	}

	public function getCurrencies(): array {
		$url = $this->getUrl('list');
		$response = $this->getResponse($url);

		return array_keys($response['currencies']);
	}

	public function getExchangeRate(string $baseCurrency, string $targetCurrency): float {
		$url = $this->getUrl('live', [
			'source' => $baseCurrency,
			'currencies' => $targetCurrency
		]);

		$response = $this->getResponse($url);
		$currency_key = $baseCurrency . $targetCurrency;

		if (!array_key_exists('quotes', $response) || !array_key_exists($currency_key, $response['quotes'])) {
			throw new ExchangeRateServiceException("This currency quote is not available at the moment");
		}

		return $response['quotes'][$currency_key];
	}

	private function getResponse(string $url): array {
		$attempts = 3;

		while ($attempts > 0) {
			try {
				$response_json = Http::get($url);
				$response = json_decode($response_json->getBody(), true);

				if ($response) {
					break;
				}
			} catch (RequestException $e) {
				$attempts--;

				if ($attempts === 0) {
					$response = ['success' => false, 'error' => ['info' => $e->getMessage()]];
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

	private function getUrl(string $endpoint, array $url_params = []): string {
		$url_params = array_merge(['access_key' => $this->apiKey], $url_params);

		return "http://api.exchangerate.host/{$endpoint}?" . http_build_query($url_params);
	}
}