<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEvaluation;
use App\Http\Resources\EvaluationResource;
use App\Jobs\EvaluationCreated;
use App\Models\Evaluation;
use App\Services\CompanyService;

class EvaluationController extends Controller
{
    protected $repository;
    protected $companyService;

    public function __construct(Evaluation $repository, CompanyService $companyService)
    {
        $this->repository = $repository;
        $this->companyService = $companyService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company)
    {
        $evaluation = $this->repository->whereCompany($company)->get();
        return EvaluationResource::collection($evaluation);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEvaluation $request, $company)
    {
        $response = $this->companyService->getCompany($company);
        $status = $response->status();
        if ($status != 200) {
            return response()->json([
                "message" => "invalid company"
            ], $status);
        }
        $evaluation = $this->repository->create([
            "company" => $company,
            "comment" => $request->get('comment'),
            "stars" => $request->get('stars')
        ]);

        $company = json_decode($response->body())->data;
        EvaluationCreated::dispatch($company->email)->onQueue('queue_email');

        return new EvaluationResource($evaluation);
    }
}
