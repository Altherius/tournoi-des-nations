<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tournament\TournamentCreateRequest;
use App\Http\Requests\Tournament\TournamentUpdateRequest;
use App\Http\Resources\GameResource;
use App\Http\Resources\TournamentResource;
use App\Models\Game;
use App\Models\Tournament;
use OpenApi\Attributes as OA;

class TournamentController extends Controller
{
    #[OA\Get(path: '/api/tournaments', summary: 'Get collection of tournaments', tags: ['Tournament'])]
    #[OA\Parameter(name: 'page', description: 'The page number', in: 'query', schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: '200', description: 'A paginated collection of tournaments', content: new OA\JsonContent(ref: '#/components/schemas/TournamentPaginatedCollection'))]
    public function index()
    {
        return TournamentResource::collection(Tournament::with(['goldTeam', 'silverTeam', 'bronzeTeam'])->paginate());
    }

    #[OA\Post(path: '/api/tournaments', summary: 'Create tournament', tags: ['Tournament'])]
    #[OA\RequestBody(ref: '#/components/requestBodies/TournamentCreateRequest')]
    #[OA\Response(response: '201', description: 'The created tournament', content: new OA\JsonContent(properties: [
        new OA\Property(property: 'data', ref: '#/components/schemas/Tournament', type: 'object'),
    ]))]
    #[OA\Response(response: '400', description: 'Input format is incorrect', content: new OA\JsonContent(ref: '#/components/schemas/Error'))]
    #[OA\Response(response: '422', description: 'Input data has not been validated', content: new OA\JsonContent(ref: '#/components/schemas/Error'))]
    public function store(TournamentCreateRequest $request)
    {
        $tournament = new Tournament;

        $tournament->name = $request->name;
        $tournament->starts_at = $request->startsAt;
        $tournament->major = $request->major;
        $tournament->balancing = $request->balancing;
        $tournament->elo_multiplier = $request->eloMultiplier;
        $tournament->save();

        return new TournamentResource($tournament);
    }

    #[OA\Get(path: '/api/tournaments/{id}', summary: 'Get tournament', tags: ['Tournament'])]
    #[OA\Parameter(name: 'id', description: 'The ID of the tournament', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: '200', description: 'The required tournament', content: new OA\JsonContent(properties: [
        new OA\Property(property: 'data', ref: '#/components/schemas/Tournament', type: 'object'),
    ]))]
    #[OA\Response(response: '404', description: 'No tournament has been found with this ID', content: new OA\JsonContent(ref: '#/components/schemas/Error'))]
    public function show(Tournament $tournament)
    {
        $tournament->load('goldTeam');
        $tournament->load('silverTeam');
        $tournament->load('bronzeTeam');

        return new TournamentResource($tournament);
    }

    #[OA\Get(path: '/api/tournaments/{id}/games', summary: 'Get games linked to a tournament', tags: ['Game'])]
    #[OA\Parameter(name: 'id', description: 'The ID of the tournament', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'page', description: 'The page number', in: 'query', schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: '200', description: 'A paginated collection of games', content: new OA\JsonContent(ref: '#/components/schemas/GamePaginatedCollection'))]
    #[OA\Response(response: '404', description: 'No tournament has been found with this ID', content: new OA\JsonContent(ref: '#/components/schemas/Error'))]
    public function games(Tournament $tournament)
    {
        $games = Game::where('tournament_id', $tournament->id)->paginate();

        return GameResource::collection($games);
    }

    #[OA\Put(path: '/api/tournaments/{id}', summary: 'Update tournament', tags: ['Tournament'])]
    #[OA\RequestBody(ref: '#/components/requestBodies/TournamentUpdateRequest')]
    #[OA\Parameter(name: 'id', description: 'The ID of the team', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: '200', description: 'The updated team', content: new OA\JsonContent(properties: [
        new OA\Property(property: 'data', ref: '#/components/schemas/Team', type: 'object'),
    ]))]
    #[OA\Response(response: '400', description: 'Input format is incorrect', content: new OA\JsonContent(ref: '#/components/schemas/Error'))]
    #[OA\Response(response: '422', description: 'Input data has not been validated', content: new OA\JsonContent(ref: '#/components/schemas/Error'))]
    public function update(TournamentUpdateRequest $request, Tournament $tournament)
    {
        $tournament->name = $request->name;
        $tournament->gold_team_id = $request->goldTeamId;
        $tournament->silver_team_id = $request->silverTeamId;
        $tournament->bronze_team_id = $request->bronzeTeamId;
        $tournament->starts_at = $request->startsAt;
        $tournament->ends_at = $request->endsAt;

        $tournament->save();

        return new TournamentResource($tournament);
    }
}
