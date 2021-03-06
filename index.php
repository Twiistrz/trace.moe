<?php
header("Link: </css/app.min.css>; rel=preload; as=style", false);
header("Link: </js/analytics.js>; rel=preload; as=script", false);
header("Link: </js/index_v3.js>; rel=preload; as=script", false);
header("Link: </js/info_v3.js>; rel=preload; as=script", false);
header("Link: </fonts/glyphicons-halflings-regular.woff>; rel=preload; as=font; crossorigin", false);

$autosearch = false;
$imageURL = "";
$originalImage = "";
if (isset($_GET["url"]) && filter_var($_GET["url"], FILTER_VALIDATE_URL)) {
    $autosearch = true;
    $imageURL = str_replace(' ', '%20', rawurldecode($_GET["url"]));
    $originalImage = "//image.trace.moe/imgproxy?url=" . str_replace(' ', '%20', rawurldecode($_GET["url"]));
}
?>
<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/Webpage">

<head>
    <!-- (๑・ω・๑) -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="Search Anime by ScreenShot. Lookup the exact moment and the episode.">
    <meta name="keywords" content="Anime Scene Search, Search by image, Anime Image Search, アニメのキャプ画像">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#3f51b5" />
    <title>WAIT: What Anime Is This? - Anime Scene Search Engine</title>

    <?php
    $og_image = 'https://trace.moe/favicon128.png';
    if (isset($_GET["url"]) && filter_var($_GET["url"], FILTER_VALIDATE_URL))
        $og_image = 'https://image.trace.moe/imgproxy?url=' . $_GET["url"];
    ?>
    <!-- Schema.org markup (Google) -->
    <meta itemprop="name" content="WAIT: What Anime Is This?">
    <meta itemprop="description" content="Anime Scene Search Engine. Lookup the exact moment and the episode.">
    <meta itemprop="image" content="<?php echo $og_image ?>">

    <!-- Twitter Card markup-->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@soruly">
    <meta name="twitter:title" content="WAIT: What Anime Is This?">
    <meta name="twitter:description" content="Anime Scene Search Engine. Lookup the exact moment and the episode.">
    <meta name="twitter:creator" content="@soruly">
    <!-- Twitter summary card with large image must be at least 280x150px -->
    <meta name="twitter:image" content="<?php echo $og_image ?>">
    <meta name="twitter:image:alt" content="Anime Scene Search Engine. Lookup the exact moment and the episode.">

    <!-- Open Graph markup (Facebook, Pinterest) -->
    <meta property="og:title" content="WAIT: What Anime Is This?" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="https://trace.moe" />
    <meta property="og:image" content="<?php echo $og_image ?>" />
    <meta property="og:description" content="Anime Scene Search Engine. Lookup the exact moment and the episode." />
    <meta property="og:site_name" content="trace.moe" />

    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="icon" type="image/png" href="/favicon128.png" sizes="128x128">
    <link rel="stylesheet" href="/css/app.min.css" type="text/css">
    <link rel="dns-prefetch" href="https://image.trace.moe/">
    <script src="/js/analytics.js" async defer></script>
</head>

