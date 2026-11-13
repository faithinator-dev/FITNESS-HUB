<?php
// 1. Enable CORS headers for Web & Mobile clients
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// 2. Handle HTTP OPTIONS preflight request from Web Browsers
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../config/database.php';

// 3. Parse requested URI path
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uriSegments = explode('/', trim($requestUri, '/'));

// Find route segment (e.g., /public/index.php/login)
$endpoint = preg_replace('/\.php$/i', '', end($uriSegments));

$database = new Database();
$db = $database->getConnection();

// 4. Basic Router Mapping
switch ($endpoint) {
    case 'login':
        require_once __DIR__ . '/../login.php';
        break;

    case 'members':
        require_once __DIR__ . '/../members.php';
        break;

    case 'register':
    case 'register_member':
        require_once __DIR__ . '/../register_member.php';
        break;

    case 'attendance':
        require_once __DIR__ . '/../attendance.php';
        break;

    case 'routines':
        require_once __DIR__ . '/../routines.php';
        break;

    case 'stats':
    case 'stat':
        require_once __DIR__ . '/../stat.php';
        break;

    default:
        $connectionInfo = $db->query(
            'SELECT DATABASE() AS database_name, VERSION() AS server_version'
        )->fetch();

        $response = [
            "status" => "success",
            "message" => "FitLife Central API Router and database connection are running.",
            "connection" => [
                "status" => "connected",
                "host" => Database::getConfig()['host'],
                "port" => Database::getConfig()['port'],
                "database" => $connectionInfo["database_name"],
                "user" => Database::getConfig()['username'],
                "server_version" => $connectionInfo["server_version"],
                "charset" => "utf8mb4"
            ],
            "available_endpoints" => ["login", "members", "register", "attendance", "routines", "stats"]
        ];

        $acceptHeader = $_SERVER['HTTP_ACCEPT'] ?? '';
        $wantsJson = strpos($acceptHeader, 'application/json') !== false;

        if ($wantsJson) {
            echo json_encode($response);
            break;
        }

        header("Content-Type: text/html; charset=UTF-8");

        $apiBaseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        $endpointDetails = [
            [
                'method' => 'POST',
                'path' => '/login',
                'label' => 'Authentication',
                'description' => 'Sign in members and issue access credentials.',
                'class' => 'violet'
            ],
            [
                'method' => 'GET',
                'path' => '/members',
                'label' => 'Members',
                'description' => 'Browse and manage the FitLife member directory.',
                'class' => 'blue'
            ],
            [
                'method' => 'POST',
                'path' => '/register',
                'label' => 'Registration',
                'description' => 'Create a new member profile in the system.',
                'class' => 'green'
            ],
            [
                'method' => 'GET',
                'path' => '/attendance',
                'label' => 'Attendance',
                'description' => 'Track gym visits and member check-ins.',
                'class' => 'orange'
            ],
            [
                'method' => 'GET',
                'path' => '/routines',
                'label' => 'Routines',
                'description' => 'Deliver workout routines and training plans.',
                'class' => 'pink'
            ],
            [
                'method' => 'GET',
                'path' => '/stats',
                'label' => 'Statistics',
                'description' => 'View fitness and operational insights.',
                'class' => 'cyan'
            ]
        ];

        $endpointCards = '';
        foreach ($endpointDetails as $endpointDetail) {
            $endpointPath = $apiBaseUrl . $endpointDetail['path'];
            $endpointCards .= sprintf(
                '<a class="endpoint-card" href="%s">
                    <span class="endpoint-icon %s" aria-hidden="true">%s</span>
                    <span class="endpoint-content">
                        <span class="endpoint-topline"><strong>%s</strong><span class="method">%s</span></span>
                        <span class="endpoint-description">%s</span>
                        <span class="endpoint-path">%s</span>
                    </span>
                    <span class="arrow" aria-hidden="true">↗</span>
                </a>',
                htmlspecialchars($endpointPath, ENT_QUOTES, 'UTF-8'),
                $endpointDetail['class'],
                strtoupper(substr($endpointDetail['label'], 0, 1)),
                htmlspecialchars($endpointDetail['label'], ENT_QUOTES, 'UTF-8'),
                $endpointDetail['method'],
                htmlspecialchars($endpointDetail['description'], ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($endpointPath, ENT_QUOTES, 'UTF-8')
            );
        }

        $databaseName = htmlspecialchars($response['connection']['database'], ENT_QUOTES, 'UTF-8');
        $serverVersion = htmlspecialchars($response['connection']['server_version'], ENT_QUOTES, 'UTF-8');
        $currentUrl = htmlspecialchars($apiBaseUrl ?: '/', ENT_QUOTES, 'UTF-8');

        echo <<<HTML
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#10172a">
    <title>FitLife API | Control Center</title>
    <style>
        :root {
            color-scheme: dark;
            --ink: #edf2ff;
            --muted: #9da9c5;
            --subtle: #687694;
            --panel: rgba(22, 32, 57, .78);
            --line: rgba(148, 163, 184, .16);
            --accent: #70e1b5;
            --accent-strong: #32c58a;
            --background: #090e1b;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-width: 320px;
            color: var(--ink);
            background: var(--background);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            line-height: 1.5;
        }

        body::before, body::after {
            position: fixed;
            z-index: -1;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            content: "";
            filter: blur(80px);
            opacity: .14;
        }

        body::before { top: -180px; right: -100px; background: #45b9ff; }
        body::after { bottom: -240px; left: -120px; background: #37c98b; }
        a { color: inherit; text-decoration: none; }
        .shell { width: min(1120px, calc(100% - 40px)); margin: 0 auto; }
        .nav { display: flex; align-items: center; justify-content: space-between; padding: 28px 0; }
        .brand { display: flex; align-items: center; gap: 12px; font-weight: 750; letter-spacing: -.02em; }
        .brand-mark {
            display: grid; width: 40px; height: 40px; place-items: center;
            border: 1px solid rgba(112, 225, 181, .45); border-radius: 12px;
            color: #07151a; background: var(--accent); font-weight: 900;
        }
        .brand small { display: block; margin-top: 1px; color: var(--subtle); font-size: 11px; font-weight: 600; letter-spacing: .12em; text-transform: uppercase; }
        .version { padding: 7px 11px; border: 1px solid var(--line); border-radius: 999px; color: var(--muted); font-size: 12px; }
        .hero { display: grid; grid-template-columns: 1.2fr .8fr; gap: 50px; align-items: end; padding: 68px 0 56px; }
        .eyebrow { display: flex; align-items: center; gap: 9px; color: var(--accent); font-size: 12px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; }
        .eyebrow::before { width: 25px; height: 1px; background: var(--accent); content: ""; }
        h1 { max-width: 680px; margin: 18px 0 20px; font-size: clamp(42px, 6vw, 78px); line-height: .98; letter-spacing: -.065em; }
        .hero-copy { max-width: 560px; margin: 0; color: var(--muted); font-size: 18px; }
        .status-panel { padding: 23px; border: 1px solid var(--line); border-radius: 20px; background: linear-gradient(145deg, rgba(33, 48, 82, .78), var(--panel)); box-shadow: 0 22px 65px rgba(0, 0, 0, .2); }
        .status-heading { display: flex; justify-content: space-between; align-items: center; color: var(--muted); font-size: 12px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; }
        .live { display: inline-flex; align-items: center; gap: 7px; color: var(--accent); letter-spacing: 0; text-transform: none; }
        .live::before { width: 7px; height: 7px; border-radius: 50%; background: var(--accent); box-shadow: 0 0 0 5px rgba(112, 225, 181, .12); content: ""; }
        .status-value { margin: 20px 0 3px; font-size: 30px; letter-spacing: -.04em; }
        .status-note { margin: 0; color: var(--muted); font-size: 13px; }
        .status-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 25px; padding-top: 17px; border-top: 1px solid var(--line); }
        .status-grid span { display: block; color: var(--subtle); font-size: 11px; text-transform: uppercase; letter-spacing: .09em; }
        .status-grid strong { display: block; margin-top: 4px; overflow: hidden; color: var(--ink); font-size: 13px; text-overflow: ellipsis; white-space: nowrap; }
        .section-heading { display: flex; justify-content: space-between; align-items: end; margin-bottom: 18px; }
        h2 { margin: 0; font-size: 21px; letter-spacing: -.03em; }
        .section-heading p { margin: 0; color: var(--subtle); font-size: 13px; }
        .endpoint-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .endpoint-card { display: flex; align-items: center; gap: 14px; min-height: 108px; padding: 17px; border: 1px solid var(--line); border-radius: 16px; background: rgba(19, 28, 50, .58); transition: transform .2s ease, border-color .2s ease, background .2s ease; }
        .endpoint-card:hover, .endpoint-card:focus-visible { transform: translateY(-2px); border-color: rgba(112, 225, 181, .55); background: rgba(32, 47, 76, .9); outline: none; }
        .endpoint-icon { display: grid; flex: 0 0 42px; width: 42px; height: 42px; place-items: center; border-radius: 12px; color: #08131d; font-size: 14px; font-weight: 850; }
        .violet { background: #c4a7ff; } .blue { background: #86c9ff; } .green { background: #80e5b7; } .orange { background: #ffc078; } .pink { background: #ff9fc6; } .cyan { background: #84e2e4; }
        .endpoint-content { min-width: 0; flex: 1; }
        .endpoint-topline { display: flex; align-items: center; gap: 9px; }
        .endpoint-topline strong { font-size: 15px; }
        .method { color: var(--accent); font-size: 10px; font-weight: 800; letter-spacing: .08em; }
        .endpoint-description { display: block; margin-top: 4px; overflow: hidden; color: var(--muted); font-size: 12px; text-overflow: ellipsis; white-space: nowrap; }
        .endpoint-path { display: block; margin-top: 8px; color: var(--subtle); font-family: ui-monospace, SFMono-Regular, Consolas, monospace; font-size: 11px; }
        .arrow { color: var(--subtle); font-size: 21px; }
        .footer { display: flex; justify-content: space-between; gap: 20px; margin: 70px 0 30px; padding-top: 18px; border-top: 1px solid var(--line); color: var(--subtle); font-size: 12px; }
        .footer a { color: var(--accent); }
        @media (max-width: 760px) {
            .shell { width: min(100% - 28px, 600px); }
            .hero { grid-template-columns: 1fr; gap: 30px; padding: 45px 0 42px; }
            h1 { font-size: clamp(43px, 14vw, 66px); }
            .hero-copy { font-size: 16px; }
            .endpoint-grid { grid-template-columns: 1fr; }
            .section-heading { align-items: start; flex-direction: column; gap: 5px; }
            .footer { flex-direction: column; gap: 7px; margin-top: 48px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <header class="shell nav">
        <a class="brand" href="$currentUrl" aria-label="FitLife API home">
            <span class="brand-mark" aria-hidden="true">F</span>
            <span>FitLife <small>Central API</small></span>
        </a>
        <span class="version">v1 · production</span>
    </header>
    <main class="shell">
        <section class="hero" aria-labelledby="page-title">
            <div>
                <div class="eyebrow">System online</div>
                <h1 id="page-title">Build healthier routines, together.</h1>
                <p class="hero-copy">The FitLife backend is ready to power member management, attendance, routines, and fitness insights.</p>
            </div>
            <aside class="status-panel" aria-label="System status">
                <div class="status-heading"><span>Connection status</span><span class="live">Operational</span></div>
                <div class="status-value">Database connected</div>
                <p class="status-note">All core services are responding normally.</p>
                <div class="status-grid">
                    <div><span>Database</span><strong>$databaseName</strong></div>
                    <div><span>Encoding</span><strong>UTF-8 / utf8mb4</strong></div>
                    <div><span>Engine</span><strong>MySQL</strong></div>
                    <div><span>Version</span><strong>$serverVersion</strong></div>
                </div>
            </aside>
        </section>
        <section aria-labelledby="endpoints-title">
            <div class="section-heading">
                <h2 id="endpoints-title">Explore the API</h2>
                <p>Choose an endpoint to begin testing</p>
            </div>
            <div class="endpoint-grid">$endpointCards</div>
        </section>
        <footer class="footer">
            <span>FitLife Central API · Secure fitness infrastructure</span>
            <a href="$currentUrl">GET $currentUrl</a>
        </footer>
    </main>
</body>
</html>
HTML;
        break;
}
