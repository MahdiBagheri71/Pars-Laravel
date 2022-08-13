<div style="color:#fff;">

    <input wire:model="search" type="text" placeholder="Search users..."/>

    <button wire:click="delete">Delete Users</button>

    <ul>

        @foreach($users as $user)

            <li>{{ $user->username }}</li>

        @endforeach

    </ul>

    <br>
    {{ $message }}

    <button wire:click="setMessageToHello">Say Hi</button>

    <br>
    {{ $message2 }}

    <button wire:click="$set('message2', 'Hello')">Say Hi</button>

    <script>

        Livewire.emit('some-event');

        Livewire.on('getId', id => {

            alert('ID : ' + id);

        });

        window.addEventListener('name-updated', event => {

            console.log(event);

        });

        document.addEventListener("DOMContentLoaded", () => {
            console.log("Loading ....");
            Livewire.hook('component.initialized', (component) => {
                console.log(component)
            })

            // Livewire.hook('element.initialized', (el, component) => {});
            //
            Livewire.hook('element.updating', (fromEl, toEl, component) => {
                console.log("updating ....");
                console.log(component)

            });

            // Livewire.hook('element.updated', (el, component) => {});
            //
            // Livewire.hook('element.removed', (el, component) => {});
            //
            // Livewire.hook('message.sent', (message, component) => {});
            //
            // Livewire.hook('message.failed', (message, component) => {});
            //
            // Livewire.hook('message.received', (message, component) => {});
            //
            // Livewire.hook('message.processed', (message, component) => {});

        });

    </script>
</div>
