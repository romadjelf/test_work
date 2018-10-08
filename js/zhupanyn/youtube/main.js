function Youtube() {

    this.player = null;
    this.playing = false;
}

Youtube.prototype.getPlayer = function (id_youtube, player_id) {

    this.player = new YT.Player(player_id, {
        height: '270',
        width: '480',
        videoId: id_youtube,
        /*events: {
        }*/
    });
};

Youtube.prototype.playVideo = function () {

    this.player.playVideo();
    if (!this.playing) {
        this.playing = true;
    }
};

Youtube.prototype.pauseVideo = function () {

    if (this.playing == true){
        this.player.pauseVideo();
    }
};

Youtube.prototype.stopVideo = function () {

    if (this.playing == true){
        this.player.stopVideo();
    }
};

var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);


var ZhupanynYoutube = new Youtube();

