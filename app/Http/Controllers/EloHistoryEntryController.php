<?php

namespace App\Http\Controllers;

use App\Http\Resources\EloHistoryEntryResource;
use App\Models\EloHistoryEntry;
use App\Models\Team;
use OpenApi\Attributes as OA;

class EloHistoryEntryController extends Controller
{
    #[OA\Get(path: '/api/teams/{team}/elo-history', summary: 'Get the paginated Elo History of a Team', tags: ['Team'])]
    #[OA\Parameter(name: 'page', in: 'query', description: 'The page number', schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'team', in: 'path', description: 'The team ID', schema: new OA\Schema(type: 'integer'), required: true)]
    #[OA\Response(response: '200', description: 'The paginated Elo History of the team', content: new OA\JsonContent(ref: '#/components/schemas/EloHistoryEntryPaginatedCollection'))]
    public function team(Team $team)
    {
        return EloHistoryEntryResource::collection(EloHistoryEntry::where('team_id', $team->id)->orderBy('id', 'desc')->paginate());
    }
}
