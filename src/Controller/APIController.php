<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Base API Controller.
 */
class APIController
{
    /**
     * default status code.
     *
     * @var int
     */
    protected $statusCode = 200;

    /**
     * get the status code.
     *
     * @return statuscode
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * set the status code.
     *
     * @param [type] $statusCode [description]
     *
     * @return statuscode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Respond.
     *
     * @param array $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($messages, $headers = "application/json")
    {
        $response = new JsonResponse();
        $response->setContent(json_encode([
            'message' => $messages,
            'code' => $this->getStatusCode()
        ]));
        $response->headers->set('Content-Type', $headers);
        return $response;
    }

    /**Note this function is same as the below function but instead of responding with error below function returns error json
     * Throw Validation.
     *
     * @param string $message
     *
     * @return mix
     */
    public function throwValidation($message)
    {
        $this->setStatusCode(422);
        return $this->respond($message);
    }
}
