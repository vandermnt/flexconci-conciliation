function TableSection(options = {}) {
  this.proxy = new Proxy({
    table: options.table || '',
    sectionName: options.section || '',
    sectionTitle: options.sectionTitle || '',
    section: {
      headers: [],
      body: [],
      footer: [],
    },
    isVisible: options.section || true,
  }, tableSectionHandler());
}

TableSection.prototype.get = function (prop = null) {
	if (!prop) {
		return this.proxy;
	}

	return this.proxy[prop];
};

TableSection.prototype.set = function (prop = '', value = null) {
	this.proxy[prop] = value;
};

TableSection.prototype.refreshTitle = function() {
  const element = document.querySelector(`
    ${this.get('table')} thead th[data-th-title][data-tb-section="${this.get('sectionName')}"]`
  );

  if(!element) return;

  const title = element.dataset.thTitle;
  this.set('sectionTitle', title);
}

TableSection.prototype._refreshHeaders = function() {
  const tbHeaders = [
    ...document.querySelectorAll(`${this.get('table')} thead th[data-tb-section="${this.get('sectionName')}"]`)
  ];

  this.set('headers', [...tbHeaders]);
}

TableSection.prototype._refreshBody = function() {
  const tbElements = [
    ...document.querySelectorAll(`${this.get('table')} tbody td[data-tb-section="${this.get('sectionName')}"]`)
  ];

  this.set('body', [...tbElements]);
}
TableSection.prototype._refreshFooter = function() {
  const tbFooters = [
    ...document.querySelectorAll(`${this.get('table')} tfoot td[data-tb-section="${this.get('sectionName')}"]`)
  ];

  this.set('footer', [...tbFooters]);
}

TableSection.prototype.refresh = function(only = ['headers', 'body', 'footer']) {
  const refreshFunctions = {
    'headers': this._refreshHeaders.bind(this),
    'body': this._refreshBody.bind(this),
    'footer': this._refreshFooter.bind(this),
  };

  if(!only || !Array.isArray(only)) return;

  only.forEach((item) => {
    const refreshFun = refreshFunctions[item];

    if(refreshFun && typeof refreshFun === 'function') {
      refreshFun();
    }
  });
}

TableSection.prototype._toggleVisiblity = function({ isVisible = true, only = ['headers', 'body', 'footer']}) {
  if(this.get('isVisible') === isVisible) return;

  if(!only || !Array.isArray(only)) return;
  const elements = only.reduce((mergedElements, type) => {
    const sectionElements = this.get(type);
    if(sectionElements) {
      mergedElements = [...mergedElements, ...sectionElements];
    }

    return mergedElements;
  }, []);

  elements.forEach(element => {
    element.classList.remove('hidden');

    if(!isVisible) {
      element.classList.add('hidden');
    }
  });

  this.set('isVisible', isVisible);
}

TableSection.prototype.hide = function(only = ['headers', 'body', 'footer']) {
  this._toggleVisiblity({ isVisible: false, only });
}

TableSection.prototype.show = function(only = ['headers', 'body', 'footer']) {
  this._toggleVisiblity({ isVisible: true, only });
}

function tableSectionHandler() {
	return {
		set: function (target, name, value) {
      if (['headers', 'body', 'footer'].includes(name)) {
				return target.section[name] = value;
			}

			target[name] = value;
		},
		get: function (target, name) {
			if (['headers', 'body', 'footer'].includes(name)) {
				return target.section[name];
			}

			return target[name];
		},
	};
}

function TableSectionContainer(options = {}) {
  this.proxy = new Proxy({
    table: options.table || '',
    sections: options.sections || {},
  }, tableSectionContainerHandler());
}

TableSectionContainer.prototype.get = function (prop = null) {
	if (!prop) {
		return this.proxy;
	}

	return this.proxy[prop];
};

TableSectionContainer.prototype.set = function (prop = '', value = null) {
	this.proxy[prop] = value;
};

TableSectionContainer.prototype.buildSections = function () {
  const sectionsHeaders = [...document.querySelectorAll(`${this.get('table')} thead th[data-tb-section]`)];

  const tableSections = sectionsHeaders.reduce((tbSections, theader) => {
    const sectionName = theader.dataset.tbSection;

    if(sectionName && !Object.keys(tbSections).includes(sectionName)) {
      const tbSection = new TableSection({
        table: this.get('table'),
        section: sectionName,
      });
      tbSection.set('isVisible', !theader.classList.contains('hidden'));
      tbSection.refreshTitle();
      tbSection.refresh();

      tbSections[sectionName] = tbSection;
    }

    return tbSections;
  }, {});

  this.set('sections', tableSections);
  return this;
}

TableSectionContainer.prototype.toSimpleObject = function(sectionKeys = null) {
  sectionKeys = sectionKeys && !Array.isArray(sectionKeys) ? [sectionKeys] : sectionKeys;
  if(sectionKeys === null) {
    sectionKeys = Object.keys(this.get('sections'));
  }

  const serializedSections = sectionKeys.reduce((sections, key) => {
    const section = this.get('sections')[key];
    if(!section) return sections;

    return [...sections, {
        id: section.get('sectionName'),
        title: section.get('sectionTitle'),
        isVisible: section.get('isVisible')
      }
    ];
  }, []);

  return serializedSections;
}

TableSectionContainer.prototype.serialize = function(sectionKeys = null) {
  sectionKeys = sectionKeys && !Array.isArray(sectionKeys) ? [sectionKeys] : sectionKeys;
  if(sectionKeys !== null) {
    sectionKeys = Object.keys(this.get('sections'));
  }

  const serializedSections = this.toSimpleObject(sectionKeys).map(section => ({
    ...section,
    section: this.get('sections')[section.id].get('section'),
  }));

  return serializedSections;
}

TableSectionContainer.prototype._toggleVisiblity = function(isVisible = true, sections = []) {
  if(!sections) return;

  sections.forEach(sectionKey => {
    const section = this.get('sections')[sectionKey];
    if(!section) return;

    isVisible ? section.show() : section.hide();
  });
}

TableSectionContainer.prototype.show = function(sections = []) {
  this._toggleVisiblity(true, sections);
}

TableSectionContainer.prototype.hide = function(sections = []) {
  this._toggleVisiblity(false, sections);
}

TableSectionContainer.prototype.refresh = function(sections = []) {
  if(!sections) return;

  sections.forEach(sectionKey => {
    const section = this.get('sections')[sectionKey];
    if(!section) return;

    section.refresh();
  });
}

TableSectionContainer.prototype.refreshAll = function() {
  const sectionKeys = Object.keys(this.get('sections'));
  this.refresh(sectionKeys);
}

function tableSectionContainerHandler() {
	return {
		set: function (target, name, value) {
			target[name] = value;
		},
		get: function (target, name) {
			return target[name];
		},
	};
}
