class Fixator {
  constructor(options) {
    this.appended = false;
    this.options = {
      wrapper: options.wrapper || '',
      table: options.table || '',
      rows: options.rows || ''
    };

    this.$wrapper = $(this.options.wrapper);
    this.$table = $(this.options.table);
    this.rows = this.constructRows();
    this.$fixWrap = $("<div/>", { class: "table-fix" });
    this.$fixTable = $("<table/>", {}).appendTo(this.$fixWrap);
    this.$fixTbody = $("<tbody/>", {}).appendTo(this.$fixTable);
  }

  constructRows() {
    const rows = [];
    this.options.rows.forEach((el, i) => {
      rows.push({
        origin: $(el),
        clone: $(el).clone().addClass('_cloned'),
        coords: {
          offset: $(el).offset().top,
          height: $(el).height()
        }
      });


    });
    return rows;
  }

  init() {
    this.$wrapper.append(this.$fixWrap);
    this.$fixWrap.width(this.coords.wrapper.width);
    this.$fixTable.width(this.coords.table.width);
    $(window).on("scroll.fixator", () => this.render());
    $(window).on("resize.fixator", () => this.update());
    this.$wrapper.on("scroll.fixator", () => this.changePosition());
  }

  update() {
    this.rows.forEach((index, element) => {
      let item = this.rows[element];
      item.origin = $(item.origin);
      item.clone.
        html(item.origin.html()).
        height(item.coords.height);
      item.coords = {
        position: item.origin.position().top,
        offset: item.origin.offset().top,
        height: item.origin.height()
      };
    });
    this.$wrapper = $(this.options.wrapper);
    this.$table = $(this.options.table);
    this.$fixWrap.width(this.coords.wrapper.width);
    this.$fixTable.width(this.coords.table.width);

  }

  destroy() {
    $(window).off('fixator');
    this.$wrapper.off('fixator');
    this.$fixTbody.remove();
    this.$fixTable.remove();
    this.$fixWrap.remove();
  }

  render() {
    this.rows.forEach((index, element) => {
      let item = this.rows[element];
      let difference = !item.appended ? this.$fixWrap.height() : this.$fixWrap.height() - item.clone.height();
      if (this.coords.window.scrollTop + difference > item.coords.offset &&
        this.coords.window.scrollTop < this.coords.wrapper.offset +
        this.coords.wrapper.height - (item.coords.height + 120)) {
        if (!item.appended) {
          this.$fixTbody.append(item.clone);
          item.appended = true;
        }
      } else {
        item.clone.detach();
        item.appended = false;
      }
    });

  }

  changePosition() {
    this.$fixTable.css("margin-left", -this.coords.wrapper.scrollLeft);
  }

  get coords() {
    return {
      wrapper: {
        height: this.$wrapper.height(),
        width: this.$wrapper.width(),
        offset: this.$wrapper.offset().top,
        scrollLeft: this.$wrapper.scrollLeft()
      },

      table: {
        width: this.$table.width()
      },

      window: {
        scrollTop: $(window).scrollTop()
      }
    };
  }
}

class GrabAndSlide {
  constructor(selector) {
    this.selector = selector;
    this.mousePosition = null;
    this.scrollPosition = null;
    this.scroll = null;
  }

  init() {
    $(this.selector).on("mousedown", e => this.start(e));
    $(this.selector).on("mousemove", e => this.onScroll(e));
    $(this.selector).on("mouseup mouseleave", () => this.end());
  }

  start(e) {
    this.mousePosition = e.pageX;
    this.scrollPosition = $(this.selector).scrollLeft();
    this.scroll = true;
  }

  onScroll(e) {
    if (this.scroll === true) {
      e.preventDefault();
      if (e.pageX > this.mousePosition) {
        $(this.selector).scrollLeft(this.scrollPosition - (e.pageX - this.mousePosition));
      } else if (e.pageX < this.mousePosition) {
        $(this.selector).scrollLeft(this.scrollPosition + (this.mousePosition - e.pageX));
      }
    }
  }

  end() {
    this.scroll = false;
  }
}

class DragAndDrop {
  constructor(options) {
    this.options = options;
    this.table = options.table;
    this.dragger = tableDragger($(options.table)[0], options.draggerConfig);
  }
}


