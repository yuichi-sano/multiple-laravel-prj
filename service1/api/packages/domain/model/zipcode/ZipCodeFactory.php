<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode;

use App\Exceptions\WebAPIException;
use packages\domain\model\zipcode\helper\SplitTownAreaFactory;
use packages\domain\model\zipcode\helper\ZipCodeConstants;

class ZipCodeFactory
{
    /**
     * @param int $id
     * @param array $row
     * @return ZipCode
     */
    public function create(int $id, array $row): ZipCode
    {
        return new ZipCode(
            new ZipCodeId($id),
            new ZipCodeJis($row[ZipCodeConstants::IDX_JIS]),
            new ZipCodeOldPostalCode($row[ZipCodeConstants::IDX_ZIPCODE5]),
            new ZipCodePostalCode($row[ZipCodeConstants::IDX_ZIPCODE]),
            $row[ZipCodeConstants::IDX_PREFECTURE_KANA],
            $row[ZipCodeConstants::IDX_CITY_KANA],
            $this->cleanTownAreaKana(
                $row[ZipCodeConstants::IDX_TOWNAREA],
                $row[ZipCodeConstants::IDX_TOWNAREA_KANA]
            ),
            $row[ZipCodeConstants::IDX_PREFECTURE],
            $this->getPrefectureCode($row[ZipCodeConstants::IDX_JIS]),
            $row[ZipCodeConstants::IDX_CITY],
            $this->cleanTownArea($row[ZipCodeConstants::IDX_TOWNAREA]),
            $row[ZipCodeConstants::IDX_IS_ONETOWN_BY_MULTIZIPCODE],
            $row[ZipCodeConstants::IDX_IS_NEEDS_SMALLAREAADDRESS],
            $row[ZipCodeConstants::IDX_IS_CHOUME],
            $row[ZipCodeConstants::IDX_IS_MULTITOWN_BY_ONEPOSTCODE],
            $row[ZipCodeConstants::IDX_UPDATED],
            $row[ZipCodeConstants::IDX_UPDATEREASON],
        );
    }

    /**
     * 表示に不要な町域情報を除外する
     * @param string $townArea 町域情報
     * @return          一部情報を削除した町域情報
     */
    public function cleanTownArea($townArea): string
    {
        $cleanTownArea = $townArea;

        if ((bool)preg_match(ZipCodeConstants::REGEX_IGNORE, $townArea)) {
            $cleanTownArea = '';
        }

        if ((bool)preg_match(ZipCodeConstants::REGEX_FLOOR, $townArea)) {
            $cleanTownArea = preg_replace(
                ZipCodeConstants::REGEX_FLOOR,
                '',
                $townArea
            );
        }

        /*
        if((bool)preg_match(ZipCodeConstants::REGEX_JIWARI, $townArea)){
            $cleanTownArea = preg_replace(
                                ZipCodeConstants::REGEX_JIWARI,
                                '',
                                $townArea
                             );
        };
        if((bool)preg_match(ZipCodeConstants::REGEX_PARENTHESES, $townArea)){
            $cleanTownArea = preg_replace(
                                ZipCodeConstants::REGEX_PARENTHESES,
                                '',
                                $cleanTownArea
                             );
        };
        */
        return $cleanTownArea;
    }

    /**
     * 町域の分割に人力での調査が必要なもの町域情報を一部削除
     * @param string $townArea 町域情報
     * @return          一部情報を削除した町域情報
     */
    public function cleanCantSplitTownArea($townArea): string
    {
        $cleanTownArea = $townArea;

        // trueになる→ 種市パターン
        if (!$this->needSplitConcrete($townArea)) {
            $hasParentheses = (bool)preg_match(
                ZipCodeConstants::REGEX_PARENTHESES,
                $cleanTownArea
            );
            if ($hasParentheses) {
                $cleanTownArea = preg_replace(
                    ZipCodeConstants::REGEX_PARENTHESES,
                    '',
                    $cleanTownArea
                );
            }

            $hasJiwari = (bool)preg_match(
                ZipCodeConstants::REGEX_JIWARI_EXCEPT_TOWN,
                $cleanTownArea
            );
            if ($hasJiwari) {
                $cleanTownArea = preg_replace(
                    ZipCodeConstants::REGEX_JIWARI_EXCEPT_TOWN,
                    '',
                    $cleanTownArea
                );
            }
        }
        return $cleanTownArea;
    }

