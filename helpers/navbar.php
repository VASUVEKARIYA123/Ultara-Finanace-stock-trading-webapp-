</head>

<body>

    <section class="p-0">

        <img class="center h" src="/revolution/image/basic-trading1.jpg" alt="">
    </section>
    <nav>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="/revolution/home.php"><img class="logo" src="/revolution/image/logo1.png"
                        alt="" style="width: 280px;height: 40px;"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <?php
                    if ( isset($_SESSION["user_id"]) ): ?>
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page"
                                    href="/revolution/features/wallet.php">Wallet</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link" href="/revolution/features/quote.php">Buy</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/revolution/features/history.php">History</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/revolution/features/news.php">News</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/revolution/login/logout.php">Logout</a>
                            </li>
                        </ul>
                    <?php else: ?>
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item"><a class="nav-link" href="/revolution/login/register.php">Register</a></li>
                            <li class="nav-item"><a class="nav-link" href="/revolution/login/login.php">Log In</a></li>
                        </ul>
                    <?php endif ?>
                </div>
            </div>
        </nav>
        </div>
    </nav>
    <main class="container-fluid py-5 text-center">