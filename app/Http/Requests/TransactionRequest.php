<?php

namespace App\Http\Requests;

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
    *              example="2021-11-27T18:13:25Z"
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
            'timestamp' => 'required|date:Y-m-d\\TH:i:sO|before_or_equal:now'
        ];
    }
}
