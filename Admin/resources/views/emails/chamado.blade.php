
<p> {{ $mensagem }} </p>

<h5> Cliente: {{ Session::get('nome_fantasia') }}</h5>
<h5> Nome: <?php  echo Auth::user()->NOME ?> </h5>
<h5> E-mail: <?php  echo Auth::user()->USUARIO ?></h5>
<h5> ERP: {{ $cod_erp }}</h5>
