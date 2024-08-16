<!-- Modal -->
<div wire:ignore.self class="modal fade" id="videoModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
     <div class="rounded-2xl modal-content">
          <div class="text-white modal-header bg-vanguard rounded-t-2xl">                
            <h5 class="modal-title" id="videoModalLabel">Ver vídeo</h5>
              <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                  <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body">

            @if ($video)
                    <video width="100%" height="240" controls>
                        <source src="{{ Storage::disk('video_sesiones')->url($video) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
            @endif
          </div>
          <div class="modal-footer">
              <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary"
                  data-dismiss="modal">Cerrar</button>
          </div>
     </div>
  </div>
</div>
