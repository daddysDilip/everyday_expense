@extends('layouts.app')

@section('extracss')

@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Languages</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('languages') }}">Languages</a></li>
              <li class="breadcrumb-item active">{{!empty($languages) ? "Update" : "Create"}} Languages</li>
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
              <a href="{{route('languages')}}" class="text-muted"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h5>{{!empty($languages) ? "Update" : "Create"}} Languages</h5>
            </div>
            @if(isset($languages))
              <form id="" class="form theme-form" method="POST" action="{{ route('languages.update', $languages->id) }}" enctype="multipart/form-data">
            @else
              <form id="languagesForm" class="user-form form theme-form" method="POST" action="{{ route('languages.store') }}" enctype="multipart/form-data">
            @endif
            @csrf
                <div class="form-row">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body row ">

                        <div class="form-group col-md-12">
                          <label for="languages">Name</label>
                          <input type="text" class="form-control" id="name" name="name" value="{{!empty($languages->name) ? $languages->name: ''}}" placeholder="Enter Name" />

                          @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                          @enderror
                        </div>

                        <div class="form-group col-md-12">
                          <label for="languages">Name In Language</label>
                          <input type="text" class="form-control" id="name_in_language" name="name_in_language" value="{{!empty($languages->name_in_language) ? $languages->name_in_language: ''}}" placeholder="Name In Language" />

                          @error('name_in_language')
                            <div class="alert alert-danger">{{ $message }}</div>
                          @enderror
                        </div>

                        <div class="form-group col-md-12">
                          <label for="languages">Language Code <h6>ISO 639-1 Language Code</h6></label>
                          <input type="text" class="form-control" id="code" name="code" value="{{!empty($languages->code) ? $languages->code: ''}}" placeholder="Enter Code" />

                          @error('code')
                            <div class="alert alert-danger">{{ $message }}</div>
                          @enderror
                        </div>

                        <div class="form-group col-md-12">
                          <label for="languages">Select Country</label>
                            <select name="country" id="country" class="form-control">
                                <option value="{{@$languages->country_id}}" >Select Country</option>
                                @foreach (get_countries() as $country)
                                @if(@$languages->country_id != null &&  @$languages->country_id == $country->id)
                                <option value="{{$country->id}}" selected>{{$country->name}}</option>
                                @else
                                <option value="{{$country->id}}">{{$country->name}}</option>
                                @endif
                                @endforeach
                            </select>

                          @error('country')
                            <div class="alert alert-danger">{{ $message }}</div>
                          @enderror
                        </div>

                        <div class="form-group col-md-12">
                          <div class="icheck-danger d-inline">
                            <input type="checkbox" id="status" name="status" value="1" {{ isset($languages) ? (($languages->status == 1) ? 'checked' : '') : '' }}>
                              <label for="status">
                              Status
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-body text-right">
                          <button class="btn btn-danger mr-2" type="submit"> {{ isset($languages) ? 'Update' : 'Submit' }} </button>
                          <a href="{{ route('languages') }}" class="btn btn-default">Cancel</a>
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
    $('#languagesForm').validate({
      errorPlacement: function(error, element) {
          error.insertAfter(element)
          error.addClass('text-danger');
      },
      rules:{
        name:{
          required:true,
          remote: {
              type: 'post',
              url: "{{ route('languages.existlanguages') }}",
              data: {
                name: function() {
                  return  $("input[id='name']").val();
                },
                id: "{{ isset($languages) ? $languages->id : '' }}",
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
