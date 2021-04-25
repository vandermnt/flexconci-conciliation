function TableConfig(options) {
  this.proxy = new Proxy({
    tableSelector: options.tableSelector || '',
    rootElement: options.rootElement || '',
    sectionContainer: options.sectionContainer || new TableSectionContainer({ table: options.tableSelector || '' }),
    checkerGroup: options.checkerGroup || 'tb-config-columns',
    visibility: {},
    checker: new Checker(),
  }, tableConfigHandler());
}

TableConfig.prototype.get = function (prop = null) {
	if (!prop) {
		return this.proxy;
	}

	return this.proxy[prop];
};

TableConfig.prototype.set = function (prop = '', value = null) {
	this.proxy[prop] = value;
};

TableConfig.prototype._registerEvents = function() {
  const tbConfigControl = this.get('rootElement').querySelector('.table-config-control');
  const confirmButton = this.get('rootElement').querySelector('button[data-action="confirm"]');
  if(confirmButton) {
    confirmButton.addEventListener('click', (e) => {
      const checkerGroup = this.get('checkerGroup');
      const checker = this.get('checker');

      this.show(checker.getCheckedValues(checkerGroup));
      this.hide(checker.getUncheckedValues(checkerGroup));
    });
    confirmButton.addEventListener('click', this.toggleDropdownMenu.bind(this));
  }

  if(tbConfigControl) {
    tbConfigControl.addEventListener('click', this.toggleDropdownMenu.bind(this));
  }
}

TableConfig.prototype.show = function(sections = []) {
  this.get('sectionContainer').show(sections);
}

TableConfig.prototype.hide = function(sections = []) {
  this.get('sectionContainer').hide(sections);
}

TableConfig.prototype.toggleDropdownMenu = function() {
  const rootElement = this.get('rootElement');

  if(!rootElement) return;

  rootElement.classList.toggle('has-focus');
}

TableConfig.prototype.init = function() {
  const tableSectionContainer = new TableSectionContainer({
    table: this.get('tableSelector'),
  })
    .buildSections();
  this.set('sectionContainer', tableSectionContainer);

  this.render();
  this._registerEvents();
  return this;
}

TableConfig.prototype.render = function() {
  const rootElement = this.get('rootElement');
  if(!rootElement) return;

  const sections = this.get('sectionContainer').toSimpleObject();
  const tableConfigList = rootElement.querySelector('.table-config-list');
  const configOptionTemplate = tableConfigList.querySelector('.table-config-option[template]').cloneNode(true);

  tableConfigList.innerHTML = "";
  tableConfigList.appendChild(configOptionTemplate);
  sections.forEach(section => {
    const configOption = configOptionTemplate.cloneNode(true);
    const checkbox = configOption.querySelector('input[type=checkbox]');
    const optionTitle = configOption.querySelector('span');

    optionTitle.textContent = section.title;
    checkbox.value = section.id;
    checkbox.dataset.checker = 'checkbox';
    section.isVisible ? checkbox.checked = true : checkbox.removeAttribute('checked');

    tableConfigList.appendChild(configOption);
  });

  this.get('checker').addGroup(this.get('checkerGroup'));
}

function tableConfigHandler() {
  return  {
    set: function (target, name, value) {
			target[name] = value;
		},
		get: function (target, name) {
			if (name === 'rootElement') {
				if (typeof target[name] !== 'string') {
					return target[name];
				}

				return document.querySelector(target[name]);
			}

			return target[name];
		},
  }
}
