function TableConfig(options) {
  this.proxy = new Proxy({
    tableSelector: options.tableSelector || '',
    rootElement: options.rootElement || '',
    sectionContainer: options.sectionContainer || new TableSectionContainer({ table: options.tableSelector || '' }),
    checkerGroup: options.checkerGroup || 'tb-config-columns',
    status: {
      visible: [],
      hidden: [],
    },
    checker: new Checker(),
    storageKey: options.storageKey || 'table_config'
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

TableConfig.prototype._generateStorageKey = function() {
  const pathname = new URL(window.location.href).pathname.replaceAll('/', '@');
  return `${pathname}${this.get('tableSelector')}.table_config`;
}

TableConfig.prototype._setup = function() {
  this.set('storageKey', this._generateStorageKey());
  const tableSectionContainer = new TableSectionContainer({
    table: this.get('tableSelector'),
  })
    .buildSections();
  this.set('sectionContainer', tableSectionContainer);
  this.loadConfiguration();
}

TableConfig.prototype._registerEvents = function() {
  const tbConfigControl = this.get('rootElement').querySelector('.table-config-control');
  const confirmButton = this.get('rootElement').querySelector('button[data-action="confirm"]');
  if(confirmButton) {
    confirmButton.addEventListener('click', this.onConfirm.bind(this));
    confirmButton.addEventListener('click', this.toggleDropdownMenu.bind(this));
  }

  if(tbConfigControl) {
    tbConfigControl.addEventListener('click', this.toggleDropdownMenu.bind(this));
  }

  window.addEventListener('beforeunload', this.saveConfiguration.bind(this));
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

TableConfig.prototype.onConfirm = function() {
  const checkerGroup = this.get('checkerGroup');
  const checker = this.get('checker');
  const checkedValues = checker.getCheckedValues(checkerGroup);
  const uncheckedValues = checker.getUncheckedValues(checkerGroup);

  this.set('status', {
    visible: checkedValues,
    hidden: uncheckedValues
  })
  this.show(checkedValues);
  this.hide(uncheckedValues);
}

TableConfig.prototype.init = function() {
  this._setup();
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
    tableConfigList.appendChild(configOption);
  });

  const checkerGroup = this.get('checkerGroup');
  this.get('checker').addGroup(checkerGroup);
  this.get('checker').checkOnly(checkerGroup, this.get('visibleSections'));
}

TableConfig.prototype.loadConfiguration = function() {
  const savedConfiguration = JSON.parse(localStorage.getItem(this.get('storageKey')));
  if(!savedConfiguration) {
    this.set('status', {
      visible: this.get('sectionContainer').getSectionsByVisibility({ mustBeVisible: true }).map(section => section.id),
      hidden: this.get('sectionContainer').getSectionsByVisibility({ mustBeVisible: false }).map(section => section.id),
    });

    return;
  };

  this.set('status', savedConfiguration);
  this.show(this.get('visibleSections'));
  this.hide(this.get('hiddenSections'));
}

TableConfig.prototype.saveConfiguration = function() {
  localStorage.setItem(this.get('storageKey'), JSON.stringify(this.get('status')));
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
      if(name === 'visibleSections') return target.status.visible;
      if(name === 'hiddenSections') return target.status.hidden;

			return target[name];
		},
  }
}
