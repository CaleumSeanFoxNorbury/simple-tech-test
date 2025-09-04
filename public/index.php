<?php
// Minimal PHP front controller and passthrough to avoid CORS in the browser.
// Add your Primary Role comment in the fetch section below explaining how you found it.

$apiBase = 'https://directory.spineservices.nhs.uk/ORD/2-0-0/organisations';

function build_query_url($postcode, $name, $roleParamKey, $roleParamValue, $limit = 50, $offset = 0) {
    // name should be a contains match using wildcards for better results, e.g. %...%
    $params = [
        'PostCode' => $postcode,
        'Name'     => '%' . $name . '%',
        'Status'   => 'Active',
        // use either PrimaryRoleId or Roles based on $roleParamKey
        $roleParamKey => $roleParamValue,
        'Limit'    => max(1, min(1000, (int)$limit)),
        'Offset'   => max(0, (int)$offset),
        '_format'  => 'json'
    ];
    return $GLOBALS['apiBase'] . '?' . http_build_query($params);
}

if (isset($_GET['action']) && $_GET['action'] === 'search') {
    header('Content-Type: application/json');
    
    // is this enough security?
    $postcode  = isset($_GET['postcode']) ? trim($_GET['postcode']) : '';
    $name      = isset($_GET['name']) ? trim($_GET['name']) : '';
    $roleKey   = isset($_GET['roleKey']) ? trim($_GET['roleKey']) : 'PrimaryRoleId'; // or 'Roles'
    $roleVal   = isset($_GET['roleVal']) ? trim($_GET['roleVal']) : '';              // choose a role value
    $limit     = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
    $offset    = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

    if ($postcode === '' && $name === '') {
        echo json_encode(['Organisations' => [], 'error' => 'Provide postcode or name']);
        exit;
    }
    if ($roleVal === '') {
        echo json_encode(['Organisations' => [], 'error' => 'Provide a role value']);
        exit;
    }

    $url = build_query_url($postcode, $name, $roleKey, $roleVal, $limit, $offset);

    $ctx = stream_context_create([
        'http' => [
            'method'  => 'GET',
            'timeout' => 8
        ]
    ]);

    $raw = @file_get_contents($url, false, $ctx);
    if ($raw === false) {
        echo json_encode(['Organisations' => [], 'error' => 'Upstream request failed']);
        exit;
    }

    echo $raw;
    exit;
}

?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>NHS ODS search</title>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <link rel="stylesheet" href="./styles.css"/>
    </head>
    <body>
        <main class="container">
            <h1>NHS ODS search</h1>
            <p class="hint">Enter a postcode and a name fragment. Choose a role key and value. Results show Active only.</p>

            <form id="search-form" class="controls">
            <label>
                Postcode
                <input id="postcode" name="postcode" placeholder="e.g. EX15"/>
            </label>
            <label>
                Name contains
                <input id="name" name="name" placeholder="e.g. Blackdown"/>
            </label>
            <label>
                Role key
                <select id="roleKey" name="roleKey">
                <option value="PrimaryRoleId">PrimaryRoleId</option>
                <option value="Roles">Roles</option>
                </select>
            </label>
            <label>
                Role value
                <input id="roleVal" name="roleVal" placeholder="e.g. RO###"/>
            </label>
            <button type="submit">Search</button>
            </form>

            <section class="filters">
            <input id="clientFilter" placeholder="Filter cards by name"/>
            </section>

            <section id="results" class="grid" aria-live="polite"></section>

            <div id="pager" class="pager" hidden>
                <button id="prevBtn">Prev</button>
                <span id="pageInfo"></span>
                <button id="nextBtn">Next</button>
            </div>
        </main>

        <script src="./main.js" type="module"></script>
    </body>
</html>