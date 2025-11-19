<?php

namespace App\Modules\Base\Application\Response;

use Illuminate\Http\JsonResponse;

use function PHPUnit\Framework\isEmpty;

class DataStatus
{
    private int $statusCode = 200;
    private $data = null;
    private $resourceData = null;
    private array $errors = [];
    private string $statusTitle = 'success';
    private bool $status = true;
    private string $message = '';
    private string $route = '';



    private array $meta = [];

    public const STATUS_SUCCESS = 'success';
    public const STATUS_ERROR = 'error';

    /**
     * @param int $statusCode
     * @param null $data
     * @param array $errors
     * @param string $statusTitle
     * @param bool $status
     * @param array $meta
     * @param array $message
     */
    public function __construct(int $statusCode = 200, $data = null, $resourceData = null, array $errors = [], string $statusTitle = '', string $message = '', bool $status = true, array $meta = [])
    {
        $this->statusCode = $statusCode;
        $this->data = $data;
        $this->errors = $errors;
        $this->statusTitle = $statusTitle;
        $this->status = $status;
        $this->meta = $meta;
        $this->resourceData = $resourceData;
    }


    /**
    * @return int
    */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return $this
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param mixed|null $resourceData
     */
    public function setResourceData(mixed $resourceData): void
    {
        $this->resourceData = $resourceData;
    }

    /**
     * @return mixed|null
     */
    public function getResourceData(): mixed
    {
        return $this->resourceData;
    }



    /**
     * @return null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param null $data
     */
    public function setData($data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }



    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus(bool $status): self
    {
        $this->status = $status;
        return $this;
    }


    /**
         * @return string
         */
    public function getStatusTitle(): string
    {
        return $this->statusTitle;
    }

    /**
     * @param string $status
     */
    public function setStatusTitle(string $statusTitle): self
    {
        $this->statusTitle = $statusTitle;
        return $this;
    }


    /**
     * @return array
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * @param array $meta
     */
    public function setMeta(array $meta): self
    {
        $this->meta = $meta;
        return $this;
    }

    public function response(string $route = '')
    {
        $response = [];

        if(!empty($route)) {
            $response['route'] = $route;
        }
        if($this->getStatusCode() >= 400) {
            $this->setStatus(false);
            $this->setStatusTitle(self::STATUS_ERROR);
        }
        $response['status'] = $this->getStatus();
        if($this->getStatus() !== JsonResponse::HTTP_OK && !empty($this->getErrors())) {
            $response['errors'] = $this->getErrors();
        }

        $response['message'] = $this->getMessage();
        if(true/* $this->getStatusTitle() === self::STATUS_SUCCESS && $this->getStatus() */) {
            $response['data'] = $this->getResourceData() ?? $this->getData();
            if($response['data'] === null) {
                unset($response['data']);
             }
        }




        return response()->json($response, $this->getStatusCode());
    }


    public function redirect(string $routeName)
    {
        return redirect()->route($routeName);
    }

}
