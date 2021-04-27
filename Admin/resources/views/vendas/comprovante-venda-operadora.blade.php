<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<style>
		.comprovante{
			background-color: #fff;
			text-align: center;
		}
		h6{
			margin: 0px 0px 7px 0px;
		}
		.image{
			width: auto;
			width: 72px;
		}
		.adquirente-image{
			width: auto;
			width: 108px;
		}
	</style>

  <script>
    window.print();
  </script>
</head>
<body class="comprovante">
  {{-- <h4 align="center">{{ $sale->NOME_EMPRESA }}</h4>
  <h6 style="margin-top: -15px;" align="center">CNPJ: {{ $sale->CNPJ }}</h6>
  <h6 style="margin-top: -15px;">----------------------------------------------------------------------------------------</h6> --}}
	{{-- <h6>DATA - HORA: {{ date('d/m/Y', strtotime($sale->DATA_VENDA)) }} - {{ $sale->HORA_TRANSACAO }}</h6> --}}
	<h6><img class="image adquirente-image" src="{{$sale->ADQUIRENTE_IMAGEM}}"/></h6>
	<h6><img class="image" src="{{$sale->BANDEIRA_IMAGEM}}"/></h6>
	<h6>FORMA DE PAGAMENTO: {{ mb_strtoupper($sale->MODALIDADE, 'UTF-8') ?? ''}}</h6>
	<h6>PRODUTO: {{ mb_strtoupper($sale->PRODUTO, 'UTF-8') ?? ''}}</h6>
	{{-- <h6>ESTABELECIMENTO: {{ mb_strtoupper($sale->ESTABELECIMENTO, 'UTF-8') ?? ''}}</h6>
	<h6>CARTÃO: {{ mb_strtoupper($sale->CARTAO, 'UTF-8') ?? ''}}</h6>
	<h6 style="font-weight: bold;">VALOR: R$ {{ number_format($sale->VALOR_BRUTO, 2, ",", ".") }}</h6>
	<h6>PREVISÃO DE PAGAMENTO: {{ date("d/m/Y", strtotime($sale->DATA_PREVISAO)) }}</h6> --}}
</body>
