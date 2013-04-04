function mpdPlayer(musicPlayer) {
	this.name = "MPD";
	this.musicPlayer = musicPlayer;
	this.currentState = null;
	this.soundmanagerPlayer = soundManager;

	this.currentSoundObject=null;
	var self = this;
	self.musicPlayer.cursor.progressbar();
	this.play = function(item) {
		
		
		$.get(Routing.generate('_cogimix_mpd_play',{serverAlias:item.pluginProperties.alias,hash:encodeURIComponent(item.pluginProperties.hash)}),function(response){

			self.currentSoundObject=self.soundmanagerPlayer.createSound({
				  id: item.pluginProperties.hash,
				  url: response.data.streamUrl,
				  autoLoad: true,
				  autoPlay: true,
				  volume: 50,
				  onload: function() {
					  self.musicPlayer.enableControls();
					  self.musicPlayer.cursor.slider("option", "max", this.duration).progressbar();			  
					  self.musicPlayer.bindCursorStop(function(value) {
						  self.currentSoundObject.setPosition(value);
						});

				  },
				  onpause: function(){
					  $.get(Routing.generate('_cogimix_mpd_pause',{serverAlias:item.pluginProperties.alias}),function(response){});
						
				  },
				  onresume:function(){
					  $.get(Routing.generate('_cogimix_mpd_pause',{serverAlias:item.pluginProperties.alias}),function(response){});
						
				  },
				  onstop: function(){
					 $.get(Routing.generate('_cogimix_mpd_stop',{serverAlias:item.pluginProperties.alias}),function(response){});
					 this.destruct();
					  self.musicPlayer.cursor.slider("option", "max", 0).progressbar('value',0);
				  },
				  onfinish: function(){
					  this.destruct();
					  self.musicPlayer.next();
				  },
				  whileplaying: function(){

					  if(self.musicPlayer.cursor.data('isdragging')==false){
					  self.musicPlayer.cursor.slider("value", this.position);
					  }
				  },
				  
				  
				});
		},'json');
		
	
	};
	this.stop = function(){
		console.log('call stop soundmanager');	
		self.currentSoundObject.stop();	
	}
	
	this.pause = function(){
		console.log('call pause soundmanager');
		self.currentSoundObject.pause();
		
	}
	this.resume = function(){
		console.log('call resume soundmanager');
		self.currentSoundObject.resume();
	}
	

}

$(document).ready(function(){
	$("#playlist-container").on('click','.showPlaylistMpdBtn',function(event){
		var playlistElement = $(this).closest('.mpd-playlist-item');
		var playlistName = $(this).html();
		var playlistAlias = playlistElement.data('alias');
		$.get(Routing.generate('_cogimix_mpd_playlist_songs',{'serverAlias':playlistElement.data('serveralias'), 'name':playlistElement.data('id')}),function(response){
			if(response.success == true){
				renderResult(response.data.tracks,{tpl:'track',tabName:playlistName,alias:playlistAlias});
            	$("#wrap").animate({scrollTop:0});
	
			}else{
				console.log('Error with mpd');
			}
		},'json');
		return false;
	});

});



