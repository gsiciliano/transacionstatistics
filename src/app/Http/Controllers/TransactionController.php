<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Response;
use App\Http\Requests\TransactionRequest;
use App\Http\Repositories\TransactionRepository;

class TransactionController extends Controller
{

    private $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @OA\Get(
     *     path="/transactions",
     *     summary="returns all the transaction from a specified timestamp",
     *     description="returns all the transaction from a specified timestamp",
     *     operationId="index",
     *     tags={"Transactions"},
     *     security={{"passport":{}}},
     *     @OA\Parameter(
     *          name="from",
     *          in="query",
     *          required=true,
     *          description="the given timestamp in the ISO 8601 format",
     *          @OA\Schema(
     *              type="string",
     *              format="date-time",
     *              example="2021-11-27T18:13:25.354Z"
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operation successful",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="from parameter is empty or invalid",
     *     )
     * )
     */
    /**
     * Return list of transaction
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TransactionRequest $request)
    {
        $validated = $request->validated();
        return response()->json($this->transactionRepository->get(
            Carbon::createFromTimeString($validated['from'])->toDateTimeString()
        ), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/transactions",
     *     summary="This endpoint is called to create a new transaction.",
     *     description="This endpoint is called to create a new transaction.",
     *     operationId="store",
     *     tags={"Transactions"},
     *     security={{"passport":{}}},
     *      @OA\RequestBody(
     *          description="Transaction",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/transactionRequest")
     *          )
     *      ),
     *      @OA\Response(
     *         response=201,
     *         description="Resource created",
     *     ),
     *      @OA\Response(
     *         response=204,
     *         description="Resource created but is older than 60 seconds",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="JSON is invalid",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Something is not parsable or timestamp is in the future",
     *     )
     * )
     */
    /**
     * Store a newly created transaction in storage.
     *
     * @param  App\Http\Repositories\TransactionRepository  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionRequest $request)
    {
        if (empty($request->json()->all())) {
            return response()->json(null,Response::HTTP_BAD_REQUEST);
        }
        $validated = $request->validated();
        $dataTimeStamp = Carbon::parse($validated['timestamp']);

        if ($dataTimeStamp->diffInSeconds() > config('time-limits.statistics.seconds')) {
            $this->transactionRepository->save($validated);
            $statusCode = Response::HTTP_NO_CONTENT;
        } else {
            $this->transactionRepository->addToQueue($validated);
            $statusCode = Response::HTTP_CREATED;
        }
        return response()->json(null, $statusCode);
    }

    /**
     * @OA\Delete(
     *     path="/transactions",
     *     summary="This endpoint causes all existing transactions to be deleted",
     *     description="This endpoint causes all existing transactions to be deleted",
     *     operationId="destroy",
     *     tags={"Transactions"},
     *     security={{"passport":{}}},
    *     @OA\Response(
     *         response=200,
     *         description="Operation successful",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     * )
     */
    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        return response()->json(
            ["deleted"=>
                $this->transactionRepository->truncateTable() +
                $this->transactionRepository->removeAllQueuedItems()
            ],Response::HTTP_OK);
    }
}
