export class ValidationResult {
  private errors: Error[] = [];
  private validators: Validator[] = [];

  constructor(validators: Validator[]) {
    this.validators = validators;
  }

  public init(): void {
    this.errors = [];
  }

  public validateList(targets: any[]) {
    targets.forEach((target, index) => this.validate(target, index));
  }

  public validate(target: any, index: number = 0) {
    Object.entries(target).forEach(([key, value]) => {
      this.validators
        .filter((validator) => validator.name === key)
        .flatMap((validator) => validator.constraints)
        .forEach((constraint) => {
          if (!constraint.method(value)) {
            if (this._hasError(key, index, constraint.method.name)) {
              return;
            }
            this.errors.push({index, name: key, message: constraint.message, constraint: constraint.method.name});
          } else {
            this._removeError(key, index, constraint.method.name);
          }
        });
    });
  }

  public hasError(name: string, index: number = 0): boolean {
    return this.errors.some((error) => (error.name === name && error.index === index));
  }

  public getMessages(viewIndex: boolean = false): string[] {
    if (viewIndex) {
      return this.errors.map((error) => (error.index + 1) + ': ' + error.message);
    }
    return this.errors.map((error) => error.message);
  }

  private _hasError(name: string, index: number = 0, constraint: string): boolean {
    return this.errors.some((error) =>
      (error.name === name && error.constraint === constraint && error.index === index));
  }

  private _removeError(name: string, index: number = 0, constraint: string): boolean {
    return this.errors.some((error, i) => {
      if (error.name === name && error.constraint === constraint && error.index === index) {
        this.errors.splice(i, 1);
      }
    });
  }

  get hasErrors(): boolean {
    return this.errors.length > 0;
  }

  get getErrors(): Error[] {
    return this.errors;
  }
}

export interface Validator {
  name: string;
  constraints: Constraint[];
}

export interface Constraint {
  method: (value: any) => boolean;
  message: string;
}

export interface Error {
  index: number;
  name: string;
  message: string;
  constraint: string;
}

export function required(value: string): boolean {
  if (!value) {
    return false;
  }
  return true;
}
