<?php

namespace App\Exceptions;

use Exception;
use \Illuminate\Http\Response;

class WebAPIException extends Exception
{
    /** Httpステータスコード */
    const HTTP_STATUS_SUCCESS = 200;
    const HTTP_STATUS_BAD_REQUEST = 400;
    const HTTP_STATUS_UN_AUTHORIZED = 401;
    const HTTP_STATUS_INTERNAL_SERVER_ERROR = 500;
    const HTTP_STATUS_MAINTENANCE = 503;

    /** 例外コード */
    protected $errorCode;
    /** 例外メッセージ */
    protected $errorMessage;
    protected $statusCode;
    /** Log出力用例外項目 */
    protected $params;

    /**
     * コンストラクタ
     *
     * @param string $code 例外コード
     * @param array $params 例外項目
     * @param int $statusCode Httpステータス
     */
    public function __construct(string $code, array $params = [], int $statusCode = 200)
    {
        parent::__construct($code, $statusCode, null);
        $this->errorCode = $code;
        $this->errorMessage = __('messages.' . $code, $params);
        $this->statusCode = $statusCode;
        $this->params = $params;
    }

    /**
     * 例外内容を成形しJson形式で返します。
     *
     * @return array|Response
     */
    public function render()
    {
        return response()->json(
            [
                'state' => $this->errorCode,
                'message' => $this->errorMessage,
            ],
            $this->statusCode,
            [],
            env('APP_DEBUG') ? JSON_UNESCAPED_UNICODE : 0
        );
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
     */
    public function setParams($params): void
    {
        $this->params = $params;
    }
}
