@extends('layouts.analytics-master')

@section('headerStyle')
<link href="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/table-dragger@1.0.3/dist/table-dragger.js"></script>
<link href="{{ URL::asset('assets/css/cadastro/cadastros.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/css/globals/global.css')}}" rel="stylesheet" type="text/css" />
@stop

@section('content')
<div id="tudo_page" class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      @component('common-components.breadcrumb')
      @slot('title') Taxas @endslot
      @slot('item1') Cadastro @endslot
      @endcomponent
    </div>
  </div>
  <div class="card">
    <div class="card-body" >
      <div class="form-group">
        <div class="col-sm-12 form">
          <h6> Empresas: </h6>
          <div class="row form-group">
            <div class="col-sm-6">
              <input type="hidden" name="cliente_op" value="{{ $clientes }}">

              <select class="form-control" name="clientes">
                <option value="" selected> -- Selecione uma Empresa -- </option>
                @foreach($clientes as $cliente)
                  <option value="{{ $cliente->CODIGO }}"> {{ $cliente->NOME_FANTASIA }} - {{ $cliente->CODIGO }} </option>
                @endforeach
              </select>
              <!-- <input type="textarea" class="form-control" name="bancos-pesquisados"> -->
            </div>
            <!-- <div class="col-sm-3">
              <button class="btn btn-sm bt-cadastro-ad form-button"> <b>Selecionar</b>  </button>
            </div> -->
          </div>
        </div>
      </div>
      <div class="col-sm-12 form">
        <h6> Operadora / Estabelecimento: </h6>
        <div class="row form-group">
          <div class="col-sm-6">
            <input type="hidden" value="{{$clientes_operadora}}" name="clientes-operadora">
            <select class="form-control" name="operadora-estabelecimento">


            </select>
            <!-- <input type="textarea" class="form-control" name="bancos-pesquisados"> -->
          </div>
          <!-- <div class="col-sm-3">
            <button class="btn btn-sm bt-cadastro-ad form-button"> <b>Selecionar</b>  </button>
          </div> -->
        </div>
      </div>
      <div class="modal-footer" style="border-top: none !important">
        <button type="button" class="btn button no-hover mr-1 submit-form" data-dismiss="modal"><b><i class="fas fa-plus"></i> Cadastrar</b></button>
        <button type="button" class="btn button no-hover mr-1 search"><b><i class="fas fa-search"></i> Pesquisar</b></button>
      </div>
    </div>

    <div class="col-sm-12 table-description d-flex align-items-center ">
      <h4 id="qtd-registros"></h4>
      <!-- <img src="assets/images/widgets/arrow-down.svg" alt="Taxas"> -->
    </div>
    <br>
    <div class="tabela">
      <table id="tabela-adquirentes" class="table">
        <thead>
          <tr style="background: #2D5275; ">
            <th> CÓDIGO </th>
            <th> CLIENTE </th>
            <th> TAXA </th>
            <th> BANDEIRA </th>
            <th> MODALIDADE </th>
            <th> DATA VIGÊNCIA </th>
            <th> AÇÕES </th>
          </tr>
        </thead>
        <tbody id="conteudo_tabe">
          <!-- @foreach($taxas as $taxa)
          <tr id="{{ $taxa->CODIGO }}">
            <td> {{ $taxa->CODIGO }}</td>
            <td> {{ $taxa->NOME_FANTASIA }}</td>
            <td> {{ $taxa->TAXA }}</td>
            <td> {{ $taxa->ADQUIRENTE }}</td>
            <td> {{ $taxa->BANDEIRA }}</td>
            <td> {{ $taxa->DESCRICAO }}</td>
            <td> <?php echo date("d-m-Y", strtotime($taxa->DATA_VIGENCIA)) ?></td>
            <td class="excluir">
              <a href="#" onclick="editarTaxa(event, {{ $taxa }})"><i class='far fa-edit'></i></a>
              <a href="#" onclick="excluirTaxa(event,{{ $taxa->CODIGO}})"><i style="margin-left: 12px"class="far fa-trash-alt"></i></a>
            </td>
          </tr>
          @endforeach -->
        </tbody>
      </table>
    </div>
  </div>

  <div class="modal" id="modalCadastroTaxa" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: #2D5275">
          <h5 class="modal-title" style="color: white">Cadastro Taxa</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="alert alert-success success-save-tx" role="alert">
          <strong><i class="fas fa-check-circle"></i> Taxa cadastrada com sucesso.</strong>
        </div>
        <div class="modal-body">
          <div class="col-sm-12 form">
            <h6> Taxa: </h6>
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <input type="textarea" class="form-control" name="taxa" onKeyPress="return(mascaraTaxa(this,'.',',',event))">
            <h6>Data Vigência:</h6>
            <input type="date" name="data_vigencia" class="form-control">
            <h6>Cliente: </h6>
            <select class="form-control" name="cliente">
              @foreach($clientes as $cliente)
              <option value="{{ $cliente->CODIGO }}"> {{ $cliente->NOME_FANTASIA }}</option>
              @endforeach
            </select>
            <h6>Bandeira: </h6>
            <select class="form-control" name="bandeira">
              @foreach($bandeiras as $bandeira)
              <option value="{{ $bandeira->CODIGO }}"> {{ $bandeira->BANDEIRA }}</option>
              @endforeach
            </select>
            <h6>Operadora: </h6>
            <select class="form-control" name="operadora">
              @foreach($operadoras as $operadora)
              <option value="{{ $operadora->CODIGO }}"> {{ $operadora->ADQUIRENTE }}</option>
              @endforeach
            </select>
            <h6>Forma de Pagamento: </h6>
            <select class="form-control" name="forma_pagamento">
              @foreach($formas_pagamento as $forma_pagamento)
              <option value="{{ $forma_pagamento->CODIGO }}"> {{ $forma_pagamento->DESCRICAO }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Cancelar</b></button>
          <button type="button" class="btn btn-success bt-salva-tx"><b>Cadastrar</b></button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="modalExcluirTaxa" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: #2D5275">
          <h5 class="modal-title" style="color: white">Exclusão Taxa</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4>Deseja excluir essa taxa?</h4>
        </div>
        <div class="modal-footer">
          <button id="sim" type="button" class="btn btn-success" data-dismiss="modal">Sim</button>
          <button id="nao" type="button" class="btn btn-primary" data-dismiss="modal">Não</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="modalEditarTaxa" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: #2D5275">
          <h5 class="modal-title" style="color: white">Editar Taxa</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="alert alert-success success-update-tx" role="alert">
          <strong><i class="fas fa-check-circle"></i> Taxa alterada com sucesso.</strong>
        </div>
        <div class="modal-body">
          <div class="col-sm-12 form">
            <h6> Taxa: </h6>
            <div class="row form-group">
              <div class="col-sm-12">
                <input type="textarea" class="form-control" name="editar-adquirente">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Cancelar</b></button>
          <button type="button" class="btn btn-success bt-salva-edicao-tx"><b>Salvar</b></button>
        </div>
      </div>
    </div>
  </div>
</div>
@section('footerScript')
<script src="{{ URL::asset('assets/js/cadastro/taxas.js')}}"></script>
@stop

@stop
