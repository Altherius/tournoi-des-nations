<?php

namespace App\Http\Controllers;

use App\Http\Resources\EloHistoryEntryResource;
use App\Models\EloHistoryEntry;
use App\Models\Team;
use OpenApi\Attributes as OA;

class EloHistoryEntryController extends Controller
{
    #[OA\Get(path: '/api/teams/{team}/elo-history', summary: 'Get the paginated Elo History of a Team', tags: ['Team'])]
    #[OA\Parameter(name: 'page', description: 'The page number', in: 'query', schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'team', description: 'The team ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: '200', description: 'The paginated Elo History of the team', content: new OA\JsonContent(ref: '#/components/schemas/EloHistoryEntryPaginatedCollection'))]
    public function team(Team $team)
    {
        return EloHistoryEntryResource::collection(
            EloHistoryEntry::with(['opposing_team'])
                ->where('team_id', $team->id)
                ->paginate(500)
        );
    }
}
