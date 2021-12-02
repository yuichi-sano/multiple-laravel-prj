export default class RetryCounter {
  static readonly limit: number = 3;
  counter: number;

  constructor() {
    this.counter = 0;

  }

  isLimit(): boolean {
    return this.counter === RetryCounter.limit;
  }

  countUp(): void {
    this.counter++;
  }

  reset(): void {
    this.counter = 0;
  }
}