class DndWithScroll extends DragAndDrop {
  constructor(options) {
    super(options);
    this.fixator = options.fixator || null;
    this.sliderForTable = options.sliderForTable || null;
    this.tableWrap = options.tableWrap;
    this.handler = options.draggerConfig.dragHandler;
    this.columnAvatar = '.gu-mirror';
    this.ignoreColumn = '.sindu_dragger li:first-child';
    this.ignoreClass = 'sindu_static';
    this.tableScroll = null;
    this.commitTableScroll = this.commitTableScroll.bind(this);
    this.scrollOnDrag = this.scrollOnDrag.bind(this);
    this.onDragStart = this.onDragStart.bind(this);
    this.onDrop = this.onDrop.bind(this);
    this.elementsToIgnore = options.elementsToIgnore || [];
    this._events = options.events || {};
  }

  init() {
    this._ignoreElements();
    this.fixator && this.fixator.init();
    this.sliderForTable && this.sliderForTable.init();
  }

  commitTableScroll() {
    this.tableScroll = $(this.tableWrap).scrollLeft();
  }

  scrollOnDrag() {
    const coords = this.coords;
    if (coords.columnAvatar.right > coords.tableWrap.right) {
      $(this.tableWrap).scrollLeft($(this.tableWrap).scrollLeft() + (coords.columnAvatar.right - coords.tableWrap.right));
    } else if (coords.columnAvatar.left < coords.tableWrap.left) {
      $(this.tableWrap).scrollLeft($(this.tableWrap).scrollLeft() - (coords.tableWrap.left - coords.columnAvatar.left));
    }
  }

  onDragStart() {
    $(this.ignoreColumn).addClass(this.ignoreClass);
    this.sliderForTable.scroll = false;
    $(this.tableWrap).scrollLeft(this.tableScroll);
    $(document).on('mousemove', this.scrollOnDrag);
  }

  onDrop() {
    $(document).off('mousemove', this.scrollOnDrag);
    this.fixator && this.fixator.update();
  }

  get coords() {
    return {
      columnAvatar: {
        left: $(this.columnAvatar)[0].getBoundingClientRect().left,
        right: $(this.columnAvatar)[0].getBoundingClientRect().right
      },

      tableWrap: {
        left: $(this.tableWrap)[0].getBoundingClientRect().left,
        right: $(this.tableWrap)[0].getBoundingClientRect().right
      }
    };
  }

  _ignoreElements() {
    this.elementsToIgnore.forEach(selector => {
      $(selector).on('mousedown', event => event.stopImmediatePropagation());
      $(selector).on('mousemove', event => event.stopImmediatePropagation());
      $(selector).on('mouseup', event => event.stopImmediatePropagation());
    });
  }

  _bindCustomEvents() {
    const allowedEvents = ['drag', 'drop'];
    Object.keys(this._events).forEach(eventName => {
      if(!allowedEvents.includes(eventName)) return;

      const event = this._events[eventName];
      if(event && typeof event === 'function') {
        this.dragger.on(eventName, event);
      }
    });
  }

  bindEvents() {
    this._bindCustomEvents();
    $(this.handler).on('mousedown', this.commitTableScroll);
    this.dragger.on('drag', this.onDragStart);
    this.dragger.on('drop', this.onDrop);
  }

  refresh() {
    this.fixator.destroy();
    this.dragger = tableDragger($(this.options.table)[0], this.options.draggerConfig);
    this.fixator = new Fixator(this.options.fixatorOptions);
    this.sliderForTable = new GrabAndSlide(this.options.sliderOptions.selector);

    this.init();
    this.bindEvents();
  }
}

function createScrollableTableDragger(options = {}) {
  const fixatorOptions = {
    wrapper: options.wrapper || '.table',
    table: options.table || ".table > table",
    rows: options.rows || []
  };
  const sliderOptions = { selector: options.slider || options.wrapper || '.table' }

  const fixator = new Fixator(fixatorOptions);
  const sliderForTable = new GrabAndSlide(sliderOptions.selector);
  const scrollableDragger = new DndWithScroll({
    fixator,
    fixatorOptions,
    sliderForTable,
    sliderOptions,
    tableWrap: options.wrapper || '.table',
    table: options.table || ".table > table",
    draggerConfig: options.draggerConfig || {
      mode: 'column',
      dragHandler: '.handler',
      onlyBody: false,
      animation: 300
    },
    events: options.events || {},
    elementsToIgnore: options.elementsToIgnore || [],
  });

  scrollableDragger.init();
  scrollableDragger.bindEvents();

  return scrollableDragger;
}
