<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ValidateRequestsTrait
{
    /**
     * Validates the request data based on the given rules.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $rules
     * @throws HttpResponseException
     * @return array
     */
    public function validateRequest($request, array $rules)
    {
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new HttpResponseException(response()->json([
                'msg' => 'Oops! Failed to validate data.',
                'status' => false,
                'errors' => $validator->errors(),
            ], 403));
        }

        return $validator->validated();
    }
}
