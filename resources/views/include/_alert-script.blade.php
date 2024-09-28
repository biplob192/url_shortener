<script>
    const MESSAGE_TYPE = {
        'success': {
            'type': 'success',
            'icon': 'check-all'
        },
        'error': {
            'type': 'danger',
            'icon': 'block-helper'
        },
        'warning': {
            'type': 'warning',
            'icon': 'alert-outline'
        },
        'info': {
            'type': 'info',
            'icon': 'alert-circle-outline'
        },
    };
    window.addEventListener('alert', event => {
        const details = event.detail[0];

        const type = MESSAGE_TYPE[details.type];
        let messageContent = document.getElementById('message-content');
        messageContent.insertAdjacentHTML('beforeend', `
            <div class="alert alert-${type.type} alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                <i class="mdi mdi-${type.icon} label-icon"></i><strong>Success</strong> - ${details.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);
    });

    window.addEventListener('show-due-order-submission', event => {
        Swal.fire({
            title: 'Are you sure ?',
            text: "You want to place this order with due amount",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#006A4E',
            cancelButtonColor: '#fd625e',
            confirmButtonText: 'Yes, place order'
        }).then((result) => {
            if (result.isConfirmed) {
                livewire.emit('orderConfirmEvent');
            } else if (result.dismiss) {
                livewire.emit('orderCancelModalEvent');
            }
        })
    });

    window.addEventListener('show-delete-notification', event => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#006A4E',
            cancelButtonColor: '#fd625e',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                window.Livewire.dispatch('deleteConfirm');
            } else if (result.dismiss) {
                window.Livewire.dispatch('deleteCancel');
            }
        })
    });
    window.addEventListener('show-reminder-notification', event => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You already sent reminder",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#006A4E',
            cancelButtonColor: '#fd625e',
            confirmButtonText: 'Yes, send it'
        }).then((result) => {
            if (result.isConfirmed) {
                livewire.emit('SendConfirm');
            } else if (result.dismiss) {
                livewire.emit('SendCancel');
            }
        })
    });
    window.addEventListener('show-delete-order-notification', event => {
        Swal.fire({
            title: 'Do you also want to permanently delete corresponding order?',
            text: "You won't be able to revert this",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#006A4E',
            cancelButtonColor: '#fd625e',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                livewire.emit('OrderDeleteConfirm');
            } else if (result.dismiss) {
                livewire.emit('OrderDeleteCancel');
            }
        })
    });

    window.addEventListener('deleteNotification', event => {
        const {
            recordId,
            deleteRoute
        } = event.detail; // Extract the record ID and delete route URL

        console.log(deleteRoute);

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#006A4E',
            cancelButtonColor: '#fd625e',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                // Make a DELETE request using jQuery's AJAX
                $.ajax({
                    url: deleteRoute,
                    type: 'POST',
                    data: {
                        _method: 'delete',
                        _token: $('meta[name="csrf-token"]').attr('content'), // Include CSRF token
                    },
                    success: function(response) {
                        // Show success message and remove the corresponding row from the table
                        Swal.fire('Deleted!', 'The record has been deleted.', 'success');

                        // Use the recordId to find the row and remove it
                        $(`tr[data-record-id="${recordId}"]`).remove();

                        // $(`a[id="deleteUrl"][data-id="${recordId}"]`).closest('tr').remove();
                    },
                    error: function(error) {
                        // Show error message
                        Swal.fire('Error!', 'There was a problem deleting the record.', 'error');
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Swal.fire('Cancelled', 'The record is safe :)', 'error');
            }
        });
    });


    window.addEventListener('editNotification', event => {
        const {
            recordId,
            updateUrl,
            ajaxShowUrl
        } = event.detail; // Extract the record ID and delete route URL


        Swal.fire({
            title: 'Are you sure?',
            text: "You want to edit this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#006A4E',
            cancelButtonColor: '#fd625e',
            confirmButtonText: 'Yes, edit it'
        }).then((result) => {
            if (result.isConfirmed) {
                // Make a GET request using jQuery's AJAX
                $.ajax({
                    url: ajaxShowUrl,
                    type: 'GET',
                    success: function(response) {
                        const form = document.getElementById('add_update_url');
                        form.setAttribute('action', updateUrl);

                        const editElements = form.querySelectorAll('.edit_url');
                        editElements.forEach(el => el.style.display = 'block');

                        const createElements = form.querySelectorAll('.create_url');

                        const modalTitle = document.getElementById('my-modal-title');
                        modalTitle.textContent = 'Edit URL';

                        var shortUrl = form.querySelector('#short_url');
                        var originalUrl = form.querySelector('#original_url');
                        shortUrl.value = response.data.short_url;
                        originalUrl.value = response.data.original_url;

                        const methodInput = document.createElement('input');
                        methodInput.setAttribute('type', 'hidden');
                        methodInput.setAttribute('name', '_method');
                        methodInput.setAttribute('value', 'PUT');
                        form.appendChild(methodInput);

                        // Dispatch the event to show the modal
                        window.dispatchEvent(new CustomEvent('openNewUserModal'));
                    },
                    error: function(error) {
                        // Show error message
                        Swal.fire('Error!', 'There was a problem deleting the record.', 'error');
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Swal.fire('Cancelled', 'The record is safe :)', 'error');
            }
        });
    });

    window.addEventListener('deleted', event => {
        Swal.fire({
            title: 'Deleted',
            text: event.detail.message,
            icon: 'success',
            // confirmButtonColor: '#C9AC60'
        })
    });

    $(document).ready(function() {
        // Enable Bootstrap tooltips on page load
        $('[data-bs-toggle="tooltip"]').tooltip();
        // Ensure Livewire updates re-instantiate tooltips
        if (typeof window.Livewire !== 'undefined') {
            window.Livewire.hook('message.processed', (message, component) => {
                $('[data-bs-toggle="tooltip"]').tooltip('dispose').tooltip();
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.getElementById('alert-success');
        if (successMessage) {
            // Add a fade-out effect if desired
            setTimeout(() => {
                successMessage.style.transition = 'opacity 0.5s ease';
                successMessage.style.opacity = 0;
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 500);
            }, 5000);
        }
    });
</script>
