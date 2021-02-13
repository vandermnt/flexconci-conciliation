<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Conciflex - Dados do gráfico</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
  <!-- <link href="{{ URL::asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" /> -->
</head>
<body>
  <h5 style="text-align: center"> Vendas por Modalidade </h5><br>
  <div style="margin-left: -35px !important">
    <table id="table_vendas_produto"  class="table" style="font-size: 12px">
      <tr>
        <th style="color: #231F20" >Bandeira
          <th style="color: #231F20" >Quantidade</th>
          <th style="color: #231F20" >Bruto</th>
          <th style="color: #231F20" >Taxa</th>
          <th style="color: #231F20" >Líquido</th>
          <th style="color: #231F20" >Ticket Médio</th>
        </tr>
        <tbody>
          @foreach($dados_vendas as $dado_venda)
          <tr>
            <td> {{ $dado_venda->DESCRICAO }}</td>
            <td> {{ $dado_venda->QUANTIDADE_REAL }}</td>
            <td> <?php $bruto =  number_format($dado_venda->TOTAL_BRUTO, 2, ',', '.'); ?> R$ {{ $bruto }}</td>
            <td style="color: red"> <?php $taxa =  number_format($dado_venda->TOTAL_TAXA, 2, ',', '.'); ?> R$ {{ $taxa }}</td>
            <td> <?php $liquido =  number_format($dado_venda->TOTAL_LIQUIDO, 2, ',', '.'); ?>R$ {{ $liquido }}</td>
            <td> <?php $ticket =  number_format($dado_venda->TICKET_MEDIO, 2, ',', '.'); ?>R$ {{ $ticket }}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot style="font-weight: bolder">
          <td> Totais </td>
          <td> {{ $total['total_qtde'] }} </td>
          <td> R$ <?php echo number_format($total['total_bruto'], 2, ',', '.'); ?></td>
          <td style="color: red"> R$ <?php echo number_format($total['total_taxa'], 2, ',', '.'); ?></td>
          <td> R$ <?php echo number_format($total['total_liquido'], 2, ',', '.'); ?></td>
          <td> R$ <?php echo number_format($total['total_ticket'], 2, ',', '.'); ?></td>
        </tfoot>
      </table>
    </div>
  </body>
  </html>
