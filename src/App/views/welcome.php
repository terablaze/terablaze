<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $pageTitle ?? 'Welcome'; ?></title>
    <style type="text/css">
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 1rem;
            font-weight: normal;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
        }
        .main-text {
            font-size: 6rem;
            font-weight: 300;
            line-height: 1.1;
            margin-top: 0;
            color: #4c9fab;
        }
        .sub-text {
            color: #215459;
        }
        .m-0 {
            margin: 0 !important;
        }
        .mt-2 {
            margin-top: 0.5rem !important;
        }

        .main-block {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center !important;
        }
        .link {
            text-decoration: none;
            color: #4c9fab;
            transition: 0.5s;
        }
        .link:hover {
            color: #61cbda;
        }
    </style>
</head>
<body>
<div class="main-block">
    <p class="sub-text m-0">Welcome to</p>
    <h1 class="main-text m-0">TeraBlaze</h1>
    <p class="sub-text m-0 mt-2">Version: <?php echo TERABLAZE_VERSION; ?> </p>
</div>
<p style="position: absolute; bottom: 0; left: 10px">View project on <a class="link" href="https://www.github.com/terablaze/terablaze" target="_blank">GitHub</a> </p>
<p style="position: absolute; bottom: 0; right: 10px">URL: <?php echo $url ?? ""; ?> </p>
</body>
</html>