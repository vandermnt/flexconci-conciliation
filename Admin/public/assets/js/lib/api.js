const API = function () {};

const api = new API();

/**
 * @method urlBuilder
 *
 * @param {String} urlBase - Base URL ('http://localhost:8000/path/...)
 * @param {Object} params - Query params to concat with URL Base ({ page: 1, key: value })
 * @param {boolean} toString @default true - Return only the build URL or the entire URL object
 *
 * @returns {URL|String}
 */
API.prototype.urlBuilder = function (urlBase, params = {}, toString = true) {
	const url = new URL(urlBase);
	Object.keys(params).forEach((key) => {
		if (Array.isArray(params[key])) {
			params[key].forEach((value) => {
				url.searchParams.append(`${key}[]`, value);
			});

			return;
		}

		url.searchParams.append(key, params[key]);
	});

	return toString ? url.href : url;
};

/**
 * @method sendRequest
 * Create and send a new request with fetch API
 *
 * @async
 * @param {String} urlBase - The base URL passed to URL Builder
 * @param {Object} options - The object with the request data as headers, body and others
 * @param {String} options.method
 * @param {Object} options.headers
 * @param {Object} options.body
 * @param {Object} options.params
 *
 * @returns {Promise} - When resolved returns the response data as JSON Object
 */
API.prototype.sendRequest = async function (
	urlBase,
	{ method, headers = {}, params = {}, ...rest }
) {
	const url = this.urlBuilder(urlBase, params);

	const response = await fetch(url, {
		method,
		headers,
		...rest,
	}).catch((err) => {
		throw err;
	});

	const json = await response.json().catch((err) => {
		throw err;
	});

	return json;
};

/**
 * @method get - Call sendRequest passing method prop as 'GET'
 * @async
 *
 * @borrows sendRequest as get
 */
API.prototype.get = async function (urlBase, options) {
	const json = await this.sendRequest(urlBase, {
		...options,
		method: 'GET',
	}).catch((err) => {
		throw err;
	});

	return json;
};

/**
 * @method post - Call sendRequest passing method prop as 'POST'
 * @async
 *
 * @borrows sendRequest as post
 */
API.prototype.post = async function (urlBase, options) {
	const json = await this.sendRequest(urlBase, {
		...options,
		method: 'POST',
	}).catch((err) => {
		throw err;
	});

	return json;
};
