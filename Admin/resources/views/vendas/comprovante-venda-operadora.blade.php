<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<style>
		.comprovante{
			font-weight: normal;
			background-color: #fff;
			text-align: center;
		}
		h6{
			margin: 0px 0px 7px 0px;
			font-weight: normal;
			font-size: 11px;
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
	<h6><img class="image adquirente-image" src="{{$sale->ADQUIRENTE_IMAGEM}}"/></h6>
	<h6><img class="image" src="{{$sale->BANDEIRA_IMAGEM}}"/></h6>
	<h6>FORMA DE PAGAMENTO: {{ mb_strtoupper($sale->MODALIDADE, 'UTF-8') ?? ''}}</h6>
	<h6>PRODUTO: {{ mb_strtoupper($sale->PRODUTO, 'UTF-8') ?? ''}}</h6>
	<h6>************************************************************************************</h6>
	<h6>NOME DA EMPRESA: {{ $sale->NOME_EMPRESA }}</h6>
	<h6>CNPJ: {{ $sale->CNPJ }}</h6>
	<h6>************************************************************************************</h6>
	<h6>ESTABELECIMENTO: {{ mb_strtoupper($sale->ESTABELECIMENTO, 'UTF-8') ?? ''}}</h6>
	<h6>CARTÃO: {{ mb_strtoupper($sale->CARTAO, 'UTF-8') ?? ''}}</h6>
	<h6>NSU: {{ mb_strtoupper($sale->NSU, 'UTF-8') ?? ''}}</h6>
	<h6>AUT: {{ mb_strtoupper($sale->AUTORIZACAO, 'UTF-8') ?? ''}}</h6>
	<h6>DATA DA VENDA: {{ date("d/m/Y", strtotime($sale->DATA_PREVISAO)) }} {{ mb_strtoupper($sale->HORA_TRANSACAO, 'UTF-8') ?? ''}}</h6> 
	<h6>PREVISÃO DE PAGAMENTO: {{ date("d/m/Y", strtotime($sale->DATA_PREVISAO)) }}</h6>
	<h6>PARCELA: {{ mb_strtoupper($sale->PARCELA, 'UTF-8') ?? ''}}</h6>
	<h6 style="font-weight: bold;">VALOR: R$ {{ number_format($sale->VALOR_BRUTO, 2, ",", ".") }}</h6>
</body>
