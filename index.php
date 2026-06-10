<?php
session_start();

$userName = 'Guest';
$userEmail = '';
$userMessagesCount = 0;
$userLastMessageDate = '';

if (isset($_SESSION['user'])) {
    if (is_array($_SESSION['user'])) {
        if (isset($_SESSION['user']['username'])) {
            $userName = $_SESSION['user']['username'];
        } elseif (isset($_SESSION['user']['nume'])) {
            $userName = $_SESSION['user']['nume'];
        } elseif (isset($_SESSION['user']['email'])) {
            $userName = $_SESSION['user']['email'];
        } else {
            $userName = 'User';
        }

        if (isset($_SESSION['user']['email'])) {
            $userEmail = $_SESSION['user']['email'];
        }
    } else {
        $userName = $_SESSION['user'];
    }
}

$caleDate = 'data/data.json';
$dateSite = [];

if (file_exists($caleDate)) {
    $continut = file_get_contents($caleDate);
    $dateDinJson = json_decode($continut, true);

    if (is_array($dateDinJson)) {
        $dateSite = $dateDinJson;
    }
}

/* Dacă în sesiune avem doar numele, încercăm să găsim emailul în data.json */
if (isset($_SESSION['user']) && empty($userEmail) && isset($dateSite['utilizatori']) && is_array($dateSite['utilizatori'])) {
    foreach ($dateSite['utilizatori'] as $utilizatorSalvat) {
        if (!is_array($utilizatorSalvat)) {
            continue;
        }

        $numeSalvat = $utilizatorSalvat['username']
            ?? $utilizatorSalvat['nume']
            ?? $utilizatorSalvat['name']
            ?? '';

        $emailSalvat = $utilizatorSalvat['email'] ?? '';

        if ($numeSalvat === $userName || $emailSalvat === $userName) {
            if (!empty($emailSalvat)) {
                $userEmail = $emailSalvat;
            }
            break;
        }
    }
}

