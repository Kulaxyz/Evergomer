@if (backpack_user()->hasRole('user'))
    <a href="javascript:void(0)" onclick="pay(this)" data-route="{{ route('invoice.pay', $id) }}"
       class="btn btn-sm btn-link" data-button-type="pay_wallet">
        <i class="fa fa-money"></i>
        Pay(Wallet)
    </a>
    <a class="btn btn-sm btn-link"  href="{{ route('pay', $id) }}"><i class="fa fa-dollar"></i>Pay(Gateway)</a>
@endif

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>

    if (typeof pay != 'function') {
        $("[data-button-type=pay_wallet]").unbind('click');

        function pay(button) {
            // ask for confirmation before deleting an item
            // e.preventDefault();
            var button = $(button);
            var route = button.attr('data-route');
            var row = $("#crudTable a[data-route='"+route+"']").closest('tr');

            swal({
                title: "{!! trans('backpack::base.warning') !!}",
                text: "Are you sure? The amount would be charged from your wallet!",
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "{!! trans('backpack::crud.cancel') !!}",
                        value: null,
                        visible: true,
                        className: "bg-secondary",
                        closeModal: true,
                    },
                    delete: {
                        text: "Pay",
                        value: true,
                        visible: true,
                        className: ".bg-success",
                    }
                },
            }).then((value) => {
                if (value) {
                    $.ajax({
                        url: route,
                        type: 'POST',
                        success: function(response) {
                            console.log(row);
                            if (!response.success) {
                                console.log(response)
                                // Show an error alert
                                swal({
                                    title: "Unpaid!",
                                    text: response.message,
                                    icon: "error",
                                    timer: 2000,
                                    buttons: false,
                                });
                            } else {
                                // Show a success message
                                swal({
                                    title: "Success!",
                                    text: response.message,
                                    icon: "success",
                                    timer: 4000,
                                    buttons: false,
                                });

                                // Hide the modal, if any
                                $('.modal').modal('hide');

                                // Remove the details row, if it is open
                                if (row.hasClass("shown")) {
                                    row.next().remove();
                                }

                                // Remove the row from the datatable
                                row.remove();
                            }
                        },
                        error: function(response) {
                            // Show an alert with the result
                            swal({
                                title: "Unpaid!",
                                text: response.message,
                                icon: "error",
                                timer: 4000,
                                buttons: false,
                            });
                        }
                    });
                }
            });

        }
    }

    // make it so that the function above is run after each DataTable draw event
    // crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
</script>
@if (!request()->ajax()) @endpush @endif
