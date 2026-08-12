<?php

// config for The3LabsTeam/NovaRedirectorSeo
return [
    'cache' => [
        /*
         * How long the rule set is kept in the cache store. Every enabled rule lives in a
         * single entry, refreshed automatically whenever a rule changes, so this only caps
         * how long a change made outside the model (a raw query, a database import) takes
         * to appear. Set it to null or 0 to read from the database on every request.
         */
        'ttl' => 60 * 60 * 24 * 7, // 7 days
    ],
];
