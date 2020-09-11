<?php

use Illuminate\Support\Facades\Route;

Auth::routes();
if(version_compare(PHP_VERSION, '7.2.0', '>=')) { error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING); }



Route::group(['middleware' => 'auth'], function() {

  //DAHSBOARD
  Route::get('/', 'DashboardController@dashboard');

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
  Route::get('/vendasoperadoras', 'VendasController@vendas');
  Route::match(['get', 'post'], '/vendasoperadorasfiltro', 'VendasController@buscarVendasFiltro');
  Route::get('/download', 'VendasController@downloadTable');

  //VENDAS - RECEBIMENTOS OPERADORAS
  Route::get('/recebimentos-operadora', 'RecebimentosOperadoraController@recebimentosOperadora');
  Route::match(['get', 'post'], '/consultar-recebimentos-operadora', 'RecebimentosOperadoraController@consultarRecebimentosOperadoras');

  //VENDAS SISTEMA ERP
  Route::get('/vendas-sistema-erp', 'VendasErpController@vendaserp');
  // Route::match(['get', 'post'], '/vendasoperadorasfiltro', 'VendasController@buscarVendasFiltro');
  // Route::match(['get', 'post'], '/vendass-operadoras', 'VendasController@itensVis');
  // Route::get('/download', 'VendasController@downloadTable');

  //USUARIO
  Route::get('/logout', 'Auth\LoginController@logout');

  //AUTORIZACAO CREDENCIADORA
  Route::post('/autorizar-acesso', 'AutorizacaoAcessoController@autorizarAcesso');

  //CADASTRO HISTÓRICO BANCÁRIO
  Route::get('/historico-bancario', 'CadastroHistoricoBancarioController@cadastroHistoricoBancario');
  Route::post('/post-historico', 'CadastroHistoricoBancarioController@newCadastroHistoricoBancario');
  Route::get('/load-historico-bancario', 'CadastroHistoricoBancarioController@loadHistoricoBancario');
  Route::get('/delete-historico-bancario', 'CadastroHistoricoBancarioController@deleteHistoricoBancario');

  //CONCILIAÇÃO
  Route::get('/conciliacao-bancaria', function() {
      return view('conciliacao.conciliacao-bancaria');
  });
  Route::post('/enviar-extrato', 'ConciliacaoController@conciliacaoBancaria');
  Route::get('/atualizar-conciliacoes-processadas', 'ConciliacaoController@atualizarConciliacoesProcessadas');


  //IMPRESSAO VENDAS
  Route::get('/impressao-vendas/{codigo}', 'VendasController@impressaoCupom');
  Route::post('/dados-cliente', 'ClienteController@dadosCliente');

  //RECEBIMENTOS - PREVISÃO RECEBIMENTOS
  Route::get('/previsao-recebimentos', 'PrevisaoRecebimentosController@previsaoRecebimentos');
  Route::post('/previsaorecebimentos', 'PrevisaoRecebimentosController@loadPrevisaoRecebimentos');

});

Route::get('/autorizacao-credenciadora', function() {
    return view('authentication.auth-lock-screen');
});

Route::get('/credeciamento', function() {
    return view('authentication.retorno-credenciamento');
});

Route::post('/credenciamento-edi', 'AutorizacaoAcessoController@credenciarEdi')->name("edi");

Route::post('/login', 'Auth\LoginController@postLogin')->name('loginlogin');
Route::post('/login-comercial', 'Auth\LoginController@loginUserComercial')->name('logincomercial');
Route::post('/login-global', 'Auth\LoginController@loginUserGlobal')->name('loginglobal');
