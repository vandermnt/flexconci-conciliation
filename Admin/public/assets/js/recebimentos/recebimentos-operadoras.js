const checker = new Checker();
const modalFilter = new ModalFilter();
const formatter = new Formatter({
	locale: 'pt-BR',
	currencyOptions: {
		type: 'BRL',
	},
});
const searchForm = createSearchForm({
	form: '#js-form-pesquisa',
	inputs: ['_token', 'data_inicial', 'data_final'],
	checker,
});
const paymentsContainer = new PaymentsContainerProxy({
	id: 'recebimentos-operadoras',
	links: {
		search: searchForm.get('form').dataset.urlBuscarRecebimentos,
		filter: searchForm.get('form').dataset.urlFiltrarRecebimentos,
	},
});
const tableRender = createTableRender({
	table: '#js-tabela-recebimentos',
	locale: 'pt-br',
	formatter,
});
const boxes = getBoxes();
let boxSubFilter = {};

boxes.forEach((box) => {
	const boxDOM = box.get('element');

	boxDOM.addEventListener('click', (event) => {
		const status = event.target.closest('.box').dataset.status;
		if (status == '*') {
			toggleElementVisibility('#js-loader');
			let tipoLancamento;
			boxDOM.dataset.key == 'TOTAL_DESPESAS'
				? (tipoLancamento = 'Ajuste a Débito')
				: (tipoLancamento = 'Ajuste a Crédito');
			boxSubFilter['TIPO_LANCAMENTO'] = tipoLancamento;
			paymentsContainer.set('active', 'filter');
			buildRequest()
				.get()
				.then(() => {
					tableRender.clearFilters();
				});
			toggleElementVisibility('#js-loader');
		}
	});
});

checker.addGroups([
	{ name: 'empresa', options: { inputName: 'grupos_clientes' } },
	{ name: 'adquirente', options: { inputName: 'adquirentes' } },
	{ name: 'bandeira', options: { inputName: 'bandeiras' } },
	{ name: 'modalidade', options: { inputName: 'modalidades' } },
	{ name: 'estabelecimento', options: { inputName: 'estabelecimentos' } },
	{ name: 'status-conciliacao', options: { inputName: 'status_conciliacao' } },
	{
		name: 'recebimento-conciliado-erp',
		options: { inputName: 'recebimento_conciliado_erp' },
	},
]);

modalFilter.addGroups([
	'empresa',
	'adquirente',
	'bandeira',
	'modalidade',
	'estabelecimento',
]);

searchForm.onSubmit(async (event) => {
	const resultadosDOM = document.querySelector('.resultados');

	await paymentsContainer.search({
		params: {
			por_pagina: paymentsContainer.get('search').get('pagination').options
				.perPage,
		},
		body: { ...searchForm.serialize() },
	});

	tableRender.clearFilters();
	tableRender.clearSortFilter();

	if (resultadosDOM.classList.contains('hidden')) {
		resultadosDOM.classList.remove('hidden');
	}
	window.scrollTo(0, document.querySelector('.resultados').offsetTop);
});

paymentsContainer.setupApi({
	headers: {
		'X-CSRF-TOKEN': searchForm.getInput('_token').value,
		'Content-Type': 'application/json',
	},
});

paymentsContainer.onEvent('beforeFetch', () => {
	toggleElementVisibility('#js-loader');
});

paymentsContainer.onEvent('fetch', (payments) => {
	toggleElementVisibility('#js-loader');
	document.querySelector('#js-quantidade-registros').textContent = `(${
		payments.get('pagination').options.total || 0
	} registros)`;

	tableRender.set('data', {
		body: payments.get('payments') || [],
		footer: payments.get('totals') || {},
	});
	tableRender.render();
	payments.get('pagination').render();
});