<body>
    <nav class="navbar">
        <div class="width">
            <a href="javascript:void(0);" class="navbar__hamburger">
                <span class="glyphicon glyphicon-menu-hamburger"></span>
            </a><!-- /.navbar__hamburger -->

            <div class="navbar__menu">
                <a class="navbar__item navbar__item--active" href="/">Home</a>
                <a class="navbar__item" href="/about">About</a>
                <a class="navbar__item" href="/changelog">Changelog</a>
                <a class="navbar__item" href="/faq">FAQ</a>
                <a class="navbar__item" href="/terms">Terms</a>
            </div><!-- /.navbar__menu -->
        </div><!-- /.width -->
    </nav><!-- /.navbar -->

    <div class="w-alert">
        <div class="width">
            <p class="w-alert__text">Read recent updates to trace.moe | soruly on <a href="https://www.patreon.com/posts/24430008">Patreon!</a></p>
        </div><!-- /.width -->
    </div><!-- /.w-alert -->

    <main>
        <input id="autoSearch" type="checkbox" style="display: none;" <?php echo $autosearch ? "checked" : ""; ?>>
        <img id="originalImage" src="<?php echo $originalImage; ?>" crossorigin="anonymous" style="display: none;">

        <div id="main" class="card card--search">
            <div class="noselect">
                <div id="loading" class="loading-ripple hidden">
                    <div id="loader" class="ripple"></div>
                </div>
                <canvas id="preview" class="preview-canvas"></canvas>
                <video id="player" style="display:none" volume="0.5" autoplay></video>
            </div><!-- /.noselect -->

            <div class="search__source">
                <span id="progressBarControl" class="search__source-progress"></span>
                <span id="fileNameDisplay" class="search__source-name">playback source</span>
                <span id="timeCodeDisplay" class="search__source-time">--:--:--/--:--:--</span>
            </div><!-- /.search__source -->

            <div id="form" class="search__form">
                <form method="post" class="search__input-group">
                    <!-- <label for="seasonSelector" style="font-weight: inherit">Search in (anilist ID):</label> -->
                    <input type="text" id="seasonSelector" class="form-control input-sm search__input" placeholder="anilist ID (Optional)">
                    <input type="url" pattern="https?://.+" name="imageURL" class="form-control input-sm search__input" id="imageURL" placeholder="Image URL" value="<?php echo $imageURL; ?>">
                    <input type="submit" id="submit" style="display:none">
                </form><!-- /.search__input-group -->

                <span id="messageText" class="loading-spin"></span>

                <div class="search__button-group">
                    <span class="btn btn--file">
                        <span class="glyphicon glyphicon-folder-open"></span> Browse a file <input type="file" id="file" name="files[]" />
                    </span>
                    <button id="safeBtn" type="button" class="btn">
                        <span class="glyphicon glyphicon-unchecked"></span> Safe Search
                    </button>
                    <button id="flipBtn" type="button" class="btn" disabled>
                        <span class="glyphicon glyphicon-unchecked"></span> Flip Image
                    </button>
                    <button id="searchBtn" type="button" class="btn btn--search" disabled>
                        <span class="glyphicon glyphicon-search"></span> Search
                    </button>
                </div><!-- /.search__button-group -->
            </div><!-- /.search__form -->

            <div class="search__footer">
                <span>Drag &amp; Drop Anime ScreenShot / Ctrl + V / Enter Image URL</span>
                <span class="text--danger"><strong>WARNING</strong>: Some results may be NSFW (Not Safe for Work) contents</span>
                <span><a href="https://telegram.me/WhatAnimeBot">Official Telegram Bot</a> | Official WebExtension for <a href="https://chrome.google.com/webstore/detail/search-anime-by-screensho/gkamnldpllcbiidlfacaccdoadedncfp">Chrome</a>, <a href="https://addons.mozilla.org/en-US/firefox/addon/search-anime-by-screenshot/">Firefox</a>, and <a href="https://addons.opera.com/en/extensions/details/search-anime-by-screenshot/">Opera</a></span>
            </div><!-- /.search__footer -->
        </div><!-- /.card .card-search -->

        <div id="results-list" class="card card--results">
            <div id="controls" class="checkbox">
                <label><input type="checkbox" id="autoplay" name="autoplay" checked />AutoPlay</label>
                <label><input type="checkbox" id="loop" name="loop" />Loop</label>
                <label><input type="checkbox" id="mute" name="mute" />Mute</label>
            </div>
            <ul id="results" class="results"></ul>
        </div><!--  /.card -->

        <div id="info" class="card card--info"></div><!-- /.card .card-info -->
    </main>

    <a href="https://github.com/soruly/trace.moe" class="github-corner" aria-label="View source on Github">
        <svg width="67" height="67" viewBox="0 0 250 250" style="fill:#151513; color:#fff; position: absolute; top: 0; border: 0; right: 0;" aria-hidden="true">
            <path d="M0,0 L115,115 L130,115 L142,142 L250,250 L250,0 Z"></path>
            <path d="M128.3,109.0 C113.8,99.7 119.0,89.6 119.0,89.6 C122.0,82.7 120.5,78.6 120.5,78.6 C119.2,72.0 123.4,76.3 123.4,76.3 C127.3,80.9 125.5,87.3 125.5,87.3 C122.9,97.6 130.6,101.9 134.4,103.2" fill="currentColor" style="transform-origin: 130px 106px;" class="octo-arm"></path>
            <path d="M115.0,115.0 C114.9,115.1 118.7,116.5 119.8,115.4 L133.7,101.6 C136.9,99.2 139.9,98.4 142.2,98.6 C133.8,88.0 127.5,74.4 143.8,58.0 C148.5,53.4 154.0,51.2 159.7,51.0 C160.3,49.4 163.2,43.6 171.4,40.1 C171.4,40.1 176.1,42.5 178.8,56.2 C183.1,58.6 187.2,61.8 190.9,65.4 C194.5,69.0 197.7,73.2 200.1,77.6 C213.8,80.2 216.3,84.9 216.3,84.9 C212.7,93.1 206.9,96.0 205.4,96.6 C205.1,102.4 203.0,107.8 198.3,112.5 C181.9,128.9 168.3,122.5 157.7,114.1 C157.9,116.9 156.7,120.9 152.7,124.9 L141.0,136.5 C139.8,137.7 141.6,141.9 141.8,141.8 Z" fill="currentColor" class="octo-body"></path>
        </svg>
    </a>

    <script src="/js/index_v3.js"></script>
    <script src="/js/info_v3.js"></script>
</body>

</html> 