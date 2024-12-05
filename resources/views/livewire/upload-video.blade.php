<div>
    <h1>Subir Video</h1>
    @if (session()->has('message'))
        <div>{{ session('message') }}</div>
    @endif
    <form wire:submit.prevent="upload">
        <div>
            <label for="title">Título</label>
            <input type="text" wire:model="title" required>
            @error('title') <span>{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="video">Video</label>
            <input type="file" wire:model="video" required>
            @error('video') <span>{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="start_time">Hora de Inicio</label>
            <input type="datetime-local" wire:model="start_time" required>
            @error('start_time') <span>{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="rows">Filas</label>
            <input type="number" wire:model="rows" min="1" required>
            @error('rows') <span>{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="cols">Columnas</label>
            <input type="number" wire:model="cols" min="1" required>
            @error('cols') <span>{{ $message }}</span> @enderror
        </div>
        <button type="submit">Subir</button>
    </form>
</div>