<?php

namespace App\Http\Controllers\Base;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Traits\ValidateRequestsTrait;

abstract class BaseController extends Controller 
{
    use ValidateRequestsTrait;

    protected $serviceClass;
    protected $resourceClass;
    
    public function __construct() {
        $this->serviceClass = app($this->serviceClass);
    }

    /**
     * Display a listing of the resource.
     *
     * @return ResourceCollection
     */
    protected function index()
    {
        return $this->resourceClass::collection($this->serviceClass->getAll());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    protected function store(Request $request)
    {
        $rules = match(true) {
            isset($this->rules['create']) => $this->rules['create'],
            isset($this->rules['always']) => $this->rules['always'],
            default => []
        };

        if ($rules) {
            $this->validateRequest($request, $rules);
        }

        return new $this->resourceClass(
            $this->serviceClass->create($request->all())
        );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    protected function show(int $id)
    {
        return new $this->resourceClass(
            $this->serviceClass->getById($id)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response|JsonResponse
     */
    protected function update(Request $request, $id)
    {
        $rules = match(true) {
            isset($this->rules['update']) => $this->rules['update'],
            isset($this->rules['always']) => $this->rules['always'],
            default => []
        };

        if ($rules) {
            $this->validateRequest($request, $rules);
        }

        return new $this->resourceClass(
            $this->serviceClass->update($id, $request->all())
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return array
     */
    protected function destroy($id)
    {
        $this->serviceClass->delete($id);

        return [];
    }
}
