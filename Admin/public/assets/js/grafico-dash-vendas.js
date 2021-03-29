function geraGraficoVendas(dados_grafico) {
	const array = [];
	const arrayNomeAdq = [];
	let ticket_medio = null;
	let qtd = null;

	dados_grafico.forEach((dado) => {
		if (dado.COD_PERIODO) {
			var percentualFloat = parseFloat(dado.PERCENTUAL);
			percentualFloat = percentualFloat.toFixed(1);
			array.push(parseFloat(percentualFloat));
			ticket_medio += parseFloat(dado.TOTAL_BRUTO).toFixed(2);
			qtd += parseFloat(dado.QUANTIDADE_REAL);
		}
	});

	dados_grafico.forEach((dado) => {
		if (dado.COD_PERIODO == periodo) {
			arrayNomeAdq.push(dado.ADQUIRENTE);
		}
	});

	cores = ['#119DA4', '#FFBC42', '#DA2C38', '#4CB944', '#FF8000'];
	coresGrafico = [];

	for (var i = 0; i < arrayNomeAdq.length; i++) {
		coresGrafico.push(cores[i]);
	}

	var options = {
		chart: {
			height: 320,
			type: 'donut',
			dropShadow: {
				enabled: true,
				top: 10,
				left: 0,
				bottom: 0,
				right: 0,
				blur: 2,
				color: '#45404a2e',
				opacity: 0.35,
			},
		},
		stroke: {
			show: true,
			width: 2,
			colors: ['transparent'],
		},
		series: array,
		legend: {
			show: true,
			position: 'bottom',
			horizontalAlign: 'center',
			verticalAlign: 'middle',
			floating: false,
			fontSize: '14px',
			offsetX: 0,
			offsetY: 6,
		},
		labels: arrayNomeAdq,
		colors: cores,
		responsive: [
			{
				breakpoint: 600,
				options: {
					chart: {
						height: 240,
					},
					legend: {
						show: false,
					},
				},
			},
		],
		fill: {
			type: 'gradient',
		},
		tooltip: {
			y: {
				formatter: function (val, series) {
					ticket_medio_correto = ticket_medio / qtd;

					t = ticket_medio_correto.toFixed(2);
					return val + '%';
				},
				title: {
					formatter: function (val) {
						dados_grafico.forEach((dado) => {
							if (dado.ADQUIRENTE == val) {
								total = parseFloat(dado.TOTAL_BRUTO);
								ticket_medio_correto = total.toFixed(2) / dado.QUANTIDADE_REAL;
							}
						});

						t = ticket_medio_correto.toFixed(2);
						return `Ticket Médio: ${t}` + ' | ' + val;
						// return val
					},
				},
			},
		},
	};

	var chart = new ApexCharts(document.querySelector('#apex_pie2'), options);
	grafico_vendas_operadora = chart;

	chart.render(options);
}

