<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Conciflex - Vendas Operadoras </title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
</head>
<body>
  <h6 style="text-align: center"> Recebimentos Operadora </h6>
  <div>
    <table border="1" style="font-size: 10px; margin: auto; text-align: center">
      <thead>
        <tr>
          <th> Empresa  </th>
          <th> ID. Loja  </th>
          <th> Adquirente  </th>
          <th> Bandeira </th>
          <th> NSU </th>
          <th> Data Transação </th>
          <th> Hora Transação </th>
          <th> Valor Bruto   </th>
          <th> Valor Líquido </th>
          <th> Banco  </th>
          <th> Agência  </th>
          <th> Conta  </th>
        </tr>
      </thead>
      <tbody>
        @foreach($vendas as $results)
        <tr>
          <td > <?php echo  ucfirst(strtolower($results->NOME_EMPRESA)) ?> </td>
          <td> {{ $results->ID_LOJA }}</td>
          <td>{{ $results->ADQUIRENTE }}</td>
          <td>{{ $results->BANDEIRA }}</td>
          <td>{{ $results->NSU }}</td>
          <?php $newDate = date("d/m/Y", strtotime($results->DATA_VENDA));?>
          <td>{{ $newDate }} </td>
          <td>{{ $results->HORA_TRANSACAO }}</td>
          <td>{{ $results->VALOR_BRUTO }}</td>
          <td>{{ $results->VALOR_LIQUIDO }}</td>
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
