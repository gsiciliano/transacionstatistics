<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Repositories\StatisticRepository;

class StatisticController extends Controller
{

    private $statisticRepository;

    public function __construct(StatisticRepository $statisticRepository)
    {
        $this->statisticRepository = $statisticRepository;
    }

    /**
     * @OA\Get(
     *     path="/statistics",
     *     summary="returns the statistic based of the transactions of the last 60 seconds.",
     *     description="returns the statistic based of the transactions of the last 60 seconds.",
     *     tags={"Statistics"},
     *      @OA\Response(
     *         response=200,
     *         description="Operation successful",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *          )
     *     ),
     *      @OA\Response(
     *         response=204,
     *         description="No content",
     *     )
     * )
     */
    public function index(){
        $response = $this->statisticRepository->all();
        if (empty($response)){
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }
        return response()->json($response, Response::HTTP_OK);

    }
}
