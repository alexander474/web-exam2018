#How to use the the googlemap code?
<br />
Call the map by using the shortcode [set_google_map]<br />
<br />
[PROTOCOL]://[URL]/[USER]/[FILEPATH]?origin=[STARTINGPOINT]&destination=[ENDPOINT]&transporttype=[TRANSPORTTYPE]<br />
<br />
[PROTOCOL]://[URL]/[USER]/[FILEPATH]?origin=otori&orilat=[latitude]&orilng=[longtitude]&destination=otdest&destlat=[latitude]&destlng=[longtitude]&transporttype=[TRANSPORTTYPE]&search=[Search-category]<br />
<br />
<br />
[STARTINGPOINT] -> Put you're starting point (example fjerdingen).<br />
[ENDPOINT] -> Put you're ending point (example vulkan). if the name of the endpoint dosen't exist then use the word otDest and specify lat/lng<br />
[TRANSPORTTYPE] -> Put you're transport type (example DRIVING, WALKING, BICYCLE).<br />
[Search-category] -> nearby markers given by category (example cafe)<br />
<br />
origin=otori&orilat=[latitude]&orilng=[longtitude] -> put you're latitude and longtitude of origin/start point<br />
destination=otdest&destlat=[latitude]&destlng=[longtitude] -> put you're latitude and logtitude of destination/stop point<br />
<br />
<br />
<br />
[STARTINGPOINT] REQUIRED (startpoint is by default set to current campus)<br />
[ENDPOINT] OPTIONAL (REQUIRED if you are using directions)<br />
[TRANSPORTTYPE] OPTIONAL (REQUIRED if you are getting directions)<br />
[Search-category] OPTIONAL<br />
[latitude] OPTIONAL (REQUIRED if you are using querys: otori, otdest, orilat, orilng, destlat, destlng)<br />
[longtitude] OPTIONAL (REQUIRED if you are using querys: otori, otdest, orilat, orilng, destlat, destlng)<br />
<br />
<br />
<br />
##Example on URL query
<br />
https://tek.westerdals.no/~breale17/wp/googlemapsjavascripttest/?origin=fjerdingen&destination=vulkan&transporttype=TRANSIT<br />
?origin=fjerdingen&destination=otDest&destlat=59.9231527&destlng=10.7516181&transporttype=TRANSIT<br />
<br />
<br />
Available named points:<br />
vulkan<br />
fjerdingen<br />
brenneriveien<br />
kirkegata<br />
<br />
<br />
Available transport types:<br />
TRANSIT<br />
DRIVING<br />
WALKING<br />
BICYCLE<br />
<br />
<br />
Example of coordinates:<br />
lat: 59.9231527 lng: 10.7516181<br />
lat: 59.9160546 lng: 10.7586542<br />
lat: 59.9201627 lng: 10.7506793<br />
lat: 59.9111719 lng: 10.742772<br />
<br />
Example of search:<br />
cafe<br />
park<br />
gym<br />
bar<br />
etc.. <br />
