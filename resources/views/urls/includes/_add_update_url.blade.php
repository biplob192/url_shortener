<form id="add_update_url" method="POST" action="{{ route('urls.store') }}">
    @csrf
    <x-modal :has-button="false" modal-id="modalNewUser" on="openNewUserModal" title="">

        <x-form.input name="original_url" required="required" type="text" id="original_url" class="create_url" label="{{ __('Place URL') }}" placeholder="{{ __('Place URL') }}" :error="$errors->first('name')" />
        <x-form.input type="text" id="short_url" class="edit_url" label="{{ __('Short URL') }}" placeholder="{{ __('Short URL') }}" :error="$errors->first('short_url')" readonly />

        <x-slot name="footer">
            <button type="submit" class="btn btn-primary">
                <div>
                    {{-- <i class="fas fa-spin fa-spinner mr-2"></i> --}}
                </div>
                {{ __('Save') }}
            </button>
        </x-slot>
    </x-modal>
</form>
