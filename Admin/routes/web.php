<?php

use Illuminate\Support\Facades\Route;

Auth::routes();
if(version_compare(PHP_VERSION, '7.2.0', '>=')) { error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING); }



Route::group(['middleware' => 'auth'], function() {

  //DAHSBOARD
  Route::get('/', 'DashboardController@dashboard');
  Route::get('/detalhe-calendario/{data}', 'DashboardController@detalheCalendario');
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
  Route::get('/vendasoperadoras', 'VendasController@vendas');
  Route::match(['get', 'post'], '/vendasoperadorasfiltro', 'VendasController@buscarVendasFiltro');
  Route::get('/download', 'VendasController@downloadTable');
  Route::get('/desfazer-justificativa/{codigo}', 'VendasController@desfazerJustificativa');


  //CONCILIAÇÃO AUTOMÁTICA
  Route::get('/conciliacao-automatica', 'ConciliacaoAutomaticaVendasController@conciliacaoAutomatica');
  Route::match(['get', 'post'], '/conciliacao-manual', 'ConciliacaoAutomaticaVendasController@conciliarManualmente');
  Route::match(['get', 'post'], '/conciliacao-justificada-venda', 'ConciliacaoAutomaticaVendasController@conciliacaoJustificadaVenda');
  Route::match(['get', 'post'], '/conciliacao-justificada-vendaerp', 'ConciliacaoAutomaticaVendasController@conciliacaoJustificadaVendaErp');
  Route::match(['get', 'post'], '/conciliar', 'ConciliacaoAutomaticaVendasController@saveConciliacao');

  //VENDAS - RECEBIMENTOS OPERADORAS
  Route::get('/recebimentos-operadora', 'RecebimentosOperadoraController@recebimentosOperadora');
  Route::match(['get', 'post'], '/consultar-recebimentos-operadora', 'RecebimentosOperadoraController@consultarRecebimentosOperadoras');
  Route::get('/download-vendas-operadora', 'RecebimentosOperadoraController@downloadTable');


  //VENDAS SISTEMA ERP
  Route::get('/vendas-sistema-erp', 'VendasErpController@vendaserp');
  Route::match(['get', 'post'], '/vendaserpfiltro', 'VendasErpController@buscarVendasErp');
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
  Route::get('/delete-historico-bancario/{codigo}', 'CadastroHistoricoBancarioController@deleteHistoricoBancario');

  //CADASTRO JUSTIFICATIVA
  Route::get('/justificativas', 'CadastroJustificativaController@justificativas');
  Route::post('/post-justificativa', 'CadastroJustificativaController@saveJustificativa');
  Route::get('/load-justificativas', 'CadastroJustificativaController@loadJustificativas');
  Route::get('/delete-justificativa/{codigo}', 'CadastroJustificativaController@deleteJustificativa');

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

Route::get('/credenciamento-cielo', function() {
    return view('authentication.auth-lock-screen');
});

Route::get('/credenciamento-stone', function() {
    return view('authentication.auth-lock-stone');
});

Route::get('/teste', function() {
    return view('acess-stone');
});

Route::get('/credeciamento', function() {
    return view('authentication.retorno-credenciamento');
});

Route::post('/credenciamento-edi', 'AutorizacaoAcessoController@credenciarEdi')->name("edi");

Route::post('/login', 'Auth\LoginController@postLogin')->name('loginlogin');
Route::post('/login-comercial', 'Auth\LoginController@loginUserComercial')->name('logincomercial');
Route::post('/login-global', 'Auth\LoginController@loginUserGlobal')->name('loginglobal');
