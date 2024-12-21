<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tournament\TournamentCreateRequest;
use App\Http\Requests\Tournament\TournamentGenerateRosterRequest;
use App\Http\Requests\Tournament\TournamentUpdateRequest;
use App\Http\Resources\GameResource;
use App\Http\Resources\TeamResource;
use App\Http\Resources\TournamentResource;
use App\Models\Game;
use App\Models\Team;
use App\Models\Tournament;
use App\Services\RosterBuilder\RosterBuilder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;
use Random\RandomException;

class TournamentController extends Controller
{
    public function __construct(protected RosterBuilder $rosterBuilder) {}

    #[OA\Get(path: '/api/tournaments', summary: 'Get collection of tournaments', tags: ['Tournament'])]
    #[OA\Parameter(name: 'page', description: 'The page number', in: 'query', schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: '200', description: 'A paginated collection of tournaments', content: new OA\JsonContent(ref: '#/components/schemas/TournamentPaginatedCollection'))]
    public function index(): AnonymousResourceCollection
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
    public function store(TournamentCreateRequest $request): TournamentResource
    {
        $tournament = new Tournament;

        $tournament->name = $request->name;
        $tournament->starts_at = $request->startsAt;
        $tournament->save();

        return new TournamentResource($tournament);
    }

    #[OA\Get(path: '/api/tournaments/{id}', summary: 'Get tournament', tags: ['Tournament'])]
    #[OA\Parameter(name: 'id', description: 'The ID of the tournament', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: '200', description: 'The required tournament', content: new OA\JsonContent(properties: [
        new OA\Property(property: 'data', ref: '#/components/schemas/Tournament', type: 'object'),
    ]))]
    #[OA\Response(response: '404', description: 'No tournament has been found with this ID', content: new OA\JsonContent(ref: '#/components/schemas/Error'))]
    public function show(Tournament $tournament): TournamentResource
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
    public function games(Tournament $tournament): AnonymousResourceCollection
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
    public function update(TournamentUpdateRequest $request, Tournament $tournament): TournamentResource
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

    /**
     * @throws RandomException
     */
    public function generateRoster(TournamentGenerateRosterRequest $request): AnonymousResourceCollection
    {
        $guaranteedTeams = [];
        foreach ($request->guaranteedTeams as $team) {
            $guaranteedTeams[] = Team::find($team);
        }

        $ticketTeams = [];
        foreach ($request->ticketTeams as $team) {
            $ticketTeam = new \stdClass();
            $ticketTeam->team = Team::find($team['teamId']);
            $ticketTeam->ticketsCount = $team['ticketsCount'];
            $ticketTeams[] = $ticketTeam;
        }

        $teams = $this->rosterBuilder->buildRoster(
            $guaranteedTeams,
            $ticketTeams,
            $request->availableSeats
        );

        return TeamResource::collection($teams);
    }
}
