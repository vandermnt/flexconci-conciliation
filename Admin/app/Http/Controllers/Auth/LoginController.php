<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\ClienteModel;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Crypt;

class LoginController extends Controller
{

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	// protected $redirectTo = RouteServiceProvider::HOME;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	// public function __construct()
	// {
	//   $this->middleware('guest')->except('logout');
	// }

	public function login()
	{
		return view('auth.login');
	}

	public function username()
	{
		return 'USUARIO';
	}
	public function password()
	{
		return 'SENHA';
	}

	public function logout()
	{
		auth()->logout();
		return view('auth.login');
	}

	public function loginUserComercial(Request $request)
	{
		$cod_cliente = $request['usuario_comercial'];
		$cod_buscas = $request['combo_cliente'];

		$user = User::where('CODIGO', '=', $cod_cliente)->first();

		session()->put('codigologin', $cod_buscas);
		session()->put('emailuserlogado', $user->USUARIO);

		date_default_timezone_set('America/Sao_Paulo');

		$user->DATA_ULTIMO_LOGIN =  date('Y/m/d');
		$user->HORA_ULTIMO_LOGIN =  date('H:i');

		$user->save();

		Auth::login($user);

		return redirect('/');
	}

	public function teste()
	{
		dd("Teste");
	}

	public function loginUserGlobal(Request $request)
	{
		$cod_empresa = $request['empresaescolhida'];

		$user = User::where('CODIGO', '=', $request['usuario_global'])->first();

		session()->put('codigologin', $cod_empresa);
		session()->put('emailuserlogado', $user->USUARIO);

		Auth::login($user);

		return redirect('/');
	}

	public function postLogin(Request $request)
	{

		$user = User::where('SENHA', '=', $request->autenticacao['password'])
			->where('USUARIO', '=', $request->autenticacao['user'])
			->first();

		if ($user) {
			if ($user->USUARIO_GLOBAL == 'S') {
				$usuario_global = ClienteModel::all();
				$login['success'] = true;

				$aux = 'user_global';

				$clientes = json_encode([$usuario_global, $aux, $user]);

				return $clientes;
			} else if ($user->COD_ORIGEM_COMERCIAL != null) {
				$aux = 'user_comercial';
				$clientes_codcomercial = ClienteModel::where('COD_COMERCIAL', '=', $user->COD_ORIGEM_COMERCIAL)->get();


				$clientes = json_encode([$clientes_codcomercial, $aux, $user]);

				return $clientes;
			} else if ($user->USUARIO_GLOBAL == 'N' && $user->COD_ORIGEM_COMERCIAL == null) {
				$aux = 'user_comum';
				Auth::login($user);
				session()->put('codigologin', $user->COD_CLIENTE);
				session()->put('emailuserlogado', $user->USUARIO);

				$clientes = json_encode([$user, $aux]);

				return $clientes;
			}
		} else {

			return json_encode(false);
		}
	}

	public function trocarEmpresa(Request $request)
	{
		$cod_empresa = $request['empresaescolhida'];

		$user = User::where('CODIGO', '=', $request['usuario_global'])->first();

		session()->put('codigologin', $cod_empresa);
		session()->put('emailuserlogado', $user->USUARIO);

		Auth::login($user);

		return response()->json(200);
	}
	public function encryptPassword(Request $request)
	{
		$encrypted = Crypt::encryptString($request->autenticacao['password']);
		return response()->json(['password' => $encrypted]);
	}

	public function decryptPassword(Request $request)
	{
		$decrypted = Crypt::decryptString($request->password);
		return response()->json(['password' => $decrypted]);
	}
}