/* Numărăm mesajele trimise de utilizator, dacă avem email */
if (!empty($userEmail) && isset($dateSite['mesaje']) && is_array($dateSite['mesaje'])) {
    foreach ($dateSite['mesaje'] as $mesajSalvat) {
        if (isset($mesajSalvat['email']) && $mesajSalvat['email'] === $userEmail) {
            $userMessagesCount++;

            if (isset($mesajSalvat['data'])) {
                $userLastMessageDate = $mesajSalvat['data'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>The Boys</title>
    <link rel="stylesheet" href="css/index.css">
    <script>document.documentElement.classList.add(localStorage.getItem("theme") === "light" ? "light-mode" : "dark-mode");</script>
</head>
<body>

<div class="nav-bar">
    <a href="index.php">
        <img src="images/theboys.png" class="logo">
    </a>

    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Search characters..." data-i18n-placeholder="search_characters">
        <button id="searchBtn" type="button">
            <img src="images/search-icon-png-5.png">
        </button>
    </div>

    <div class="site-controls">
        <button id="themeToggle" class="control-btn" type="button" data-i18n="theme_light">Light mode</button>
        <select id="languageSelect" class="language-select" aria-label="Language">
            <option value="en">EN</option>
            <option value="ro">RO</option>
            <option value="ru">RU</option>
        </select>
    </div>

    <ul class="nav-links">

    <li><a href="contact.php" data-i18n="nav_contact">Contact</a></li>
    <li><a href="#" id="charactersNav" data-i18n="nav_characters">Characters</a></li>
    <li><a href="#" id="episodesNav" data-i18n="nav_episodes">Episodes</a></li>
    <li><a href="#loreSection" id="loreNav" data-i18n="nav_lore">Lore</a></li>

    <?php if (isset($_SESSION['user'])): ?>
        <li><a href="php/logout.php" data-i18n="nav_logout">Log Out</a></li>
    <?php else: ?>
        <li><a href="php/login.php" data-i18n="nav_auth">Authentication</a></li>
    <?php endif; ?>

    <li class="profile-nav">
        <button class="profile-btn" id="profileBtn" type="button">
            <span class="profile-icon">👤</span>
        </button>

        <div class="profile-dropdown" id="profileDropdown">
            <?php if (isset($_SESSION['user'])): ?>
    <div class="profile-user">
        <div class="profile-avatar">👤</div>
        <div>
            <strong><?php echo htmlspecialchars($userName); ?></strong>

            <?php if (!empty($userEmail)): ?>
                <span><?php echo htmlspecialchars($userEmail); ?></span>
            <?php else: ?>
                <span data-i18n="email_not_saved">Email not saved</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="profile-info">
        <p><strong data-i18n="status_label">Status:</strong> <span data-i18n="logged_in">Logged in</span></p>
        <p><strong data-i18n="messages_sent">Messages sent:</strong> <?php echo $userMessagesCount; ?></p>
        <?php if (!empty($userLastMessageDate)): ?>
            <p><strong data-i18n="last_message">Last message:</strong> <?php echo htmlspecialchars($userLastMessageDate); ?></p>
        <?php endif; ?>
    </div>

    <a href="messages.php" data-i18n="my_messages">My Messages</a>
    <a href="php/logout.php" data-i18n="nav_logout">Log Out</a>
            <?php else: ?>
                <div class="profile-user">
                    <div class="profile-avatar">👤</div>
                    <div>
                        <strong data-i18n="guest">Guest</strong>
                        <span data-i18n="not_authenticated">Not authenticated</span>
                    </div>
                </div>

                <a href="php/login.php?mode=login" data-i18n="login">Log In</a>
                <a href="php/login.php?mode=register" data-i18n="register">Register</a>
            <?php endif; ?>
        </div>
    </li>
</ul>
</div>

<main id="homeContent">

    <section class="hero-section">

        <div class="hero-left">

            <p class="italic">
                In a world where superheroes are corporate assets, a group of vigilantes fights back against the corrupt ones who abuse their power.
            </p>

            <div class="hero-buttons">
                <button class="explore-btn" id="exploreBtn" data-i18n="explore_characters">Explore Characters</button>
                <button class="scroll-btn" id="scrollBtn" data-i18n="scroll_down">Scroll down ˅</button>
            </div>

            <div class="character-menu" id="characterMenu" hidden>
                <button id="showBoys" class="team-option active-team">The Boys</button>
                <button id="showSupes" class="team-option">The Supes</button>
            </div>

            <div class="hero-info">
                <span class="hero-label">Fanpage • The Boys Universe</span>

                <h1>
                    Welcome to the world of <span>The Boys</span>
                </h1>

                <p class="hero-description">
                    Discover characters, actors, heroes, secrets and the dark side of Vought.
                </p>

                <div class="hero-stats">
                    <div class="stat-box">
                        <strong>5</strong>
                        <span>The Boys</span>
                    </div>

                    <div class="stat-box">
                        <strong>8</strong>
                        <span>Supes</span>
                    </div>

                    <div class="stat-box">
                        <strong>10+</strong>
                        <span>Actors</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="hero-right">
            <img src="images/The Boys (2019_2026).jpg" class="hero-main-image">
        </div>

    </section>

    <h2 class="section-title" id="charactersTitle" data-i18n="boys_characters">The Boys Characters</h2>

    <div class="characters-grid" id="boysGrid">

        <div class="character-card" data-id="butcher">
            <img src="images/7f691723a0c31fef2a322cd6b78e5484-removebg-preview.png">
            <span class="char-initial">B</span>
            <span class="char-name">Billy Butcher</span>
        </div>

        <div class="character-card" data-id="hughie">
            <img src="images/50e05182f1d278d9bdb717fe8cf6fd45-removebg-preview.png">
            <span class="char-initial">H</span>
            <span class="char-name">Hughie Campbell</span>
        </div>

        <div class="character-card" data-id="milk">
            <img src="images/34f158721f8b44105f0054ddf4117c52-removebg-preview.png">
            <span class="char-initial">M</span>
            <span class="char-name">Mother's Milk</span>
        </div>

        <div class="character-card" data-id="frenchie">
            <img src="images/787f3725c206b77390a2a560edb93116-removebg-preview.png">
            <span class="char-initial">F</span>
            <span class="char-name">Frenchie</span>
        </div>

        <div class="character-card" data-id="kimiko">
            <img src="images/ba6d90d2b0f30af07e4ecb192c417cce-removebg-preview.png">
            <span class="char-initial">K</span>
            <span class="char-name">Kimiko</span>
        </div>

    </div>

    <div class="characters-grid" id="supesGrid">

        <div class="character-card" data-id="homelander">
            <img src="images/homelander.png">
            <span class="char-initial">H</span>
            <span class="char-name">Homelander</span>
        </div>

        <div class="character-card" data-id="starlight">
            <img src="images/starlight.png">
            <span class="char-initial">S</span>
            <span class="char-name">Starlight</span>
        </div>

        <div class="character-card" data-id="soldierboy">
            <img src="images/soldierboy.png">
            <span class="char-initial">S</span>
            <span class="char-name">Soldier Boy</span>
        </div>

        <div class="character-card" data-id="atrain">
            <img src="images/atrain.png">
            <span class="char-initial">A</span>
            <span class="char-name">A-Train</span>
        </div>

        <div class="character-card" data-id="thedeep">
            <img src="images/thedeep.png">
            <span class="char-initial">D</span>
            <span class="char-name">The Deep</span>
        </div>

        <div class="character-card" data-id="blacknoir">
            <img src="images/blacknoir.png">
            <span class="char-initial">B</span>
            <span class="char-name">Black Noir</span>
        </div>

        <div class="character-card" data-id="queenmaeve">
            <img src="images/queenmaeve.png">
            <span class="char-initial">Q</span>
            <span class="char-name">Queen Maeve</span>
        </div>

        <div class="character-card" data-id="translucent">
            <img src="images/translucent.png">
            <span class="char-initial">T</span>
            <span class="char-name">Translucent</span>
        </div>

    </div>

    <div class="actors-section">
        <h2 class="actors-title" data-i18n="actors">Actors</h2>
        <hr class="actors-line">

        <div class="actors-grid">

            <div class="actor-card">
                <img src="images/105bd11b-6034-4830-af0c-670604072571.avif">
                <span class="actor-name">Karl Urban</span>
            </div>

            <div class="actor-card">
                <img src="images/Jack_Quaid_-_Novocaine-Companion.jpg">
                <span class="actor-name">Jack Quaid</span>
            </div>

            <div class="actor-card">
                <img src="images/20200902-Antony-Starr-01.webp">
                <span class="actor-name">Antony Starr</span>
            </div>

            <div class="actor-card">
                <img src="images/MV5BMmZlNDQ5MzktMWMzOS00NTQ3LWE3ZGEtY2U2MzlkNTA2M2UyXkEyXkFqcGc@._V1_.jpg">
                <span class="actor-name">Erin Moriarty</span>
            </div>

            <div class="actor-card">
                <img src="images/jensen-ackles-1-070125-358e53cd0a3f4526b8cd754d95279142-1200x800.jpg">
                <span class="actor-name">Jensen Ackles</span>
            </div>

            <div class="actor-card">
                <img src="images/Karen_Fukuhara.webp">
                <span class="actor-name">Karen Fukuhara</span>
            </div>

            <div class="actor-card">
                <img src="images/images.jpg">
                <span class="actor-name">Chace Crawford</span>
            </div>

            <div class="actor-card">
                <img src="images/lazalonso.jpg">
                <span class="actor-name">Laz Alonso</span>
            </div>

            <div class="actor-card">
                <img src="images/tomercapone.jpg">
                <span class="actor-name">Tomer Capone</span>
            </div>

            <div class="actor-card">
                <img src="images/giancarloesposito.jpg">
                <span class="actor-name">Giancarlo Esposito</span>
            </div>

        </div>
    </div>

    <section class="lore-section" id="loreSection">
        <div class="lore-header">
            <span class="lore-small-title">The Truth Behind Vought</span>
            <h2 data-i18n="lore_title">Lore</h2>
            <p>
                Behind the public image of heroes, fame and justice, there is a darker world controlled by power, money and secrets.
            </p>
        </div>

        <div class="lore-content">

            <div class="lore-card main-lore-card">
                <h3>Vought International</h3>
                <p>
                    Vought is the powerful corporation behind most superheroes. To the public, it presents Supes as symbols of hope,
                    protection and patriotism. In reality, the company hides scandals, manipulation and dangerous experiments.
                </p>
            </div>

            <div class="lore-card">
                <span class="lore-number">01</span>
                <h3>Compound V</h3>
                <p>
                    Compound V is the secret substance responsible for creating Supes. While the world believes heroes are born naturally,
                    Vought hides the truth about how their powers were created.
                </p>
            </div>

            <div class="lore-card">
                <span class="lore-number">02</span>
                <h3>The Seven</h3>
                <p>
                    The Seven are Vought's most famous superhero team. They are treated like celebrities, but behind the cameras their actions
                    often reveal arrogance, violence and corruption.
                </p>
            </div>

            <div class="lore-card">
                <span class="lore-number">03</span>
                <h3>The Boys</h3>
                <p>
                    The Boys are a group of vigilantes who fight against corrupt Supes and the company protecting them. Their mission is dangerous,
                    personal and full of sacrifices.
                </p>
            </div>

            <div class="lore-card">
                <span class="lore-number">04</span>
                <h3>Public Image</h3>
                <p>
                    Supes are promoted through movies, campaigns and news appearances. Their heroic image is carefully controlled by Vought,
                    while their mistakes are hidden from the public.
                </p>
            </div>

            <div class="lore-card">
                <span class="lore-number">05</span>
                <h3>The Conflict</h3>
                <p>
                    The main conflict is not only between heroes and vigilantes, but between truth and propaganda. The Boys try to expose what
                    Vought wants to keep buried.
                </p>
            </div>

        </div>
    </section>

</main>

<section class="characters-page" id="charactersPage">

    <div class="characters-page-header">
        <span>The Boys Universe</span>
        <h1 data-i18n="characters_title">Characters</h1>
        <p>
            Explore all main characters from The Boys universe, from the vigilante group The Boys to Vought's powerful superhero team, The Seven.
        </p>

        <button class="back-home-btn" id="backCharactersHomeBtn" data-i18n="back_home">← Back to Home</button>
    </div>

    <div class="characters-page-block">
        <h2>The Boys</h2>

        <div class="characters-page-grid">

            <div class="character-card" data-id="butcher">
                <img src="images/7f691723a0c31fef2a322cd6b78e5484-removebg-preview.png">
                <span class="char-initial">B</span>
                <span class="char-name">Billy Butcher</span>
            </div>

            <div class="character-card" data-id="hughie">
                <img src="images/50e05182f1d278d9bdb717fe8cf6fd45-removebg-preview.png">
                <span class="char-initial">H</span>
                <span class="char-name">Hughie Campbell</span>
            </div>

            <div class="character-card" data-id="milk">
                <img src="images/34f158721f8b44105f0054ddf4117c52-removebg-preview.png">
                <span class="char-initial">M</span>
                <span class="char-name">Mother's Milk</span>
            </div>

            <div class="character-card" data-id="frenchie">
                <img src="images/787f3725c206b77390a2a560edb93116-removebg-preview.png">
                <span class="char-initial">F</span>
                <span class="char-name">Frenchie</span>
            </div>

            <div class="character-card" data-id="kimiko">
                <img src="images/ba6d90d2b0f30af07e4ecb192c417cce-removebg-preview.png">
                <span class="char-initial">K</span>
                <span class="char-name">Kimiko</span>
            </div>

        </div>
    </div>

    <div class="characters-page-block">
        <h2>The Seven</h2>

        <div class="characters-page-grid">

            <div class="character-card" data-id="homelander">
                <img src="images/homelander.png">
                <span class="char-initial">H</span>
                <span class="char-name">Homelander</span>
            </div>

            <div class="character-card" data-id="starlight">
                <img src="images/starlight.png">
                <span class="char-initial">S</span>
                <span class="char-name">Starlight</span>
            </div>

            <div class="character-card" data-id="soldierboy">
                <img src="images/soldierboy.png">
                <span class="char-initial">S</span>
                <span class="char-name">Soldier Boy</span>
            </div>

            <div class="character-card" data-id="atrain">
                <img src="images/atrain.png">
                <span class="char-initial">A</span>
                <span class="char-name">A-Train</span>
            </div>

            <div class="character-card" data-id="thedeep">
                <img src="images/thedeep.png">
                <span class="char-initial">D</span>
                <span class="char-name">The Deep</span>
            </div>

            <div class="character-card" data-id="blacknoir">
                <img src="images/blacknoir.png">
                <span class="char-initial">B</span>
                <span class="char-name">Black Noir</span>
            </div>

            <div class="character-card" data-id="queenmaeve">
                <img src="images/queenmaeve.png">
                <span class="char-initial">Q</span>
                <span class="char-name">Queen Maeve</span>
            </div>

            <div class="character-card" data-id="translucent">
                <img src="images/translucent.png">
                <span class="char-initial">T</span>
                <span class="char-name">Translucent</span>
            </div>

        </div>
    </div>

</section>

<section class="episodes-page" id="episodesPage">

    <div class="episodes-header">
        <span>The Boys Universe</span>
        <h1 data-i18n="episodes_title">Episodes</h1>
        <p>
            Explore the story through seasons and episode summaries. Each episode reveals more about Vought, The Seven and the fight of The Boys.
        </p>

        <button class="back-home-btn" id="backHomeBtn" data-i18n="back_home">← Back to Home</button>
    </div>

    <div class="season-block">
        <h2>Season 1</h2>

        <div class="episode-card">
            <div class="episode-number">S01 • E01</div>
            <div class="episode-info">
                <h3>The Name of the Game</h3>
                <p>
                    When a Supe kills the love of his life, A/V salesman Hughie Campbell teams up with Billy Butcher, a vigilante hell-bent on punishing corrupt Supes -- and Hughie's life will never be the same again.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S01 • E02</div>
            <div class="episode-info">
                <h3>Cherry</h3>
                <p>
                    The Boys get themselves a Superhero, Starlight gets payback, Homelander gets naughty, and a Senator gets naughtier.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S01 • E03</div>
            <div class="episode-info">
                <h3>Get Some</h3>
                <p>
                    It's the race of the century. A-Train versus Shockwave, vying for the title of World's Fastest Man. Meanwhile, the Boys are reunited and it feels so good.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S01 • E04</div>
            <div class="episode-info">
                <h3>The Female of the Species</h3>
                <p>
                    On a very special episode of The Boys... an hour of guts, gutterballs, airplane hijackings, madness, ghosts, and one very intriguing Female.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S01 • E05</div>
            <div class="episode-info">
                <h3>Good for the Soul</h3>
                <p>
                    The Boys head to the "Believe" Expo to follow a promising lead in their ongoing war against the Supes.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S01 • E06</div>
            <div class="episode-info">
                <h3>The Innocents</h3>
                <p>
                    Vought Studios promotes Supes through media and entertainment while The Boys continue searching for the truth behind the company's image.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S01 • E07</div>
            <div class="episode-info">
                <h3>The Self-Preservation Society</h3>
                <p>
                    The Boys learn that trusting a washed-up Supe can be dangerous, while Homelander digs deeper into his past.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S01 • E08</div>
            <div class="episode-info">
                <h3>You Found Me</h3>
                <p>
                    Secrets are revealed, conflicts explode, and the season ends with major consequences for The Boys and Vought.
                </p>
            </div>
        </div>

    </div>

    <div class="season-block">
        <h2>Season 2</h2>

        <div class="episode-card">
            <div class="episode-number">S02 • E01</div>
            <div class="episode-info">
                <h3>The Big Ride</h3>
                <p>
                    With Butcher missing, Hughie, Mother's Milk, Frenchie and Kimiko are fugitives, while Vought and Homelander become stronger.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S02 • E02</div>
            <div class="episode-info">
                <h3>Proper Preparation and Planning</h3>
                <p>
                    Butcher returns, tensions rise, and The Boys continue their mission while Vought hides more secrets.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S02 • E03</div>
            <div class="episode-info">
                <h3>Over the Hill with the Swords of a Thousand Men</h3>
                <p>
                    The Boys try to protect a prisoner, while Homelander and Stormfront reveal more dangerous sides of themselves.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S02 • E04</div>
            <div class="episode-info">
                <h3>Nothing Like It in the World</h3>
                <p>
                    Milk, Hughie and Annie search for answers, while Frenchie faces personal struggles and Homelander continues to unravel.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S02 • E05</div>
            <div class="episode-info">
                <h3>We Gotta Go Now</h3>
                <p>
                    The world of Vought becomes even more theatrical as Supes are used for propaganda, films and public manipulation.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S02 • E06</div>
            <div class="episode-info">
                <h3>The Bloody Doors Off</h3>
                <p>
                    The Boys and Starlight discover one of Vought's darkest secrets at Sage Grove Center.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S02 • E07</div>
            <div class="episode-info">
                <h3>Butcher, Baker, Candlestick Maker</h3>
                <p>
                    The group discovers that the superhero world is built on lies, while Vought continues protecting its public image.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S02 • E08</div>
            <div class="episode-info">
                <h3>What I Know</h3>
                <p>
                    The Boys, Starlight and Butcher finally face off against Homelander and Stormfront in a dangerous confrontation.
                </p>
            </div>
        </div>

    </div>

    <div class="season-block">
        <h2>Season 3</h2>

        <div class="episode-card">
            <div class="episode-number">S03 • E01</div>
            <div class="episode-info">
                <h3>Payback</h3>
                <p>
                    The Boys now work with the Bureau of Superhero Affairs, but old conflicts and Vought's secrets continue to return.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S03 • E02</div>
            <div class="episode-info">
                <h3>The Only Man in the Sky</h3>
                <p>
                    Homelander's public image is celebrated while his private instability becomes more dangerous.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S03 • E03</div>
            <div class="episode-info">
                <h3>Barbary Coast</h3>
                <p>
                    The Seven changes while Vought continues turning superhero life into entertainment and competition.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S03 • E04</div>
            <div class="episode-info">
                <h3>Glorious Five Year Plan</h3>
                <p>
                    The Boys search for a mysterious weapon in Russia while Vought faces more internal tension.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S03 • E05</div>
            <div class="episode-info">
                <h3>The Last Time to Look on This World of Lies</h3>
                <p>
                    The Boys run into a major obstacle and turn their attention toward Soldier Boy.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S03 • E06</div>
            <div class="episode-info">
                <h3>Herogasm</h3>
                <p>
                    A chaotic and dangerous event exposes the darker, uncontrolled side of the Supe world.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S03 • E07</div>
            <div class="episode-info">
                <h3>Here Comes a Candle to Light You to Bed</h3>
                <p>
                    Soldier Boy searches for former Payback members, while Black Noir remembers what truly happened in the past.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S03 • E08</div>
            <div class="episode-info">
                <h3>The Instant White-Hot Wild</h3>
                <p>
                    The season ends with dangerous alliances, public conflict and a dramatic shift in Homelander's influence.
                </p>
            </div>
        </div>

    </div>

    <div class="season-block">
        <h2>Season 4</h2>

        <div class="episode-card">
            <div class="episode-number">S04 • E01</div>
            <div class="episode-info">
                <h3>Department of Dirty Tricks</h3>
                <p>
                    Butcher tries to fix his mistakes, while Homelander searches for a new ally and struggles with his mortality.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S04 • E02</div>
            <div class="episode-info">
                <h3>Life Among the Septics</h3>
                <p>
                    The world becomes more divided as conspiracy, media and politics become part of Vought's influence.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S04 • E03</div>
            <div class="episode-info">
                <h3>We'll Keep the Red Flag Flying Here</h3>
                <p>
                    Problems grow between Homelander and Starlight supporters as public conflict becomes more dangerous.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S04 • E04</div>
            <div class="episode-info">
                <h3>Wisdom of the Ages</h3>
                <p>
                    Vought uses media manipulation to attack Starlight and strengthen its propaganda machine.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S04 • E05</div>
            <div class="episode-info">
                <h3>Beware the Jabberwock, My Son</h3>
                <p>
                    The Boys search for the Anti-Supe virus while facing strange and violent dangers.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S04 • E06</div>
            <div class="episode-info">
                <h3>Dirty Business</h3>
                <p>
                    Power, politics and secrets collide as Vought continues working behind the scenes.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S04 • E07</div>
            <div class="episode-info">
                <h3>The Insider</h3>
                <p>
                    The line between propaganda and reality becomes harder to see as the world moves toward chaos.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S04 • E08</div>
            <div class="episode-info">
                <h3>Season Four Finale</h3>
                <p>
                    The season ends with open conflict, political danger and a terrifying future for The Boys.
                </p>
            </div>
        </div>

    </div>

    <div class="season-block">
        <h2>Season 5</h2>

        <div class="episode-card">
            <div class="episode-number">S05 • E01</div>
            <div class="episode-info">
                <h3>Fifteen Inches of Sheer Dynamite</h3>
                <p>
                    Attention, freedom campers: escape attempts will be met with deadly force. Have a super day.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S05 • E02</div>
            <div class="episode-info">
                <h3>Teenage Kix</h3>
                <p>
                    Butcher's team searches for an experimental weapon while Homelander awakens a dangerous asset.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S05 • E03</div>
            <div class="episode-info">
                <h3>Every One of You Sons of Bitches</h3>
                <p>
                    Vought's influence continues through marketing, control and dangerous secrets.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S05 • E04</div>
            <div class="episode-info">
                <h3>King of Hell</h3>
                <p>
                    The Boys, Homelander and Soldier Boy move toward a dangerous collision.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S05 • E05</div>
            <div class="episode-info">
                <h3>One-Shots</h3>
                <p>
                    Connected stories reveal how different powers and plans begin moving toward the final conflict.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S05 • E06</div>
            <div class="episode-info">
                <h3>Though the Heavens Fall</h3>
                <p>
                    The Boys continue their mission while enemies and old secrets threaten everything.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S05 • E07</div>
            <div class="episode-info">
                <h3>The Frenchman, the Female, and the Man Called Mother's Milk</h3>
                <p>
                    The team faces dangerous missions, betrayals and the collapse of old structures.
                </p>
            </div>
        </div>

        <div class="episode-card">
            <div class="episode-number">S05 • E08</div>
            <div class="episode-info">
                <h3>Blood and Bone</h3>
                <p>
                    With everything at stake, The Boys prepare for one final diabolical showdown.
                </p>
            </div>
        </div>

    </div>

</section>

<div class="modal-overlay" id="modalOverlay">
    <div class="modal-box">
        <button class="modal-close" id="modalClose">✕</button>
        <img class="modal-img" id="modalImg" src="">
        <div class="modal-info">
            <h2 class="modal-name" id="modalName"></h2>
            <p class="modal-role" id="modalRole"></p>
            <p class="modal-desc" id="modalDesc"></p>
        </div>
        <video class="modal-video" id="modalVideo" src="" controls></video>
    </div>
</div>

<div class="modal-overlay" id="loginPrompt">
    <div class="modal-box small-modal">
        <button class="modal-close" id="loginPromptClose">✕</button>
        <h2 data-i18n="must_login">You have to be logged in</h2>
        <p data-i18n="auth_to_see">Authenticate to see info about characters.</p>

        <div class="login-actions">
            <a href="php/login.php?mode=login" class="submit-btn" data-i18n="login">Log In</a>
            <a href="php/login.php?mode=register" class="submit-btn register-btn" data-i18n="register">Register</a>
        </div>
    </div>
</div>

<script>
    const isLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
</script>
<script src="js/index.js"></script>

</body>
</html>