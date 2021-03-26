<?php

namespace App\EdiServices\Cielo;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\ConnectionException;
use App\EdiServices\Cielo\CieloEdiService;
use App\Exceptions\EdiService\ConnectionTimeoutException;
use App\Exceptions\EdiService\EdiServiceException;

class CieloEdiRegister extends CieloEdiService {
  const AVAILABLE_STATUS = "available";
  const UNAVAILABLE_STATUS = "unavailable";

  private $accessToken = null;

  public function invoke($accessToken, $params) {
    try {
      $this->accessToken = $accessToken;

      $availableKey = Str::lower(self::AVAILABLE_STATUS);
      $unavailableKey = Str::lower(self::UNAVAILABLE_STATUS);

      $merchants = collect($this->getAllMerchants());
      $mainMerchants = collect($this->getMainMerchants());

      $duplicatedMerchantIds = collect($mainMerchants->get($availableKey))
        ->pluck('merchants')
        ->collapse();

      $duplicatedMerchants = collect($merchants->get($unavailableKey))
        ->filter(function ($value, $key) use ($duplicatedMerchantIds) {
          return $duplicatedMerchantIds->contains($value['merchantID']);
        });

      $registered = $this->registerEdi($merchants->get($availableKey), $params);
      $registeredMerchants = collect([
          $registered,
          $duplicatedMerchants
        ])
          ->collapse();

      if(collect($merchants->get($unavailableKey))->isEmpty()) {
        return [
          "registeredMerchants" => $registeredMerchants->toArray(),
          "duplicatedMerchants" => [
            "successfull" => [],
            "failed" => []
          ]
        ];
      }

      $duplicatedMainMerchants = $this->duplicateMainMerchants($mainMerchants->get($availableKey));

      return [
        "registeredMerchants" => $registeredMerchants->toArray(),
        "duplicatedMerchants" => [
          "successfull" => $duplicatedMainMerchants,
          "failed" => $mainMerchants[$unavailableKey]
        ]
      ];
    } catch(EdiServiceException $e) {
      throw $e;
    }
  }

  private function getAllMerchants() {
    $response = $this->sendRequest(function($request) {
      return $request
        ->retry(3, 60)
        ->get($this->getBaseUrl().'/edi-api/v2/edi/merchantgroup');
    });

    $merchants = $response->json();
    return $this->groupByStatus($merchants['branches'], 'status');
  }

  private function getMainMerchants() {
    $response = $this->sendRequest(function($request) {
      return $request->retry(3, 60)
        ->get($this->getBaseUrl().'/edi-api/v2/edi/mainmerchants');
    });

    $mainMerchants = $response->json();

    return $this->groupByStatus($mainMerchants, 'editStatus');
  }

  private function registerEdi($merchants, $params) {
    if(collect($merchants)->isEmpty()) {
      return $merchants;
    }

    $params = collect($params);

    $payload = [
      'merchantEMail' => $params->get('email'),
      'merchants' => $merchants,
      'type' => [
        'SELL',
        'PAYMENT',
        'ANTECIPATION_CIELO',
        'ASSIGNMENT',
        'BALANCE',
        'ANTECIPATION_ALELO',
      ],
    ];

    $response = $this->sendRequest(function($request) use ($payload) {
      return $request->retry(3, 60)
        ->post($this->getBaseUrl().'/edi-api/v2/edi/registers', $payload);
    });

    return $merchants;
  }

  private function duplicateMainMerchants($mainMerchants) {
    if(collect($mainMerchants)->isEmpty()) {
      return $mainMerchants;
    }

    foreach($mainMerchants as $mainMerchant) {
      $response = $this->sendRequest(function($request) use ($mainMerchant) {
        $mainMerchant = collect($mainMerchant);
        return $request->retry(3, 60)
          ->put($this->getBaseUrl().'/edi-api/v2/edi', [
            'mainMerchantID' => $mainMerchant->get('mainMerchantID'),
            'registerID' => $mainMerchant->get('registerID'),
            'merchants' => $mainMerchant->get('merchants'),
            'type' => $mainMerchant->get('type'),
          ]);
      });
    }

    return $mainMerchants;
  }

  private function buildRequest() {
    return Http::withHeaders([
      'Content-Type' => 'application/json',
      'Authorization' => $this->getAuthorizationHeader($this->accessToken)
    ]);
  }

  private function sendRequest($callback) {
    try {
      $request = $this->buildRequest();

      if($callback) {
        $response = $callback($request);
        $response->throw();

        return $response;
      }

      return null;
    } catch(ConnectionException $exception) {
      throw new ConnectionTimeoutException('A conexão com a Cielo falhou, tempo de espera excedido. Tente novamente.');
    } catch(RequestException $exception) {
      $isClientError = collect([400, 401, 403])->contains($exception->response->status());
      throw_if($isClientError, new EdiServiceException('Um problema ocorreu. Tente novamente.'));
      throw_if(!$isClientError, new ConnectionTimeoutException('Tempo de espera excedido, tente novamente.'));
    }
  }

  private function groupByStatus($merchants, $statusKey = 'status') {
    $merchantCollection = collect($merchants);

    $groupedMerchants = $merchantCollection->mapToGroups(function ($item, $key) use ($statusKey) {
      return [Str::lower($item[$statusKey]) => $item];
    })
      ->toArray();

    return collect($groupedMerchants)->mergeRecursive([
      Str::lower(self::AVAILABLE_STATUS) => [],
      Str::lower(self::UNAVAILABLE_STATUS) => [],
    ])
      ->toArray();
  }
}
