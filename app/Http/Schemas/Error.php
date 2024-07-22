<?php

namespace App\Http\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Error',
    required: ['message'],
    properties: [
        new OA\Property(property: 'message', description: 'A message describing the error', type: 'string', nullable: false),
        new OA\Property(property: 'errors', description: 'A list of validation errors', type: 'object', nullable: false),
    ]
)]
abstract readonly class Error {}
