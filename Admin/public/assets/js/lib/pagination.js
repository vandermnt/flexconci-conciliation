function Pagination(data  = [], options = {}) {
  this.setData(data);
  this.options = {
    perPage: 10,
    ...options
  };
}

/**
 * @function setData
 * Set a new data array
 * 
 * @param {Array} data
 * 
 * @returns {Pagination}
 */
Pagination.prototype.setData = function(data = []) {
  data = Array.isArray(data) ? [...data] : [data];
  this.data = data || [];

  return this;
}

/**
 * @function setData
 * Set a new data array
 * 
 * @param {Array} data
 * 
 * @returns {Pagination}
 */
Pagination.prototype.setOptions = function(options = {}) {
  this.options = {
    ...this.options,
    ...options
  }

  return this;
}

/**
 * @function paginate
 * Set the pagination metadata for the given data
 * 
 * @param {Number} perPage - The amount of items per page
 * @returns {Pagination}
*/
Pagination.prototype.paginate = function(perPage = this.options.perPage) {
  const total = this.options.total || (this.data || []).length;
  const numberOfPages = Math.ceil((total / perPage));

  this.options = {
    ...this.options,
    currentPage: 1,
    total: total,
    lastPage: numberOfPages > 0 ? numberOfPages : 1,
    perPage: Number(perPage),
  }

  return this;
}

/** 
 * @function getPageData
 * Return the page data
 * 
 * @param {Number} page - The number of the page or by default the current page
 * 
 * @returns {Array}
*/
Pagination.prototype.getPageData = function(page = this.options.currentPage) {
  const data = [...this.data];

  page = Number(page);

  if(!page) {
    return data;
  }
  
  if(this.options.total > this.data.length) {
    return this.data;
  }

  page = page.toFixed(0);
  page = Math.abs(page);

  const offset = Math.abs((this.options.perPage * (page - 1)))

  return data.slice(offset, (offset + this.options.perPage));
}

/** 
 * @function goToPage
 * Set a new value to the current page
 * 
 * @param {Number} page - The new current  page
 * @returns {Pagination}
*/
Pagination.prototype.goToPage = function(page = 1) {
  page = Number(page);

  if(!page) {
    this.options.currentPage = 1;
    return this;
  }

  page = page.toFixed(0);
  this.options.currentPage = Math.abs(page);

  if(this.options.currentPage > this.options.lastPage) {
    this.options.currentPage = this.options.lastPage;
  }

  return this;
}

/**
 * @function prev
 * Go to the previous page by decrementing one page relative to the current page
 * 
 * @returns {Pagination}
 */
Pagination.prototype.prev = function() {
  this.goToPage(this.options.currentPage - 1);
  return this;
}

/**
 * @function next
 * Go to the next page by incrementing one page relative to the current page
 * 
 * @returns {Pagination}
 */
Pagination.prototype.next = function() {
  this.goToPage(this.options.currentPage + 1);
  return this;
}

/**
 * @function toArray
 * Serialize the pagination data in an array
 * 
 * @param {boolean} shouldFragment
 * @param {Number} fragmentOn
 * @param {String} fragmentSeparator
 * 
 * @returns {Array}
 */
Pagination.prototype.toArray = function(shouldFragment = false, fragmentOn = 10, fragmentSeparator = '...') {
  const MIN_FRAGMENT_LIMIT = 9;
  const pages = Array.from({ length: this.options.lastPage }, (value, index) => index + 1);
  const { currentPage, lastPage } = this.options;

  fragmentOn = Number.isInteger(fragmentOn) ? fragmentOn : 1;
  fragmentOn = Math.abs(fragmentOn) + 1;
  fragmentOn = fragmentOn < MIN_FRAGMENT_LIMIT ? 9 : fragmentOn;

  if(shouldFragment && this.options.lastPage < fragmentOn) {
      return pages;
  }

  let start = pages.slice(0, 2);
  let middle = [fragmentSeparator];
  let end = pages.slice(-3);

  if(currentPage < 5) {
      start = pages.slice(0, 5);
  } else if(currentPage > 4 && currentPage < (lastPage - 3)) {
      middle = pages.slice(currentPage - 2, currentPage + 1);
      middle = [fragmentSeparator, ...middle]
      end = [fragmentSeparator, ...end];
  } else {
      if(currentPage === (lastPage - 3)) {
          end = [currentPage, ...end]
      }
  }

  return [...start, ...middle, ...end];
}