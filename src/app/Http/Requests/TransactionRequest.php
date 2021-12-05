<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation(){
        if ($this->isMethod('post')){
            $this->merge(['timestamp'=>
                $this->timestamp = Carbon::createFromTimeString($this->timestamp)->format('Y-m-d H:i:s.v')
            ]);
        }
    }

    /**
    *     @OA\Schema(
    *         schema="transactionRequest",
    *         type="object",
    *         @OA\Property(
    *             property="amount",
    *             type="string",
    *             format="numeric",
    *             example="1000"
    *         ),
    *         @OA\Property(
    *             property="timestamp",
    *              type="string",
    *              format="date-time",
    *              example="2021-11-27T18:13:25.354Z"
    *         ),
    *     )
    */
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod('get')){
            return [
                'from' => ['required','date:Y-m-d\\TH:i:s.v\\Z']
            ];
        }

        if ($this->isMethod('post')){
            return [
                'amount' => ['required','numeric','gt:0'],
                'timestamp' => ['required','date:Y-m-d\\TH:i:s.v\\Z',
                    function($attribute, $value, $fail) {
                        if (Carbon::createFromTimeString($value)->greaterThan(Carbon::now())){
                            $fail($attribute.' must be before or equal to now');
                        }
                    }
                ]
            ];
        }
    }
}
