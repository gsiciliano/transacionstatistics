<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Repositories\StatisticRepository;
use App\Http\Repositories\TransactionRepository;

class StatisticController extends Controller
{

    private $statisticRepository;
    private $transactionRepository;

    public function __construct(StatisticRepository $statisticRepository, TransactionRepository $transactionRepository)
    {
        $this->statisticRepository = $statisticRepository;
        $this->transactionRepository = $transactionRepository;
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
        $transactionsList = $this->transactionRepository->getAllFromQueue();
        $response = $this->statisticRepository->all($transactionsList);
        return response()->json($response, Response::HTTP_OK);
    }
}
