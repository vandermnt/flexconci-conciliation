<?php

namespace App\View\Components\Tables;

use App\View\Components\Tables\Table;
use Illuminate\View\Component;

class TabelaConciliacaoBancaria extends Table
{
	/**
	 * Create a new component instance.
	 *
	 * @return void
	 */
	public function __construct($hiddenColumns = [], $headers = [])
	{
		parent::__construct($hiddenColumns, $headers);
	}

	/**
	 * Get the view / contents that represent the component.
	 *
	 * @return \Illuminate\View\View|string
	 */
	public function render()
	{
		return view('components.tables.tabela-conciliacao-bancaria');
	}
}