    /**
     * 表示に不要な町域情報カナを除外する
     * @param string $townArea 町域情報カナ
     * @return          一部情報を削除した町域情報カナ
     */
    public function cleanTownAreaKana($townArea, $townAreaKana): string
    {
        $cleanTownAreaKana = $townAreaKana;

        if ((bool)preg_match(ZipCodeConstants::REGEX_IGNORE, $townArea)) {
            $cleanTownAreaKana = '';
        }

        if ((bool)preg_match(ZipCodeConstants::REGEX_FLOOR_KANA, $townAreaKana)) {
            $cleanTownAreaKana = preg_replace(
                ZipCodeConstants::REGEX_FLOOR_KANA,
                '',
                $townAreaKana
            );
        }

        /*
        if((bool)preg_match(ZipCodeConstants::REGEX_JIWARI_KANA, $townAreaKana)) {
            $cleanTownAreaKana = preg_replace(
                                    ZipCodeConstants::REGEX_JIWARI_KANA,
                                    '',
                                    $townAreaKana
                                 );
        };
        */
        /*
        if((bool)preg_match(ZipCodeConstants::REGEX_PARENTHESES, $townArea)) {
            $cleanTownArea = preg_replace(
                                ZipCodeConstants::REGEX_PARENTHESES,
                                '',
                                $cleanTownArea
                             );
        };
        */
        return $cleanTownAreaKana;
    }

    /**
     * 町域の分割に人力での調査が必要なものの情報を一部削除
     * @param string $townArea 町域情報カナ
     * @return          一部情報を削除した町域情報カナ
     */
    public function cleanCantSplitTownAreaKana(string $townArea, string $townAreaKana): string
    {
        $cleanTownAreaKana = $townAreaKana;

        // needSplitConcreteは半角を受け付けていない
        if (!$this->needSplitConcrete($townArea)) {
            $hasParentheses = (bool)preg_match(
                ZipCodeConstants::REGEX_PARENTHESES_KANA,
                $cleanTownAreaKana
            );
            if ($hasParentheses) {
                $cleanTownAreaKana = preg_replace(
                    ZipCodeConstants::REGEX_PARENTHESES_KANA,
                    '',
                    $cleanTownAreaKana
                );
            }

            $hasJiwari = (bool)preg_match(
                ZipCodeConstants::REGEX_JIWARI_KANA_EXCEPT_TOWN,
                $cleanTownAreaKana
            );
            if ($hasJiwari) {
                $cleanTownAreaKana = preg_replace(
                    ZipCodeConstants::REGEX_JIWARI_KANA_EXCEPT_TOWN,
                    '',
                    $cleanTownAreaKana
                );
            }
        }
        return $cleanTownAreaKana;
    }

    /**
     * レコードの町域名称（カナ）の括弧が明示的に閉じられていないか判定する
     * @param array $row レコード
     * @return bool        判定結果
     */
    public function isUnClose(array $row): bool
    {
        $isUnclose = (bool)preg_match(
            ZipCodeConstants::REGEX_UNCLOSED_PARENTHESES,
            $row[ZipCodeConstants::IDX_TOWNAREA]
        );
        $isUncloseKana = (bool)preg_match(
            ZipCodeConstants::REGEX_UNCLOSED_PARENTHESES_KANA,
            $row[ZipCodeConstants::IDX_TOWNAREA_KANA]
        );

        return $isUnclose || $isUncloseKana;
    }

    /**
     * レコードの町域名称（カナ）の括弧が明示的に閉じられているか判定する
     * @param array $row レコード
     * @return bool        判定結果
     */
    public function isClose(array $row): bool
    {
        $isClose = (bool)preg_match(
            ZipCodeConstants::REGEX_CLOSED_PARENTHESES,
            $row[ZipCodeConstants::IDX_TOWNAREA]
        );
        $isCloseKana = (bool)preg_match(
            ZipCodeConstants::REGEX_CLOSED_PARENTHESES_KANA,
            $row[ZipCodeConstants::IDX_TOWNAREA_KANA]
        );

        return $isClose || $isCloseKana;
    }

    /**
     * 町域名称の値を元にレコードの分割要否を判定する
     * @param array $row zipcode
     * @return bool       分割要否
     */
    public function needSplit(array $row): bool
    {
        $townArea = $row[ZipCodeConstants::IDX_TOWNAREA];
        return $this->needSplitConcrete($townArea);
    }

