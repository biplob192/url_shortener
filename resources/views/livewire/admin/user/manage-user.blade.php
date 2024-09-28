<div>
    @section('page-title')
        {{ __('Manage Users') }}
    @endsection

    @push('header')
        <style>
            .loading-modal {
                position: fixed;
                z-index: 1000;
                top: 0;
                left: 0;
                height: 100%;
                width: 100%;
                background: rgba(255, 255, 255, .8) url("../../loading.gif") 50% 40% no-repeat;
            }
        </style>
    @endpush


    @section('header')
        <x-common.header title="{{ __('Manage Users') }}">
            <li class="breadcrumb-item">
                <a href="javascript: void(0);">{{ __('User Management') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('Users') }}</li>
        </x-common.header>
    @endsection

    <x-action-box>

        <x-slot name="left">
            <button wire:click="openNewUserModal()" type="button" class="btn waves-effect btn-primary">
                <i class="fa fa-plus me-2"></i> {{ __('New User') }}
            </button>
            @include('livewire.admin.user._add_update_user')
        </x-slot>

        <x-slot name="right">
            <div class="d-flex justify-content-between">
                <x-form.select id="perPage" wire:model.live="perPage">
                    <option value="5"> 5 </option>
                    <option value="10"> 10 </option>
                    <option value="50"> 50 </option>
                    <option value="100"> 100 </option>
                </x-form.select>

                <div class="ms-2">
                    <button
                        class="btn btn-primary"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasFilter"
                        aria-controls="offcanvasFilter">
                        <i class="fa fa-filter pe-2"></i> {{ __('Search') }}
                    </button>
                    <x-offcanvas id="offcanvasFilter" size="sm" title="{{ __('Search') }}">
                        <form>
                            <x-form.input id="txt_name_filter" wire:model.defer="filter.name" placeholder="{{ __('Name') }}" />
                            <x-form.input id="txt_email_filter" wire:model.defer="filter.email" placeholder="{{ __('Email') }}" />
                            <button type="submit" wire:click.prevent="search" class="btn btn-primary">{{ __('Search') }}</button>
                            <button type="button" wire:click.prevent="resetSearch" class="btn btn-link">{{ __('Reset') }}</button>
                        </form>
                    </x-offcanvas>
                </div>
            </div>

        </x-slot>

    </x-action-box>

    <x-table.table>
        <x-slot name="head">
            <tr>
                <x-table.th>{{__('Name')}}</x-table.th>
                <x-table.th>{{__('Email')}}</x-table.th>
                <x-table.th>{{__('Role')}}</x-table.th>
                <x-table.th style="width: 98px">{{__('Status')}}</x-table.th>
                <x-table.th style="width: 90px">{{__('Action')}}</x-table.th>
            </tr>
        </x-slot>
        <x-slot name="body">
            @forelse($users as $user)
                <tr wire:key="{{ $user->id }}">
                {{-- <tr> --}}
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->roles->isNotEmpty())
                            <span class="badge badge-soft-primary">{{ $user->roles[0]->name }}</span>
                        @else
                            <span class="badge badge-soft-secondary">No Role</span>
                        @endif
                    </td>
                    <td>
                        @if($user->id == auth()->user()->id)
                            <span class="badge badge-soft-primary">Active</span>
                        @else
                            @livewire('toggle-switch', ['model'=>$user, 'field'=>'is_active','name'=>$user->name], key($user->id))
                        @endif
                    </td>
                    <td>
                        <button type="button"  wire:click="openEditUserModal({{ $user->id }})"  class="btn btn-secondary btn-sm"> <i class="fa fa-edit fa-color-primary"></i> </button>
                        @if($user->id == auth()->user()->id)
                        @else
                            <a wire:click="UserconfirmDelete({{ $user->id }})" class="btn btn-primary btn-sm"><i class="fas fa-trash"></i></a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No Records Found!</td>
                </tr>
            @endforelse
        </x-slot>
    </x-table.table>
    <div class="row">
        {{-- <div class="col-sm-12 col-md-3">{{ pagination_stats_text($users) }} </div> --}}
        <div class="col-sm-12 col-md-12">{{ $users->links() }}</div>
    </div>
    @include('livewire.x-loading')
    <x-notify/>
</div>
