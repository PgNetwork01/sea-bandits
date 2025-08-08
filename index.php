<?php
session_start();
header("Access-Control-Allow-Credentials: true");

// // Debugging: Check if session variables are set
// if (isset($_SESSION['username'])) {
//     echo "Username: " . htmlspecialchars($_SESSION['username']) . "<br>";
// } else {
//     echo "User is not logged in.<br>";
// }

// if (isset($_SESSION['profile_image'])) {
//     echo "Profile Image: " . htmlspecialchars($_SESSION['profile_image']) . "<br>";
// } else {
//     echo "Profile image not set.<br>";
// }
// $output = shell_exec('python bot.py');
// echo $output;

include('includes/db.php');

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql = "UPDATE users SET last_active = NOW() WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();
}
// Prepare a SQL query to fetch the title
$sql = "SELECT title FROM blog_posts ORDER BY id DESC LIMIT 1"; // Fetch the latest post's title

// Execute the query
$result = $conn->query($sql);

// Check if the query was successful and the result contains data
if ($result->num_rows > 0) {
    // Fetch the title
    $row = $result->fetch_assoc();
    $title = $row['title'];
} else {
    $title = "No posts found"; // Fallback if no posts exist
}


// Prepare a SQL query to fetch the title
$sql = "SELECT created_at FROM blog_posts ORDER BY id DESC LIMIT 1"; // Fetch the latest post's title

// Execute the query
$result = $conn->query($sql);

// Check if the query was successful and the result contains data
if ($result->num_rows > 0) {
    // Fetch the title
    $row = $result->fetch_assoc();
    $time = $row['created_at'];
} else {
    $time = "No posts found"; // Fallback if no posts exist
}

$sql = "SELECT created_at FROM blog_posts ORDER BY id DESC LIMIT 2"; // Fetch the latest post's title

// Execute the query
$result = $conn->query($sql);

// Check if the query was successful and the result contains data
if ($result->num_rows == 2) {
    // Fetch the title
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $time2 = $rows[1]['created_at'];
} else {
    $time2 = "No posts found"; // Fallback if no posts exist
}

$sql = "SELECT content FROM blog_posts ORDER BY id DESC LIMIT 1"; // Fetch the latest post's title

// Execute the query
$result = $conn->query($sql);

// Check if the query was successful and the result contains data
if ($result->num_rows > 0) {
    // Fetch the title
    $row = $result->fetch_assoc();
    $body = $row['content'];
} else {
    $body = "No posts found"; // Fallback if no posts exist
}

$sql = "SELECT id FROM blog_posts ORDER BY id DESC LIMIT 1"; // Fetch the latest post's title

// Execute the query
$result = $conn->query($sql);

// Check if the query was successful and the result contains data
if ($result->num_rows > 0) {
    // Fetch the title
    $row = $result->fetch_assoc();
    $id = $row['id'];
} else {
    $id = "No id found"; // Fallback if no posts exist
}

$sql = "SELECT title, description FROM blog_posts ORDER BY id DESC LIMIT 1"; // Fetch the latest post

// Execute the query
$result = $conn->query($sql);

// Check if the query was successful and the result contains data
if ($result->num_rows > 0) {
    // Fetch the title and content
    $row = $result->fetch_assoc();
    $title = $row['title'];
    $description = $row['description'];

    // Split the content into words
    $words = explode(' ', $description);

    // Limit the content to the first 15 words
    $limited_content = implode(' ', array_slice($words, 0, 20));
} else {
    $title = "No posts found"; // Fallback if no posts exist
    $limited_content = "";
}

$sql = "SELECT id FROM blog_posts ORDER BY id DESC LIMIT 2"; // Fetch the two latest posts

// Execute the query
$result = $conn->query($sql);

// Check if the query was successful and the result contains data
if ($result->num_rows == 2) {
    // Fetch the results into an array
    $rows = $result->fetch_all(MYSQLI_ASSOC);

    // The second latest post's ID
    $id2 = $rows[1]['id'];
} else {
    $id2 = "No id found"; // Fallback if fewer than 2 posts exist
}


