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
    <title>secure app </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <nav class="navbar bg-ligth navbar-expand-lg border-bottom border-body bg-body-tertiary" data-bs-theme="ligth">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fa-brands fa-twitter" style="color: #146ebe;"></i></a>
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


                </ul>
                <div class="d-flex">
                    <form class="d-flex" role="search">
                        <?php
                        if ($obj->loggedin($obj)) {
                            echo '<a href="/logout.php" class="btn btn-sm btn-outline-warning me-2">
                               logout
                            </a>';
                        } else {
                            echo '
                            <a class="btn btn-sm btn-outline-success me-2" href="Register.php">Register</a>
                            <a href="/login.php" class="btn btn-sm btn-outline-primary me-2">
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

    <div class="container main-container">
        <div class="row">
            <div class="col-3">

                <?php
                if ($obj->loggedin($obj) && $obj->acountVerified($obj)) {
                    echo '<div class="sidebar">
                    <div class="profile_image">
                        <img src="uploads/profile.jpg" alt="">
                    </div>
                    <div class="profile_info">
                        ahmadi zabiullah
                        <span class="post__headerSpecial">
                            <span class="material-icons post__badge"> verified
                            </span>
                        </span>
                    </div>
    
                    <div class="profile_menu">
                        <ul>
                            <li><a href="profile.php"><i class="fa-solid fa-address-card"></i> <i>Profile</i></a></li>
                            <li><a href="twits.php"><i class="fa-brands fa-twitter"></i> <i>twits</i></a></li>
                            <li><a href="friends.php"><i class="fa-solid fa-user-group"></i> <i>friends</i></a></li>
                            <li><a href=""><i class="fa-regular fa-message"></i> <i>messages</i></a></li>
                            <li><a href="setting.php"><i class="fa-solid fa-gear"></i> <i>settings</i></a></li>
    
                        </ul>
                    </div>
                </div> <!--end of sidebar -->';
                }


                ?>

            </div> <!--end of col-3 -->
            <div class="col-6">