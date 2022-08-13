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

</div>
