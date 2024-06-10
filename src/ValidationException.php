<?php

namespace Brondby\SaloonHelpers;

use Illuminate\Contracts\Validation\Validator;
use Saloon\Exceptions\SaloonException;
use Saloon\Http\Request;

class ValidationException extends SaloonException
{
    /**
     * The validator instance.
     *
     * @var \Illuminate\Contracts\Validation\Validator
     */
    public $validator;

    /**
     * The recommended response to send to the client.
     *
     * @var \Saloon\Http\Request|null
     */
    public $request;

    /**
     * The status code to use for the response.
     *
     * @var int
     */
    public $status = 422;

    /**
     * Create a new exception instance.
     *
     * @return void
     */
    public function __construct(Validator $validator, ?Request $request = null)
    {
        parent::__construct(static::summarize($validator, $request));

        $this->request = $request;
        $this->validator = $validator;
    }

    /**
     * Create an error message summary from the validation errors.
     */
    protected static function summarize(Validator $validator, Request $request): string
    {
        $messages = $validator->errors()->all();

        if (! count($messages)) {
            return 'The given data was invalid.';
        }

        $message = 'The query '.$request::class.' failed: ';
        $message .= json_encode($messages);

        return $message;
    }
}