paymentsContainer.onEvent('search', (payments) => {
	const totals = payments.get('totals');
	const negativeBoxes = {
		TOTAL_TAXA: (totals.TOTAL_TAXA || 0) * -1,
		TOTAL_VALOR_TAXA_ANTECIPACAO:
			(totals.TOTAL_VALOR_TAXA_ANTECIPACAO || 0) * -1,
		TOTAL_DESPESAS: totals.TOTAL_DESPESAS || 0,
		TOTAL_CHARGEBACK: (totals.TOTAL_CHARGEBACK || 0) * -1,
		TOTAL_CANCELAMENTO: (totals.TOTAL_CHARGEBACK || 0) * -1,
	};
	updateBoxes(boxes, {
		...totals,
		...negativeBoxes,
	});
	boxes.forEach((box) => {
		const boxDOM = box.get('element');
		const value = negativeBoxes[boxDOM.dataset.key];
		if (value < 0) {
			boxDOM.querySelector('.content').classList.add('text-danger');
		} else {
			if (boxDOM.dataset.key != 'TOTAL_DESPESAS')
				boxDOM.querySelector('.content').classList.remove('text-danger');
		}
	});
});

paymentsContainer.onEvent('fail', (err) => {
	document.querySelector('#js-loader').classList.remove('hidden');
	document.querySelector('#js-loader').classList.add('hidden');
});

paymentsContainer.setPaginationConfig(
	{
		paginationContainer: document.querySelector('#js-paginacao-recebimentos'),
	},
	async (page, pagination, event) => {
		await buildRequest({
			page,
			por_pagina: pagination.options.perPage,
		}).get();
	}
);

function buildRequest(params) {
	let requestHandler = () => {};

	const isSearchActive = paymentsContainer.get('active') === 'search';
	const sendRequest = isSearchActive
		? paymentsContainer.search.bind(paymentsContainer)
		: paymentsContainer.filter.bind(paymentsContainer);

	const filters = {
		...searchForm.serialize(),
		...tableRender.serializeSortFilter(),
	};

	const subfilters = {
		...tableRender.serializeTableFilters(),
		...boxSubFilter,
	};
	const bodyPayload = isSearchActive
		? { ...filters }
		: {
				filters: { ...filters },
				subfilters: { ...subfilters },
		  };

	const requestPayload = {
		params: {},
		body: bodyPayload,
	};

	requestHandler = async (params) => {
		requestPayload.params = {
			por_pagina: paymentsContainer.get('search').get('pagination').options
				.perPage,
			...params,
		};

		await sendRequest(requestPayload);
	};

	return {
		requestHandler,
		params,
		get: async function () {
			await this.requestHandler(this.params);
		},
	};
}

tableRender.onFilter(async (filters) => {
	const params = {
		por_pagina: document.querySelector('#js-por-pagina').value,
	};

	paymentsContainer.toggleActiveData('filter');
	if (Object.keys(filters).length === 0) {
		paymentsContainer.toggleActiveData('search');
		params.page = 1;
	}

	await buildRequest(params).get();
});

tableRender.onSort(async (elementDOM, tableInstance) => {
	const params = {
		por_pagina: document.querySelector('#js-por-pagina').value,
	};

	_defaultEvents.table.onSort(elementDOM, tableInstance);
	await buildRequest(params).get();
});

async function onPerPageChanged(event) {
	paymentsContainer
		.get('search')
		.get('pagination')
		.setOptions({ perPage: event.target.value });
	paymentsContainer
		.get('filtered')
		.get('pagination')
		.setOptions({ perPage: event.target.value });
	await buildRequest({
		page: 1,
		por_pagina: event.target.value,
	}).get();
}

function exportar() {
	swal('Aguarde um momento...', 'A sua planilha está sendo gerada.', 'warning');
	setTimeout(() => {
		openUrl(searchForm.get('form').dataset.urlExportar, {
			...searchForm.serialize(),
			...tableRender.serializeTableFilters(),
			...serializeTableSortToExport(tableRender.serializeSortFilter()),
		});
	}, 500);
}

function retornoCsv() {
	swal('Aguarde um momento...', 'A sua planilha está sendo gerada.', 'warning');
	setTimeout(() => {
		openUrl(searchForm.get('form').dataset.urlRetornoCsv, {
			...searchForm.serialize(),
			...tableRender.serializeTableFilters(),
			...serializeTableSortToExport(tableRender.serializeSortFilter()),
		});
	}, 500);
}

