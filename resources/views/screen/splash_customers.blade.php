<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PICKING</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #0b1120;
            color: #f1f5f9;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .splash-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
            animation: fadeIn 2s ease-in-out;
        }

        .logo-wrapper {
            width: 120px;
            height: 120px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo-wrapper svg {
            width: 100%;
            height: 100%;
        }

        .splash-title {
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: 4px;
            color: #f8fafc;
        }

        .splash-name {
            font-size: 1.0rem;
            font-weight: 700;
            letter-spacing: 4px;
            color: #cfcfcf;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

    <div class="splash-container">
        <h1 class="splash-title">PICKING</h1>
        <div class="logo-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.0" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-icon lucide-package">
                <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/>
                <path d="M12 22V12"/>
                <polyline points="3.29 7 12 12 20.71 7"/>
                <path d="m7.5 4.27 9 5.15"/>
            </svg>
        </div>
        <h1 class="splash-name">customers screen</h1>
    </div>

</body>
</html>