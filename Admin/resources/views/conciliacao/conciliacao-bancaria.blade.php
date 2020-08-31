@extends('layouts.analytics-master')

@section('title', 'Metrica - Admin & Dashboard Template')

@section('headerStyle')
<link href="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/table-dragger@1.0.3/dist/table-dragger.js"></script>

@stop

@section('content')

<div id="tudo_page" class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      @component('common-components.breadcrumb')
      @slot('title') Conciliação Bancária @endslot
      @slot('item1') Conciliação @endslot
      @endcomponent
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <form enctype="multipart/form-data" method="post">
              <div class="col-lg-12">
                <div class="form-group">
                  <label for="exampleInputPassword1">Upload Extratos Bancários</label>
                  <div class="input-group">
                  <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
                  <div class="custom-file">
                    <input type="file" name="extratos[]" class="custom-file-input" id="files" multiple>
                    <label class="custom-file-label" for="inputGroupFile04">Selecione os arquivos</label>
                  </div>
                  <div class="input-group-append">
                    <button class="btn btn" id="submitExtratos" onclick="enviarExtratos()" type="button">Enviar</button>
                  </div>
                </div><br>
                <label id="resultado"></label>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div><!--end card-body-->

@section('footerScript')

<script>

document.getElementById('files').addEventListener('change', handleFileSelect, false);

function handleFileSelect() {
  var div = document.getElementById("resultado");

  arquivos = $("#files").prop("files");
  var nomes = $.map(arquivos, function(val) { return val.name; });
  for(x=0;x<nomes.length;x++){
    var extensao = nomes[x].split('.').pop().toLowerCase();
    var nome = nomes[x].substring(nomes[x].lastIndexOf("/"),nomes[x].length);
    div.innerHTML = div.innerHTML + nome + ", ";
  }
}

function enviarExtratos(){
  var extratos = document.getElementsByName("extratos");

  var form = $('form')[0];
  var formData = new FormData(form);

  $.ajax({
    url: "{{ url('conciliacao-bancaria') }}",
    type: "post",
    header:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: formData,
    processData: false,
    contentType: false,
    success: function (response){
      alert("Extratos enviados com sucesso!");
    }
  })
}

</script>
@stop
@stop
