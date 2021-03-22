<?php

namespace App\Http\Controllers\EdiServices;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\EdiServices\Cielo\CieloEdiAuthorize;
use App\Exceptions\EdiService\EdiServiceException;
use App\Exceptions\EdiService\UnmatchStateException;

class CieloEdiController extends Controller
{
  private $service = null;

  public function __construct(CieloEdiAuthorize $service) {
    $this->service = $service;
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

  public function authorize(Request $request) {
    try {
      $error = $request->get('error', null);
      $states = ['sessionState' => session('cielo_state'), 'returnedState' => $request->get('state')];
      $code = $request->input('code', null);

      $this->service->handleAuthError($states, $error);
      $data = $this->service->authorize($code);
      $access_token = $data['data']['access_token'];

      return view('edi-services.cielo.callback')->with([
        'access_token' => $access_token,
        'success' => 'Autorização concedida.'
      ]);
    } catch(UnmatchStateException $exception) {
      $error = 'Por motivos de segurança essa operação foi cancelada.';
    } catch (EdiServiceException $exception) {
      $error = $exception->getMessage();
    } finally {
      if($error) return view('edi-services.cielo.callback')->withErrors(['error' => $error]);
      session()->forget('cielo_state');
    }
  }
}
