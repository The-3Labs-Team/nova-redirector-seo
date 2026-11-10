<?php

// config for The3LabsTeam/NovaRedirectorSeo
return [
    'cache' => [
        /*
         * How long the regex rules are kept in the cache store, in a single entry.
         * Exact rules are always read from the database. Null or 0 disables the cache.
         */
        'ttl' => 60 * 60 * 24 * 7, // 7 days
    ],
];
