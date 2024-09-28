<form wire:submit.prevent='submit'>
    <x-modal :has-button="false" modal-id="modalNewUser" on="openNewUserModal" title="{{ $user_id ? __('Edit User') : __('Add New User') }}">

        <x-form.input required="required" type="text" wire:model.defer='name' id="txt_name" label="{{ __('Full Name') }}" placeholder="{{ __('Full Name') }}" :error="$errors->first('name')" />

        @if (!$user_id)
            <x-form.input required="required" type="email" wire:model.defer='email' id="txt_email" label="{{ __('Email') }}" placeholder="{{ __('Email') }}" :error="$errors->first('email')" />
        @else
            <x-form.input required="required" type="email" wire:model.defer='email' id="txt_email" label="{{ __('Email') }}" placeholder="{{ __('Email') }}" :error="$errors->first('email')" readonly />
        @endif


        @if (!$user_id)
            <x-form.input required="required" type="password" wire:model.defer='password' id="txt_pasword" label="{{ __('Password') }}" placeholder="{{ __('Password') }}" :error="$errors->first('password')" />
        @endif

        @if ($user_id == auth()->user()->id)
        @else
            <x-form.select wire:model.defer='role' required="required" :error="$errors->first('role')" id="txt_role" label="{{ __('Role') }}">
                <option value="">-Select Role-</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                @endforeach
            </x-form.select>

            <x-form.check wire:model.defer='active' id="txt_active" label="{{ __('Active') }}" />
        @endif
        <x-slot name="footer">
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                <div wire:loading>
                    <i class="fas fa-spin fa-spinner mr-2"></i>
                </div>
                {{ __('Save') }}
            </button>
        </x-slot>
    </x-modal>
</form>
