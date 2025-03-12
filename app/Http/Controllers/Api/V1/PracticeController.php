<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PracticeResource;
use App\Models\Practice;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PracticeController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $practices = Practice::all();

        return PracticeResource::collection($practices);
    }
}
