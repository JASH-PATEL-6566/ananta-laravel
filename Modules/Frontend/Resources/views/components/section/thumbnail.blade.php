{{-- 
<div class="detail-page-banner">
        <div class="video-player">

            @if($type=='Local')

            <video id="videoPlayer" class="video-js vjs-default-skin" controls  width="560"
            height="315"
            autoplay="{{ auth()->check() ? 'true' : 'false' }}"
            muted
            data-setup="{}"
              poster="{{$thumbnail_image}}"
                data-setup='{"autoplay": {{ auth()->check() ? 'true' : 'false' }}, "muted": true}'>
            <source src="{{ $data }}" type="video/mp4" id="videoSource">

          </video>


            @else

            <!-- Video.js Player -->
            <video
                id="videoPlayer"
                class="video-js vjs-default-skin"
                controls
                width="560"
                height="315"
                autoplay="{{ auth()->check() ? 'true' : 'false' }}"
                muted
                data-watch-time="{{$watched_time??0}}"
                data-movie-access="{{$dataAccess??''}}"
                data-encrypted="{{ $data }}"
                 poster="{{$thumbnail_image}}"
                data-setup='{"autoplay": {{ auth()->check() ? 'true' : 'false' }}, "muted": true}'>

            </video>
            @endif

        </div>
</div>





<!-- Include the custom JS -->
<script src="{{ asset('js/videoplayer.min.js') }}"></script>
<script>
    var isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
    var loginUrl = "{{ route('login') }}";  // Update with your actual login route
</script> --}}
{{-- <style>
  #quality-controls {
    margin-top: 15px;
    text-align: center;
  }
  select {
    padding: 6px 10px;
    font-size: 16px;
  }
</style> --}}
<style>
  body {
    background-color: #111;
    color: white;
    font-family: Arial, sans-serif;
    padding: 20px;
  }
  .video-container {
    width: 90%;
    max-width: 960px;
    margin: auto;
  }
  .controls {
    margin-top: 10px;
    text-align: center;
  }
  select {
    margin: 0 10px;
    padding: 6px 10px;
    font-size: 16px;
    background: #222;
    color: #fff;
    border: 1px solid #444;
    border-radius: 5px;
  }
  .vjs-big-play-button {
    background-color: #d9ed38 !important;
    color: black;
  }
  .video-js .vjs-control-bar {
    background-color: rgba(0, 0, 0, 0.7);
  }
</style>
<div class="detail-page-banner">
        <div class="video-player">

            @if($type=='Local')

            <video id="videoPlayer" class="video-js vjs-default-skin" controls  width="560"
            height="315"
            autoplay="{{ auth()->check() ? 'true' : 'false' }}"
            muted
            data-setup="{}"
              poster="{{$thumbnail_image}}"
                data-setup='{"autoplay": {{ auth()->check() ? 'true' : 'false' }}, "muted": true}'>
            <source src="{{ $data }}" type="video/mp4" id="videoSource">

          </video>


            @else

            <!-- Video.js Player -->
            {{-- <video
    id="videoPlayer"
    class="video-js vjs-default-skin"
    controls
    width="560"
    height="315"
    autoplay="{{ auth()->check() ? 'true' : 'false' }}"
    muted
    data-watch-time="{{$watched_time??0}}"
    data-movie-access="{{$dataAccess??''}}"
    data-encrypted="{{ $data }}"
    poster="{{$thumbnail_image}}"
    data-setup='{"autoplay": {{ auth()->check() ? 'true' : 'false' }}, "muted": true}'>
</video>
<div id="quality-controls">
    <label for="quality">Quality:</label>
    <select id="quality">
        <option>Loading...</option>
    </select>
</div>

<!-- Video.js JS -->
<script src="https://vjs.zencdn.net/8.10.0/video.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

<script>
    const video = document.getElementById('videoPlayer');
    const qualitySelector = document.getElementById('quality');

    const basePath = 'https://ananta-video-s3.s3.ca-central-1.amazonaws.com/processed/inputfinal';

    const qualityOptions = [
        { label: '1080p', url: `${basePath}/1080p/1080p.m3u8` },
        { label: '720p',  url: `${basePath}/720p/720p.m3u8` },
        { label: '360p',  url: `${basePath}/360p/360p.m3u8` }
    ];

    // Populate the dropdown
    qualityOptions.forEach(opt => {
        const option = document.createElement('option');
        option.value = opt.url;
        option.textContent = opt.label;
        qualitySelector.appendChild(option);
    });

    let hls;
    let currentTime = 0;

    // Function to load the video stream
    function loadVideo(url) {
        if (hls) {
            hls.destroy(); // clean up previous instance
        }

        if (Hls.isSupported()) {
            hls = new Hls();
            hls.loadSource(url);
            hls.attachMedia(video);
            hls.on(Hls.Events.MANIFEST_PARSED, function () {
                video.play();
            });
            hls.on(Hls.Events.LEVEL_SWITCHED, function () {
                // Maintain the current time when switching quality levels
                video.currentTime = currentTime;  
            });
            hls.on(Hls.Events.ERROR, function (event, data) {
                console.error('HLS error:', data);
                if (data.fatal) {
                    alert("An error occurred while loading the video.");
                }
            });
        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            video.src = url;
            video.addEventListener('loadedmetadata', () => {
                video.play();
                video.currentTime = currentTime;  // Restore position
            });
        } else {
            alert("HLS not supported in this browser.");
        }

        // Enable seeking functionality
        video.addEventListener('seeking', function () {
            currentTime = video.currentTime;  // Save the time when seeking
        });

        video.addEventListener('seeked', function () {
            currentTime = video.currentTime;  // Update time after seek is complete
        });
    }

    // Load the default video source
    loadVideo(qualityOptions[0].url);

    // Handle quality change
    qualitySelector.addEventListener('change', function () {
        const selectedUrl = this.value;
        currentTime = video.currentTime;  // Save current playback time before switching
        loadVideo(selectedUrl);
    });

    // Fullscreen fix: Ensuring video is not reset when switching to fullscreen
    video.addEventListener('fullscreenchange', function () {
        if (document.fullscreenElement) {
            // When entering fullscreen, do nothing but prevent reload.
            console.log('Fullscreen mode activated');
        } else {
            // When exiting fullscreen, we ensure that the video doesn't reload.
            video.play();
            video.currentTime = currentTime;
        }
    });

</script> --}}
<div class="video-container">
  <video
    id="my-video"
    class="video-js vjs-default-skin vjs-big-play-centered"
    controls
    preload="auto"
    width="960"
    height="540"
    data-setup='{}'
  >
    <source src="https://ananta-video-s3.s3.ca-central-1.amazonaws.com/processed/inputfinal/master.m3u8" type="application/x-mpegURL">
  </video>

  <div class="controls">
    <label for="qualitySelect">🔽 Quality:</label>
    <select id="qualitySelect">
      <option>Loading...</option>
    </select>

    <label for="audioSelect">🔈 Audio:</label>
    <select id="audioSelect">
      <option>Loading...</option>
    </select>
  </div>
