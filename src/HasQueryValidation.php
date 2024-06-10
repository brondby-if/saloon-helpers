<?php

declare(strict_types=1);

namespace Brondby\SaloonHelpers;

use App\Saloon\Exceptions\ValidationException;
use Illuminate\Support\Facades\Validator;
use Saloon\Http\PendingRequest;

trait HasQueryValidation
{
    public function rules(): array
    {
        return [];
    }
    public function bootHasQueryValidation(PendingRequest $pendingRequest): void
    {
        $request = $pendingRequest->getRequest();
        $this->validate($request);
    }

    protected function validate($request)
    {
        $validator = Validator::make($request->query()->all(), $this->rules());

        if ($validator->fails()) {
            throw new ValidationException($validator, $request);
        }
    }
}
