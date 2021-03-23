<?php

namespace App\EdiServices\Cielo;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use App\Exceptions\EdiService\EdiServiceException;

class CieloEdiRegister {
  const AVAILABLE_STATUS = "available";
  const UNAVAILABLE_STATUS = "unavailable";

  private $accessToken = null;

  public function invoke($accessToken, $params) {
    try {
      $this->accessToken = $accessToken;

      $merchants = $this->getAllMerchants();
      $mainMerchants = $this->getMainMerchants();

      list($availableMerchants, $unavailableMerchants) = [
        $merchants[self::AVAILABLE_STATUS],
        $merchants[self::UNAVAILABLE_STATUS]
      ];
      list($availableMain, $unavailableMain) = [
        $mainMerchants[self::AVAILABLE_STATUS],
        $mainMerchants[self::UNAVAILABLE_STATUS]
      ];

      $registeredMerchants = $this->registerEdi($availableMerchants, $params);
      $duplicatedMainMerchants = $this->duplicateMainMerchants($availableMain);

      $duplicatedMerchantIds = array_reduce($duplicatedMainMerchants, function($ids, $mainMerchant) {
        $ids = Arr::collapse([$ids, $mainMerchant['merchants']]);
        return $ids;
      }, []);

      $successfullMerchants = array_reduce($unavailableMerchants,
        function($data, $merchant) use ($duplicatedMerchantIds) {
          if(in_array($merchant['merchantID'], $duplicatedMerchantIds)) {
            array_push($data, $merchant);
          }

          return $data;
        }, []);


      return [
        "registeredMerchants" => Arr::collapse([$registeredMerchants, $successfullMerchants]),
        "duplicatedMerchants" => [
          "successfull" => $duplicatedMainMerchants,
          "failed" => $unavailableMain
        ]
      ];
    } catch(EdiServiceException $e) {
      throw $e;
    }
  }

  private function checkResponse($response) {
    throw_if($response->failed(), new EdiServiceException('Um problema ocorreu. Reinicie o processo!'));

    return true;
  }

  private function groupByStatus($merchants, $statusKey = 'status') {
    return array_reduce($merchants, function($grouped, $merchant) use ($statusKey) {
      $key = strtolower($merchant[$statusKey]);
      if(!Arr::has($grouped, $key)) Arr::set($grouped, $key, []);

      array_push($grouped[$key], $merchant);
      return $grouped;
    }, [
      self::AVAILABLE_STATUS => [],
      self::UNAVAILABLE_STATUS => [],
    ]);
  }

  private function getAllMerchants() {
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer '.$this->accessToken,
      ])
      ->get('https://api2.cielo.com.br/edi-api/v2/edi/merchantgroup');

    $this->response = $this->checkResponse($response);

    $merchants = $response->json();
    return $this->groupByStatus($merchants['branches'], 'status');
  }

  private function getMainMerchants() {
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer '.$this->accessToken,
      ])
      ->get('https://api2.cielo.com.br/edi-api/v2/edi/mainmerchants');

    $this->checkResponse($response);
    $mainMerchants = $response->json();

    $this->response = $this->checkResponse($response);


    return $this->groupByStatus($mainMerchants, 'editStatus');
  }

  private function registerEdi($merchants, $params) {
    if(empty($merchants)) {
      return $merchants;
    }

    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer '.$this->accessToken,
      ])
      ->post('https://api2.cielo.com.br/edi-api/v2/edi/registers', [
        'merchantEMail' => $params['email'],
        'merchants' => $merchants,
        'type' => [
          'SELL',
          'PAYMENT',
          'ANTECIPATION_CIELO',
          'ASSIGNMENT',
          'BALANCE',
          'ANTECIPATION_ALELO',
        ],
      ]);

    $this->checkResponse($response);
    return $merchants;
  }

  private function duplicateMainMerchants($mainMerchants) {
    if(empty($mainMerchants)) {
      return $mainMerchants;
    }

    foreach($mainMerchants as $mainMerchant) {
      $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer '.$this->accessToken,
      ])
      ->put('https://api2.cielo.com.br/edi-api/v2/edi', [
        'mainMerchantID' => $mainMerchant['mainMerchantID'],
        'registerID' => $mainMerchant['registerID'],
        'merchants' => $mainMerchant['merchants'],
        'type' => $mainMerchant['type']
      ]);

      $this->checkResponse($response);
    }

    return $mainMerchants;
  }
}
