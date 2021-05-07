<?php

namespace App\Filters;

use App\ExtratoBancarioModel;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Filters\BaseFilter;
use App\PagamentoOperadoraModel;

class ExtratoBancarioFilter extends BaseFilter
{
	protected $query = null;
	protected $whiteList = [
		'cliente_id',
		'data_pagamento',
		'conta',
		'agencia',
		'adquirente',
		'banco',
		'numero_conta'
	];

	public static function filter($params)
	{
		$extratoBancarioFilter = app(ExtratoBancarioFilter::class);
		return $extratoBancarioFilter->apply($params);
	}

	public function apply($params)
	{
		$params = Arr::only($params, $this->getAllowedKeys());
		$params = Arr::where($params, function ($value, $key) {
			return boolval($value);
		});
		$filters = Arr::except($params, 'sort');
		$sort = collect(Arr::only($params, 'sort'))->get('sort');

		$this->query = ExtratoBancarioModel::select([
			'dados_arquivo_conciliacao_bancaria.CODIGO as ID',
			'dados_arquivo_conciliacao_bancaria.DTPOSTED as DATA',
			'dados_arquivo_conciliacao_bancaria.MEMO as DESCRICAO',
			'dados_arquivo_conciliacao_bancaria.TRNAMT as VALOR',
		]);

		if (Arr::has($filters, 'data_pagamento')) {
			$this->query->where('dados_arquivo_conciliacao_bancaria.DTPOSTED', $filters['data_pagamento']);
		}

		if (Arr::has($filters, 'numero_conta')) {
			$this->query->where('dados_arquivo_conciliacao_bancaria.NUMERO_CONTA', 'like', '' . $filters['numero_conta'] . '%');
		}

		$this->buildOrderClause($sort);

		return $this;
	}

	public function getQuery()
	{
		return $this->query;
	}
}
