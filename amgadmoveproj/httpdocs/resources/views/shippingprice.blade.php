@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
    	<div class="row align-items-center">
    		<div class="col-md-12">
    			<h1 class="h3">Shipping</h1>
    		</div>
    	</div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <form class="" id="sort_cities" action="" method="GET">
                    <div class="card-header row gutters-5">
                        <div class="col text-center text-md-left">
                            <h5 class="mb-md-0 h6">shipping price</h5>
                        </div>



                    </div>
                </form>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th data-breakpoints="lg">#</th>
                                <th>{{translate('State')}}</th>
                                <th>Categort</th>
                                <th>{{translate('Area Wise Shipping Cost')}}</th>

                                <th data-breakpoints="lg" class="text-right">{{translate('Options')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shippingprices as $key => $city)
                                <tr>
                                    <td>{{ ($key+1) }}</td>
                                    <td>{{ $city->state->name }}</td>
                                    <td>{{ $city->catshipping->name }}</td>
                                    <td>{{ $city->price }} </td>

                                    <td class="text-right">
                                        <a data-toggle="modal" data-target="#exampleModal{{$city->state_id}}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="#" title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>

{{--------modal-----}}
                                    <div class="modal fade" id="exampleModal{{$city->state_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="card-body">
                                                    <form action="{{ url("update/shippingprice/$city->id") }}" method="POST">
                                                        @csrf


                                                        <div class="form-group">
                                                            <label for="country">{{translate('State')}}</label>
                                                            <select class="select2 form-control aiz-selectpicker" name="state_id" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                                                                @foreach ($states as $state)
                                                                    <option @if($city->state_id == $state->id) selected @endif value="{{ $state->id }}">{{ $state->name }}</option>


                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="country">Category</label>
                                                            <select class="select2 form-control aiz-selectpicker" name="cat_id" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                                                                @foreach ($cats as $state)
                                                                    <option @if($city->catshipping_id == $state->id) selected @endif value="{{ $state->id }}">{{ $state->name }}</option>

                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label for="name">{{translate('Cost')}}</label>
                                                            <input type="number" min="0" step="0.01" value="{{$city->price}}" placeholder="{{translate('Cost')}}" name="price" class="form-control" required>
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
{{----modal end----}}

                                        <a href="{{url("delete/shippingprice/$city->id")}}" class="btn btn-soft-danger btn-icon btn-circle btn-sm "  title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <div class="col-md-5">
    		<div class="card">
    			<div class="card-header">
    				<h5 class="mb-0 h6">{{ translate('Add New city') }}</h5>
    			</div>
    			<div class="card-body">
    				<form action="{{ url('add/shippingprice') }}" method="POST">
    					@csrf


                        <div class="form-group">
                            <label for="country">{{translate('State')}}</label>
                            <select class="select2 form-control aiz-selectpicker" name="state_id" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="country">Category</label>
                            <select class="select2 form-control aiz-selectpicker" name="cat_id" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                                @foreach ($cats as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
    						<label for="name">{{translate('Cost')}}</label>
    						<input type="number" min="0" step="0.01" placeholder="{{translate('Cost')}}" name="price" class="form-control" required>
    					</div>
    					<div class="form-group mb-3 text-right">
    						<button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
    					</div>
    				</form>
    			</div>
    		</div>
    	</div>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')
    <script type="text/javascript">
        function sort_cities(el){
            $('#sort_cities').submit();
        }

        function update_status(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('cities.status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Country status updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

    </script>
@endsection