    /**
     * 町域名称の値を元にレコードの分割要否を判定する実装
     * @param string $townArea
     * @return bool  分割要否
     */
    private function needSplitConcrete(string $townArea): bool
    {
        $hasMain = (bool)preg_match(
            ZipCodeConstants::REGEX_BEFORE_PARENTHESES,
            $townArea
        );
        $hasSerial = (bool)preg_match(
            ZipCodeConstants::REGEX_SERIAL_TOWNAREA,
            $townArea
        );
        $hasSeparater = (bool)preg_match(
            ZipCodeConstants::REGEX_SEPARATE_TOWNAREA,
            $townArea
        );

        if ($hasMain && $hasSerial && $hasSeparater) {
            // 主の町域と従属する町域と連続する町域が存在する際に、
            // 主の町域が連続する町域であった場合は分割の対象外となる
            // 例：種市第１５地割～第２１地割（鹿糠、小路合、緑町、大久保、高取）
            preg_match(
                ZipCodeConstants::REGEX_BEFORE_PARENTHESES,
                $townArea,
                $mainTownArea
            );
            return !(bool)preg_match(
                ZipCodeConstants::REGEX_SERIAL_TOWNAREA,
                $mainTownArea[0]
            );
        }

        if ($hasSerial) {
            //連番の町域であり、かつ始点と終点に異なる番地を保持している場合は、
            //分割の対象外となる 例: ２４３０－１～２４３１－７５
            $hasSerialBanchiGou = (bool)preg_match(
                ZipCodeConstants::REGEX_SERIAL_BANCHIGOU,
                $townArea
            );

            //〜のあとに町域情報が存在しないものは分割の対象外となる
            // 例：大前（細原２２５９〜）
            $hasNotSerialEnd = (bool)preg_match(
                ZipCodeConstants::REGEX_NOT_SERIALEND_NUMBER,
                $townArea
            );

            // $hasSerialBanchiGou, $hasNotSerialEndに該当するのが
            // 複数まとめられた町域のうちの1つであれば分割の対象となる
            return (!$hasSerialBanchiGou && !$hasNotSerialEnd) ||
                count(explode('、', $townArea)) > 1;
        }

        return $hasSerial || $hasSeparater;
    }

    /**
     * 単一のレコードを複数に分割する
     * 実際に分割を行うのは町域名称（カナ）の値のみでそれ以外の属性値は変わらない
     * 仕様の詳細は、同ディレクトリ配下にあるREADME.mdを参照してください
     * @param array $row zipcode
     * @return array     分割したzipcodeの配列
     * @throws WebAPIException
     */
    public function splitRow(array $row): array
    {
        $factory = new SplitTownAreaFactory();
        $splitter = $factory->production(
            $row[ZipCodeConstants::IDX_TOWNAREA]
        );

        $splittedTownAreas = $splitter->split(
            $row[ZipCodeConstants::IDX_TOWNAREA],
            $row[ZipCodeConstants::IDX_TOWNAREA_KANA]
        );
        $splittedRows = [];
        foreach ($splittedTownAreas['townArea'] as $index => $townArea) {
            $splittedRow = $this->generateTemplateRow($row);

            $splittedRow[ZipCodeConstants::IDX_TOWNAREA] = $townArea;
            $splittedRow[ZipCodeConstants::IDX_TOWNAREA_KANA]
                = $splittedTownAreas['townAreaKana'][$index];

            $splittedRows[] = $splittedRow;
        }
        return $splittedRows;
    }

    /**
     * 分割するレコードのテンプレートを生成する
     * @param array $row テンプレート生成元のzipcode
     * @return array      テンプレートのzipcode
     */
    private function generateTemplateRow(array $row): array
    {
        $samePieceRow = [];
        foreach ($row as $attributeIndex => $attribute) {
            // レコード内の、町域属性と町域カナ属性以外は同一の値となる
            if (
                $attributeIndex != ZipCodeConstants::IDX_TOWNAREA &&
                $attributeIndex != ZipCodeConstants::IDX_TOWNAREA_KANA
            ) {
                $samePieceRow[$attributeIndex] = $attribute;
            } else {
                $samePieceRow[$attributeIndex] = '';
            }
        }
        return $samePieceRow;
    }

    public function isDeprecated()
    {
    }

    /**
     * 複数のRowデータをマージ
     * @param array $mergeRows
     * @return array|mixed
     */
    public function mergeRows(array $mergeRows)
    {
        $mergeTownArea = [];
        $mergeTownAreaKana = [];
        $mergedRow = [];
        foreach ($mergeRows as $row) {
            $mergeTownArea[] = $row[ZipCodeConstants::IDX_TOWNAREA];
            $mergeTownAreaKana[] = $row[ZipCodeConstants::IDX_TOWNAREA_KANA];
            $mergedRow = $row;
        }
        $mergedTownArea = implode('', $mergeTownArea);
        $mergedTownAreaKana = implode('', $mergeTownAreaKana);
        $mergedRow[ZipCodeConstants::IDX_TOWNAREA_KANA] = $mergedTownAreaKana;
        $mergedRow[ZipCodeConstants::IDX_TOWNAREA] = $mergedTownArea;
        return $mergedRow;
    }

    public function mergeTownAreaKana()
    {
    }

    /**
     * JISコードから都道府県コードを抜き出し返却
     * @param string $jis
     * @return int
     */
    private function getPrefectureCode(string $jis): int
    {
        return (int)substr($jis, 0, 2);
    }
}
