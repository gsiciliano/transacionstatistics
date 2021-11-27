<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatisticController extends Controller
{
    /**
     * @OA\Get(
     *     path="/statistics",
     *     summary="",
     *     description="returns the statistic based of the transactions of the last 60 seconds.",
     *     tags={"Statistics"},
     *      @OA\Response(
     *         response=200,
     *         description="Operation successful",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *          )
     *     )
     * )
     */
    public function index(){

    }
}
