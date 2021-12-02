import {required, ValidationResult, Validator} from '@/types/validation/ValidationResult';
import {AuthenticationRequest} from '@/types/authentication/Authentication';

export class SignInChecker {
  result: ValidationResult;

  constructor(result: AuthenticationRequest) {
    const checker = new ValidationResult(SignInValidator);
    checker.validate(result);
    this.result = checker;
  }

  get hasErrors(): boolean {
    return this.result.hasErrors;
  }

  get getMessages(): string[] {
    return this.result.getMessages();
  }
}

export const SignInValidator: Validator[] = [
  {
    name: 'access_id',
    constraints: [
      {method: required, message: 'ログインIDを入力してください'},
    ],
  },
  {
    name: 'password',
    constraints: [
      {method: required, message: 'パスワードを入力してください'},
    ],
  },
];

