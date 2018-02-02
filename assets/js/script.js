let currentPlaylist;
let tempSongIds;
let tempPlaylist = [];
let audioElement;
let mouseDown = false;
let currentIndex = 0;
let repeat = false;
let shuffle = false;
let shufflePlaylist = [];
let timer;

function openPage(url) {
	if (timer !== null) {
		clearTimeout(timer);
	}

	if (url.indexOf('?') === -1) {
		url = url + '?';
	}

	let encodedUrl = encodeURI(`${url}&userLoggedIn=${userLoggedIn}`);
	$('#mainContent').load(encodedUrl);
	$('body').scrollTop(0);
	history.pushState(null, null, url);
}

function createPlaylist() {
	let popup = prompt('Please enter the name of your playlist:');

	if (popup) {
		$.post('includes/handlers/ajax/createPlaylist.php', { name: popup, username: userLoggedIn }).done(function(
			error
		) {
			if (error) {
				alert(error);
				return;
			}

			openPage('yourMusic.php');
		});
	}
}

function deletePlaylist(playlistId) {
	let prompt = confirm('Are you sure you want to delete this playlist?');

	if (prompt) {
		$.post('includes/handlers/ajax/deletePlaylist.php', { playlistId }).done(function(error) {
			if (error) {
				alert(error);
				return;
			}

			openPage('yourMusic.php');
		});
	}
}

function hideOptionsMenu() {
	let menu = $('.optionsMenu');

	if (menu.css('display') !== 'none') {
		menu.css('display', 'none');
	}
}

function showOptionsMenu(button) {
	let songId = $(button)
		.prevAll('.songId')
		.val();
	let menu = $('.optionsMenu');
	let menuWidth = menu.width();
	let scrollTop = $(window).scrollTop();
	let elementOffset = $(button).offset().top;
	let top = elementOffset - scrollTop;
	let left = $(button).position().left;

	menu.find('.songId').val(songId);
	menu.css({ top: top + 'px', left: left - menuWidth + 'px', display: 'inline' });
}

$(document).ready(() => {
	audioElement = new Audio();
	audioElement.audio.volume = 1;

	setTrack(newPlaylist[0], newPlaylist, false);

	$('#nowPlayingBarContainer').on('mousedown touchstart mousemove touchmove', e => {
		e.preventDefault();
	});

	let $progressBar = $('.playbackBar .progressBar');
	let $volumeBar = $('.volumeBar .progressBar');

	$progressBar.mousedown(() => {
		mouseDown = true;
	});

	$progressBar.mousemove(function(e) {
		if (mouseDown) {
			timeFromOffset(e, this);
		}
	});

	$progressBar.mouseup(function(e) {
		timeFromOffset(e, this);
	});

	$volumeBar.mousedown(() => {
		mouseDown = true;
	});

	$volumeBar.mousemove(function(e) {
		if (mouseDown) {
			let percentage = e.offsetX / $(this).width();

			if (percentage >= 0 && percentage <= 1) {
				audioElement.audio.volume = percentage;
			}
		}
	});

	$volumeBar.mouseup(function(e) {
		let percentage = e.offsetX / $(this).width();

		if (percentage >= 0 && percentage <= 1) {
			audioElement.audio.volume = percentage;
		}
	});

	$(document).mouseup(() => {
		mouseDown = false;
	});

	$(window).scroll(function() {
		hideOptionsMenu();
	});

	$(document).click(function(click) {
		let target = $(click.target);

		if (!target.hasClass('item') && !target.hasClass('optionsButton')) {
			hideOptionsMenu();
		}
	});

	$(document).on('change', 'select.playlist', function() {
		let select = $(this);
		let playlistId = select.val();
		let songId = select.prev('.songId').val();

		$.post('includes/handlers/ajax/addToPlaylist.php', { playlistId, songId }).done(function(error) {
			if (error) {
				alert(error);
				return;
			}

			hideOptionsMenu();
			select.val('');
		});
	});
});

class Audio {
	constructor() {
		this.currentlyPlaying = null;
		this.audio = document.createElement('audio');

		this.audio.addEventListener('ended', () => {
			nextSong();
		});

		this.audio.addEventListener('canplay', function() {
			let duration = formatTime(this.duration);
			$('.progressTime.remaining').text(duration);
			updateVolumeProgressBar(this);
		});

		this.audio.addEventListener('timeupdate', function() {
			if (this.duration) {
				updateTimeProgressBar(this);
			}
		});

		this.audio.addEventListener('volumechange', function() {
			updateVolumeProgressBar(this);
		});
	}

	setTrack(track) {
		this.currentlyPlaying = track;
		this.audio.src = track.path;
	}

	play() {
		this.audio.play();
	}

	pause() {
		this.audio.pause();
	}

