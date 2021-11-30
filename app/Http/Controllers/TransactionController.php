<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
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
     *         @OA\MediaType(
     *            mediaType="application/json",
     *          )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *     )
     * )
     */
    /**
     * Return list of transaction
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fromTime = $request->input('from');
        if (empty($fromTime)) {
            return response()->json(null,Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json($this->transactionRepository->get($fromTime), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/transactions",
     *     description="This endpoint is called to create a new transaction.",
     *     operationId="store",
     *     tags={"Transactions"},
     *     security={{"passport":{}}},
     *      @OA\RequestBody(
     *          description="Amount",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/transactionRequest")
     *          )
     *      ),
     *      @OA\Response(
     *         response=201,
     *         description="Resource created",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *          )
     *     ),
     *      @OA\Response(
     *         response=204,
     *         description="Resource created but is older than 60 seconds",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *          )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
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

        if ($dataTimeStamp->diffInSeconds() > 60) {
            $statusCode = Response::HTTP_NO_CONTENT;
        } else {
            $statusCode = Response::HTTP_CREATED;
            Cache::put([$validated['timestamp'] => $validated['amount']], 60);
        }

        $this->transactionRepository->save($validated);
        return response()->json(null, $statusCode);
    }

    /**
     * @OA\Delete(
     *     path="/transactions",
     *     description="This endpoint causes all existing transactions to be deleted",
     *     operationId="destroy",
     *     tags={"Transactions"},
     *     security={{"passport":{}}},
    *     @OA\Response(
     *         response=200,
     *         description="Operation successful",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *          )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),     * )
     */
    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        return response()->json(["deleted"=>$this->transactionRepository->truncate()],Response::HTTP_OK);
    }
}
