@extends('layouts.app')

@section('extracss')

@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Country</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('country') }}">Country</a></li>
              <li class="breadcrumb-item active">{{!empty($country) ? "Update" : "Create"}} Country</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-header">
              <a href="{{route('country')}}" class="text-muted"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h5>{{!empty($country) ? "Update" : "Create"}} Country</h5>
            </div>
            @if(isset($country))
              <form id="" class="form theme-form" method="POST" action="{{ route('country.update', $country->id) }}" enctype="multipart/form-data">
            @else
              <form id="countryForm" class="user-form form theme-form" method="POST" action="{{ route('country.store') }}" enctype="multipart/form-data">
            @endif
            @csrf
                <div class="form-row">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body row ">

                        <div class="form-group col-md-12">
                          <label for="country">Name</label>
                          <input type="text" class="form-control" id="name" name="name" value="{{!empty($country->name) ? $country->name: ''}}" placeholder="Enter Name" />

                          @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                          @enderror
                        </div>
                        <div class="form-group col-md-12">
                          <label for="country">Currency Name</label>
                          <input type="text" class="form-control" id="currency_name" name="currency_name" value="{{!empty($country->currency_name) ? $country->currency_name: ''}}" placeholder="Enter Currency Name" />

                          @error('currency_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                          @enderror
                        </div>
                        <div class="form-group col-md-12">
                          <label for="country">Currency Symbol</label>
                          <input type="text" class="form-control" id="currency_symbol" name="currency_symbol" value="{{!empty($country->currency_symbol) ? $country->currency_symbol: ''}}" placeholder="Enter Currency Symbol" />

                          @error('currency_symbol')
                            <div class="alert alert-danger">{{ $message }}</div>
                          @enderror
                        </div>

                        <div class="form-group col-md-12">
                          <div class="icheck-danger d-inline">
                            <input type="checkbox" id="status" name="status" value="1" {{ isset($country) ? (($country->status == 1) ? 'checked' : '') : '' }}>
                              <label for="status">
                              Status
                            </label>
                          </div>
                        </div>
                      </div>
                      
                    </div>
                    <div class="card">
                      <div class="card-body text-right">
                          <button class="btn btn-danger mr-2" type="submit"> {{ isset($country) ? 'Update' : 'Submit' }} </button>
                          <a href="{{ route('country') }}" class="btn btn-default">Cancel</a>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
          </div>
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->

@endsection

@section('extrajs')
<script src="{{ asset('backend/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<script>
  $(document).ready(function() {
    $('#countryForm').validate({
      errorPlacement: function(error, element) {
          error.insertAfter(element)
          error.addClass('text-danger');
      },
      rules:{
        name:{
          required:true,
          remote: {
              type: 'post',
              url: "{{ route('country.existcountry') }}",
              data: {
                name: function() {
                  return  $("input[id='name']").val();
                },
                id: "{{ isset($country) ? $country->id : '' }}",
                "_token": "{{ csrf_token() }}"
              },
            async: false
          }
        },
      }
    });
  });

</script>
@endsection
