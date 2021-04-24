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
    $(window).on("scroll", () => this.render());
    $(window).on("resize", () => this.update());
    this.$wrapper.on("scroll", () => this.changePosition());
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
    this.table = options.table;
    this.dragger = tableDragger($(options.table)[0], options.draggerConfig);
  }
}


class DndWithScroll extends DragAndDrop {
  constructor(options) {
    super(options);
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
    sliderForTable.scroll = false;
    $(this.tableWrap).scrollLeft(this.tableScroll);
    $(document).on('mousemove', this.scrollOnDrag);
  }

  onDrop() {
    $(document).off('mousemove', this.scrollOnDrag);
    fixator.update();
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

  bindEvents() {
    $(this.handler).on('mousedown', this.commitTableScroll);
    this.dragger.on('drag', this.onDragStart);
    this.dragger.on('drop', this.onDrop);
  }
}
let fixator = null;
let sliderForTable = null;

function createScrollableTableDragger(options = {}) {
  fixator = new Fixator({
    wrapper: options.wrapper || '.table',
    table: options.table || ".table > table",
    rows: options.rows || []
  });
  sliderForTable = new GrabAndSlide(options.wrapper || '.table');
  const scrollableDragger = new DndWithScroll({
    tableWrap: options.wrapper || '.table',
    table: options.table || ".table > table",
    draggerConfig: options.draggerConfig || {
      mode: 'column',
      dragHandler: '.handler',
      onlyBody: false,
      animation: 300
    }
  });

  fixator.init();
  sliderForTable.init();

  return scrollableDragger;
}
