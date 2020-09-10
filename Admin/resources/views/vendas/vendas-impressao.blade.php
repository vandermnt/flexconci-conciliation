<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <script>
  window.print();
</script>

<style type="text/css">
html { margin: 0 }
</style>

<!-- <script>
$(window).on("load", function () {
var codigo = "{{ Request::segment(2) }}"
$.ajax({
url: "{{ url('dados-cliente') }}",
type: "post",
header:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
data: ({_token: '{{csrf_token()}}' ,codigo}),
dataType: 'json',
success: function (response){
console.log("dopwakodpak");
// document.getElementById("titulo").innerHTML = response
}
})
})
</script> -->


</head>
<body bgcolor ="#F3F781">

  <?php
  //    $texto = 'TEXTO PARA IMPRIMIR'; // texto que será impresso
  //
  // if ( $handle = printer_open() ){ // impressora configurada no windows
  // printer_set_option($handle, PRINTER_MODE, "RAW");
  // printer_write($handle, $texto );
  // printer_close($handle); }
  ?>
  <h4 align="center" id="titulo"> {{$venda->EMPRESA}} </h4>
  <h6 align="center" style="margin-top: -15px"> CNPJ: {{ $venda->CNPJ}}</h6>
  <h6 style="margin-top: -15px">----------------------------------------------------------------------------------------</h6>
  <!-- <h6 align="center" style="margin-top: -15px"> NÃO É DOCUMENTO FISCAL </h6> -->
  <!-- <h6 style="margin-top: -15px"></h6>-->
  <?php $newDate = date("d/m/Y", strtotime($venda->DATA_VENDA));?>

  <div style="text-align: center">
    <h6 style="margin-top: -15px;"> DATA - HORA: {{ $newDate }} - {{ $venda->HORA_TRANSACAO }} </h6>
    <h6 style="margin-top: -15px;"> OPERADORA: {{ $venda->ADQUIRENTE }} </h6>
    <h6 style="margin-top: -15px;"> BANDEIRA: {{ $venda->BANDEIRA }} </h6>
    <h6 style="margin-top: -15px;"> FORMA DE PAGAMENTO: {{ $venda->DESCRICAO }} </h6>
    <h6 style="margin-top: -15px;"> ESTABELECIMENTO: {{ $venda->ESTABELECIMENTO }} </h6>
    <h6 style="margin-top: -15px;"> CARTÃO: {{ $venda->CARTAO }} </h6>
    <h6 style="margin-top: -15px; font-weight: bold"> <b>VALOR: R$ <?php echo number_format($venda->VALOR_BRUTO,2,",","."); ?></b> </h6>
    <h6 style="margin-top: -15px;"> PREVISÃO DE PAGAMENTO:   <?php echo date("d/m/Y", strtotime($venda->DATA_PREVISTA_PAGTO));?>
 </h6>

<!--





    <h6 style="margin-top: -15px;"> VALOR: R$  <?php echo number_format($venda->VALOR_BRUTO,2,",","."); ?> </h6>
    <h6 style="margin-top: -15px;"> Nº PARCELAS: {{ $venda->PARCELA }} </h6>
    <h6 style="margin-top: -15px;"> TOTAL PARCELAS: {{ $venda->TOTAL_PARCELAS }} </h6>
    <h6 style="margin-top: -15px;"> PRODUTO: {{ $venda->PRODUTO_WEB }} </h6> -->
    <h6 style="margin-top: -15px;"></h6>
  </div>
</body>
