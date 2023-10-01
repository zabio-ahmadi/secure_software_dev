<?php
ob_start();
session_start();


require_once 'connection.php';
$obj = new Connection();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <nav class="navbar bg-dark navbar-expand-lg border-bottom border-body bg-body-tertiary" data-bs-theme="dark">
        <div class="container">
            <a class="navbar-brand" href="index.php"> secure app </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Acueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="posts.php">post</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="Register.php">Register</a>
                    </li>

                </ul>
                <div class="d-flex">
                    <form class="d-flex" role="search">
                        <!-- <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
                        <button class="btn btn-sm btn-outline-success me-2" type="submit">
                            Search
                        </button> -->
                        <?php
                        if ($obj->loggedin()) {
                            echo '<a href="/logout.php" class="btn btn-sm btn-outline-warning me-2">
                               logout
                            </a>';
                        } else {
                            echo '<a href="/login.php" class="btn btn-sm btn-outline-primary me-2">
                                login
                            </a>';
                        }
                        ?>

                        </button>
                    </form>


                </div>

            </div>

        </div>
    </nav>