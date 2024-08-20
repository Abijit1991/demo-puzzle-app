<?php

return [
    // Roles
    'ROLES' => [
        'ADMIN' => 'admin',
        'STUDENT' => 'student'
    ],

    // Success Message for the Seeders
    'SEEDER_SUCCESS_MSG' => [
        'ROLE_SEEDER' => 'Roles seeding completed successfully.',
        'STUDENT_SEEDER' => 'Students seeding completed successfully.',
        'PUZZLE_SEEDER' => 'Puzzles seeding completed successfully.',
    ],

    // Free API endpoint
    // Source: https://dictionaryapi.dev/
    // Endpoint: https://api.dictionaryapi.dev/api/v2/entries/en/<word>
    // Note: We have to replace '<word>' in the endpoint with the actual response word.
    'FREE_DICTIONARY_API' => 'https://api.dictionaryapi.dev/api/v2/entries/en/',

    // Top Scores Limit
    'TOP_SCORERS_LIMIT' => 10,
];
