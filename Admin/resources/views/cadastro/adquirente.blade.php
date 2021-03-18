@extends('layouts.analytics-master')

@section('headerStyle')
<link href="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/table-dragger@1.0.3/dist/table-dragger.js"></script>
<link href="{{ URL::asset('assets/css/globals/global.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/css/cadastro/cadastros.css')}}" rel="stylesheet" type="text/css" />
@stop

@section('content')

<div id="tudo_page" class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      @component('common-components.breadcrumb')
      @slot('title') Operadoras @endslot
      @slot('item1') Cadastro @endslot
      @endcomponent
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body" >
          <div class="form-group">
            <div class="col-sm-12 form">
              <h6> Operadora: </h6>
              <div class="row form-group">
                <div class="col-sm-6">
                  <input type="hidden" value="{{$adquirentes}}" name="adquirentes-carregados">
                  <input type="textarea" class="form-control" placeholder="Pequise a operadora" name="adquirentes-pesquisados">
                </div>
                <div class="col-sm-2">
                  <button class="btn btn-sm bt-cadastro-ad form-button"> <i class="fas fa-plus"></i> <b>Nova operadora</b>  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-12">
            <div id="btfiltro" style="display:block; text-align: right;">
              <!-- <button style="align-items: right; background: white; color: #2D5275; border-color: #2D5275" class="btn btn-sm limpa-filtro"> <i class="far fa-trash-alt"></i> <b>Limpar Campos</b>  </button> -->
            </div>
          </div>
        </div>

        <div class="col-sm-12 table-description d-flex align-items-center ">
          <h4 id="qtd-registros">Total de operadoras ({{ $count_adquirentes }} registros)</h4>
          <img src="assets/images/widgets/arrow-down.svg" alt="Adquirentes">
        </div>
        <br>
        <div class="tabela">
          <table id="tabela-adquirentes" class="table">
            <thead>
              <tr style="background: #2D5275; ">
                <th> CÓDIGO </th>
                <th> OPERADORA </th>
                <th> HOMOLOGADO </th>
                <th> AÇÕES </th>
              </tr>
            </thead>
            <tbody id="conteudo_tabe">
              @foreach($adquirentes as $adquirente)
              <tr id="{{ $adquirente->CODIGO }}">
                <td> {{ $adquirente->CODIGO }}</td>
                <td> {{ $adquirente->ADQUIRENTE }}</td>
                <td> @if($adquirente->HOMOLOGADO)
                  <i style="color: green" class="fas fa-check"></i>
                  @else
                  <i  style="color: red" class="fas fa-times"></i>
                  @endif
                </td>
                <td class="excluir">
                  <a href="#" onclick="editarAdquirente(event, {{$adquirente}})"><i class='far fa-edit'></i></a>
                  <a href="#" onclick="excluirAdquirente(event,{{ $adquirente->CODIGO}})"><i style="margin-left: 12px"class="far fa-trash-alt"></i></a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="modalCadastroAdquirente" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: #2D5275">
          <h5 class="modal-title" style="color: white">Cadastro Operadora</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="alert alert-success success-save-ad" role="alert">
          <strong><i class="fas fa-check-circle"></i> Operadora cadastrada com sucesso.</strong>
        </div>
        <div class="modal-body">
          <div class="col-sm-12 form">
            <h6> Operadora: </h6>
            <div class="row form-group">
              <div class="col-sm-12">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="textarea" class="form-control" name="adquirente">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Cancelar</b></button>
          <button type="button" class="btn btn-success bt-salva-ad"><b>Cadastrar</b></button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="modalExcluirAdquirente" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: #2D5275">
          <h5 class="modal-title" style="color: white">Exclusão Operadora</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4>Deseja excluir essa operadora?</h4>
        </div>
        <div class="modal-footer">
          <button id="sim" type="button" class="btn btn-success" data-dismiss="modal">Sim</button>
          <button id="nao" type="button" class="btn btn-primary" data-dismiss="modal">Não</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="modalEditarAdquirente" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: #2D5275">
          <h5 class="modal-title" style="color: white">Editar Operadora</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="alert alert-success success-update-ad" role="alert">
          <strong><i class="fas fa-check-circle"></i> Operadora alterada com sucesso.</strong>
        </div>
        <div class="modal-body">
          <div class="col-sm-12 form">
            <h6> Operadora: </h6>
            <div class="row form-group">
              <div class="col-sm-12">
                <input type="textarea" class="form-control" name="editar-adquirente">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Cancelar</b></button>
          <button type="button" class="btn btn-success bt-salva-edicao-ad"><b>Salvar</b></button>
        </div>
      </div>
    </div>
  </div>
</div>
@section('footerScript')
<script src="{{ URL::asset('plugins/datatables/dataTables.responsive.min.js')}}"></script>
<script src="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ URL::asset('assets/pages/jquery.datatable.init.js')}}"></script>
<script src="{{ URL::asset('assets/js/cadastro/adquirentes.js')}}"></script>
@stop

@stop
