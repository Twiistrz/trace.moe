<?php
header("Link: </css/style.css>; rel=preload; as=style", false);
header("Link: </css/bootstrap.min.css>; rel=preload; as=style", false);
header("Link: </js/analytics.js>; rel=preload; as=script", false);

ini_set("display_errors", 0);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "http://192.168.2.12:8983/solr/admin/cores?wt=json");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$res = curl_exec($curl);
$result = json_decode($res);
curl_close($curl);

function humanTiming($time)
{
    $time = time() - $time; // to get the time since that moment
    $time = ($time < 1) ? 1 : $time;
    $tokens = array(
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );
    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
    }
}
$numDocs = 0;
$sizeInBytes = 0;
$lastModified = 0;
foreach ($result->status as $core) {
    $numDocs += $core->index->numDocs;
    $sizeInBytes += $core->index->sizeInBytes;
    if (strtotime($core->index->lastModified) > $lastModified) {
        $lastModified = strtotime($core->index->lastModified);
    }
}
$numDocsMillion = floor($numDocs / 1000000);
$sizeInGB = floor($sizeInBytes / 1073741824);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="description" content="Search Anime by ScreenShot. Lookup the exact moment and the episode.">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#60738b" />
    <title>WAIT: What Anime Is This? - About</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="icon" type="image/png" href="/favicon128.png" sizes="128x128">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <script src="/js/analytics.js" defer></script>
</head>

