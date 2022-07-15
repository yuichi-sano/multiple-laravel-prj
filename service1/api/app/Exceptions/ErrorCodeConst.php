<?php

namespace App\Exceptions;

/**
 * エラーコードの定数クラス
 */
class ErrorCodeConst
{
    public const VALIDATION = 'V_0000000';
    /** 新規登録に失敗（DBエラー） */
    public const ERROR_500_REGISTER = 'E_500_0000001';
    /** 更新に失敗（DBエラー） */
    public const ERROR_500_UPDATE = 'E_500_0000002';
    /** 削除に失敗（DBエラー） */
    public const ERROR_500_DELETE = 'E_500_0000003';
    /** 外部通信失敗  */
    public const ERROR_503_EXTERNAL = 'E_503_0000001';
    /** 適用開始日が適用終了日よりも未来*/
    public const ERROR_400_INVALID_AVAILABILITY_START_DATE = 'E_400_0000001';
    /** 配送端末ホスト名、またはIPアドレスが重複*/
    public const ERROR_400_DUPLICATE_HOSR_NAME_OR_IP_ADDRESS = 'E_400_0000002';
    /** IPアドレスが重複*/
    public const ERROR_400_DUPLICATE_IP_ADDRESS = 'E_400_0000003';
    /** 認証期限切れ*/
    public const ERROR_401_REFRESH_UN_AUTHORIZED = 'E_401_0001';
    public const ERROR_401_UNAUTHORIZED = 'E_401_0000000';
    /** 郵便番号から住所が検索できない */
    public const ERROR_404_NOTFOUND_ZIP = 'E_404_0000002';
    /** 郵便番号が存在しない */
    public const ERROR_204_NOCONTENT_ZIP = 'E_204_0000001';
    /** 配送端末が存在しない */
    public const ERROR_204_NOCONTENT_DELIVERY_DEVICE = 'E_204_0000003';
}
