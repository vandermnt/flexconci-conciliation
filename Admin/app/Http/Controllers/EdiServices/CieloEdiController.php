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
      $data = $this->service->authorize($code);
      $accessToken = $data['data']['access_token'];

      $this->registerCieloCredentials(array_merge($data['data'], ['code' => $code]));
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
    return view('edi-services.cielo.callback');
  }

  public function ediRegister(Request $request) {
    try {
      $accessToken = session('cielo_access_token');
      $results = $this->ediRegister->invoke($accessToken, []);

      $this->registerMerchants($results['registeredMerchants']);
      session()->forget('cielo_access_token');

      return response()->json($results);
    } catch(EdiServiceException $exception) {
      return response()->json([
          'status' => 'failed',
          'error' => $exception->getMessage(),
        ]);
    }
  }

  public function show() {
    return view('edi-services.cielo.results')->with([
        'accessToken' => session('cielo_access_token')
      ]);
  }

  private function registerCieloCredentials($data) {
    DB::table('credenciamento_cielo')
      ->updateOrInsert(
        [
          'ACCESS_TOKEN' => $data['access_token'],
        ],
        [
          'ACCESS_TOKEN' => $data['access_token'],
          'REFRESH_TOKEN' => $data['refresh_token'],
          'CODE' => $data['code'],
        ]
      );

    return true;
  }

  private function registerMerchants($merchants) {
    set_time_limit(180);

    $credentialsRegisterId = DB::table('credenciamento_cielo')
      ->select('CODIGO')
      ->where('ACCESS_TOKEN', session('cielo_access_token'))
      ->first()
      ->CODIGO;

    $merchantsIds = array_reduce($merchants, function($ids, $merchant) use ($credentialsRegisterId) {
      array_push($ids, [
        'ESTABELECIMENTO' => $merchant['merchantID'],
        'COD_CREDENCIAMENTO' => $credentialsRegisterId
      ]);
      return $ids;
    }, []);

    DB::table('estabelecimentos_credenciamento')->insert($merchantsIds);
  }
}
