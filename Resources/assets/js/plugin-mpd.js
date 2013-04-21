function mpdPlayer(musicPlayer) {
	this.name = "MPD";
	this.cancelRequested = false;
	this.musicPlayer = musicPlayer;
	this.currentState = null;
	this.soundmanagerPlayer = soundManager;
    this.intervale = null;
    this.currentPosition=0;
	this.currentSoundObject=null;
	var self = this;
	self.musicPlayer.cursor.progressbar();
	
	this.requestCancel=function(){
		self.cancelRequested=true;
	};
	
	this.play = function(item) {
		
		self.currentPosition=0;
		$.get(Routing.generate('_cogimix_mpd_play',{serverAlias:item.pluginProperties.alias,hash:encodeURIComponent(item.pluginProperties.hash)}),function(response){

			self.currentSoundObject=self.soundmanagerPlayer.createSound({
				  id: item.pluginProperties.hash,
				  url: response.data.streamUrl,
				  autoLoad: true,
				  multiShot: false,
				  autoPlay: true,
				  volume: self.musicPlayer.volume,
				  onplay: function(){
					 self.musicPlayer.enableControls();
				     self.musicPlayer.cursor.slider("option", "max", item.pluginProperties.duration).progressbar();			  
					 self.createCursorInterval(1000);
					 self.musicPlayer.bindCursorStop(function(value) {
							
						  self.seekTo(item.pluginProperties.alias,value);
						  
						});
				  },
				  onpause: function(){
					  self.clearInterval();
					  $.get(Routing.generate('_cogimix_mpd_pause',{serverAlias:item.pluginProperties.alias}),function(response){});
						
				  },
				  onresume:function(){
					  self.createCursorInterval(1000);
					  $.get(Routing.generate('_cogimix_mpd_pause',{serverAlias:item.pluginProperties.alias}),function(response){});
						
				  },
				  onstop: function(){
					  self.clearInterval();
					 $.get(Routing.generate('_cogimix_mpd_stop',{serverAlias:item.pluginProperties.alias}),function(response){});
					 this.destruct();
					  self.musicPlayer.cursor.slider("option", "max", 0).progressbar('value',0);
				  },
				  onfinish: function(){
					  self.clearInterval();
					  this.destruct();
					  self.musicPlayer.next();
				  },
				  
				  
				});
		},'json');
		
	
	};
	this.stop = function(){
		loggerMpd.debug('call stop soundmanager');	
		if(self.currentSoundObject !=null){
			self.currentSoundObject.stop();	
		}
	}
	
	this.pause = function(){
		loggerMpd.debug('call pause soundmanager');
		if(self.currentSoundObject !=null){
			self.currentSoundObject.pause();
		}
		
	}
	this.resume = function(){
		loggerMpd.debug('call resume soundmanager');
		if(self.currentSoundObject !=null){
			self.currentSoundObject.resume();
		}
	}
	this.setVolume = function(value){
		loggerMpd.debug('call setvolume soundmanager');
		if(self.currentSoundObject!=null){
			self.currentSoundObject.setVolume(value);
		}
	}
	
	this.seekTo = function(serverAlias,value){
		//self.currentSoundObject.unload();
		
		$.get(Routing.generate('_cogimix_mpd_seekTo',{serverAlias:serverAlias,value:value}),function(response){
			
			self.currentPosition=value;
		},'json');
	}
	this.clearInterval=function(){
		loggerMpd.debug('clear interval: '+self.interval);
		window.clearInterval(self.interval);
	};
	
	this.createCursorInterval=function(delay){
		self.clearInterval();
		self.interval = window.setInterval(function() {
			
			self.currentPosition+=1;
			loggerMpd.debug('update Interval position : '+self.currentPosition);
			if(self.musicPlayer.cursor.data('isdragging')==false){
				self.musicPlayer.cursor.slider("value", self.currentPosition);
			}
		}, delay);
		loggerMpd.debug('Interval : '+self.interval+' created');
	};

}

$(document).ready(function(){

	$cogimix.playlistContainer.on('click','.showPlaylistMPDBtn',function(event){
		var playlistElement = $(this).closest('.mpd-playlist-item');
		var playlistName = $(this).html();
		var playlistAlias = playlistElement.data('alias');
		$.get(Routing.generate('_cogimix_mpd_playlist_songs',{'serverAlias':playlistElement.data('serveralias'), 'name':playlistElement.data('id')}),function(response){
			if(response.success == true){
				renderResult(response.data.tracks,{tpl:'trackNoSortTpl',tabName:playlistName,alias:playlistAlias});
            	$("#wrap").animate({scrollTop:0});
	
			}else{
				loggerMpd.debug('Error with mpd');
			}
		},'json');
		return false;
	});
	
	$cogimix.playlistContainer.on('click','.playPlaylistMPDBtn',function(event){
		var playlistElement = $(this).closest('.mpd-playlist-item');
		var playlistName = $(this).html();
		var playlistAlias = playlistElement.data('alias');
		$.get(Routing.generate('_cogimix_mpd_playlist_songs',{'serverAlias':playlistElement.data('serveralias'), 'name':playlistElement.data('id')}),function(response){
			if(response.success == true){
				musicPlayer.removeAllSongs();
				musicPlayer.addSongs(response.data.tracks);
                musicPlayer.play();
	
			}else{
				loggerMpd.debug('Error with mpd');
			}
		},'json');
	});

});



