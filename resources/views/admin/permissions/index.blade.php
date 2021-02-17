@extends('layouts.app')
@section('extracss')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>User Permissions</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('admin')}}">Home</a></li>
            <li class="breadcrumb-item active">User Permissions</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

 <!-- Main content -->
 <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Select Role</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group ">
                        <label>Select Role</label>
                            <select name="role" id="role" class="form-control">
                                <option value="" >Select Role</option>

                                @if (!empty(get_roles()))
                                @foreach (get_roles() as $role)
                                @if($id != null &&  $id == $role->id)
                                <option value="{{$role->id}}" selected>{{$role->name}}</option>
                                @else
                                <option value="{{$role->id}}">{{$role->name}}</option>
                                @endif
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
            </div>
          </div>
            <!-- /.card-header -->



							@if (!empty($Permissions))
							<form id="permissionForm" method="post" action="{{route('save.all.permission')}}">
                            @csrf

                            <div class="card">
                                <div class="card-header">
                                  <h3 class="card-title">User Permissions</h3>
                                  <div class="text-right">
                                    <button type="submit" class="btn btn-danger">Save</button>
                                </div>
                                </div>
                            <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">

                                </div>
                            </div>

                                <table id="PermissionsTable" class="table table-hover display nowrap table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>

                                            <th>Module</th>
                                            <th>Add</th>
                                            <th>Update</th>
                                            <th>Delete</th>
                                            <th>View</th>

                                        </tr>
                                    </thead>
                                    <tbody>


                                        @if (!empty($Permissions))
											<?php $i=0; ?>
                                        @foreach ($Permissions as $perm)

                                        <tr>
                                            <td>{{ $i+1 }}</td>

                                            <td>{{$perm->moduleName}} </td>
                                            <td>
                                            <input type="hidden" name="permission[{{$i}}][moduleId]" value="{{$perm->moduleId}}">
                                            <input type="hidden" name="permission[{{$i}}][roleId]" value="{{$perm->roleId}}">
                                            <input type="checkbox" name="permission[{{$i}}][add]"  {{$perm->add == 1 ? "checked" : ""}} value="1" />
                                            </td>
                                            <td>
                                                <input type="checkbox" name="permission[{{$i}}][edit]"  {{$perm->edit == 1 ? "checked" : ""}} value="1" />
                                            </td>
                                            <td>
                                                <input type="checkbox" name="permission[{{$i}}][delete]" {{$perm->delete == 1 ? "checked" : ""}} value="1" />
                                            </td>
                                            <td>
                                                <input type="checkbox" name="permission[{{$i}}][view]"  {{$perm->view == 1 ? "checked" : ""}} value="1" />
                                            </td>

                                        </tr>
										<?php $i++; ?>
                                        @endforeach
                                        @endif

                                    </tbody>
                                </table>

                            </div>
                            <div class="card-footer"><div class="text-right">
                                <button type="submit" class="btn btn-danger">Save</button>
                            </div></div>


                            </div>
                                </form>

								@endif


                </div>
            </div>
        </div>
 </section>

@endsection


@section('extrajs')
@if ($message = Session::get('success'))

<script>
Swal.fire(
  'Thank You!',
   '{{ $message }}',
  'success'
										)
</script>

@endif

@if ($message = Session::get('error'))

<script>
Swal.fire(
  'Sorry!',
   '{{ $message }}',
  'error'
										)
</script>
@endif
<!-- DataTables  & Plugins -->
<script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

<script>
    $(document).ready(function() {

        $("#PermissionsTable").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
     // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    });
    });

    $('#role').change(function() {
        var roleId = $(this).val();
        if (roleId != "") {
            location.href = "{{url('admin/permissions')}}/" + roleId;
        }
    });

    function changePermission(roleId, moduleId, value, type) {
        if (roleId != "" && moduleId != "" && value != "" && type != "") {
            $.ajax({
                type: "GET",
                url: "{{url('add-permission')}}?roleId=" + roleId + "&moduleId=" + moduleId + "&value=" + value + "&type=" + type,
                success: function(res) {
                    if (res == "success") {
                        location.reload();
                    }
                    console.log(res);
                }
            });
        }
    }
</script>
@endsection
