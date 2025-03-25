<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PracticeResource;
use App\Models\Practice;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class PracticeController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $practices = Practice::where('active', 1)->get();

        return PracticeResource::collection($practices);
    }
}
