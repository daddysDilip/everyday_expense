@extends('layouts.app')

@section('extracss')

@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">Module</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('admin')}}">Home</a></li>

                <li class="breadcrumb-item"><a href="{{ route('module') }}">Module</a></li>
                <li class="breadcrumb-item active">{{!empty($module) ? "Update" : "Create"}} Module</li>
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
              <a href="{{route('module')}}" class="text-muted"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h5>{{!empty($module) ? "Update" : "Create"}} Module</h5>
            </div>
            @if(isset($module))
              <form id="" class="form theme-form" method="POST" action="{{ route('module.update', $module->id) }}" enctype="multipart/form-data">
            @else
              <form id="moduleForm" class="user-form form theme-form" method="POST" action="{{ route('module.store') }}" enctype="multipart/form-data">
            @endif
            @csrf
                <div class="form-row">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body row ">

                        <div class="form-group col-md-12">
                            <label for="module">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{!empty($module->name) ? $module->name: ''}}" placeholder="Enter Name" />
                            @error('name')
                              <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                          </div>



                            <!-- checkbox -->
                    <div class="form-group col-md-12">

                        <div class="icheck-danger d-inline">
                            <input type="checkbox" id="status" name="status" value="1" {{ isset($module) ? (($module->status == 1) ? 'checked' : '') : '' }}>
                            <label for="status">
                            Status
                          </label>
                        </div>
                      </div>


                        </div>
                      </div>
                      <div class="card">
                          <div class="card-body text-right">
                              <button class="btn btn-danger mr-2" type="submit"> {{ isset($module) ? 'Update' : 'Submit' }} </button>
                              <a href="{{ route('module') }}" class="btn btn-default">Cancel</a>
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

    $(document).ready(function() {

        $('#moduleForm').validate({
            errorPlacement: function(error, element) {
                error.insertAfter(element)
                error.addClass('text-danger');
            },

            rules: {
        name:{

          required:true,
          remote: {
        type: 'post',
        url: "{{ route('module.existmodule') }}",
        data: {
          name: function() {
            return  $("input[id='name']").val();
          },
          id: "{{ isset($module) ? $module->id : '' }}",

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
