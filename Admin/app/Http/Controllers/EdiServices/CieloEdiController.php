<?php

namespace App\Http\Controllers\EdiServices;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\EdiServices\Cielo\CieloEdiAuthorize;
use App\EdiServices\Cielo\CieloEdiRegister;
use App\Exceptions\EdiService\EdiServiceException;
use App\Exceptions\EdiService\UnmatchStateException;
use App\Exceptions\EdiService\ConnectionTimeoutException;

class CieloEdiController extends Controller
{
  private $service = null;
  private $ediRegister = null;

  public function __construct(CieloEdiAuthorize $service, CieloEdiRegister $ediRegister) {
    $this->service = $service;
    $this->ediRegister = $ediRegister;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('edi-services.cielo.credenciamento');
  }

  public function authenticate(Request $request) {
    $email = $request->input('email', null);

    if(!$email) {
      return redirect()
        ->route('cielo.credenciamento')
        ->withErrors(['email' => 'Preencha o email para avançar.']);
    }

    $state = $this->service->generateState();
    session()->put('merchant_email', $email);
    session()->put('cielo_state', $state);

    $authenticateUrl = $this->service->authenticate([
      'state' => $state,
      'scope' => 'profile_read,transaction_read,transaction_write',
    ]);

    return redirect($authenticateUrl);
  }

  public function callback(Request $request) {
    try {
      $error = $request->get('error', null);
      $states = ['sessionState' => session('cielo_state'), 'returnedState' => $request->get('state')];
      $code = $request->input('code', null);

      $this->service->handleAuthError($states, $error);
      $data = collect($this->service->authorize($code))->get('data');
      $data = collect($data);
      $accessToken = $data->get('access_token');

      $this->registerCieloCredentials($data->merge(['code' => $code])->all());
      session()->put('cielo_access_token', $accessToken);
      return redirect()->route('cielo.authorize')->with([
        'access_token' => $accessToken,
        'success' => 'Autorização concedida.'
      ]);
    } catch(UnmatchStateException $exception) {
      $error = 'Por motivos de segurança essa operação foi cancelada.';
    } catch (EdiServiceException $exception) {
      $error = $exception->getMessage();
    } finally {
      if($error) return redirect()->route('cielo.authorize')->withErrors(['error' => $error]);
      session()->forget('cielo_state');
    }
  }

  public function authorize(Request $request) {
    return view('edi-services.cielo.authorize');
  }

  public function checkout(Request $request) {
    try {
      $accessToken = session('cielo_access_token', null);
      $merchants = $request->input('merchants', []);

      if(is_null($accessToken)) {
        return response()->json([
          'status' => 'failed',
          'error' => 'An access token is required.',
        ], 401);
      }

      $this->registerMerchants($merchants);
      session()->forget(['cielo_access_token', 'merchant_email']);
      return response()->json([
        'status' => 'success',
      ], 201);
    } catch(Exception $e) {
      return response()->json([
        'status' => 'failed',
        'error' => 'An error occurred.',
      ], 500);
    }
  }

  public function show() {
    return view('edi-services.cielo.results')->with([
        'accessToken' => session('cielo_access_token'),
        'baseUrl' => $this->service->getBaseUrl().'/edi-api/v2/edi',
        'merchantEmail' => session('merchant_email'),
      ]);
  }

  private function registerCieloCredentials($data) {
    $data = collect($data);
    DB::table('credenciamento_cielo')
      ->updateOrInsert(
        [
          'ACCESS_TOKEN' => $data->get('access_token'),
        ],
        [
          'ACCESS_TOKEN' => $data->get('access_token'),
          'REFRESH_TOKEN' => $data->get('refresh_token'),
          'CODE' => $data->get('code'),
        ]
      );

    return true;
  }

  private function registerMerchants($merchants) {
    $merchants = collect($merchants);
    $credentialsRegisterId = DB::table('credenciamento_cielo')
      ->select('CODIGO')
      ->where('ACCESS_TOKEN', session('cielo_access_token'))
      ->first()
      ->CODIGO;


    $merchantsIds = $merchants->pluck('merchantID')->all();
    DB::table('estabelecimentos_credenciamento')
      ->whereIn('ESTABELECIMENTO', $merchantsIds)
      ->delete();

    $data = $merchants->map(function($merchant, $key) use ($credentialsRegisterId) {
        return [
          'ESTABELECIMENTO' => $merchant['merchantID'],
          'COD_CREDENCIAMENTO' => $credentialsRegisterId
        ];
      })
      ->all();

    DB::table('estabelecimentos_credenciamento')->insert($data);
  }
}
