@extends('backend.layouts.app')

@section('content')

    @php
        CoreComponentRepository::instantiateShopRepository();
        CoreComponentRepository::initializeCache();
    @endphp

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-auto">
                <h1 class="h3">{{ translate('Category Shipping') }}</h1>
            </div>

        </div>
    </div>
    <br>

    <div class="card">

            <div class="card-header row gutters-5">

                <div class="col-10"></div>
                <div class="col-2">
                    <a href="#" data-toggle="modal" data-target="#exampleModaladd" class="btn btn-primary btn-sm">
                        <i class="las la-plus"></i>
                        {{ translate('Add New Category Shipping') }}
                    </a>

                    <div class="modal fade" id="exampleModaladd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                            <form action="{{url("add/catshipping")}}" method="post">
                                @csrf

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Type of product</label>
                                    <input type="text" name="name"  class="form-control"  placeholder="type" required >
                                </div>



                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                            </div>

                        </div>
                        </div>
                    </div>




                </div>

            </div>


            <div class="card-body">

                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>

                            <th>{{ translate('Name') }}</th>

                            <th data-breakpoints="lg" class="text-right">Options</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($catshippings as $key => $cat)
                            <tr>

                                <td>
                                    {{$cat->name}}
                                </td>



                                <td class="text-right">

                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                        data-toggle="modal"  data-target="#exampleModal{{$cat->id}}"
                                                href="#"
                                                title="{{ translate('Edit') }}">
                                                <i class="las la-edit"></i>
                                        </a>

                                        <!--modal-->
                                         <!-- Button trigger modal -->

                                                <!-- Modal -->
                                                <div class="modal fade" id="exampleModal{{$cat->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        </div>
                                                        <div class="modal-body">
                                                        <form action="{{url("admin/update/catshipping/$cat->id")}}" method="post">
                                                            @csrf

                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1">Type of product</label>
                                                                <input type="text" name="name" value="{{$cat->name}}" class="form-control"  placeholder="type" required >
                                                            </div>
                                                          


                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                                            </div>
                                                        </form>
                                                        </div>

                                                    </div>
                                                    </div>
                                                </div>

                                         <!--end modal-->
                                        <a href="{{url("admin/delete/catshipping/$cat->id")}}" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"

                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

    </div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')
    <script type="text/javascript">
        $(document).on("change", ".check-all", function() {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        $(document).ready(function() {
            //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
        });

        function update_todays_deal(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('products.todays_deal') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Todays Deal updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function update_published(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('products.published') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Published products updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function update_approved(el) {
            if (el.checked) {
                var approved = 1;
            } else {
                var approved = 0;
            }
            $.post('{{ route('products.approved') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                approved: approved
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Product approval update successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function update_featured(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('products.featured') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Featured products updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function sort_products(el) {
            $('#sort_products').submit();
        }

        function bulk_delete() {
            var data = new FormData($('#sort_products')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('bulk-product-delete') }}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response == 1) {
                        location.reload();
                    }
                }
            });
        }
    </script>
@endsection
