<?php

namespace App\Http;

use OpenApi\Attributes as OA;

#[OA\Info(version: '0.0', title: 'Tournoi des nations')]
#[OA\Server(url: 'http://localhost', description: 'Local development environment')]
#[OA\Server(url: 'https://tournoi-des-nations.ovh', description: 'Production environment')]
final readonly class OpenApi {}
