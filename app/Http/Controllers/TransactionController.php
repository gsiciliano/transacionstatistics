<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/transactions",
     *     description="returns all the transaction from a specified timestamp",
     *     operationId="index",
     *     tags={"Transactions"},
     *     security={{"passport":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="Operation successful",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *          )
     *     )
     * )
     */        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }
}
