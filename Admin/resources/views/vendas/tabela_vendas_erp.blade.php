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
          <th> DATA VENDA  </th>
          <th> PREVIS. PGT  </th>
          <th> NSU  </th>
          <th> TOTAL VENDA </th>
          <th> Nº PARCELA </th>
          <th> TOTAL PARCELA </th>
          <th> LIQ. PARCELA </th>
          <th> DESCRIÇÃO ERP </th>
          <th> COD. AUTORIZAÇÃO </th>
          <th> ID. VENDA CLIENTE  </th>
        </tr>
      </thead>
      <tbody>
        <!-- @foreach($vendas as $results)
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
        </tr>
        @endforeach -->
      </tbody>
      </table>
  </div>
</body>
</html>
