@extends('layouts.app')

@section('extracss')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">


@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0 text-dark">Roles</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('admin')}}">Home</a></li>

                <li class="breadcrumb-item" active>Roles</li>

            </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
          <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Roles</h3>
                    @if (get_user_permission("Roles","add"))
                      <a href="{{route('role.create')}}" class="btn btn-sm btn-info " style="float: right">Add Role</a>
                    @endif
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="CategoryTable" class="table table-hover  display nowrap" style="width:100%">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Name</th>
                          <th>Description</th>
                          <th>Level</th>
                          <th>Type</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection


@section('extrajs')
<!-- DataTables -->
<script src="{{ asset('backend/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script>
  $(document).ready(function() {
    $('#CategoryTable').DataTable({
      scrollY: '60vh',
      "scrollX": true,
      "processing": true,
      "serverSide": true,
      "ajax":{
        "url": "{{ route('allrole') }}",
        "dataType": "json",
        "type": "GET",
        "data":{ _token: "{{csrf_token()}}"}
      },
      "lengthMenu": [[10, 25, 50,100], [10, 25, 50,100]],
      "columns": [
        { "data": "id" },
        { "data": "name" },
        { "data": "description" },
        { "data": "level" },
        { "data": "type" },
        { "data": "status" },
        { "data": "options" },
      ],
      'columnDefs': [ {
        'targets': [2], // column index (start from 0)
        'orderable': false, // set orderable false for selected columns
     }],
      buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });
  });
  $('.changeCategoryStatus').click(function() {
    var id = $(this).attr("data-category_id");
    var status = $(this).attr("data-status");
    $.ajax({
      type: "GET",
      url: "{{url('change-role-status')}}?id=" + id + "&status=" + status,
      success: function(res) {
        if (res == "success") {
          $('.msg').addClass("alert alert-success");
          $('.msg').text("Status changed successfully.");
          $('.msg').delay(800).fadeOut('slow');
          setTimeout(function() {
          location.reload();
          }, 2000);
        } else {
          $('.msg').addClass("alert alert-danger");
          $('.msg').text("Failed to change status.");
          $('.msg').delay(500).fadeOut('slow');
        }
      }
    });
    });
</script>

<script>

</script>
@endsection
