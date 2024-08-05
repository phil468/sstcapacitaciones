<div {{ $attributes->merge(['class' => $makeAlertClass()]) }} class='rounded-2xl' >

    {{-- Dismiss button --}}
    @isset($dismissable)
        <button type="button" class="text-light close" data-dismiss="alert" aria-hidden="true">
            {{-- <div class="text-light"> --}}
                &times;                
            {{-- </div> --}}
        </button>
    @endisset

    {{-- Alert header --}}
    @if(! empty($title) || ! empty($icon))
        <h5>
            @if(! empty($icon))
                <i class="icon {{ $icon }}"></i>
            @endif

            @if(! empty($title))
                {{ $title }}
            @endif
        </h5>
    @endif

    {{-- Alert content --}}
    {{ $slot }}

</div>
