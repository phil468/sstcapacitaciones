<!-- Modal -->
<div wire:ignore.self class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="videoModalLabel">Video</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          {{-- <iframe width="100%" height="400" src="{{$urlVideo}}" frameborder="0" allowfullscreen></iframe> --}}
          <video  width="100%" height="400" allowfullscreen controls>
              <source src="{{ $urlVideo }}" type="video/mp4">
              Your browser does not support the video tag.
          </video>
        </div>
      </div>
    </div>
  </div>