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
    html {
      margin: 0;
    }
  </style>
</head>
<body bgcolor="#F3F781">
  <h4 align="center">{{ $sale->NOME_EMPRESA }}</h4>
  <h6 style="margin-top: -15px;" align="center">CNPJ: {{ $sale->CNPJ }}</h6>
  <h6 style="margin-top: -15px;">----------------------------------------------------------------------------------------</h6>
  <div style="text-align: center;">
    <h6 style="margin-top: -15px;">DATA - HORA: {{ date('d/m/Y', strtotime($sale->DATA_VENDA)) }} - {{ $sale->HORA_TRANSACAO }}</h6>
    <h6 style="margin-top: -15px;">OPERADORA: {{ mb_strtoupper($sale->ADQUIRENTE, 'UTF-8') ?? 'SEM IDENTIFICAÇÃO' }}</h6>
    <h6 style="margin-top: -15px;">BANDEIRA: {{ mb_strtoupper($sale->BANDEIRA, 'UTF-8') ?? 'SEM IDENTIFICAÇÃO' }}</h6>
    <h6 style="margin-top: -15px;">FORMA DE PAGAMENTO: {{ mb_strtoupper($sale->MODALIDADE, 'UTF-8') ?? ''}}</h6>
    <h6 style="margin-top: -15px;">ESTABELECIMENTO: {{ mb_strtoupper($sale->ESTABELECIMENTO, 'UTF-8') ?? ''}}</h6>
    <h6 style="margin-top: -15px;">CARTÃO: {{ mb_strtoupper($sale->CARTAO, 'UTF-8') ?? ''}}</h6>
    <h6 style="margin-top: -15px; font-weight: bold;">VALOR: R$ {{ number_format($sale->VALOR_BRUTO, 2, ",", ".") }}</h6>
    <h6 style="margin-top: -15px;">PREVISÃO DE PAGAMENTO: {{ date("d/m/Y", strtotime($sale->DATA_PREVISAO)) }}</h6>
  </div>
</body>
