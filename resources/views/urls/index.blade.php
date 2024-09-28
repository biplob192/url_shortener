@extends('layouts.master_layout')

@section('page-title')
    {{ __('Manage Urls') }}
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
    <x-common.header title="{{ __('Manage Urls') }}">
        <li class="breadcrumb-item">
            <a href="javascript: void(0);">{{ __('Url Management') }}</a>
        </li>
        <li class="breadcrumb-item active">{{ __('Urls') }}</li>
    </x-common.header>
@endsection

@section('content')
    <x-action-box>

        <x-slot name="left">
            <button type="button" class="btn waves-effect btn-primary" onclick="openModal(true)">
                <i class="fa fa-plus me-2"></i> {{ __('New Url') }}
            </button>
            @include('.urls.includes._add_update_url')
        </x-slot>

        <x-slot name="right">
            <div class="d-flex justify-content-between">
                <!-- Pagination Control -->
                <x-form.select id="perPage" onchange="changePerPage(this.value)">
                    <option value="5" {{ $perPage == 5 ? 'selected' : '' }}> 5 </option>
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}> 10 </option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}> 50 </option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}> 100 </option>
                </x-form.select>

                <div class="ms-2">
                    <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasFilter" aria-controls="offcanvasFilter">
                        <i class="fa fa-filter pe-2"></i> {{ __('Search') }}
                    </button>
                    <x-offcanvas id="offcanvasFilter" size="sm" title="{{ __('Search') }}">
                        <form method="GET" action="{{ route('urls.index') }}">
                            <x-form.input id="original_url" name='original_url' value="{{ old('original_url', $specialSearch['original_url'] ?? '') }}" placeholder="{{ __('Original URL') }}" />
                            <x-form.input id="short_url" name='short_url' value="{{ old('short_url', $specialSearch['short_url'] ?? '') }}" placeholder="{{ __('Short URL') }}" />
                            <x-form.input id="search" name='search' value="{{ old('search', $search ?? '') }}" placeholder="{{ __('Search...') }}" />
                            <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                            <button type="submit" id="reset" name='reset' value="1" class="btn btn-link">{{ __('Reset') }}</button>
                        </form>
                    </x-offcanvas>
                </div>
            </div>

        </x-slot>

    </x-action-box>

    <x-table.table>
        <x-slot name="head">
            <tr>
                <x-table.th>{{ __('SN') }}</x-table.th>
                <x-table.th style="width: 20%">{{ __('Short URL') }}</x-table.th>
                <x-table.th>{{ __('Original URL') }}</x-table.th>
                <x-table.th style="width: 10%" class="text-center">{{ __('Visit Count') }}</x-table.th>
                <x-table.th style="width: 10%">{{ __('Action') }}</x-table.th>
            </tr>
        </x-slot>
        <x-slot name="body">
            @forelse($urls['data'] as $url)
                <tr data-record-id="{{ $url->id }}">
                    <td>{{ $url->id }}</td>
                    <td><a href="{{ $url->short_url }}" target="_blank">{{ $url->short_url }}</a></td>
                    <td>{{ $url->original_url }}</td>
                    <td class="text-center">
                        @if ($url->url_visit_count > 0)
                            <span class="badge badge-soft-primary">{{ $url->url_visit_count }}</span>
                        @else
                            <span class="badge badge-soft-secondary">Not visited</span>
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="editNotification(event)" data-id="{{ $url->id }}"> <i class="fa fa-edit fa-color-primary"></i> </button>
                        <a class="btn btn-primary btn-sm" onclick="deleteNotification({{ $url->id }}, '{{ route('urls.ajaxDestroy', $url->id) }}')" id="deleteUrl"><i class="fas fa-trash"></i></a>
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
        <div class="col-sm-12 col-md-12">
            {{ $urls['data']->appends(['perPage' => $perPage])->links() }}
        </div>
    </div>

    <!-- Loader Modal -->
    <div class="loading-modal" id="loading-modal"></div>
    <x-notify />
@endsection

@push('footer')
    <script>
        // Hide the loader when the page has fully loaded
        window.onload = function() {
            document.getElementById('loading-modal').style.display = 'none';
        };

        // handle perPage records
        function changePerPage(perPage) {
            // Show the loader
            document.getElementById('loading-modal').style.display = 'block';

            const url = new URL(window.location.href);
            url.searchParams.set('perPage', perPage);

            // Redirect with the new perPage value
            window.location.href = url.toString();
        }

        // Open add/edit modal
        function openModal(status = false, event = null) {
            const form = document.getElementById('add_update_url');
            const editElements = form.querySelectorAll('.edit_url');
            const createElements = form.querySelectorAll('.create_url');
            const modalTitle = document.getElementById('my-modal-title');

            if (status) {
                modalTitle.textContent = 'Create Short URL';

                editElements.forEach(el => el.style.display = 'none');
                createElements.forEach(el => el.style.display = 'block');
            } else if (event) {
                modalTitle.textContent = 'Edit URL';

                editElements.forEach(el => el.style.display = 'block');
                createElements.forEach(el => el.style.display = 'block');

                const button = event.currentTarget;
                urlId = button.getAttribute('data-id');

                if (urlId) {
                    const updateUrl = '{{ route('urls.update', ':id') }}'.replace(':id', urlId);
                    const methodInput = document.createElement('input');

                    form.setAttribute('action', updateUrl);
                    methodInput.setAttribute('type', 'hidden');
                    methodInput.setAttribute('name', '_method');
                    methodInput.setAttribute('value', 'PUT');
                    form.appendChild(methodInput);
                } else {
                    console.error('URL ID is required for updating.');
                }
            }

            // Dispatch the event to show the modal
            window.dispatchEvent(new CustomEvent('openNewUserModal'));
        }

        // Confirm edit
        function editNotification(event) {
            const button = event.currentTarget;
            recordId = button.getAttribute('data-id');

            const updateUrl = '{{ route('urls.update', ':id') }}'.replace(':id', recordId);
            const ajaxShowUrl = '{{ route('urls.ajaxShow', ':id') }}'.replace(':id', recordId);

            window.dispatchEvent(new CustomEvent('editNotification', {
                detail: {
                    recordId,
                    updateUrl,
                    ajaxShowUrl
                }
            }));
        }

        // Confirm delete
        function deleteNotification(recordId, deleteRoute) {
            window.dispatchEvent(new CustomEvent('deleteNotification', {
                detail: {
                    recordId,
                    deleteRoute
                }
            }));
        }
    </script>
@endpush
