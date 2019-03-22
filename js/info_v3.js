
var showAnilistInfo = function (anilistID) {
  var xhr = new XMLHttpRequest();

  xhr.open("GET", "/info?anilist_id=" + anilistID, true);
  xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      var data = JSON.parse(xhr.responseText);

      if (data.length > 0) {
        displayInfo(data[0]);
        document.querySelector("#info").style.display = "inline-block";
        document.querySelector("#info").style.visibility = "visible";
        document.querySelector("#info").style.opacity = 1;
      }
    }
  };
  xhr.send();
};

var displayInfo = function (src) {
  var title = document.createElement("h1");
  title.innerText = src.title.native;
  title.style = "font-size:1.5em";
  document.querySelector("#info").appendChild(title);
  var title2 = document.createElement("h2");
  title2.innerText = src.title.romaji;
  document.querySelector("#info").appendChild(title2);
  var title3 = document.createElement("h2");
  title3.innerText = src.title.english;
  document.querySelector("#info").appendChild(title3);
  var title4 = document.createElement("h2");
  title4.innerText = src.title.chinese;
  document.querySelector("#info").appendChild(title4);
  var line = document.createElement("div");
  line.style = "clear:both; border-bottom:1px solid #666; margin-bottom:13px";
  document.querySelector("#info").appendChild(line);

  if (src.coverImage.large) {
    var img = document.createElement("img");
    img.src = src.coverImage.large.replace("http:", "");
    var a = document.createElement("a");
    a.href = "//anilist.co/anime/" + src.id;
    var div = document.createElement("div");
    div.id = "poster";
    a.appendChild(img);
    div.appendChild(a);
    document.querySelector("#info").appendChild(div);
  }

  var naturalText = "";

  if (src.duration) {
    if (src.episodes === 1) {
      naturalText += src.duration + " minutes";
    }
  }

  if (src.episodes) {
    if (src.format !== "MOVIE") {
      naturalText += src.episodes + " episode ";
    }
  }
  if (src.format) {
    naturalText += src.format;
  }
  naturalText += " Anime";

  if (src.duration) {
    if (src.episodes > 1) {
      naturalText += " (" + src.duration + " minutes each)";
    }
  }

  var strStartDate = src.startDate && src.startDate.year && src.startDate.month && src.startDate.day ? src.startDate.year + "-" + src.startDate.month + "-" + src.startDate.day : null;
  var strEndDate = src.endDate && src.endDate.year && src.endDate.month && src.endDate.day ? src.endDate.year + "-" + src.endDate.month + "-" + src.endDate.day : null;

  if (strStartDate && strEndDate) {
    if (src.format === "MOVIE") {
      if (strStartDate === strEndDate) {
        naturalText += ". Released on " + strStartDate;
      } else {
        naturalText += ". Released during " + strStartDate + " to " + strEndDate;
      }
    } else if (strStartDate === strEndDate) {
      naturalText += ". Released on " + strStartDate;
    } else {
      naturalText += ". Airing from " + strStartDate + " to " + strEndDate;
    }
  } else if (strStartDate) {
    if (src.format === "TV" || src.format === "TV_SHORT") {
      naturalText += ". Airing since " + strStartDate;
    }
  }

  naturalText += ". ";
  var divDescription = document.createElement("div");
  divDescription.id = "naturalText";
  divDescription.innerText = naturalText;
  document.querySelector("#info").appendChild(divDescription);

  var table = document.createElement("table");
  table.id = "table";

  var tr1 = document.createElement("tr");
  var td = document.createElement("td");
  td.innerText = "Score";
  tr1.appendChild(td);
  var td2 = document.createElement("td");
  td2.innerText = src.averageScore > 0 ? parseFloat(src.averageScore).toFixed(1) : "-";
  tr1.appendChild(td2);
  table.appendChild(tr1);

  var tr2 = document.createElement("tr");
  var td3 = document.createElement("td");
  td3.innerText = "Popularity";
  tr2.appendChild(td3);
  var td4 = document.createElement("td");
  td4.innerText = src.popularity;
  tr2.appendChild(td4);
  table.appendChild(tr2);

  var tr3 = document.createElement("tr");
  var td5 = document.createElement("td");
  td5.innerText = "Drop rate";
  tr3.appendChild(td5);
  var td6 = document.createElement("td");
  td6.innerText = src.popularity > 0 ? (src.stats.statusDistribution.filter(function (e) {
    return e.status === "DROPPED";
  })[0].amount / src.popularity * 100).toFixed(1) + "%" : "-";
  tr3.appendChild(td6);
  table.appendChild(tr3);

  if (src.genres.length > 0) {
    var tr4 = document.createElement("tr");
    var td7 = document.createElement("td");
    td7.innerText = "Genre";
    tr4.appendChild(td7);
    var td8 = document.createElement("td");
    td8.innerText = src.genres.join(", ");
    tr4.appendChild(td8);
    table.appendChild(tr4);
  }

  if (src.studios && src.studios && src.studios.edges.length > 0) {
    var tr5 = document.createElement("tr");
    var td9 = document.createElement("td");
    td9.innerText = "Studio";
    tr5.appendChild(td9);
    var td10 = document.createElement("td");
    src.studios.edges.forEach(function (entry) {
      if (entry.node.siteUrl) {
        var a2 = document.createElement("a");
        a2.href = entry.node.siteUrl;
        a2.innerText = entry.node.name;
        td10.appendChild(a2);
      } else {
        var span = document.createElement("span");
        span.innerText = entry.node.name;
        td10.appendChild(span);
      }
      td10.appendChild(document.createElement("br"));
    });
    tr5.appendChild(td10);
    table.appendChild(tr5);
  }

  if (src.synonyms.length > 0) {
    var tr6 = document.createElement("tr");
    var td11 = document.createElement("td");
    td11.innerText = "Alias";
    tr6.appendChild(td11);
    var td12 = document.createElement("td");
    src.synonyms.forEach(function (entry) {
      td12.appendChild(document.createTextNode(entry));
      td12.appendChild(document.createElement("br"));
    });
    tr6.appendChild(td12);
    table.appendChild(tr6);
  }
  if (src.synonyms_chinese.length > 0 && Array.isArray(src.synonyms_chinese)) {
    var tr7 = document.createElement("tr");
    var td13 = document.createElement("td");
    td13.innerText = "Alias";
    tr7.appendChild(td13);
    var td14 = document.createElement("td");
    src.synonyms_chinese.forEach(function (entry) {
      td14.appendChild(document.createTextNode(entry));
      td14.appendChild(document.createElement("br"));
    });
    tr7.appendChild(td14);
    table.appendChild(tr7);
  }

  if (src.externalLinks && src.externalLinks.length > 0) {
    var tr8 = document.createElement("tr");
    var td15 = document.createElement("td");
    td15.innerText = "External Links";
    tr8.appendChild(td15);
    var td16 = document.createElement("td");
    src.externalLinks.forEach(function (entry) {
      var a3 = document.createElement("a");
      a3.href = entry.url;
      a3.innerText = entry.site + " ";
      td16.appendChild(a3);
      td16.appendChild(document.createElement("br"));
    });
    tr8.appendChild(td16);
    table.appendChild(tr8);
  }
  document.querySelector("#info").appendChild(table);

  if (src.staff && src.staff.edges && src.staff.edges.length > 0) {
    var br2 = document.createElement("br");
    br2.style = "clear:both";
    document.querySelector("#info").appendChild(br2);
    var h3 = document.createElement("h3");
    h3.innerText = "Staff";
    document.querySelector("#info").appendChild(h3);
    var div2 = document.createElement("div");
    div2.style = "clear:both; border-bottom:1px solid #666; margin-bottom:3px";
    document.querySelector("#info").appendChild(div2);

    var table2 = document.createElement("table");
    table2.id = "staff";

    src.staff.edges.forEach(function (entry) {
      var tr9 = document.createElement("tr");
      var name = entry.node.name.native;

      if (!name && entry.node.name.first && entry.node.name.last) {
        name = entry.node.name.last + " " + entry.node.name.first;
      }
      var td17 = document.createElement("td");
      td17.innerText = entry.role;
      tr9.appendChild(td17);
      var td18 = document.createElement("td");
      var a4 = document.createElement("a");
      a4.className = "staff_" + entry.node.id;
      a4.href = "//anilist.co/staff/" + entry.node.id;
      a4.innerText = name;
      td18.appendChild(a4);
      tr9.appendChild(td18);
      table2.appendChild(tr9);
    });
    document.querySelector("#info").appendChild(table2);
  }

  if (src.trailer && src.trailer && src.trailer.site === "youtube") {
    var br3 = document.createElement("br");
    br3.style = "clear:both";
    document.querySelector("#info").appendChild(br3);
    var h32 = document.createElement("h3");
    h32.innerText = "Youtube PV";
    document.querySelector("#info").appendChild(h32);
    var div3 = document.createElement("div");
    div3.style = "clear:both; border-bottom:1px solid #666; margin-bottom:3px";
    document.querySelector("#info").appendChild(div3);
    var div4 = document.createElement("div");
    div4.innerHTML = "<iframe id=\"youtube\" width=\"100%\" height=\"360\" src=\"https://www.youtube.com/embed/" + src.trailer.id + "\" frameborder=\"0\" allowfullscreen></iframe>";
    document.querySelector("#info").appendChild(div4);
  }
  var br4 = document.createElement("br");
  br4.style = "clear:both";
  document.querySelector("#info").appendChild(br4);
  var h33 = document.createElement("h3");
  h33.innerText = "Synopsis";
  document.querySelector("#info").appendChild(h33);
  var div5 = document.createElement("div");
  div5.style = "clear:both; border-bottom:1px solid #666; margin-bottom:3px";
  document.querySelector("#info").appendChild(div5);
  var div6 = document.createElement("div");
  div6.style = "text-align:justify";
  div6.innerHTML = src.description;
  document.querySelector("#info").appendChild(div6);

  if (src.characters && src.characters.edges && src.characters.edges.length > 0) {
    var br5 = document.createElement("br");
    br5.style = "clear:both";
    document.querySelector("#info").appendChild(br5);
    var h34 = document.createElement("h3");
    h34.innerText = "Characters";
    document.querySelector("#info").appendChild(h34);
    var div7 = document.createElement("div");
    div7.style = "clear:both; border-bottom:1px solid #666; margin-bottom:3px";
    document.querySelector("#info").appendChild(div7);

    var characterDIV = document.createElement("div");

    src.characters.edges.forEach(function (entry) {
      var charDIV = document.createElement("div");
      charDIV.className = "character";
      var charImgDiv = document.createElement("div");

      if (entry.node.image.large === "//anilist.co") {
        entry.node.image.large = "//anilist.co/img/dir/anime/reg/noimg.jpg";
      }
      if (entry.node.image.medium === "//anilist.co") {
        entry.node.image.medium = "//anilist.co/img/dir/anime/med/noimg.jpg";
      }
      var charIMG = document.createElement("a");
      charIMG.href = entry.node.image.large.replace("http:", "");
      charImgDiv.appendChild(charIMG);

      var div8 = document.createElement("div");
      div8.style = "background-image:url(" + entry.node.image.medium.replace("http:", "") + ")";
      charIMG.appendChild(div8);
      charDIV.appendChild(charImgDiv);
      var charNameDiv = document.createElement("div");
      var charName = document.createElement("div");
      var char_name = entry.node.name.native;

      if (!char_name && entry.node.name.first && entry.node.name.last) {
        char_name = entry.node.name.last + " " + entry.node.name.first;
      }
      charName = document.createElement("div");

      var a5 = document.createElement("a");
      a5.className = "character_" + entry.node.id;
      a5.href = "//anilist.co/character/" + entry.node.id;
      a5.innerText = char_name;
      charName.appendChild(a5);
      if (entry.voiceActors && entry.voiceActors.length > 0) {
        charName.appendChild(document.createElement("br"));
        var name = entry.voiceActors[0].name.native;

        if (!name && entry.voiceActors[0].name.first && entry.voiceActors[0].name.last) {
          name = entry.voiceActors[0].name.last + " " + entry.voiceActors[0].name.first;
        }
        charName.appendChild(document.createTextNode("(CV: "));
        var a6 = document.createElement("a");
        a6.className = "staff_" + entry.voiceActors[0].id;
        a6.href = "//anilist.co/staff/" + entry.voiceActors[0].id;
        a6.innerText = name;
        charName.appendChild(a6);
        charName.appendChild(document.createTextNode(")"));
      }
      charNameDiv.appendChild(charName);
      charDIV.appendChild(charNameDiv);
      characterDIV.appendChild(charDIV);
    });
    document.querySelector("#info").appendChild(characterDIV);
  }

  var div9 = document.createElement("div");
  div9.style = "clear:both; border-bottom:1px solid #666; margin-bottom:3px";
  document.querySelector("#info").appendChild(div9);
  var div10 = document.createElement("div");
  div10.innerHTML = "Information provided by <a href=\"https://anilist.co\">anilist.co</a>";
  div10.style = "float:right;font-size:12px";
  document.querySelector("#info").appendChild(div10);
  var div11 = document.createElement("div");
  div11.style = "clear:both";
  document.querySelector("#info").appendChild(div11);
};
