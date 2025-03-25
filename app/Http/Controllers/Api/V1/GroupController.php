<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class GroupController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $groups = Group::where('active', 1)->get();

        return GroupResource::collection($groups);
    }
}
