<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

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
        $this->merge(['timestamp'=>
            $this->timestamp = Carbon::createFromTimeString($this->timestamp)->toDateTimeString()
        ]);
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
        return [
            'amount' => 'required|numeric',
            'timestamp' => 'required|date:Y-m-d\\TH:i:s.v\\Z|before_or_equal:now'
        ];
    }
}
