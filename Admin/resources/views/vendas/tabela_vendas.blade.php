<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Conciflex - Vendas Operadoras </title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

</head>
<body>
  <h6 style="text-align: center"> Previsão de Recebimentos </h6>
  <div style="margin-left: -35px !important">
    <table border="1" style="font-size: 10px;">
      <thead>
        <tr>
          <th> Empresa </th>
          <th> CNPJ </th>
          <th> Operadora</th>
          <th> Dt.Venda </th>
          <th> Dt.Prevista </th>
          <th> Bandeira </th>
          <th> Modalidade </th>
          <th> NSU </th>
          <th> Autorização </th>
          <th> Cartão</th>
          <th> Valor Bruto </th>
          <th> Taxa % </th>
          <th> Taxa R$</th>
          <th> Valor Líquido </th>
          <th> Parcela</th>
          <th> Total Parc. </th>
          <th> Hora</th>
          <th> Estabelecimento </th>
          <th> Banco </th>
          <th> Agência </th>
          <th> Conta </th>
        </tr>
      </thead>
      <tbody>
        @foreach($vendas as $results)
        <tr>
          <td > <?php echo  ucfirst(strtolower($results->EMPRESA)) ?> </td>
          <td> {{ $results->CNPJ }}</td>
          <td>{{ $results->ADQUIRENTE }}</td>
          <?php $newDate = date("d/m/Y", strtotime($results->DATA_VENDA));?>
          <td>{{ $newDate }} </td>
          <?php $newDatePrev= date("d/m/Y", strtotime($results->DATA_PREVISTA_PAGTO));?>
          <td>{{ $newDatePrev }} </td>
          <td>bandeira</td>
          <td>{{ $results->DESCRICAO }}</td>
          <td>{{ $results->NSU }}</td>
          <td>{{ $results->AUTORIZACAO }}</td>
          <td>{{ $results->CARTAO }}</td>
          <td>{{ $results->VALOR_BRUTO }}</td>
          <td>{{ $results->PERCENTUAL_TAXA }}</td>
          <td >{{ $results->VALOR_TAXA }}</td>
          <td>{{ $results->VALOR_LIQUIDO }}</td>
          <td>{{ $results->PARCELA }}</td>
          <td>{{ $results->TOTAL_PARCELAS }}</td>
          <td> {{ $results->HORA_TRANSACAO }} </td>
          <td>{{ $results->ESTABELECIMENTO }}</td>
          <td>{{ $results->BANCO }}</td>
          <td>{{ $results->AGENCIA }}</td>
          <td>{{ $results->CONTA }}</td>
        </tr>
        @endforeach
      </tbody>
      </table>
  </div>

</body>
</html>
