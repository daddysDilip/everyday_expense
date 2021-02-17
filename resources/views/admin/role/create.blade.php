@extends('layouts.app')

@section('extracss')

@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">Role</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('admin')}}">Home</a></li>

                <li class="breadcrumb-item"><a href="{{ route('role') }}">Role</a></li>
                <li class="breadcrumb-item active">{{!empty($role) ? "Update" : "Create"}} Role</li>
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
              <a href="{{route('role')}}" class="text-muted"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h5>{{!empty($role) ? "Update" : "Create"}} Role</h5>
            </div>
            @if(isset($role))
              <form id="" class="form theme-form" method="POST" action="{{ route('role.update', $role->id) }}" enctype="multipart/form-data">
            @else
              <form id="roleForm" class="user-form form theme-form" method="POST" action="{{ route('role.store') }}" enctype="multipart/form-data">
            @endif
            @csrf
                <div class="form-row">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body row ">

                        <div class="form-group col-md-12">
                            <label for="role">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{!empty($role->name) ? $role->name: ''}}" placeholder="Enter Name" />

                            @error('name')
                              <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                          </div>


                            <div class="form-group col-md-12">
                                <label for="role">Description</label>
                                <input type="text" class="form-control" id="description" name="description" value="{{!empty($role->description) ? $role->description: ''}}" placeholder="Enter link" />

                                @error('btn_link')
                                  <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                              </div>

                              <div class="form-group col-md-12">
                                <label for="role">level</label>
                                <input type="number" class="form-control" id="level" name="level" value="{{!empty($role->level) ? $role->level: 0}}" placeholder="Enter Level" min="0" required />

                                @error('name')
                                  <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                              </div>

                              <div class="form-group col-md-12">
                                <label for="role">Role Type</label>
                                <select class="form-control" id="type" name="type">
                                    <option value="admin"  {{ isset($role) ? (($role->type == 'admin') ? 'selected' : '') : '' }}>Admin</option>
                                    <option value="user" {{ isset($role) ? (($role->type == 'user') ? 'selected' : '') : '' }}>User</option>
                                </select>


                                @error('type')
                                  <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                              </div>

                          <div class="form-group col-md-12">
                          <label for="name">Status</label>
                          <div class="">
                              <label class="switch">
                                <input type="hidden" class="" name="status" value="0">

                                <input type="checkbox" name="status" value="1" {{ isset($role) ? (($role->status == 1) ? 'checked' : '') : '' }}>
                                <span class="slider round"></span>
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card">
                          <div class="card-body text-right">
                              <button class="btn btn-danger mr-2" type="submit"> {{ isset($role) ? 'Update' : 'Submit' }} </button>
                              <a href="{{ route('role') }}" class="btn btn-default">Cancel</a>
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

    });
        $('#roleForm').validate({
            errorPlacement: function(error, element) {
                error.insertAfter(element)
                error.addClass('text-danger');
            },

            rules: {
        name:{

          required:true,
          remote: {
        type: 'post',
        url: "{{ route('role.existrole') }}",
        data: {
          name: function() {
            return  $("input[id='name']").val();
          },
          id: "{{ isset($role) ? $role->id : '' }}",

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
