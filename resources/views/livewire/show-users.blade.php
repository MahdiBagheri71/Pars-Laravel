<div class="card">
    <div class="card-header">
        <a class="nav-link float-end m-1"
           href="{{ route('usersList') }}">{{$deleted ?  __('List Users Delete') : __('List Users') }}</a>
        <a wire:click="refresh" type="button"  title="{{__("Refresh")}}" class="text-dark float-start m-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-arrow-counterclockwise"><path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"></path> <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"></path></svg>
        </a>
        {{--create user--}}
        <a wire:click="showModal(0,'create')" type="button" title="{{__("Create")}}" class="text-primary float-start m-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
            </svg>
        </a>
    </div>
    <div class="card-body">

        {{--show alert message--}}
        <div>
            @if (session()->has('message'))

                <div class="alert alert-{{$message_type}}">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    {{ session('message') }}
                </div>

            @endif

            @if ($errors_message)
                <div class="alert alert-danger">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <ul>
                        @foreach ($errors_message as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="col-md-12 text-center table-responsive">

            {{--tabel list users--}}
            <table class="table table-bordered table-striped">
                <thead>
                {{-- for show header &sorting--}}
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">
                        <a href="#" wire:click="orderBy('name')">
                            {{__('Name')}}
                            @includeWhen( $order_by == 'name', 'dashboard.user.order', ['order' => $order])
                        </a>
                    </th>
                    <th scope="col">
                        <a href="#" wire:click="orderBy('last_name')">
                            {{__('Last Name')}}
                            @includeWhen( $order_by == 'last_name', 'dashboard.user.order', ['order' => $order])
                        </a>
                    </th>
                    <th scope="col">
                        <a href="#" wire:click="orderBy('email')">
                            {{__('Email Address')}}
                            @includeWhen( $order_by == 'email', 'dashboard.user.order', ['order' => $order])
                        </a>
                    </th>
                    <th scope="col">
                        <a href="#" wire:click="orderBy('username')">
                            {{__('Username')}}
                            @includeWhen( $order_by == 'username', 'dashboard.user.order', ['order' => $order])
                        </a>
                    </th>
                    <th scope="col">
                        <a href="#" wire:click="orderBy('api_token')">
                            {{__('API Token')}}
                            @includeWhen( $order_by == 'api_token', 'dashboard.user.order', ['order' => $order])
                        </a>
                    </th>
                    <th scope="col">{{__('Role')}}</th>
                    <th scope="col">{{__('Action')}}</th>
                </tr>

                {{--for filter users--}}
                <tr>
                    <th scope="col"></th>
                    <th scope="col">
                        <input class="form-control" wire:model="search_users.name" type="text"
                               placeholder="{{__('Name')}}"/>
                    </th>
                    <th scope="col">
                        <input class="form-control" wire:model="search_users.last_name" type="text"
                               placeholder="{{__('Last Name')}}"/>
                    </th>
                    <th scope="col">
                        <input class="form-control" wire:model="search_users.email" type="text"
                               placeholder="{{__('Email Address')}}"/>
                    </th>
                    <th scope="col">
                        <input class="form-control" wire:model="search_users.username" type="text"
                               placeholder="{{__('Username')}}"/>
                    </th>
                    <th scope="col">
                        <input class="form-control" wire:model="search_users.api_token" type="text"
                               placeholder="{{__('API Token')}}"/>
                    </th>
                    <th scope="col">
                        <select multiple wire:model="search_users.role" class="form-select" aria-label="{{__('Role')}}" style="text-align: center;">
                            @foreach ($roles as $role)
                                <option value="{{$role}}">{{$role}}</option>
                            @endforeach
                        </select>
                    </th>
                    <th scope="col"></th>
                </tr>

                </thead>
                <tbody>
                {{--show data user--}}
                @foreach ($users as $row=>$user)
                    <tr>
                        {{--                    Show row by start & pagination--}}
                        <th scope="row">{{$row+($users->firstItem())}}</th>
                        <td>{{$user->name}}</td>
                        <td>{{$user->last_name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->username}}</td>
                        <td>{{$user->api_token}}</td>
                        <td>{!! $user->getRoleNames()->join("<br>") !!}</td>
                        <td>
                            @if(!$deleted)
                                {{--edit user --}}
                                <a title="{{__("Edit")}}" wire:click="showModal({{$user->id}},'edit')" type="button"
                                   class="text-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                         fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                        <path
                                            d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                    </svg>
                                </a>
                                {{--if admin delete for ever taks--}}
                                <a  title="{{__("Delete")}}" wire:click="showModal({{$user->id}},'delete')" type="button" class="text-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                         fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"></path>
                                    </svg>
                                </a>
                            @else
                                <a wire:click="showModal({{$user->id}},'restore')"  title="{{__("Restore")}}"  type="button" class="text-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                         class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                              d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                                        <path
                                            d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                                    </svg>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Modal Delete-->
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog"
                 aria-labelledby="deleteModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">
                                {{__("Delete")}}
                            </h5>
                        </div>
                        <div class="modal-body">
                            {{__('Are you sure you want to delete this resource?')}}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary deleteModalClose">{{__('No')}}</button>
                            <button type="button" class="btn btn-danger deleteModalClose"
                                    wire:click="delete({{$modal_user_id}})">{{__('Yes')}}</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Edit-->
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalCenterTitle"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">
                                {{__("Edit")}}
                            </h5>
                        </div>
                        <div class="modal-body">
                            @if($modal_task)
                                @livewire('edit-user',['live_wire'=>true,'user_id' => $modal_user_id])
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Create-->
            <div class="modal fade" id="createModal" tabindex="-1" role="dialog"
                 aria-labelledby="createModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">
                                {{__("User Create")}}
                            </h5>
                        </div>
                        <div class="modal-body">
                            @livewire('create-user',['live_wire'=>true])
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Restore-->
            <div class="modal fade" id="restoreModal" tabindex="-1" role="dialog"
                 aria-labelledby="restoreModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">
                                {{__("Restore")}}
                            </h5>
                        </div>
                        <div class="modal-body">
                            {{__('Are you sure you want to restore this resource?')}}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary restoreModalClose">{{__('No')}}</button>
                            <button type="button" class="btn btn-success restoreModalClose"
                                    wire:click="restore({{$modal_user_id}})">{{__('Yes')}}</button>
                        </div>
                    </div>
                </div>
            </div>

            {{--paginate--}}
            <div class="col-md-12">
                {{ $users->links() }}
            </div>

        </div>
    </div>
</div>
