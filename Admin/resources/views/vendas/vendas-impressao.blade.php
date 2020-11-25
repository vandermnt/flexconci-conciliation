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

</head>
<body bgcolor ="#F3F781">
  <h4 align="center" id="titulo"> {{$venda->EMPRESA}} </h4>
  <h6 align="center" style="margin-top: -15px"> CNPJ: {{ $venda->CNPJ}}</h6>
  <h6 style="margin-top: -15px">----------------------------------------------------------------------------------------</h6>
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

    <h6 style="margin-top: -15px;"></h6>
  </div>
</body>