function closeRetornoModal() {
	document.querySelector(
		'#js-retorno-recebimento-modal #js-data-inicial'
	).valueAsDate = new Date();
	document.querySelector(
		'#js-retorno-recebimento-modal #js-data-final'
	).valueAsDate = new Date();

	$('#js-retorno-recebimento-modal').modal('hide');
}

function updatePayments(payments, newPayments, idKey = 'ID') {
	const updated = updateData([...payments], [...newPayments], idKey);
	const updatedPayments = updated.data;
	const affectedRows = updated.updated.reduce(
		(ids, value) => [...ids, value.ID],
		[]
	);

	paymentsContainer.get('data').set('payments', [...updatedPayments]);
	console.log(affectedRows);

	tableRender.set('selectedRows', affectedRows);
	tableRender.set('data', {
		body: [...updatedPayments] || [],
		footer: { ...paymentsContainer.get('data').get('totals') } || {},
	});
	tableRender.render();
}

function retornoRecebimentoErp() {
	const dataInicial = document.querySelector(
		'#js-retorno-recebimento-modal #js-data-inicial'
	).value;
	const dataFinal = document.querySelector(
		'#js-retorno-recebimento-modal #js-data-final'
	).value;

	if (!dataInicial || !dataFinal) {
		swal('Ooops...', 'A data inicial e final devem ser informadas!', 'error');
	}

	swal(
		'Aguarde um momento...',
		'O processo pode levar alguns segundos.',
		'warning'
	);
	toggleElementVisibility('#js-loader');
	api
		.get(searchForm.get('form').dataset.urlRetornoRecebimento, {
			params: {
				'data-inicial': dataInicial,
				'data-final': dataFinal,
			},
		})
		.then((res) => {
			if (res.status === 'erro' && res.mensagem) {
				swal('Ooops...', res.mensagem, 'error');
				return;
			}

			const updatedPayments = res.pagamentos.reduce((values, id) => {
				return [
					...values,
					{
						ID: id,
						RETORNO_ERP_BAIXA: 'Sim',
					},
				];
			}, []);

			updatePayments(
				paymentsContainer.get('data').get('payments'),
				[...updatedPayments],
				'ID'
			);

			swal(
				'Baixa Executada!',
				`${res.vendas.length} de ${res.total} registros atualizados!`,
				'success'
			);
		})
		.catch((err) => {
			swal(
				'Ooops...',
				'Um erro inesperado ocorreu. Tente novamente mais tarde!',
				'error'
			);
		})
		.finally(() => {
			toggleElementVisibility('#js-loader');
		});

	closeRetornoModal();
}

document
	.querySelector('#js-por-pagina')
	.addEventListener('change', onPerPageChanged);

searchForm
	.get('form')
	.querySelector('button[data-form-action="submit"')
	.addEventListener('click', searchForm.get('onSubmitHandler'));

document.querySelector('#js-exportar').addEventListener('click', exportar);

document.querySelector('#js-retorno-csv').addEventListener('click', retornoCsv);

document
	.querySelector('#js-abrir-modal-retorno')
	.addEventListener('click', () =>
		$('#js-retorno-recebimento-modal').modal('show')
	);

document
	.querySelector('#js-cancelar-retorno-recebimento')
	.addEventListener('click', closeRetornoModal);

document
	.querySelector('#js-retorno-recebimento')
	.addEventListener('click', retornoRecebimentoErp);

document
	.querySelector('#js-retorno-recebimento-modal *[data-dismiss]')
	.addEventListener('click', closeRetornoModal);

document
	.querySelector('#dropdownUserSettings')
	.addEventListener('click', (e) => {
		$('#dropdownUserSettings').dropdown('toggle');
	});

document.querySelector('#dropdownCadastros').addEventListener('click', (e) => {
	$('#dropdownCadastros').dropdown('toggle');
});
