<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            height: 100vh;
            color: #777;
            font-weight: 300;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 1024px;
            padding: 2rem;
            background: #fff;
            text-align: center;
            border: 1px solid #efefef;
            border-radius: 0.5rem;
            position: relative;
        }

        h1 {
            font-weight: lighter;
            letter-spacing: normal;
            font-size: 3rem;
            margin-top: 0;
            margin-bottom: 0;
            color: #222;
        }

        code {
            background: #fafafa;
            border: 1px solid #efefef;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            display: block;
            margin-top: 0.5rem;
        }

        p {
            margin-top: 1.5rem;
        }

        a {
            color: #dd4814;
        }
    </style>

</head>

<body>
    <div class="container">
        <h1>404 Not Found</h1>
        <p>The requested page could not be found.</p>
        <p>
            Link not found:
            <code>
                <?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>
            </code>
        </p>
        <p>
            Page not found:
            <code>
                <?php echo isset($file) ? htmlspecialchars($file) : ''; ?>
            </code>
        </p>
        <p><a href="<?= baseUrl(''); ?>">
                Go to Home Page
            </a></p>
    </div>

</body>

</html>