<body>
    <nav class="navbar header">
        <div class="container">
            <ul class="nav navbar-nav">
                <li><a href="/">Home</a></li>
                <li><a href="/about" class="active">About</a></li>
                <li><a href="/changelog">Changelog</a></li>
                <li><a href="/faq">FAQ's</a></li>
                <li><a href="/terms">Terms</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <ul class="wait-info__group">
            <li>
                <h2>About</h2>
            </li>
            <li>
                <div>
                    <p>Life is too short to answer all the "What Anime Is This?" questions. Let computers do that for you.</p>
                    <p>
                        trace.moe is a test-of-concept prototype search engine that helps users trace back the original anime by screenshot.
                        It searches over 22300 hours of anime and find the best matching scene.
                        It tells you what anime it is, from which episode and the time that scene appears.
                        Since the search result may not be accurate, it provides a few seconds of preview for verification.
                    </p>
                    <p>There has been a lot of anime screencaps and GIFs spreading around the internet, but very few of them mention the source. While those online platforms are gaining popularity, trace.moe respects the original producers and staffs by showing interested anime fans what the original source is. This search engine encourages users to give credits to the original creater / owner before they share stuff online.</p>
                    <p>
                        This website is non-profit making. There is no pro/premium features at all.
                        This website is not intended for watching anime. The server has effective measures to forbid users to access the original video beyond the preview limit. I would like to redirect users to somewhere they can watch that anime legally, if possible.
                    </p>
                    <p>
                        Most Anime since 2000 are indexed, but some are excluded (see FAQ).
                        No Doujin work, no derived art work are indexed. The system only analyzes officially published anime.
                        If you wish to search artwork / wallpapers, try to use <a href="https://saucenao.com/">SauceNAO</a> and <a href="https://iqdb.org/">iqdb.org</a>
                    </p>
                </div>
            </li>
        </ul>
        <ul class="wait-info__group">
            <li>
                <h2>WebExtension</h2>
            </li>
            <li>
                <div>
                    <p>WebExtension available for <a href="https://chrome.google.com/webstore/detail/search-anime-by-screensho/gkamnldpllcbiidlfacaccdoadedncfp">Chrome</a>, <a href="https://addons.mozilla.org/en-US/firefox/addon/search-anime-by-screenshot/">Firefox</a>, or <a href="https://addons.opera.com/en/extensions/details/search-anime-by-screenshot/">Opera</a> to search.</p>
                    <p>Source code and user guide on Github: <a href="https://github.com/soruly/trace.moe-WebExtension">https://github.com/soruly/trace.moe-WebExtension</a></p>
                </div>
            </li>
        </ul>
        <ul class="wait-info__group">
            <li>
                <h2>Telegram Bot</h2>
            </li>
            <li>
                <div>
                    <p>Telegram Bot available <a href="https://telegram.me/WhatAnimeBot">@WhatAnimeBot</a></p>
                    <p>Source code and user guide on Github: <a href="https://github.com/soruly/trace.moe-telegram-bot">https://github.com/soruly/trace.moe-telegram-bot</a></p>
                </div>
            </li>
        </ul>
        <ul class="wait-info__group">
            <li>
                <h2>Official API (Beta)</h2>
            </li>
            <li>
                <div>
                    <p>Official API Docs available at <a href="https://soruly.github.io/trace.moe/#/">GitHub</a></p>
                </div>
            </li>
        </ul>
        <ul class="wait-info__group">
            <li>
                <h2>Mobile Applications</h2>
            </li>
            <li>
                <h4>WhatAnime by Andrée Torres</h4>
                <div>
                    <p>Download: <a href="https://play.google.com/store/apps/details?id=com.maddog05.whatanime">https://play.google.com/store/apps/details?id=com.maddog05.whatanime</a></p>
                    <p>Source: <a href="https://github.com/maddog05/whatanime-android">https://github.com/maddog05/whatanime-android</a></p>
                </div><br>
                <h4>WhatAnime - 以图搜番 by Mystery0 (Simplified Chinese)</h4>
                <div>
                    <p>Download: <a href="https://play.google.com/store/apps/details?id=pw.janyo.whatanime">https://play.google.com/store/apps/details?id=pw.janyo.whatanime</a></p>
                    <p>Source: <a href="https://github.com/JanYoStudio/WhatAnime">https://github.com/JanYoStudio/WhatAnime</a></p>
                </div>
            </li>
        </ul>
        <ul class="wait-info__group">
            <li>
                <h2>Presentation slides</h2>
            </li>
            <li>
                <div>
                    <p><a href="https://go-talks.appspot.com/github.com/soruly/slides/whatanime.ga.slide">Go-talk presentation on 27 May 2016</a></p>
                    <p><a href="https://go-talks.appspot.com/github.com/soruly/slides/whatanime.ga-2017.slide">Go-talk presentation on 4 Jun 2017</a></p>
                    <p><a href="https://go-talks.appspot.com/github.com/soruly/slides/whatanime.ga-2018.slide">Go-talk presentation on 17 Jun 2018</a></p>
                </div>
            </li>
        </ul>
        <ul class="wait-info__group">
            <li>
                <h2>System Status</h2>
            </li>
            <li>
                <div>
                    <p>System status page: <a href="https://status.trace.moe">https://status.trace.moe</a> (Powered by UptimeRobot)</p>
                    <p><?php echo 'Last Database Index update: ' . humanTiming($lastModified) . ' ago with ' . $numDocsMillion . ' Million analyzed frames. (' . $sizeInGB . ' GB)<br>'; ?></p>
                    <p>This database automatically index most airing anime in a few hours after broadcast.<br>You may subscribe to the updates on Telegram <a href="https://t.me/whatanimeupdates">@whatanimeupdates</a></p>
                    <p><a href="https://nyaa.si/download/1023979.torrent">Full Database Dump 2018-04 (16.5GB)</a></p>
                    <p><a href="magnet:?xt=urn:btih:VUOXSGHJ5CSBP6C3KK4IZCFS7NCVXPKX&dn=whatanime.ga+database+dump+2018-04&tr=http%3A%2F%2Fnyaa.tracker.wf%3A7777%2Fannounce&tr=udp%3A%2F%2Fopen.stealth.si%3A80%2Fannounce&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337%2Fannounce&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969%2Fannounce&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969%2Fannounce">magnet:?xt=urn:btih:VUOXSGHJ5CSBP6C3KK4IZCFS7NCVXPKX</a></p>
                </div>
            </li>
        </ul>
        <ul class="wait-info__group">
            <li>
                <h2>Contact</h2>
            </li>
            <li>
                <div>
                    <p>If you have any feedback, suggestions or anything else, please email to <a href="mailto:help@trace.moe">help@trace.moe</a>.</p>
                    <p>You may also reach the author on Telegram <a href="https://t.me/soruly">@soruly</a> or <a href="https://discord.gg/K9jn6Kj">Discord</a>.</p>
                    <p>Follow the development of trace.moe and learn more about the underlying technologies on <a href="https://github.com/soruly/slides">GitHub</a>, <a href="https://www.facebook.com/whatanime.ga/">Facebook Page</a> or <a href="https://www.patreon.com/soruly">Patreon page</a>.</p>
                </div>
            </li>
        </ul>
        <ul class="wait-info__group">
            <li>
                <h2>Credits</h2>
            </li>
            <li>
                <h4>Dr. Mathias Lux (<a href="http://www.lire-project.net/">LIRE Project</a>)</h4>
                <div>
                    <p>Lux Mathias, Savvas A. Chatzichristofis. Lire: Lucene Image Retrieval – An Extensible Java CBIR Library. In proceedings of the 16th ACM International Conference on Multimedia, pp. 1085-1088, Vancouver, Canada, 2008 <a href="http://www.morganclaypool.com/doi/abs/10.2200/S00468ED1V01Y201301ICR025">Visual Information Retrieval with Java and LIRE</a></p>
                </div>
                <h4>Josh (<a href="https://anilist.co/">Anilist</a>) and Anilist team</h4>
            </li>
        </ul>
        <ul class="wait-info__group">
            <li>
                <h2>Donate</h2>
            </li>
            <li>
                <div>
                    <p>
                        <a href="https://www.paypal.me/soruly"><img class="wait-donate__btn" src="img/donate-with-paypal.png" alt="Donate with PayPal"></a>
                        <a href="https://www.patreon.com/soruly"><img class="wait-donate__btn" src="img/become_a_patron_button.png" alt="Become a Patron!"></a>
                    </p>
                    <p>Former and Current Supporters:</p>
                    <ul>
                        <li><p><a href="https://chenxublog.com">chenxuuu</a></p></li>
                        <li><p><a href="http://desmonding.me/">Desmond</a></p></li>
                        <li><p><a href="http://imvery.moe/">FangzhouL</a></p></li>
                        <li><p><a href="https://fym.moe/">FiveYellowMice</a></p></li>
                        <li><p>Snadzies</p></li>
                        <li><p><a href="https://taw.moe">thatanimeweirdo</a></p></li>
                        <li><p>WelkinWill</p></li>
                        <li><p><a href="https://twitter.com/yuriks">yuriks</a></p></li>
                        <li><p>...and dozens of anonymous donators</p></li>
                    </ul>
                    <p><strong>And of course, contributions and support from all anime lovers!</strong></p>
                </div>
            </li>
        </ul>
    </div>

    <footer class="footer">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/about">About</a></li>
                <li><a href="/changelog">Changelog</a></li>
                <li><a href="/faq">FAQ's</a></li>
                <li><a href="/terms">Terms</a></li>
            </ol>
        </div>
    </footer>
</body>

</html> 