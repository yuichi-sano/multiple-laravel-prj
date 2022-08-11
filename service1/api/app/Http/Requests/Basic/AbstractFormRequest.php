<?php

namespace App\Http\Requests\Basic;

use App\Exceptions\ValidationException;
use App\Http\Requests\Definition\Basic\DefinitionInterface;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use packages\domain\model\authentication\Account;

abstract class AbstractFormRequest extends FormRequest
{
    public array $validationMessage = [];
    protected ?DefinitionInterface $definition;

    abstract protected function transform(array $attrs);

    public function __construct(DefinitionInterface $definition = null)
    {
        $this->definition = $definition;
    }

    /**
     * 無加工なHTTPリクエストプロパティを配列で返却します。
     * '_' で始まるパラメータは除外されます。
     */
    public function attrs()
    {
        $attrs = array_filter($this->all(), function ($k) {
            return !str_starts_with($k, '_');
        }, ARRAY_FILTER_USE_KEY);

        if ($this->definition === null) {
            return $this->transform($attrs);
        }
        return $this->definition->transform($attrs);
    }

    /**
     * requestでは認証回りは扱わない
     * @return bool
     */
    final public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return $this->definition->buildValidateRules();
    }

    /**
     * 項目を和名で返却します。
     * @return array
     */
    public function attributes(): array
    {
        return $this->definition->buildAttribute();
    }

    /**
     * リクエストパラメータとURIの{id}部分を取得して
     * バリデーションの範囲に追加します。
     *
     * @return array $request リクエスト
     */
    public function validationData(): array
    {
        $request = parent::validationData();
        $request['id'] = $this->route('id');

        return $request;
    }

    /**
     * バリデーションのルールに沿わなかった場合に呼び出されるメソッド
     * エラーメッセージを配列で返却します。
     * TODO Exception要
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $this->validationMessage = $validator->errors()->toArray();
        logger($validator->errors()->toJson());
        throw new ValidationException('V_0000000', $this->validationMessage);
    }

    public function messages(): array
    {
        return $this->validationMessage;
    }

    /**
     * @return Account
     */
    public function getAuthedUser(): Account
    {
        return Auth::user();
    }
}
