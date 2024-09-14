export default (flash, animated, wire, text, enter, leave, close) => ({
  show: animated === false && wire === false,
  animated: animated,
  type: 'primary',
  text: text,
  enter: enter,
  leave: leave,
  close: close,
  init() {
    if (flash) window.onload = () => this.add(flash);

    if (this.animated) {
      setTimeout(() => this.show = true, this.enter ? this.enter * 1000 : 0);

      if (this.leave) {
        setTimeout(() => this.show = false, this.leave * 1000);
      }
    }

    this.$watch('show', (value) => {
      if (value === false || !wire || !this.leave) {
        return;
      }

      setTimeout(() => this.show = false, this.leave * 1000);
    });
  },
  /**
   * Add a new banner.
   *
   * @param event {Event|Object}
   */
  add(event) {
    const data = event.detail ?? event;

    this.type = data.type;
    this.text = data.title;
    this.description = data.description;
    this.close = data.close;
    this.enter = data.enter;
    this.leave = data.leave;

    if (!this.enter) {
      this.show = true;

      return;
    }

    setTimeout(() => this.show = true, this.enter * 1000);
  },
});