	setTime(seconds) {
		this.audio.currentTime = seconds;
	}
}

function playFirstSong() {
	setTrack(tempPlaylist[0], tempPlaylist, true);
}

function setTrack(trackId, newPlaylist, play) {
	if (newPlaylist !== currentPlaylist) {
		currentPlaylist = newPlaylist;
		shufflePlaylist = currentPlaylist.slice();
		randomizePlaylist(shufflePlaylist);
	}

	if (shuffle) {
		currentIndex = shufflePlaylist.indexOf(trackId);
	} else {
		currentIndex = currentPlaylist.indexOf(trackId);
	}

	pauseSong();

	$.post('includes/handlers/ajax/getSongJson.php', { trackId }, data => {
		let track = JSON.parse(data);
		let $trackSpan = $('.trackInfo .trackName span');

		$trackSpan.text(track.title);

		$.post('includes/handlers/ajax/getArtistJson.php', { artistId: track.artist }, data => {
			let artist = JSON.parse(data);
			let $artistSpan = $('.trackInfo .artistName span');

			$artistSpan.text(artist.name);
			$artistSpan.attr('onclick', `openPage("artist.php?id=${artist.id}")`);
		});

		$.post('includes/handlers/ajax/getAlbumJson.php', { albumId: track.album }, data => {
			let album = JSON.parse(data);
			let $img = $('.content .albumLink img');

			$img.attr('src', album.artworkPath);
			$img.attr('alt', album.title);
			$img.attr('onclick', `openPage("album.php?id=${album.id}")`);
			$trackSpan.attr('onclick', `openPage("album.php?id=${album.id}")`);
		});

		audioElement.setTrack(track);

		if (play) {
			playSong();
		}
	});
}

function playSong() {
	if (audioElement.audio.currentTime === 0) {
		$.post('includes/handlers/ajax/updatePlays.php', { songId: audioElement.currentlyPlaying.id });
	}

	$('.controlButton.play').hide();
	$('.controlButton.pause').show();
	audioElement.play();
}

function pauseSong() {
	$('.controlButton.play').show();
	$('.controlButton.pause').hide();
	audioElement.pause();
}

function nextSong() {
	if (repeat) {
		audioElement.setTime(0);
		playSong();
		return;
	}

	if (currentIndex === currentPlaylist.length - 1) {
		currentIndex = 0;
	} else {
		currentIndex++;
	}

	let trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
	setTrack(trackToPlay, currentPlaylist, true);
}

function prevSong() {
	if (audioElement.audio.currentTime >= 3 || currentIndex === 0) {
		audioElement.setTime(0);
	} else {
		currentIndex--;
		setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
	}
}

function setRepeat() {
	repeat = !repeat;
	let imageName = repeat ? 'repeat-active.png' : 'repeat.png';
	$('.controlButton.repeat img').attr('src', `assets/images/icons/${imageName}`);
}

function setMute() {
	audioElement.audio.muted = !audioElement.audio.muted;
	let imageName = audioElement.audio.muted ? 'volume-mute.png' : 'volume.png';
	$('.controlButton.volume img').attr('src', `assets/images/icons/${imageName}`);
}

function setShuffle() {
	shuffle = !shuffle;
	let imageName = shuffle ? 'shuffle-active.png' : 'shuffle.png';
	$('.controlButton.shuffle img').attr('src', `assets/images/icons/${imageName}`);

	if (shuffle) {
		randomizePlaylist(shufflePlaylist);
		currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
	} else {
		currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
	}
}

function randomizePlaylist(playlist) {
	for (let i = playlist.length; i; i--) {
		let j = Math.floor(Math.random() * i);
		[playlist[i - 1], playlist[j]] = [playlist[j], playlist[i - 1]];
	}
}

function formatTime(seconds) {
	let time = Math.round(seconds);
	let minutes = Math.floor(time / 60);
	seconds = time - minutes * 60;

	let extraZero = seconds < 10 ? '0' : '';

	return `${minutes}:${extraZero}${seconds}`;
}

function updateTimeProgressBar(audio) {
	$('.progressTime.current').text(formatTime(audio.currentTime));
	$('.progressTime.remaining').text(formatTime(audio.duration - audio.currentTime));

	let progress = audio.currentTime / audio.duration * 100;
	$('.playbackBar .progress').css('width', progress + '%');
}

function updateVolumeProgressBar(audio) {
	let volume = audio.volume * 100;
	$('.volumeBar .progress').css('width', volume + '%');
}

function timeFromOffset(mouse, progressBar) {
	let percentage = mouse.offsetX / $(progressBar).width() * 100;
	let seconds = audioElement.audio.duration * (percentage / 100);
	audioElement.setTime(seconds);
}
