@extends('layouts.analytics-master')

@section('headerStyle')
<link href="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/css/vendas/venda-operadora.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/css/globals/global.css')}}" rel="stylesheet" type="text/css" />
@stop

@section('content')

<!-- <div id="preloader" class="loader"></div> -->

<div id="tudo_page" class="container-fluid">
  <div class="row">
    <div class="col-sm-12">

      @component('common-components.breadcrumb')
      @slot('title') Desconciliação Automática @endslot
      @slot('item1') Administrativo @endslot
      @endcomponent

    </div>
  </div>
  <form action="{{ action('AdmDesconciliacaoController@desconciliar')}}" method="post">
    <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
    <input type ="hidden" name="nome_responsavel" value="{{ Auth::user()->NOME }}">

    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body" >
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <div class="row">
                    <div class="col-sm-6">
                      <h6> Data Inicial: </h6>
                      <input class="form-control" type="date" value="{{  date("Y-m-01")}}" name="data_inicial" max="3000-12-31">
                    </div>
                    <div class="col-sm-6">
                      <h6> Data Final: </h6>
                      <input class="form-control" type="date" value="{{ date("Y-m-d") }}" name="data_final">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row containers-input">
              <div class="col-sm-6">
                <div class="form-group">
                  <div class="row">
                    <div class="col-sm-12">
                      <h6> Empresa: </h6>
                      <select class="form-control" name="empresa">
                        @foreach($clientes as $cliente)
                        <option value="{{ $cliente->CODIGO }}"> {{ $cliente->NOME_FANTASIA }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- <div class="col-sm-2">
            <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#staticBackdrop">
            <b>Selecionar</b>
          </button>
        </div> -->

        <div class="row col-sm-6 containers-input">
          <div id="filtroOperadora">
            <div class="form-group">
              <div class="row">
                <div class="col-sm-12">
                  <h6> Operadora: </h6>
                  <select class="form-control" name="operadora">
                    @foreach($operadoras as $operadora)
                    <option value="{{ $operadora->CODIGO }}"> {{ $operadora->ADQUIRENTE }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- <div class="col-sm-2">
        <button type="button" class="btn btn-sm bt-pesquisa" data-toggle="modal" data-target="#staticBackdropTipoConciliacao">
        <b>Selecionar</b>
      </button>
    </div> -->

    <div class="modal-footer" style="border-top: none !important">
      <button type="button" class="btn button no-hover mr-1 submit-form" data-dismiss="modal"><b><i class="fas fa-handshake-slash"></i> Desconciliar</b></button>
    </div>
  </div>
</form>
</div>
<div id="resultadosPesquisa">
  <div class="row">
    <div class="col-md-6 col-lg-3">
      <div class="card report-card">
        <div class="card-body">
          <h4> RESPONSÁVEL</h4>
          <div class="d-flex align-items-center justify-content-between">
          <p class="responsavel"> Vanderson Mantovani </p>
          <img src="http://localhost:8000/assets/images/widgets/user.svg" alt="Valor Taxa">
        </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="card report-card">
        <div class="card-body">
          <h4> DATA / HORA</h4>
          <div class="d-flex align-items-center justify-content-between">
            <p class="data-hora"> 15/10/2020 às 13:25:27</p>
          <img src="http://localhost:8000/assets/images/widgets/periodo.svg" alt="Valor Taxa">
        </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="card report-card">
        <div class="card-body">
          <h4>PERÍODO</h4>
          <div class="d-flex align-items-center justify-content-between">
            <p class="periodo"> 10/10/2020 à 12/12/2020 </p>
          <img src="http://localhost:8000/assets/images/financeiro/taxa-adm.svg" alt="Valor Taxa">
        </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="card report-card">
        <div class="card-body">
          <h4>QTDE DESCONCILIAÇÕES</h4>
          <div class="d-flex align-items-center justify-content-between">
            <p class="count-desconciliacao"> 15000 </p>
          <img src="http://localhost:8000/assets/images/financeiro/despesas.svg" alt="Valor Taxa">
        </div>
        </div>
      </div>
    </div>
  </div>
  <br>

</div>
</div>

@section('footerScript')
<!-- Required datatable js -->
<script src="{{ URL::asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<!-- Responsive examples -->
<script src="{{ URL::asset('plugins/datatables/dataTables.responsive.min.js')}}"></script>
<script src="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ URL::asset('assets/pages/jquery.datatable.init.js')}}"></script>

@stop

<script>

let filtros_formulario_principal = {};

const form_submit = document.querySelector(".submit-form");
const form = document.querySelector("form");

form_submit.addEventListener("click", (e) => montarPayload());

function montarPayload() {
  const payload = {
    data_inicial: document.querySelector("input[name='data_inicial']").value,
    data_final: document.querySelector("input[name='data_final']").value,
    empresa: document.querySelector("select[name='empresa']").value,
    operadora: document.querySelector("select[name='operadora']").value
  }

  desconciliar(payload);
}

function desconciliar(payload) {
  fetch("/adm/desconciliar", {
    method: "PUT",
    headers: new Headers({
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    }),
    body: JSON.stringify(payload)
  })
  .then((data) => {
    data.json().then((response) => {
      atualizaBoxes(response);
      document.querySelector("#resultadosPesquisa").style.display = "block";
    })
  })
  .catch((error) => {
    console.error(`${error.status} - Erro: ${error}`);
    alert("Erro ao fazer a desconciliação!");
  })
}

function formataData(data){
  const new_data_ = new Date(data);
  const data_formatada = new_data_.toLocaleDateString('pt-BR', {timeZone: 'UTC'});
  return data_formatada;
}

function atualizaBoxes(response){
  const horas = new Date();
  const data = formataData(new Date());
  const data_inicial =  formataData(document.querySelector("input[name='data_inicial']").value);
  const data_final =  formataData(document.querySelector("input[name='data_final']").value);
  const responsavel = document.querySelector("input[name='nome_responsavel']").value;
  const hora = horas.getHours() > 9 ? horas.getHours() : `0${horas.getHours()}`;
  const minutos = horas.getMinutes() > 9 ? horas.getMinutes() : `0${horas.getMinutes()}`

  document.querySelector(".responsavel").innerHTML = responsavel;
  document.querySelector(".data-hora").innerHTML = `${data} às ${hora}:${minutos}`;
  document.querySelector(".periodo").innerHTML = `${data_inicial} à ${data_final}`;
  document.querySelector(".count-desconciliacao").innerHTML = response.total_desconciliacao;

  window.scrollTo(0, 1000);
}

</script>

@stop
