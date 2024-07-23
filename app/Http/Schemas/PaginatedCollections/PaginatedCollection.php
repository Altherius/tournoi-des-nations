<?php

namespace App\Http\Schemas\PaginatedCollections;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaginatedCollection',
    required: ['data', 'links', 'meta'],
    properties: [
        new OA\Property(property: 'links', required: ['first', 'last', 'prev', 'next'], properties: [
            new OA\Property(property: 'first', type: 'string', nullable: false),
            new OA\Property(property: 'last', type: 'string', nullable: false),
            new OA\Property(property: 'prev', type: 'string', nullable: true),
            new OA\Property(property: 'next', type: 'string', nullable: true),
        ], type: 'object'),
        new OA\Property(property: 'meta', required: ['current_page', 'last_page', 'from', 'to', 'per_page', 'total', 'links', 'path'], properties: [
            new OA\Property(property: 'current_page', type: 'integer', nullable: false),
            new OA\Property(property: 'last_page', type: 'integer', nullable: false),
            new OA\Property(property: 'from', type: 'integer', nullable: false),
            new OA\Property(property: 'to', type: 'integer', nullable: false),
            new OA\Property(property: 'per_page', type: 'integer', nullable: false),
            new OA\Property(property: 'total', type: 'integer', nullable: false),
            new OA\Property(property: 'links', type: 'array', items: new OA\Items(
                required: ['url', 'label', 'active'],
                properties: [
                    new OA\Property(property: 'url', type: 'string', nullable: true),
                    new OA\Property(property: 'label', type: 'string', nullable: false),
                    new OA\Property(property: 'active', type: 'boolean', nullable: false),
                ],
                type: 'object'
            ), nullable: false),
            new OA\Property(property: 'path', type: 'string', nullable: false),
        ], type: 'object'),
    ]
)]
abstract readonly class PaginatedCollection {}
