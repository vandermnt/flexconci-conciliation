<?php

namespace App\Http\Controllers\EdiServices;

use App\EdiServices\Cielo\CieloEdiAuthorize;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    $state = bin2hex(random_bytes(16));
    session()->put('merchant_email', $email);
    session()->put('cielo_state', $state);

    $authenticateUrl = $this->service->authenticate([
      'state' => $state,
      'scope' => 'profile_read,transaction_read,transaction_write',
    ]);

    return redirect($authenticateUrl);
  }

  public function authorize(Request $request) {
    $error = $request->get('error');
    $state = $request->get('state');

    /** Reference: https://auth0.com/docs/protocols/state-parameters */
    if(session('cielo_state') !== $state) {
      return view('edi-services.cielo.callback')->withErrors(['error' => 'Por segurança essa operação foi cancelada.']);
    }
    else if($error && $error === 'access_denied') {
      return view('edi-services.cielo.callback')->withErrors(['error' => 'Acesso negado.']);
    }

    $code = $request->get('code');

    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => $this->service->getAuthHeader(),
      ])
      ->post($this->service->getBaseUrl().'/consent/v1/oauth/access-token', [
        'grant_type' => 'authorization_code',
        'code' => $code
      ]);

    if($response->failed()) {
      return view('edi-services.cielo.callback')->withErrors(['error' => 'Autorização não concedida. Falha ao gerar chave de acesso!']);
    }

    $data = $response->json();
    return view('edi-services.cielo.callback')->with(['access_token' => $data['access_token'], 'success' => 'Autorização concedida.']);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}