</div>

<!-- Scripts -->
<script src="https://vjs.zencdn.net/8.10.0/video.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/videojs-contrib-quality-levels@3.0.0/dist/videojs-contrib-quality-levels.min.js"></script>

<script>
  const player = videojs('my-video');
  const qualitySelect = document.getElementById('qualitySelect');
  const audioSelect = document.getElementById('audioSelect');

  player.ready(function () {
    const qualityLevels = player.qualityLevels();

    // Wait for levels to load
    qualityLevels.on('addqualitylevel', function () {
      // Clear previous options
      qualitySelect.innerHTML = '';

      // Auto option
      const autoOption = document.createElement('option');
      autoOption.value = 'auto';
      autoOption.textContent = 'Auto';
      qualitySelect.appendChild(autoOption);

      // Unique resolutions (prevent duplicates)
      const added = new Set();

      for (let i = 0; i < qualityLevels.length; i++) {
        const level = qualityLevels[i];
        const label = `${level.height}p`;

        if (!added.has(label)) {
          const option = document.createElement('option');
          option.value = i;
          option.textContent = label;
          qualitySelect.appendChild(option);
          added.add(label);
        }
      }
    });

    qualitySelect.addEventListener('change', function () {
      const selected = qualitySelect.value;
      for (let i = 0; i < qualityLevels.length; i++) {
        qualityLevels[i].enabled = (selected === 'auto') || (parseInt(selected) === i);
      }
    });

    // Audio track selector
    const updateAudioDropdown = () => {
      const audioTracks = player.audioTracks();
      audioSelect.innerHTML = '';

      for (let i = 0; i < audioTracks.length; i++) {
        const track = audioTracks[i];
        const option = document.createElement('option');
        option.value = i;
        option.textContent = track.label || `Track ${i + 1}`;
        if (track.enabled) option.selected = true;
        audioSelect.appendChild(option);
      }

      audioSelect.addEventListener('change', function () {
        const index = parseInt(this.value);
        for (let i = 0; i < audioTracks.length; i++) {
          audioTracks[i].enabled = (i === index);
        }
      });
    };

    player.on('loadedmetadata', updateAudioDropdown);
  });
</script>





            {{-- <video
            
                id="videoPlayer"
                class="video-js vjs-default-skin"
                controls
                width="560"
                height="315"
                autoplay="{{ auth()->check() ? 'true' : 'false' }}"
                muted
                data-watch-time="{{$watched_time??0}}"
                data-movie-access="{{$dataAccess??''}}"
                data-encrypted="{{ $data }}"
                poster="{{$thumbnail_image}}"
                data-setup='{"autoplay": {{ auth()->check() ? 'true' : 'false' }}, "muted": true}'>

            </video> --}}

            <meta name="baseUrl" content="{{ url('/') }}">
            <meta name="csrf-token" content="{{ csrf_token() }}">

            @endif

        </div>
</div>





<!-- Include the custom JS -->
<script src="{{ asset('js/videoplayer.min.js') }}"></script>
<script>
    var isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
    var loginUrl = "{{ route('login') }}";  // Update with your actual login route
</script>