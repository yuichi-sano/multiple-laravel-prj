<?php

namespace App\Http\Requests\Definition\Basic;

abstract class AbstractRequestDefinition
{
    protected array $rules = array();
    protected array $attribute = array();

    public function buildValidateRules(): array
    {
        foreach ($this as $key => $val) {
            if (!(empty($val) || $key == 'rules' || $key == 'attribute')) {
                if (str_contains($val, 'collectionObject')) {
                    $this->rules[$key] = $this->cleanObjectRule($val);
                    $children = $this->childDefinition();
                    $child_rules = $children[$key][0]->buildValidateRules();
                    foreach ($child_rules as $child_key => $child_rule) {
                        $this->rules[$key . '.*.' . $child_key] = $child_rule;
                    }
                } elseif (str_contains($val, 'object')) {
                    $this->rules[$key] = $this->cleanObjectRule($val);
                    $children = $this->childDefinition();
                    $child_rules = $children[$key]->buildValidateRules();
                    foreach ($child_rules as $child_key => $child_rule) {
                        $this->rules[$key . '.' . $child_key] = $child_rule;
                    }
                } else {
                    $this->rules[$key] = $val;
                }
            }
        }

        return $this->rules;
    }

    public function buildAttribute(): array
    {
        foreach ($this as $key => $val) {
            if (!(empty($val) || $key == 'rules' || $key == 'attribute')) {
                $this->attribute[$key] = __('attributes.' . $key);
                if (str_contains($val, 'collectionObject')) {
                    $children = $this->childDefinition();
                    $child_attrs = $children[$key][0]->buildAttribute();
                    foreach ($child_attrs as $child_key => $child_attr) {
                        $this->attribute[$key . '.*.' . $child_key] = $child_attr;
                    }
                } elseif (str_contains($val, 'object')) {
                    $children = $this->childDefinition();
                    $child_attrs = $children[$key]->buildAttribute();
                    foreach ($child_attrs as $child_key => $child_attr) {
                        $this->attribute[$key . '.' . $child_key] = $child_attr;
                    }
                }
            }
        }
        return $this->attribute;
    }

    /**
     * プロパティ間の連結など加工したいパラメータを設定
     * @param array $attrs
     * @return array
     */
    public function transform(array $attrs): array
    {
        //$attrs['tel'] = implode('', $attrs['tel']);
        return $attrs;
    }

    /**
     * 入れ子のオブジェクトがある場合下記関数に追記される。
     * @return array
     */
    public function childDefinition(): array
    {
        /*
        return [
            'customer'=> new CustomerDefinition(),
        ];
        */
        return [];
    }

        /**
     * バリデーションルールの文字列から
     * collectionObjectとobjectが有れば削除する
     */
    private function cleanObjectRule(string $rule): string
    {
        $rule_arr = explode("|", $rule);
        $rule_str = array_filter($rule_arr, function ($rule) {
            if ($rule == 'collectionObject' | $rule == 'object') {
                return false;
            }
            return true;
        });

        return implode('|', $rule_str);
    }
}
