<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Destination</title>
    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #10b981;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text: #1e293b;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 600px;
            background-color: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            padding: 50px;
            text-align: center;
        }

        h2 {
            color: var(--text);
            margin-bottom: 30px;
            font-weight: 600;
        }

        .button-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
            text-decoration: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .login-btn {
            background-color: #f1f5f9;
            color: var(--primary);
        }

        .login-btn:hover {
            background-color: var(--primary);
            color: white;
            transform: translateY(-5px);
        }

        .to-btn {
            background-color: #f1f5f9;
            color: var(--secondary);
        }

        .to-btn:hover {
            background-color: var(--secondary);
            color: white;
            transform: translateY(-5px);
        }

        h3 { margin: 0; font-size: 1.2rem; }
        p { font-size: 0.9rem; margin-top: 8px; opacity: 0.8; }

    </style>
</head>
<body>

    <div class="container">
        
        <div class="button-group">
            <a href="../TO_MGB/login.php" class="btn login-btn">
                <h3>Login</h3>
            </a>

            <a href="../TO_MGB/TO.php" class="btn to-btn">
                <h3>Open TO</h3>
            </a>
        </div>
    </div>

</body>
</html> 