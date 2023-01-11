General description

Start running the docker container using "init.d/init.up.sh"
After the container is started, you can access it at http://localhost:12000/

====
Available parameters:
width,
height,
mode,
title
====

Example :
http://localhost:12000/Johnrogershousemay2020.webp/?width=250&height=250&mode=resize&title=my name is john house webp RESIZE


===

Other specification

app.php - contain the constants and autoload to vendor packages
handler.php - stand-alone page that handle the request
index.php - default webserver page

===

git rm --cached -r .idea

