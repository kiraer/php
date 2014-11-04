function videoBtnClick() {
	document.getElementById('videoPlayer').play();
}
function videoStart() {
	
}
function videoEnd() {
	if (typeof videoAutoplay == 'undefined' || !videoAutoplay)
		return;
	//alert('Play Video');
	
	curVideo++;
	if (curVideo >= videoPlaylist.length)
		curVideo = 0;
	var videoPlayer = document.getElementById('videoPlayer');
	//alert(videoPlaylist[curVideo]+"xx");
	videoPlayer.src = videoPlaylist[curVideo];
	videoPlayer.load();
	videoPlayer.play();
}
/*
function playVideo (url,poster,artid,avid) {
	for (var i=0; i<videoPlaylist.length; i++) {
		if (videoPlaylist[i] == url) {
			//alert(videoPlaylist[i] + ', ' + url + ', ' + i);
			curVideo = i;
			break;
		}
	}
	
//	videoTracker(artid, avid);
	var container = document.getElementById("video_player_container");
	var video_player = document.getElementById("videoPlayer");
	var dimension = 'width="1px" height="1px"';
	
	if(is_ipad){
		container.style.width = '100%';
                container.style.height = '360px';
				container.style.marginTop = '27px';
				container.style.marginBottom = '27px';
                dimension = 'width="640px" height="360px"';
		container.innerHTML = '<video id="video_player" src="'+url+'" poster="'+poster+'" controls="control" tabindex="0" '+dimension+' style="margin:0px; padding:0px;" autoplay="autoplay"></video>';
		pageScroll();
		try{
	    	var video_player = document.getElementById("video_player");
	    	video_player.play();
	        } catch (e) {
        	}

	} else {
		video_player.setAttribute('src', url);
		video_player.play();
	}
	
	/*} else {
		
		var video_player = document.getElementById("video_player");
	        video_player.setAttribute('src', url);

		video_player.play();
	}*/
//}
/*
function audioBtnClick(play) {
	//if (curVideo < 0) {
	//	audioEnd();
	//	return;
	//}
	if (typeof audioPlaying == 'undefined')
		audioPlaying = false;
	
	audioPlaying = typeof play != 'undefined' ? play : !audioPlaying;	
	var audioPlayer = document.getElementById('audioPlayer');
	var audioBtn = document.getElementById('audioBtn');
	var audioNext = document.getElementById('audioNext');
	var audioPrev = document.getElementById('audioPrev');
	//audioBtn.innerHTML = audioPlaying ? 'Pause Audio' : 'Play Audio';
	audioBtn.className = audioPlaying ? 'audioBtn sprite-sharp sprite-npause' : 'audioBtn sprite-sharp sprite-mplay';
	//audioNext.style.visibility = audioPlaying ? 'visible' : 'hidden';
	//audioPrev.style.visibility = audioPlaying ? 'visible' : 'hidden';
	if (audioPlaying) {
		//var mediaPanel = document.getElementById('mediaPanel');
		//if (mediaPanel)		mediaPanel.style.height = "110px";
		//audioPlayer.style.display = "block";
		//audioPlayer.style.width = "100%";
		//audioPlayer.style.height = "20px";
		//audioPlayer.style.overflow = "hidden";
		playPvAudio(audioPlaylist[curAudio][0],audioPlaylist[curAudio][1],audioPlaylist[curAudio][3])
		//audioPlayer.play();
		audioPlaying = true;
	}
	else {
		//audioPlayer.style.width = "0px";
		//audioPlayer.style.height = "0px";
		//audioPlayer.style.overflow = "hidden";
		//audioPlayer.style.display = "none";
		audioPlayer.pause();
	}
}*/
/*
function playAudio(play) {
  if(Sys.chrome || Sys.safari){
	if (typeof audioPlaying == 'undefined')
		audioPlaying = false;
		
	audioPlaying = typeof play != 'undefined' ? play : !audioPlaying;
	var audioPlayer = document.getElementById('audioPlayer');
	var audioBtn = document.getElementById('audioBtn');
	audioBtn.className = audioPlaying ? 'audioBtn sprite-sharp sprite-npause' : 'audioBtn sprite-sharp sprite-mplay';
	if (audioPlaying) {		
		document.getElementById("PVVideo").src = "../../tw.nextmedia.com/other/mobilesharpdailypv/type/g/art_id/"+document.getElementById("pvArtID").value+"/psecid/"+document.getElementById("pvSecID").value+"/issueid/"+document.getElementById("pvIssueID").value;
		audioPlayer.play();
	}
	else {
		audioPlayer.pause();
	}
  }else{
      var audioBtn = document.getElementById('audioBtn');              
  	if(is_playing)
  		$("#jquery_jplayer_1").jPlayer("pause");
  	else {
  		if(current_audio != null) {
  			jaudioPlaylist.playlistChange(current_audio);
  			current_audio = null;
  		} else {
  			$("#jquery_jplayer_1").jPlayer("play");
  		}
  	} 	  
	  
	  
  }
}*/



/*
function highlightElement(id) {
	//alert($('li.list-art').attr('class'));
	$('li.list-art').css('background-color', 'white');
	
	for (var i=0; i<audioPlaylist.length; i++) {
		if (audioPlaylist[i][0] == id)
			continue;
			
		var media = document.getElementById('item_'+audioPlaylist[i][0]);
		if (media)
			media.style.backgroundColor = '#FFF';
	}
	document.getElementById('item_'+id).style.backgroundColor = 'pink';
}
*/
function audioStart() {
}
/*
function audioEnd() {
	nextAudio();
}
*/
/*
function prevAudio() {
	//if (!audioPlaying)
	//	return;		
	curAudio--;
	if (curAudio < 0)
		curAudio = audioPlaylist.length-1;
	refreshAudio();
	highlightElement(audioPlaylist[curAudio][0]);
}

function nextAudio() {
	//if (!audioPlaying)
	//	return;	
	curAudio++;
	if (curAudio >= audioPlaylist.length){
		curAudio = 0;
		
	}else{
		refreshAudio();
		highlightElement(audioPlaylist[curAudio][0]);
	}
			
}*/
/*
function refreshAudio() {
	var audioPlayer = document.getElementById('audioPlayer');
	audioPlayer.setAttribute('src', audioPlaylist[curAudio][2]);
	//alert('Play Audio ' + audioPlaylist[curAudio][1]);
	console.log(audioPlaylist[curAudio][2]);
	playPvAudio(audioPlaylist[curAudio][0],audioPlaylist[curAudio][1],audioPlaylist[curAudio][3])
	//audioPlayer.play();
	audioPlaying = true;
}
*/
/*
function playPvAudio(art_id,issueid,secid){
			document.getElementById("PVVideo").src = "../../tw.nextmedia.com/other/mobilesharpdailypv/type/g/art_id/"+art_id+"/psecid/"+secid+"/issueid/"+issueid;
			audioPlayer.play();		
	}
	*/
/*
function jumpToAudio(art_id) {
	var found = false;
	for (var i=0; i<audioPlaylist.length; i++) {
		if (audioPlaylist[i][0] == art_id) {
			found = true;
			curAudio = i;
			break;
		}
	}
	if (!found)
		return;		
	refreshAudio();
}*/