<div class="detail-page-banner">
  <div class="video-player">
      @if($type=='Local')
      <video id="videoPlayer" class="video-js vjs-default-skin" controls width="560"
          height="315"
          autoplay="{{ auth()->check() ? 'true' : 'false' }}"
          poster="{{$thumbnail_image}}"
          data-setup='{"autoplay": {{ auth()->check() ? 'true' : 'false' }}, "muted": true}'>
          <source src="{{ $data }}" type="video/mp4" id="videoSource">
          @if(isset($captions) && $captions)
          <track kind="captions" src="{{ $captions }}" srclang="en" label="English">
          @endif
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
          {{-- muted --}}
          data-watch-time="{{$watched_time??0}}"
          data-movie-access="{{$dataAccess??''}}"
          data-encrypted="{{ $data }}"
          poster="{{$thumbnail_image}}"
          data-setup='{"autoplay": {{ auth()->check() ? 'true' : 'false' }}, "muted": true}'>
      </video>
      @endif
  </div>
</div>

<!-- Include the required libraries -->
<script src="https://vjs.zencdn.net/7.20.3/video.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.15.0/videojs-contrib-hls.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/videojs-contrib-quality-levels@3.0.0/dist/videojs-contrib-quality-levels.min.js"></script>

<!-- Add resolution and caption buttons with quality levels plugin -->
<script>
    console.log("hello");
    
    document.addEventListener('DOMContentLoaded', function() {
      // Wait for video.js to initialize
      setTimeout(function() {
          var player = videojs('videoPlayer');
          if (!player) {
              console.error('Player not found');
              return;
          }
          
          console.log('Player initialized');
          
          console.log("in hu");
          // Initialize quality levels plugin
          var qualityLevels = player.qualityLevels();
          console.log('Quality levels plugin initialized');
          
          // Add custom CSS
          var style = document.createElement('style');
          style.textContent = `
              .vjs-resolution-button {
                  font-family: 'VideoJS';
                  cursor: pointer;
                  position: relative;
              }
              .vjs-resolution-button .vjs-menu {
                  display: none;
                  position: absolute;
                  bottom: 40px;
                  left: 0;
                  background-color: rgba(43, 51, 63, 0.7);
                  border-radius: 2px;
                  padding: 5px 0;
                  z-index: 100;
                  width: 120px;
              }
              .vjs-resolution-button:hover .vjs-menu,
              .vjs-resolution-button .vjs-menu.vjs-menu-active {
                  display: block;
              }
              .vjs-resolution-button .vjs-menu-content {
                  max-height: 200px;
                  overflow-y: auto;
              }
              .vjs-resolution-button .vjs-menu-item {
                  text-align: center;
                  padding: 6px 12px;
                  margin: 0;
                  font-size: 14px;
                  color: #fff;
                  cursor: pointer;
              }
              .vjs-resolution-button .vjs-menu-item:hover {
                  background-color: rgba(255, 255, 255, 0.2);
              }
              .vjs-resolution-button .vjs-menu-item.vjs-selected {
                  background-color: rgba(115, 133, 159, 0.5);
              }
              .vjs-caption-button.vjs-caption-active {
                  color: #2B93D1;
              }
          `;
          document.head.appendChild(style);
          
          // Create resolution button
          var resolutionButton = document.createElement('button');
          resolutionButton.className = 'vjs-control vjs-button vjs-resolution-button';
          resolutionButton.type = 'button';
          resolutionButton.title = 'Quality';
          resolutionButton.innerHTML = '<span class="vjs-icon-placeholder">Auto</span>';
          
          // Create resolution menu
          var resolutionMenu = document.createElement('div');
          resolutionMenu.className = 'vjs-menu';
          
          var resolutionMenuContent = document.createElement('div');
          resolutionMenuContent.className = 'vjs-menu-content';
          resolutionMenu.appendChild(resolutionMenuContent);
          
          // Add menu to button
          resolutionButton.appendChild(resolutionMenu);
          
          // Toggle menu on button click
          resolutionButton.addEventListener('click', function(e) {
              e.preventDefault();
              e.stopPropagation();
              console.log('Resolution button clicked');
              resolutionMenu.classList.toggle('vjs-menu-active');
          });
          
          // Close menu when clicking elsewhere
          document.addEventListener('click', function(e) {
              if (!resolutionButton.contains(e.target)) {
                  resolutionMenu.classList.remove('vjs-menu-active');
              }
          });
          
          // Create caption button
          var captionButton = document.createElement('button');
          captionButton.className = 'vjs-control vjs-button vjs-caption-button';
          captionButton.type = 'button';
          captionButton.title = 'Captions';
          captionButton.innerHTML = '<span class="vjs-icon-placeholder vjs-icon-subtitles"></span>';
          
          var captionsEnabled = false;
          captionButton.addEventListener('click', function() {
              captionsEnabled = !captionsEnabled;
              
              // Toggle captions
              var tracks = player.textTracks();
              for (var i = 0; i < tracks.length; i++) {
                  var track = tracks[i];
                  if (track.kind === 'subtitles' || track.kind === 'captions') {
                      track.mode = captionsEnabled ? 'showing' : 'hidden';
                  }
              }
              
              // Update button appearance
              if (captionsEnabled) {
                  captionButton.classList.add('vjs-caption-active');
              } else {
                  captionButton.classList.remove('vjs-caption-active');
              }
          });
          
          // Add buttons to control bar
          var fullscreenButton = player.controlBar.getChild('fullscreenToggle').el();
          if (fullscreenButton) {
              player.controlBar.el().insertBefore(captionButton, fullscreenButton);
              player.controlBar.el().insertBefore(resolutionButton, fullscreenButton);
              console.log('Buttons added to control bar');
          } else {
              console.error('Fullscreen button not found');
              player.controlBar.el().appendChild(captionButton);
              player.controlBar.el().appendChild(resolutionButton);
              console.log('Buttons added to end of control bar');
          }
          
          // Check for captions
          player.on('loadedmetadata', function() {
              var currentSource = player.currentSource();
              if (currentSource && currentSource.src) {
                  var baseUrl = currentSource.src.substring(0, currentSource.src.lastIndexOf('/') + 1);
                  
                  // Check if captions exist and add them
                  fetch(baseUrl + 'captions.vtt')
                      .then(function(response) {
                          if (response.ok) {
                              player.addRemoteTextTrack({
                                  kind: 'captions',
                                  label: 'English',
                                  language: 'en',
                                  src: baseUrl + 'captions.vtt'
                              }, false);
                          }
                      })
                      .catch(function() {
                          console.log('No captions found');
                      });
              }
          });
          
          // Wait for quality levels to load
          qualityLevels.on('addqualitylevel', function() {
              console.log('Quality level added, updating menu');
              updateQualityMenu();
          });
          
          // Function to update quality menu
          function updateQualityMenu() {
              // Clear previous options
              resolutionMenuContent.innerHTML = '';
              
              // Add Auto option
              var autoItem = document.createElement('div');
              autoItem.className = 'vjs-menu-item vjs-selected';
              autoItem.textContent = 'Auto';
              autoItem.setAttribute('data-quality', 'auto');
              
              autoItem.addEventListener('click', function(e) {
                  e.preventDefault();
                  e.stopPropagation();
                  
                  // Enable all quality levels (auto mode)
                  for (var i = 0; i < qualityLevels.length; i++) {
                      qualityLevels[i].enabled = true;
                  }
                  
                  // Update selected state
                  updateSelectedQuality('auto');
                  
                  // Update button text
                  updateResolutionButtonText('Auto');
                  
                  // Close menu after selection
                  resolutionMenu.classList.remove('vjs-menu-active');
                  
                  console.log('Selected Auto quality');
              });
              
              resolutionMenuContent.appendChild(autoItem);
              
              // Track added resolutions to avoid duplicates
              var addedResolutions = new Set();
              
              // Add quality level options
              for (var i = 0; i < qualityLevels.length; i++) {
                  var level = qualityLevels[i];
                  var height = level.height;
                  var resolution = height + 'p';
                  
                  // Skip duplicates
                  if (addedResolutions.has(resolution)) continue;
                  addedResolutions.add(resolution);
                  
                  var item = document.createElement('div');
                  item.className = 'vjs-menu-item';
                  item.textContent = resolution;
                  item.setAttribute('data-quality', i);
                  
                  (function(index, res) {
                      item.addEventListener('click', function(e) {
                          e.preventDefault();
                          e.stopPropagation();
                          
                          // Enable only this quality level
                          for (var j = 0; j < qualityLevels.length; j++) {
                              var level = qualityLevels[j];
                              // Enable this resolution and any with the same height
                              level.enabled = (level.height === qualityLevels[index].height);
                          }
                          
                          // Update selected state
                          updateSelectedQuality(index.toString());
                          
                          // Update button text
                          updateResolutionButtonText(res);
                          
                          // Close menu after selection
                          resolutionMenu.classList.remove('vjs-menu-active');
                          
                          console.log('Selected resolution:', res);
                      });
                  })(i, resolution);
                  
                  resolutionMenuContent.appendChild(item);
              }
              
              // If no quality levels were found, add a message
              if (qualityLevels.length === 0) {
                  var noLevelsItem = document.createElement('div');
                  noLevelsItem.className = 'vjs-menu-item';
                  noLevelsItem.textContent = 'No quality levels found';
                  resolutionMenuContent.appendChild(noLevelsItem);
                  console.log('No quality levels found');
              } else {
                  console.log('Found', qualityLevels.length, 'quality levels');
              }
          }
          console.log("hu chuj");
          
          // Function to update selected quality in menu
          function updateSelectedQuality(quality) {
              var items = resolutionMenuContent.querySelectorAll('.vjs-menu-item');
              items.forEach(function(item) {
                  if (item.getAttribute('data-quality') === quality) {
                      item.classList.add('vjs-selected');
                  } else {
                      item.classList.remove('vjs-selected');
                  }
              });
          }
          
          // Function to update resolution button text
          function updateResolutionButtonText(text) {
              var placeholder = resolutionButton.querySelector('.vjs-icon-placeholder');
              if (placeholder) {
                  placeholder.textContent = text;
              }
          }
          
          // Add audio track selector
          player.on('loadedmetadata', function() {
              var audioTracks = player.audioTracks();
              
              if (audioTracks && audioTracks.length > 1) {
                  console.log('Multiple audio tracks found:', audioTracks.length);
                  
                  // Create audio button
                  var audioButton = document.createElement('button');
                  audioButton.className = 'vjs-control vjs-button vjs-audio-button';
                  audioButton.type = 'button';
                  audioButton.title = 'Audio';
                  audioButton.innerHTML = '<span class="vjs-icon-placeholder">🔊</span>';
                  
                  // Create audio menu
                  var audioMenu = document.createElement('div');
                  audioMenu.className = 'vjs-menu';
                  
                  var audioMenuContent = document.createElement('div');
                  audioMenuContent.className = 'vjs-menu-content';
                  audioMenu.appendChild(audioMenuContent);
                  
                  // Toggle menu on button click
                  audioButton.addEventListener('click', function(e) {
                      e.preventDefault();
                      e.stopPropagation();
                      audioMenu.classList.toggle('vjs-menu-active');
                  });
                  
                  // Close menu when clicking elsewhere
                  document.addEventListener('click', function(e) {
                      if (!audioButton.contains(e.target)) {
                          audioMenu.classList.remove('vjs-menu-active');
                      }
                  });
                  
                  audioButton.appendChild(audioMenu);
                  
                  // Add audio tracks to menu
                  for (var i = 0; i < audioTracks.length; i++) {
                      var track = audioTracks[i];
                      var item = document.createElement('div');
                      item.className = 'vjs-menu-item';
                      if (track.enabled) {
                          item.className += ' vjs-selected';
                      }
                      item.textContent = track.label || 'Track ' + (i + 1);
                      
                      (function(index) {
                          item.addEventListener('click', function(e) {
                              e.preventDefault();
                              e.stopPropagation();
                              
                              // Enable this audio track and disable others
                              for (var j = 0; j < audioTracks.length; j++) {
                                  audioTracks[j].enabled = (j === index);
                              }
                              
                              // Update selected state
                              var items = audioMenuContent.querySelectorAll('.vjs-menu-item');
                              items.forEach(function(menuItem, idx) {
                                  if (idx === index) {
                                      menuItem.classList.add('vjs-selected');
                                  } else {
                                      menuItem.classList.remove('vjs-selected');
                                  }
                              });
                              
                              // Close menu after selection
                              audioMenu.classList.remove('vjs-menu-active');
                          });
                      })(i);
                      
                      audioMenuContent.appendChild(item);
                  }
                  
                  // Add audio button to control bar
                  player.controlBar.el().insertBefore(audioButton, fullscreenButton || null);
              }
          });
          
          // Initialize quality menu
          updateQualityMenu();
      }, 500); // Give video.js time to initialize
    });
    </script>

<!-- Include the custom JS -->
<script src="{{ asset('js/videoplayer.min.js') }}"></script>
<script>
  var isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
  var loginUrl = "{{ route('login') }}";  // Update with your actual login route
</script>