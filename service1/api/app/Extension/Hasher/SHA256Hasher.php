<?php
namespace App\Extension\Hasher;
use \Illuminate\Contracts\Hashing\Hasher;

class SHA256Hasher implements Hasher {

    // ハッシュ作成
    // $value のハッシュを返す
    // password_hash() に相当する
    public function make($value, array $options = [])
    {
        return hash('sha256', $value);
    }

    // ハッシュのチェック
    // $value のハッシュと、与えられたハッシュが一致するかをチェックする
    // password_verify() に相当する
    public function check($value, $hashedValue, array $options = []): bool
    {
        return $this->make($value) === $hashedValue;
    }

    // ハッシュの再計算が必要かのチェック
    // password_needs_rehash() に相当する
    // SHA256 では不要なので、常に false を返す
    public function needsRehash($hashedValue, array $options = []): bool
    {
        return false;
    }

    // ハッシュの情報取得
    // password_get_info() に相当する
    // SHA256 では不要なので、常に null を返す
    public function info($hashedValue)
    {
        return null;
    }
}
