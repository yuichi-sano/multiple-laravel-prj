<?php

namespace App\Http\Resources\Basic;
use App\Http\Resources\Definition\Basic\ResultDefinitionInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use stdClass;

abstract class AbstractJsonResource extends JsonResource
{
    public static $wrap = "result";
    protected string $state = '200';
    protected ?string $message = null;
    protected  ResultDefinitionInterface  $result;

    public function __construct(ResultDefinitionInterface  $resource)
    {
        parent::__construct($resource);
        $this->setResult($resource);
    }

    public function getState(): string
    {
        return $this->state;
    }
    public function getMessage(): ?string
    {
        return $this->message;
    }
    private function setState($state){
        $this->state = $state;
    }
    private function setMessage($message){
        $this->message = $message;
    }
    private function setResult(ResultDefinitionInterface $resource)
    {
        $this->result = $resource;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'state'       => $this->state,
            'message'     => $this->message,
            'result'      => ($this->result) ? $this->result->toArray() : new stdClass(),
        ];
    }
}
