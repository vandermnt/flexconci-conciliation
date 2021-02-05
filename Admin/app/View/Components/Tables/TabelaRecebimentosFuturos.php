<?php

namespace App\View\Components\Tables;

use App\View\Components\Tables\Table;
use Illuminate\View\Component;

class TabelaRecebimentosFuturos extends Table
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct($hiddenColumns);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.tables.tabela-recebimentos-futuros');
    }
}
