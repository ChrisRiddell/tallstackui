export default (model, rules) => ({
  model: model,
  show: false,
  rules: false,
  input: '',
  min: rules.min ?? null,
  symbols: rules.symbols ?? null,
  numbers: rules.numbers ?? null,
  mixed: rules.mixed ?? null,
  caps: false,
  results: {
    min: false,
    symbols: false,
    numbers: false,
    mixed: false,
  },
  init() {
    this.$watch('input', (value) => {
      if (!value) {
        this.reset();

        return;
      }

      this.check(value);
    });
  },
  /**
   * Toggle the visibility of the password.
   *
   * @returns {void}
   */
  toggle() {
    this.show = !this.show;

    this.$el.dispatchEvent(new CustomEvent('reveal', {detail: {status: this.show}}));
  },
  /**
   * @returns {void}
   */
  reset() {
    this.results = {min: false, symbols: false, numbers: false, mixed: false};
  },
  /**
   * Check if the password meets the requirements.
   *
   * @param value {String}
   */
  check(value) {
    this.results.min = this.min && value.length >= this.min;

    if (this.symbols) {
      /* eslint-disable-next-line no-useless-escape */
      this.results.symbols = (new RegExp(`[${this.symbols.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&')}]`)).test(value);
    }

    this.results.numbers = this.numbers &&value.match(/\d/) !== null;

    this.results.mixed = this.mixed && value.match(/[a-z]/) && value.match(/[A-Z]/);
  },
  /**
   * Generate a random password based on the rules.
   *
   * @returns {void}
   */
  generator() {
    this.$refs.generator.classList.add('animate-spin');

    let password = '';

    const lower = 'abcdefghijklmnopqrstuvwxyz';
    const upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const numeric = '0123456789';

    password += lower.charAt(Math.floor(Math.random() * lower.length));

    if (this.mixed) {
      password += upper.charAt(Math.floor(Math.random() * upper.length));
    }

    if (this.numbers) {
      password += numeric.charAt(Math.floor(Math.random() * numeric.length));
    }

    if (this.symbols) {
      password += this.symbols.charAt(Math.floor(Math.random() * this.symbols.length));
    }

    // We just fill the remaining password with random characters from all selected types
    const allCharacters = lower + (this.mixed ? upper : '') + (this.numbers ? numeric : '') + (this.symbols ? this.symbols : '');
    for (let i = password.length; i < this.min; i++) {
      password += allCharacters.charAt(Math.floor(Math.random() * allCharacters.length));
    }

    // We just shuffle the password to avoid predictable patterns
    password = password.split('').sort(() => 0.5 - Math.random()).join('');

    this.input = this.model = password;

    this.$el.dispatchEvent(new CustomEvent('generate', {detail: {password: password}}));

    setTimeout(() => this.$refs.generator.classList.remove('animate-spin'), 250);
  },
  /**
   * Activate the caps lock indicator.
   *
   * @param {Event} event
   */
  indicator(event) {
    // This was necessary to prevent the "$event.getModifierState is not a function." error.
    if (typeof event.getModifierState !== 'function') return;

    this.caps = event.getModifierState('CapsLock');
  }
});
