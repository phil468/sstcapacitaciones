
<div>
    <div wire:poll.3s
    class="p-0 right-2 top-27 position-fixed" style="z-index: 1035; opacity: 0.85;">
        @if (session()->has('success'))
            <x-adminlte-alert theme="success" dismissable>
                {{ session('success') }}
            </x-adminlte-alert>
        @endif
    
        @if (session()->has('danger'))
            <x-adminlte-alert theme="danger" dismissable>
                {{ session('danger') }}
            </x-adminlte-alert>
        @endif
        
        @if (session()->has('error'))
            <x-adminlte-alert theme="danger" dismissable>
                {{ session('error') }}
            </x-adminlte-alert>
        @endif
        
        @if (session()->has('warning'))
            <x-adminlte-alert theme="warning" dismissable>
                {{ session('warning') }}
            </x-adminlte-alert>
        @endif
    </div>
</div>