$sql = "SELECT title, description FROM blog_posts ORDER BY id DESC LIMIT 2"; // Fetch the latest post

// Execute the query
$result = $conn->query($sql);

// Check if the query was successful and the result contains data
if ($result->num_rows == 2) {
    // Fetch the title and content
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $title2 = $rows[1]['title'];
    $description2 = $rows[1]['description'];

    // Split the content into words
    $words2 = explode(' ', $description2);

    // Limit the content to the first 15 words
    $limited_content2 = implode(' ', array_slice($words2, 0, 5));
} else {
    $title2 = "No posts found"; // Fallback if no posts exist
    $limited_content2 = "";
}
// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sea Bandits</title>
    <link rel="icon" href="assests/img-logo.png" type="image/png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="var.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bungee+Shade&family=Creepster&family=Pirata+One&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/css2?family=Bungee+Inline&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Englebert&display=swap" rel="stylesheet">
</head>

<body>
    <div id="content">
        <!-- Nav Bar-->
        <nav style="font-family: Montserrat-MetroMedium, sans-serif;font-weight: 900;">
            <img src="assests/logo.png" alt="Logo" class="logo">

            <!-- Ensure this path is correct -->
            <ul class="nav-links">
                <li><a href="index.php" class="n1">Home</a></li>
                <li><a href="#about" class="n1">About</a></li>
                <li><a href="/community.php" class="n1"><span class="material-symbols-outlined">
                            arrow_drop_down
                        </span>Community</a></li>
                <li><a href="faq.html" class="n1"><span class="material-symbols-outlined">
                            arrow_drop_down
                        </span>FAQ</a></li>
                <li class="li1" style=" background-repeat: no-repeat; background-size: contain; background-position: center;">
                    <a href="#download"><span style="color: dodgerblue;" class="material-symbols-outlined">
                            download
                        </span><span style="color: dodgerblue; padding-right: 8px;">Download</span></a>
                </li>

                <?php if (isset($_SESSION['username'])): ?>
                    <!-- User is logged in, show user logo -->
                    <?php if (isset($_SESSION['profile_image']) && !empty($_SESSION['profile_image'])): ?>
                        <li style="border:none;" onclick="togglePopup()">
                            <img src="<?php echo htmlspecialchars($_SESSION['profile_image']); ?>" alt="User Logo" style="padding: 15px; width: 32px; height: 32px; border-radius: 50%; background: url(assests/pfp-bg.png); background-size: contain; background-repeat: no-repeat;">
                        </li>
                    <?php else: ?>
                        <li style="border:none;" onclick="togglePopup()">
                            <img src="uploads/default_profile.png" alt="User Logo" style="padding: 10px; width: 25px; height: 25px; border-radius: 50%; background: url(assests/pfp-bg.png); background-size: contain; background-repeat: no-repeat;">
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- User is not logged in, show login link -->
                    <li class="li2" style=" background-repeat: no-repeat; background-size: contain; background-position: center;">
                        <a id="popupBtn" style="cursor: pointer;">
                            <span style="color: var(--dodgerred);" class="material-symbols-outlined">person</span>
                            <span style="color: var(--dodgerred);">Login</span>
                        </a>
                    </li>
                <?php endif; ?>

                <li>
                    <span class="open-nav" onclick="openNav()">&#9776;</span>
                </li>
            </ul>
        </nav>

        <!-- Side Navigation -->
        <div id="mySidenav1" class="sidenav1">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="index.php"><i class="fa fa-home"></i> Home</a>
            <a href="faq.html"><i class="fa fa-briefcase"></i> FAQ</a>
            <a href="#about"><i class="fa fa-users"></i> About</a>
            <a href="#contact"><i class="fa fa-envelope"></i> Contact</a>
        </div>

        <div class="welcome">
            <h1 style="font-family: Anton, sans-serif; font-weight: 900; ">
                Sea Bandits
            </h1>
            <hr style="border-top: 1px soild #b3b3b3; width:36%;">
            <hr style="border-top: 1px solid #b3b3b3; width:50%;">
            <p style=" font-family: Dosis, sans-serif; font-optical-sizing: auto; word-spacing: 1px; font-size: small; width: auto;" class="description">Sea Bandits is a free-to-play pirate MMORPG featuring intense ship battles, treasure hunts, and fair gameplay with no pay-to-win elements. Join the adventure on the high seas!</p>
            <li class="li1" style="background-position: center;background: transparent;display: flex;flex-direction: row;justify-content: flex-end;align-content: center;align-items: flex-end;flex-wrap: nowrap;width: min-content;padding: 8px;text-align: center;margin-left: auto;padding-top: 50px;">
                <!-- <button class="download">Download</button> -->
                <a href="#download" style="margin: 4px;"><img class="e1" src="assests/win-32.png" /></a>
                <a href="#download" style="margin: 4px;"><img class="e1" src="assests/linux-32.png" /></a>
                <a href="#download" style="margin: 4px;"><img class="e1" src="assests/mac.png" /></a>
            </li>
        </div>

        <div id="userPopup" class="popup1">
            <p class="email"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <img src="<?php echo isset($_SESSION['profile_image']) ? htmlspecialchars($_SESSION['profile_image']) : 'uploads/default-logo.png'; ?>" alt="userlogo" class="user-logo">
            <p class="username">Hi, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <a onclick="profile()" class="manage-acc"><span class="material-symbols-outlined">manage_accounts</span> Manage your Account</a>
            <a onclick="signout()" class="signout"><span class="material-symbols-outlined">logout</span> Log out</a>
        </div>

        <!-- Popup -->
        <div id="popup" class="popup">
            <div class="popup-content">
                <span class="close">&times;</span>
                <div class="tab">
                    <button class="tablinks" id="defaultTab" onclick="openTab(event, 'Login')">Login</button>
                    <button class="tablinks" onclick="openTab(event, 'Signup')">Signup</button>
                </div>

                <!-- Login Form -->
                <div id="Login" class="tabcontent">
                    <h2>Login</h2>
                    <form method="POST" action="login.php">
                        <label for="loginUsername">Username:</label>
                        <input type="text" id="loginUsername" name="username" required>
                        <label for="loginPassword">Password:</label>
                        <input type="password" id="loginPassword" name="password" required>
                        <button type="submit">Login</button>
                    </form>
                </div>

                <!-- Signup Form -->
                <div id="Signup" class="tabcontent">
                    <h2>Signup</h2>
                    <form method="POST" action="signup.php" enctype="multipart/form-data">
                        <label for="signupUsername">Username:</label>
                        <input type="text" id="signupUsername" name="username" required>
                        <label for="signupEmail">Email:</label>
                        <input type="email" id="signupEmail" name="email" required>
                        <label for="signupPassword">Password:</label>
                        <input type="password" id="signupPassword" name="password" required>
                        <label for="profileImage">Profile Image:</label>
                        <input type="file" id="profileImage" name="profileImage" accept="image/*" required>
                        <button type="submit">Signup</button>
                    </form>
                </div>
            </div>
        </div>

        <!--side nav-->
        <div class="sidenav">
            <!-- <a href="#section2" onclick="togglePopup1()" id="section2"><span onclick="togglePopup1()" class="material-symbols-outlined">
                    system_update_alt
                </span></a> -->
            <a href="#section1"><span class="material-symbols-outlined">
                    grid_view
                </span></a>

            <a href="#section3"><span class="material-symbols-outlined">
                    mail
                </span></a>
        </div>

        <!-- Latest post and updates grid-->
        <div class="pu-container">
            <div class="post-main">

                <div class="card">
                    <div class="card-header">
                        <h3 style="font-family: Pirata One, system-ui; word-spacing: 10px; letter-spacing: 2px;"><?php echo htmlspecialchars($title); ?></h3>
                        <p class="sub-header"><?php echo htmlspecialchars($time); ?></p>
                    </div>
                    <div class="card-content">
                        <p><?php echo $body; ?></p>
                    </div>

                    <div class="card-footer">
                        <a href="#" style="text-decoration: none;"><span class="material-symbols-outlined"
                                style="font-size: 18px; font-weight: bold;">
                                favorite
                            </span>Like</a>
                        <a href="#" style="text-decoration: none;"><span class="material-symbols-outlined"
                                style="font-size: 18px; font-weight: bold;">
                                comment
                            </span>Comment</a>
                        <a href="#" style="text-decoration: none;"><span class="material-symbols-outlined"
                                style="font-size: 18px; font-weight: bold;">
                                upload
                            </span>Share</a>
                    </div>
                </div>

            </div>

            <div class="update">
                <div class="latest-posts">
                    <div class="latest-posts-header">
                        <h4 style="font-family: Pirata One, system-ui; word-spacing: 10px; padding:22px 5px 22px 5px; letter-spacing: 2px;">LATEST
                            POSTS</h4>
                    </div>

                    <div class="post">
                        <h5><?php echo $title; ?></h5>
                        <p><span class="author">Admin</span> - <?php echo htmlspecialchars($time); ?> <span class="arrow">→</span></p>
                        <p>
                            <?php echo $limited_content; ?>...
                            <a href="full_post.php?id=<?php echo htmlspecialchars($id); ?>">Read More</a>
                        </p>
                    </div>

                    <hr>

                    <div class="post">
                        <h5><?php echo $title2; ?></h5>
                        <p><span class="author">Admin</span> - <?php echo htmlspecialchars($time2); ?></p>
                        <p>
                            <?php echo $limited_content2; ?>...
                            <a href="full_post.php?id=<?php echo htmlspecialchars($id2); ?>">Read More</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <section style="margin: 20% 10%; padding-top:15%; text-align: center;" id="download">
            <h2>Download</h2>
            <div class="div-download">
                <div class="div-download-item1">
                    <h2>Windows</h2>
                    <img class="e2" src="assests/win-32.png" />

                    <div class="ec1">
                        <p>Size: Not calculated</p>
                        <p>Version: 0.1.8</p>
                        <p>Release Date: 21/07/2024</p>
                    </div>
                    <button class="e2b">Download</button>
                </div>
                <div class="div-download-item2">
                    <h2>
                        Linux
                    </h2>
                    <img class="e2" src="assests/linux-32.png" />
                    <div class="ec1">
                        <p>Size: Not calculated</p>
                        <p>Version: 0.1.8</p>
                        <p>Release Date: 21/07/2024</p>
                    </div>
                    <button class="e2b">Download</button>
                </div>
                <div class="div-download-item3">
                    <h2>
                        Mac
                    </h2>
                    <img class="e2" src="assests/mac.png" />
                    <div class="ec1">
                        <p>Size: Not calculated</p>
                        <p>Version: 0.1.8</p>
                        <p>Release Date: 21/07/2024</p>
                    </div>
                    <button class="e2b">Download</button>
                </div>
            </div>
        </section>
        <section style="margin: 20% 10%; padding-top:15%; text-align: center;" id="about">
            <h2>About</h2>
            <p>Sea Bandits is a Free-to-Play Online Multiplayer High Seas Pirate RPG taking place in the vast player-driven ocean.

                Create an account, and venture adrift amongst other pirates alike, find treasures, complete quests, upgrade your ship and be social with other players. Defeat monsters, NPC Ships, and other players, or explore the world as a peaceful forager of goods, making a name for yourself as a feared pirate or a nice bloke who gets along with everyone.

                The game is meant to be socially focused, with opportunities to create or join a guild and engage in large sea battles. <a style="color: dodgerblue; text-decoration:none;" href="about.html">Read More</a></p>
        </section>
        <section style="margin: 20% 10% 20% 7%; padding-top:15%; text-align: center;" id="faq">
            <h2>Frequently Asked Questions</h2>
            <div class="layout">
                <div class="accordion">
                    <div class="accordion__question">
                        <p>What is Sea Bandits?</p>

                    </div>
                    <div class="accordion__answer">
                        <p>Sea Bandits is a Free-to-Play Online Multiplayer High Seas Pirate RPG taking place in the vast player-driven ocean.

                            Create an account, and venture adrift amongst other pirates alike, find treasures, complete quests, upgrade your ship and be social with other players. Defeat monsters, NPC Ships, and other players, or explore the world as a peaceful forager of goods, making a name for yourself as a feared pirate or a nice bloke who gets along with everyone.

                            The game is meant to be socially focused, with opportunities to create or join a guild and engage in large sea battles.</p>
                    </div>
                </div>
                <div class="accordion">
                    <div class="accordion__question">
                        <p>Is Sea Banits PvP or PvE-oriented?</p>
                    </div>

                    <div class="accordion__answer">
                        <p>
                            Sea Bandits is rich on both PvP and PvE.

                            PvP zones are free-for-all PvP & PvE zones where you travel to collect the rarest loot, slay high level monsters or NPC Ships, or fight other players either solo or by teaming up with your guild / group of friends in fierce battles against other players. But be careful - there are pirates here who might not have any mercy that will do everything to sink your ship, much to the joy of your opponent.

                            PvE zones are zero-risk zones - considered safe-havens. You can still slay monsters and have fun in these zones, but for those daring to engage in PvP, the rewards are plentiful, making the risk worth it.
                        </p>
                    </div>
                </div>
                <div class="accordion">
                    <div class="accordion__question">
                        <p>Which weapon types are there in Sea Bandits?</p>
                    </div>

                    <div class="accordion__answer">
                        <p>
                            There are two weapon types; cannons and harpoons. Cannons are used in battles against ships and harpoons are used to kill sea monsters.
                        </p>
                    </div>
                </div>
                <div class="accordion">
                    <div class="accordion__question">
                        <p>Which types of ammunition exists?</p>
                    </div>

                    <div class="accordion__answer">
                        <p>
                            There are several different levels of ammunition for the two weapon types, the higher the level of ammunition you have, naturally the higher your damage will be
                        </p>
                    </div>
                </div>
                <div class="accordion">
                    <div class="accordion__question">
                        <p>Which types of ship upgrades exist?</p>
                    </div>

                    <div class="accordion__answer">
                        <p>
                            There are various types of ship upgrades that you can upgrade your ship with: Slaves, Ship hull, and much more.
                        </p>
                    </div>
                </div>
                <div class="accordion">
                    <div class="accordion__question">
                        <p>Which skills will Sea Bandits feature?</p>
                    </div>

                    <div class="accordion__answer">
                        <p>
                            Progression in Sea Bandits is based on advancing your skills based on your play-style. Improving your skills allows you to do higher damage against either ships or monsters, depending on which skill you advance

                            The following skill sheets are planned for Sea Bandits:
                            Damage, Defense, Economy
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <footer>
            <div class="footer-container">
                <div class="footer-column">
                    <h3>Resources</h3>
                    <ul>
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">Changelog</a></li>
                        <li><a href="#">Community Forum</a></li>
                        <li><a href="#">Developer Blog</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Sea Bandits</h3>
                    <ul>
                        <li><a href="#">Tutorial</a></li>
                        <li><a href="#">Items</a></li>
                        <li><a href="#">Ships</a></li>
                        <li><a href="#">Store</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Links</h3>
                    <ul>
                        <li><a href="about.html">About</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Social</h3>
                    <ul>
                        <li><a href="#">Discord</a></li>
                        <li><a href="#">Instagram</a></li>
                        <li><a href="#">Twitter</a></li>
                        <li><a href="#">Youtube</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Team</h3>
                    <ul>
                        <li class="mm"><a href="#">MatiasMunk</a></li>
                        <li class="pg"><a href="#">Pg Network</a></li>
                        <li class="bb"><a href="#">BlackBird33</a></li>
                    </ul>
                </div>
            </div>
            <h2>Download Now</h2>
            <button class="f1b">Download</button>
            <div class="footer-bottom">
                <p>&copy; 2024 Sea Bandits | All rights reserved.</p>
                <a class="a1" href="tos.html">Terms & service</a> |
                <a class="a1" href="pp.html">Privacy Policy</a>
            </div>
        </footer>
    </div>
    <script src="script.js"></script>
</body>

</html>