function geraGraficoVendasBandeira(dados_grafico) {
	array = [];
	arrayNomeAdq = [];
	let ticket_medio = null;
	let qtd = null;

	dados_grafico.forEach((dado) => {
		if (dado.COD_PERIODO == periodo && dado.QUANTIDADE > 0) {
			dado.PERCENTUAL.toLocaleString(undefined, {
				maximumFractionDigits: 2,
				minimumFractionDigits: 2,
			});

			let percentualFloat = parseFloat(dado.PERCENTUAL);
			percentualFloat = percentualFloat.toFixed(1);
			array.push(parseFloat(percentualFloat));
			ticket_medio += parseFloat(dado.TOTAL_BRUTO).toFixed(2);
			qtd += parseFloat(dado.QUANTIDADE_REAL);
		}
	});

	dados_grafico.forEach((dado) => {
		if (dado.COD_PERIODO == periodo) {
			arrayNomeAdq.push(dado.BANDEIRA);
		}
	});

	cores = [
		'#119DA4',
		'#FFBC42',
		'#DA2C38',
		'#4CB944',
		'#FF8000',
		'#848484',
		'#00FFFF',
		'#086A87',
		'#FA58F4',
		'#7401DF',
		'#8181F7',
		'#D0A9F5',
	];
	coresGrafico = [];

	for (var i = 0; i < arrayNomeAdq.length; i++) {
		coresGrafico.push(cores[i]);
	}

	var options = {
		chart: {
			height: 320,
			type: 'donut',
			dropShadow: {
				enabled: true,
				top: 10,
				left: 0,
				bottom: 0,
				right: 0,
				blur: 2,
				color: '#45404a2e',
				opacity: 0.35,
			},
		},
		stroke: {
			show: true,
			width: 2,
			colors: ['transparent'],
		},
		series: array,
		legend: {
			show: true,
			position: 'bottom',
			horizontalAlign: 'center',
			verticalAlign: 'middle',
			floating: false,
			fontSize: '14px',
			offsetX: 0,
			offsetY: 6,
		},
		labels: arrayNomeAdq,
		colors: cores,
		responsive: [
			{
				breakpoint: 600,
				options: {
					chart: {
						height: 240,
					},
					legend: {
						show: false,
					},
				},
			},
		],
		fill: {
			type: 'gradient',
		},
		tooltip: {
			y: {
				formatter: function (val, series) {
					ticket_medio_correto = ticket_medio / qtd;

					t = ticket_medio_correto.toFixed(2);
					return val + '%';
				},
				title: {
					formatter: function (val) {
						dados_grafico.forEach((dado) => {
							if (dado.BANDEIRA == val) {
								total = parseFloat(dado.TOTAL_BRUTO);
								ticket_medio_correto = total.toFixed(2) / dado.QUANTIDADE_REAL;
							}
						});

						t = ticket_medio_correto.toFixed(2);
						return `Ticket Médio: ${t}` + ' | ' + val;
						// return val
					},
				},
			},
		},
	};

	var chart = new ApexCharts(document.querySelector('#apex_pie7'), options);

	grafico_vendas_bandeira = chart;

	chart.render(options);
}

function geraGraficoVendasProduto(dados_grafico) {
	array = [];
	arrayNomeAdq = [];
	let ticket_medio = null;
	let qtd = null;
	console.log(dados_grafico);
	dados_grafico.forEach((dado) => {
		if (dado.COD_PERIODO == periodo && dado.QUANTIDADE > 0) {
			var percentualFloat = parseFloat(dado.PERCENTUAL);
			percentualFloat = percentualFloat.toFixed(1);
			array.push(parseFloat(percentualFloat));
			console.log(array);
			ticket_medio += parseFloat(dado.TOTAL_BRUTO).toFixed(2);
			qtd += parseFloat(dado.QUANTIDADE_REAL);
		}
	});

	dados_grafico.forEach((dado) => {
		if (dado.COD_PERIODO == periodo) {
			arrayNomeAdq.push(dado.PRODUTO_WEB);
		}
	});

	cores = [
		'#119DA4',
		'#FFBC42',
		'#DA2C38',
		'#4CB944',
		'#FF8000',
		'#848484',
		'#00FFFF',
		'#086A87',
		'#FA58F4',
		'#7401DF',
		'#8181F7',
		'#D0A9F5',
	];
	coresGrafico = [];

	for (var i = 0; i < arrayNomeAdq.length; i++) {
		coresGrafico.push(cores[i]);
	}

	console.log(array);

	var options = {
		chart: {
			height: 320,
			type: 'donut',
			dropShadow: {
				enabled: true,
				top: 10,
				left: 0,
				bottom: 0,
				right: 0,
				blur: 2,
				color: '#45404a2e',
				opacity: 0.35,
			},
		},
		stroke: {
			show: true,
			width: 2,
			colors: ['transparent'],
		},
		series: array,
		legend: {
			show: true,
			position: 'bottom',
			horizontalAlign: 'center',
			verticalAlign: 'middle',
			floating: false,
			fontSize: '14px',
			offsetX: 0,
			offsetY: 6,
		},
		labels: arrayNomeAdq,
		colors: cores,
		responsive: [
			{
				breakpoint: 600,
				options: {
					chart: {
						height: 240,
					},
					legend: {
						show: false,
					},
				},
			},
		],
		fill: {
			type: 'gradient',
		},
		tooltip: {
			y: {
				formatter: function (val, series) {
					ticket_medio_correto = ticket_medio / qtd;

					t = ticket_medio_correto.toFixed(2);
					return val + '%';
				},
				title: {
					formatter: function (val) {
						console.log(dados_grafico);
						dados_grafico.forEach((dado) => {
							if (dado.PRODUTO_WEB == val) {
								total = parseFloat(dado.TOTAL_BRUTO);
								ticket_medio_correto = total.toFixed(2) / dado.QUANTIDADE_REAL;
							}
						});

						t = ticket_medio_correto.toFixed(2);
						return `Ticket Médio: ${t}` + ' | ' + val;
						// return val
					},
				},
			},
		},
	};

	console.log(options);

	var chart = new ApexCharts(document.querySelector('#apex_pie9'), options);

	grafico_vendas_produto = chart;

	chart.render(options);
}

