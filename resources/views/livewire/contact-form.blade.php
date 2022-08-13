<form wire:submit.prevent="submit" style="color:#fff";>

    <div>

        @if (session()->has('message'))

            <div class="alert alert-success">

                {{ session('message') }}

            </div>

        @endif

    </div>

    <input type="text" wire:model="name">

    @error('name') <span class="error">{{ $message }}</span> @enderror

    <input type="text" wire:model="last_name">

    @error('last_name') <span class="error">{{ $message }}</span> @enderror



    <input type="text" wire:model="email">

    @error('email') <span class="error">{{ $message }}</span> @enderror



    <button type="submit">Save Contact</button>

</form>
