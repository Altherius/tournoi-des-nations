<?php

namespace App\Http\Controllers;

use App\Http\Requests\Game\GameCreateRequest;
use App\Http\Resources\GameResource;
use App\Models\Game;
use OpenApi\Attributes as OA;

class GameController extends Controller
{
    #[OA\Get(path: '/api/games', summary: 'Get collection of games', tags: ['Game'])]
    #[OA\Parameter(name: 'page', in: 'query', description: 'The page number', schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: '200', description: 'A paginated collection of games', content: new OA\JsonContent(ref: '#/components/schemas/GamePaginatedCollection'))]
    public function index()
    {
        return GameResource::collection(Game::with(['hostingTeam', 'receivingTeam'])->paginate());
    }

    #[OA\Post(path: '/api/games', summary: 'Create game', tags: ['Game'])]
    #[OA\RequestBody(ref: '#/components/requestBodies/GameCreateRequest')]
    #[OA\Response(response: '201', description: 'The created game', content: new OA\JsonContent(properties: [
        new OA\Property(property: 'data', ref: '#/components/schemas/Game', type: 'object'),
    ]))]
    #[OA\Response(response: '400', description: 'Input format is incorrect', content: new OA\JsonContent(ref: '#/components/schemas/Error'))]
    #[OA\Response(response: '422', description: 'Input data has not been validated', content: new OA\JsonContent(ref: '#/components/schemas/Error'))]
    public function store(GameCreateRequest $request)
    {
        $game = new Game;

        $game->hosting_team_id = $request->hostingTeamId;
        $game->receiving_team_id = $request->receivingTeamId;
        $game->winning_team_id = $request->winningTeamId;
        $game->tournament_id = $request->tournamentId;
        $game->host_score_1 = $request->hostScore1;
        $game->guest_score_1 = $request->hostScore2;
        $game->host_score_2 = $request->guestScore1;
        $game->guest_score_2 = $request->guestScore2;

        $game->save();

        return new GameResource($game);
    }

    #[OA\Get(path: '/api/games/{id}', summary: 'Get game', tags: ['Game'])]
    #[OA\Parameter(name: 'id', description: 'The ID of the game', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: '200', description: 'The required game', content: new OA\JsonContent(properties: [
        new OA\Property(property: 'data', ref: '#/components/schemas/Game', type: 'object'),
    ]))]
    #[OA\Response(response: '404', description: 'No game has been found with this ID', content: new OA\JsonContent(ref: '#/components/schemas/Error'))]
    public function show(Game $game)
    {
        return new GameResource($game);
    }
}