function geraGraficoVendasModalidade(dados_grafico) {
	array = [];
	arrayNomeAdq = [];
	let ticket_medio = null;
	let qtd = null;

	dados_grafico.forEach((dado) => {
		if (dado.COD_PERIODO == periodo && dado.QUANTIDADE > 0) {
			var percentualFloat = parseFloat(dado.PERCENTUAL);
			percentualFloat = percentualFloat.toFixed(1);
			array.push(parseFloat(percentualFloat));
			ticket_medio += parseFloat(dado.TOTAL_BRUTO).toFixed(2);
			qtd += parseFloat(dado.QUANTIDADE_REAL);
		}
	});

	dados_grafico.forEach((dado) => {
		if (dado.COD_PERIODO == periodo) {
			arrayNomeAdq.push(dado.DESCRICAO);
		}
	});

	cores = ['#119DA4', '#FFBC42', '#DA2C38', '#4CB944'];
	coresGrafico = [];

	for (var i = 0; i < arrayNomeAdq.length; i++) {
		coresGrafico.push(cores[i]);
	}

	var options = {
		chart: {
			height: 320,
			type: 'donut',
			dropShadow: {
				enabled: true,
				top: 10,
				left: 0,
				bottom: 0,
				right: 0,
				blur: 2,
				color: '#45404a2e',
				opacity: 0.35,
			},
		},
		stroke: {
			show: true,
			width: 2,
			colors: ['transparent'],
		},
		series: array,
		legend: {
			show: true,
			position: 'bottom',
			horizontalAlign: 'center',
			verticalAlign: 'middle',
			floating: false,
			fontSize: '14px',
			offsetX: 0,
			offsetY: 6,
		},
		labels: arrayNomeAdq,
		colors: cores,
		responsive: [
			{
				breakpoint: 600,
				options: {
					chart: {
						height: 240,
					},
					legend: {
						show: false,
					},
				},
			},
		],
		fill: {
			type: 'gradient',
		},
		tooltip: {
			y: {
				formatter: function (val, series) {
					ticket_medio_correto = ticket_medio / qtd;

					t = ticket_medio_correto.toFixed(2);
					return val + '%';
				},
				title: {
					formatter: function (val) {
						dados_grafico.forEach((dado) => {
							if (dado.DESCRICAO == val) {
								total = parseFloat(dado.TOTAL_BRUTO);
								ticket_medio_correto = total.toFixed(2) / dado.QUANTIDADE_REAL;
							}
						});

						t = ticket_medio_correto.toFixed(2);
						return `Ticket Médio: ${t}` + ' | ' + val;
						// return val
					},
				},
			},
		},
	};

	var chart = new ApexCharts(document.querySelector('#apex_pie8'), options);

	grafico_vendas_modalidade = chart;

	chart.render(options);
}
