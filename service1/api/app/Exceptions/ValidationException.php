<?php

namespace App\Exceptions;

class ValidationException extends WebAPIException
{
    /** 例外コード */
    protected $errorCode;
    /** 例外メッセージ */
    protected $errorMessage;
    private array $validationMessages;

    /**
     * ValidationException constructor.
     * @param string $code
     * @param array $validationErrors
     */
    public function __construct(string $code = 'W_0000000', array $validationErrors = [])
    {
        $this->errorCode = $code;
        $this->errorMessage = __('messages.' . $code);
        $this->validationMessages = $validationErrors;
        parent::__construct($this->errorCode, [], parent::HTTP_STATUS_BAD_REQUEST);
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
                'result' => $this->validationMessages,
            ],
            parent::HTTP_STATUS_BAD_REQUEST,
            [],
            config('app.debug') ? JSON_UNESCAPED_UNICODE : 0
        );
    }
}
