<?php

namespace App\Http\Controllers;

use App\Http\Requests\Team\TeamCreateRequest;
use App\Http\Requests\Team\TeamUpdateRequest;
use App\Http\Resources\GameResource;
use App\Http\Resources\TeamResource;
use App\Models\Game;
use App\Models\Team;
use OpenApi\Attributes as OA;

class TeamController extends Controller
{
    #[OA\Get(path: '/api/teams', summary: 'Get collection of teams', tags: ['Team'])]
    #[OA\Parameter(name: 'page', in: 'query', description: 'The page number', schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: '200', description: 'A paginated collection of teams', content: new OA\JsonContent(ref: '#/components/schemas/TeamPaginatedCollection'))]
    public function index()
    {
        return TeamResource::collection(Team::with('gamesHosting')->with('gamesReceiving')->paginate());
    }

    #[OA\Post(path: '/api/teams', summary: 'Create team', tags: ['Team'])]
    #[OA\RequestBody(ref: '#/components/requestBodies/TeamCreateRequest')]
    #[OA\Response(response: '201', description: 'The created team', content: new OA\JsonContent(properties: [
        new OA\Property(property: 'data', ref: '#/components/schemas/Team', type: 'object'),
    ]))]
    #[OA\Response(response: '400', description: 'Input format is incorrect', content: new OA\JsonContent(ref: '#/components/schemas/Error'))]
    #[OA\Response(response: '422', description: 'Input data has not been validated', content: new OA\JsonContent(ref: '#/components/schemas/Error'))]
    public function store(TeamCreateRequest $request)
    {
        $team = new Team;

        $team->name = $request->name;
        $team->country_code = $request->countryCode;
        $team->region = $request->region;

        $team->save();
        $team->refresh();

        return new TeamResource($team);
    }

    #[OA\Get(path: '/api/teams/{id}', summary: 'Get team', tags: ['Team'])]
    #[OA\Parameter(name: 'id', description: 'The ID of the team', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: '200', description: 'The required team', content: new OA\JsonContent(properties: [
        new OA\Property(property: 'data', ref: '#/components/schemas/Team', type: 'object'),
    ]))]
    #[OA\Response(response: '404', description: 'No team has been found with this ID', content: new OA\JsonContent(ref: '#/components/schemas/Error'))]
    public function show(Team $team)
    {
        return new TeamResource($team);
    }

    #[OA\Get(path: '/api/teams/{id}/games', summary: 'Get games linked to a team', tags: ['Game'])]
    #[OA\Parameter(name: 'page', in: 'query', description: 'The page number', schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'id', description: 'The ID of the team', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: '200', description: 'A paginated collection of games', content: new OA\JsonContent(ref: '#/components/schemas/GamePaginatedCollection'))]
    #[OA\Response(response: '404', description: 'No team has been found with this ID', content: new OA\JsonContent(ref: '#/components/schemas/Error'))]
    public function games(Team $team)
    {
        $games = Game::where('hosting_team_id', $team->id)->orWhere('receiving_team_id', $team->id)->paginate();

        return GameResource::collection($games);
    }

    #[OA\Put(path: '/api/teams/{id}', summary: 'Update team', tags: ['Team'])]
    #[OA\RequestBody(ref: '#/components/requestBodies/TeamUpdateRequest')]
    #[OA\Parameter(name: 'id', description: 'The ID of the team', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: '200', description: 'The updated team', content: new OA\JsonContent(properties: [
        new OA\Property(property: 'data', ref: '#/components/schemas/Team', type: 'object'),
    ]))]
    #[OA\Response(response: '400', description: 'Input format is incorrect', content: new OA\JsonContent(ref: '#/components/schemas/Error'))]
    #[OA\Response(response: '422', description: 'Input data has not been validated', content: new OA\JsonContent(ref: '#/components/schemas/Error'))]
    public function update(TeamUpdateRequest $request, Team $team)
    {
        $team->name = $request->name;
        $team->country_code = $request->countryCode;
        $team->region = $request->region;

        $team->save();
        $team->refresh();

        return new TeamResource($team);
    }
}
