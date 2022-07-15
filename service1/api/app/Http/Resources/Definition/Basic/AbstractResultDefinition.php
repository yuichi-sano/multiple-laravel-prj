<?php

namespace App\Http\Resources\Definition\Basic;

/**
 * Abstract Class
 * AbstractResultDefinition
 * ResultDefinitionが継承する関数、共通関数を実装
 * @note  interfaceは実装しません
 * @package App\Http\Result\Definition\Basic
 */
abstract class AbstractResultDefinition
{
    /**
     * toArray
     * 階層構造の場合、階層部も生成し詰めなおす
     * @param $request
     * @return array
     */
    public function toArray(): array
    {
        $result = array();
        foreach ($this as $key => $val) {
            $result[$key] = is_array($val) ? $this->childConvValue($val) : $this->convValue($val);
        }
        return $result;
    }

    /**
     * 渡された配列の中身を適宜生成し詰めなおしたものをreturn
     * @param $vals
     * @return array
     */
    private function childConvValue(array $vals): array
    {
        $value = array();
        foreach ($vals as $key => $val) {
            $value[$key] = $this->convValue($val);
        }
        return $value;
    }

    /**
     * プロパティの中身にオブジェクト階層があれば生成してreturn
     * @param $val
     * @return mixed
     */
    private function convValue($val)
    {
        return $val instanceof ResultDefinitionInterface ? $val->toArray() : $val;
    }
}
