<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

Auth::routes();
if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}



Route::group(['middleware' => 'auth'], function () {

	//DAHSBOARD
	Route::get('/', 'DashboardController@dashboard');
	Route::get('/detalhe-calendario/{data}', 'DashboardController@detalheCalendario');
	Route::get('/detalhe-calendario-prev/{data}', 'DashboardController@detalheCalendarioPrevisaoPagamento');

	Route::get('/export-vendasoperadora/{periodo}', 'DashboardController@exportarPdfVendasOperadoras');
	Route::get('/export-vendasbandeira/{periodo}', 'DashboardController@exportarPdfVendasBandeiras');
	Route::get('/export-vendasmodalidade/{periodo}', 'DashboardController@exportarPdfVendasModalidade');
	Route::get('/export-vendasproduto/{periodo}', 'DashboardController@exportarPdfVendasProduto');


	// Route::get('/', function () {
	//     return redirect('/analytics/analytics-index');
	// });

	//ANTECIPAÇÃO
	Route::get('/antecipacao', 'AntecipacaoController@antecipar');
	Route::match(['get', 'post'], '/anticipationn', 'AntecipacaoController@filtro');
	Route::post('/simulacao-anticipation', 'HistoricoAntecipacaoController@antecipation');

	//PROJETOS
	Route::get('/lista-projetos', 'ProjetosController@listarProjetos');
	Route::get('/projeto/{codprojeto}', 'ProjetosController@detalhamentoProjeto');

	//VENDAS
	//   Route::get('/vendasoperadoras', 'VendasController@vendas');
	Route::match(['get', 'post'], '/vendasoperadorasfiltro', 'VendasController@buscarVendasFiltro');
	Route::get('/download', 'VendasController@downloadTable');
	Route::get('/desfazer-justificativa/{codigo}', 'VendasController@desfazerJustificativa');
	Route::match(['get', 'post'], '/exportxls-vendas-operadora', 'VendasController@exportXls');

	Route::get('/vendas-operadoras', 'VendasOperadorasController@index')->name('vendas-operadoras.index');
	Route::post('/vendas-operadoras/buscar', 'VendasOperadorasController@search')->name('vendas-operadoras.search');
	Route::post('/vendas-operadoras/filtrar', 'VendasOperadorasController@filter')->name('vendas-operadoras.filter');
	Route::post('/vendas-operadoras/justificar', 'VendasOperadorasController@justify')->name('vendas-operadoras.justify');
	Route::post('/vendas-operadoras/desjustificar', 'VendasOperadorasController@unjustify')->name('vendas-operadoras.unjustify');
	Route::get('/vendas-operadoras/exportar', 'VendasOperadorasController@export')->name('vendas-operadoras.export');
	Route::get('/vendas-operadoras/retorno-csv', 'VendasOperadorasController@exportCsv')->name('vendas-operadoras.retorno-csv');
	Route::get('/vendas-operadoras/imprimir/{id}', 'VendasOperadorasController@print')->name('vendas-operadoras.print');

	Route::get('/conciliacao-vendas', 'ConciliacaoVendasController@index')->name('conciliacao-vendas');
	Route::post('/conciliacao-vendas/buscar/erp', 'ConciliacaoVendasController@searchErp')->name('conciliacao-vendas.buscarErp');
	Route::post('/conciliacao-vendas/filtrar/erp', 'ConciliacaoVendasController@filterErp')->name('conciliacao-vendas.filtrarErp');
	Route::post('/conciliacao-vendas/buscar/operadoras', 'ConciliacaoVendasController@searchOperadoras')->name('conciliacao-vendas.buscarOperadoras');
	Route::post('/conciliacao-vendas/filtrar/operadoras', 'ConciliacaoVendasController@filterOperadoras')->name('conciliacao-vendas.filtrarOperadoras');
	Route::post('/conciliacao-vendas/conciliar/manualmente', 'ConciliacaoVendasController@conciliarManualmente')
		->name('conciliacao-vendas.conciliarManualmente')
		->middleware('verifica_vendas_conciliacao');
	Route::post('/conciliacao-vendas/desconciliar/manualmente', 'ConciliacaoVendasController@desconciliarManualmente')
		->name('conciliacao-vendas.desconciliarManualmente')
		->middleware('verifica_vendas_desconciliacao');
	Route::get('/conciliacao-vendas/exportar/erp', 'ConciliacaoVendasController@exportarErp')
		->name('conciliacao-vendas.exportar.erp');
	Route::get('/conciliacao-vendas/exportar/operadoras', 'ConciliacaoVendasController@exportarOperadoras')
		->name('conciliacao-vendas.exportar.operadoras');

	Route::match(['get', 'post'], '/conciliacao-manual', 'ConciliacaoAutomaticaVendasController@conciliarManualmente');
	Route::match(['get', 'post'], '/conciliacao-justificada-venda', 'ConciliacaoAutomaticaVendasController@conciliacaoJustificadaVenda');
	Route::match(['get', 'post'], '/conciliacao-justificada-vendaerp', 'ConciliacaoAutomaticaVendasController@conciliacaoJustificadaVendaErp');
	Route::match(['get', 'post'], '/conciliar', 'ConciliacaoAutomaticaVendasController@saveConciliacao');

	//VENDAS - RECEBIMENTOS OPERADORAS
	// Route::get('/recebimentos-operadora', 'RecebimentosOperadoraController@recebimentosOperadora');
	// Route::match(['get', 'post'], '/consultar-recebimentos-operadora', 'RecebimentosOperadoraController@consultarRecebimentosOperadoras');
	// Route::get('/download-vendas-operadora', 'RecebimentosOperadoraController@downloadTable');
	Route::get('/recebimentos-operadoras', 'RecebimentosOperadorasController@index')->name('recebimentos-operadoras.index');
	Route::post('/recebimentos-operadoras/buscar', 'RecebimentosOperadorasController@search')->name('recebimentos-operadoras.search');
	Route::post('/recebimentos-operadoras/filtrar', 'RecebimentosOperadorasController@filter')->name('recebimentos-operadoras.filter');
	Route::get('/recebimentos-operadoras/exportar', 'RecebimentosOperadorasController@export')->name('recebimentos-operadoras.export');
	Route::get('/recebimentos-operadoras/retorno-csv', 'RecebimentosOperadorasController@exportCsv')->name('recebimentos-operadoras.retorno-csv');
	Route::get('/recebimentos-operadoras/retorno-recebimento', 'RetornoRecebimentoController@index')
		->name('recebimentos-operadoras.retorno-recebimento')
		->middleware('must_be_global_user');

	//VENDAS SISTEMA ERP
	Route::get('/vendas-sistema-erp', 'VendasErpController@index')->name('vendas-erp.index');
	Route::post('/vendas-sistema-erp/buscar', 'VendasErpController@search')->name('vendas-erp.search');
	Route::post('/vendas-sistema-erp/filtrar', 'VendasErpController@filter')->name('vendas-erp.filter');
	Route::post('/vendas-sistema-erp/justificar', 'VendasErpController@justify')->name('vendas-erp.justify');
	Route::post('/vendas-sistema-erp/desjustificar', 'VendasErpController@unjustify')->name('vendas-erp.unjustify');
	Route::get('/vendas-sistema-erp/exportar', 'VendasErpController@export')->name('vendas-erp.export');
	Route::get('/vendas-sistema-erp/retorno-erp', 'RetornoErpController@index')
		->name('vendas-erp.retorno-erp')
		->middleware('must_be_global_user');

	//USUARIO
	Route::get('/logout', 'Auth\LoginController@logout');

	//AUTORIZACAO CREDENCIADORA
	Route::post('/autorizar-acesso', 'AutorizacaoAcessoController@autorizarAcesso');

	//CADASTRO HISTÓRICO BANCÁRIO
	Route::get('/historico-bancario', 'CadastroHistoricoBancarioController@cadastroHistoricoBancario');
	Route::post('/post-historico', 'CadastroHistoricoBancarioController@newCadastroHistoricoBancario');
	Route::get('/load-historico-bancario', 'CadastroHistoricoBancarioController@loadHistoricoBancario');
	Route::get('/delete-historico-bancario/{codigo}', 'CadastroHistoricoBancarioController@deleteHistoricoBancario');

	//CADASTRO JUSTIFICATIVA
	Route::get('/justificativas', 'CadastroJustificativaController@justificativas');
	Route::post('/post-justificativa', 'CadastroJustificativaController@saveJustificativa');
	Route::get('/load-justificativas', 'CadastroJustificativaController@loadJustificativas');
	Route::get('/delete-justificativa/{codigo}', 'CadastroJustificativaController@deleteJustificativa');
	Route::get('/justificativa/{codigo}', 'CadastroJustificativaController@show');

	//CONCILIAÇÃO
	Route::get('/conciliacao-bancaria', function () {
		return view('conciliacao.conciliacao-bancaria');
	});
	Route::post('/enviar-extrato', 'ConciliacaoController@conciliacaoBancaria');
	Route::get('/atualizar-conciliacoes-processadas', 'ConciliacaoController@atualizarConciliacoesProcessadas');

	//CONCILIAÇÃO DE TAXAS
	Route::get('/conciliacao-taxas', 'ConciliacaoTaxasController@index')->name('conciliacao-taxas');

	//ENVIO EMAIL CHAMADO
	Route::get('/enviar-email', 'DashboardController@enviaEmail');


	//IMPRESSAO VENDAS
	Route::get('/impressao-vendas/{codigo}', 'VendasController@impressaoCupom');
	Route::post('/dados-cliente', 'ClienteController@dadosCliente');

	//RECEBIMENTOS - PREVISÃO RECEBIMENTOS
	// Route::get('/previsao-recebimentos', 'PrevisaoRecebimentosController@previsaoRecebimentos');
	// Route::post('/previsaorecebimentos', 'PrevisaoRecebimentosController@loadPrevisaoRecebimentos');
	Route::get('/recebimentos-futuros', 'RecebimentosFuturosController@index')->name('recebimentos-futuros.index');
	Route::post('/recebimentos-futuros/buscar', 'RecebimentosFuturosController@search')->name('recebimentos-futuros.search');
	Route::post('/recebimentos-futuros/filtrar', 'RecebimentosFuturosController@filter')->name('recebimentos-futuros.filter');
	Route::get('/recebimentos-futuros/exportar', 'RecebimentosFuturosController@export')->name('recebimentos-futuros.export');

    Route::get('/cielo/credenciamento', 'EdiServices\CieloEdiController@index')->name('cielo.credenciamento');
    Route::get('/cielo/authenticate', 'EdiServices\CieloEdiController@authenticate')->name('cielo.authenticate');
    Route::get('/cielo/callback', 'EdiServices\CieloEdiController@callback')->name('cielo.callback');
    Route::get('/cielo/autorizacao', 'EdiServices\CieloEdiController@authorize')->name('cielo.authorize');
    Route::get('/cielo/registro-edi', 'EdiServices\CieloEdiController@ediRegister')->name('cielo.register-edi');
    Route::get('/cielo/resultados', 'EdiServices\CieloEdiController@show')->name('cielo.results');
});

Route::get('/credenciamento-cielo', function () {
	return view('authentication.auth-lock-screen');
});


Route::get('/reset', function () {
	return view('auth.passwords.email');
});

// Route::post('/forgot-password', function (Request $request) {

//     $request->validate(['email' => 'required|email']);

//     $status = Password::sendResetLink(
//         $request->only('email')
//     );

//     return $status === Password::RESET_LINK_SENT
//                 ? back()->with(['status' => __($status)])
//                 : back()->withErrors(['email' => __($status)]);
// })->middleware('guest')->name('post-reset-password');

Route::get('/credenciamento-stone', function () {
	return view('authentication.auth-lock-stone');
});

Route::get('/teste', function () {
	return view('acess-stone');
});

Route::get('/credeciamento', function () {
	return view('authentication.retorno-credenciamento');
});

Route::post('/credenciamento-edi', 'AutorizacaoAcessoController@credenciarEdi')->name("edi");

Route::post('/login', 'Auth\LoginController@postLogin')->name('loginlogin');
Route::post('/login-comercial', 'Auth\LoginController@loginUserComercial')->name('logincomercial');
Route::post('/login-global', 'Auth\LoginController@loginUserGlobal')->name('loginglobal');
