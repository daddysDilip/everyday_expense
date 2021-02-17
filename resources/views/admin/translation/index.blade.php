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
            <h1 class="m-0 text-dark">Translation</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('admin')}}">Home</a></li>

                <li class="breadcrumb-item" active>Translation</li>

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
                    <h3 class="card-title">All Translation</h3>
                    @if (get_user_permission("Translation","add"))
                      <a href="{{route('translation.create')}}" class="btn btn-sm btn-info " style="float: right">Add Translation</a>
                    @endif
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="TranslationTable" class="table table-hover display nowrap" style="width:100%">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>English String</th>
                          <th>Language</th>
                          <th>Language Code</th>
                          <th>Translation</th>
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
    $('#TranslationTable').DataTable({
      scrollY: '60vh',
      "scrollX": true,
      "processing": true,
      "serverSide": true,
      "stateSave": true,
      "ajax":{
        "url": "{{ route('alltranslation') }}",
        "dataType": "json",
        "type": "GET",
        "data":{ _token: "{{csrf_token()}}"}
      },
      "lengthMenu": [[10, 25, 50,100], [10, 25, 50,100]],
      "columns": [
        { "data": "id" },
        { "data": "english_string" },
        { "data": "language" },
        { "data": "code" },
        { "data": "translation" },
        { "data": "options" },
      ],
      'columnDefs': [ {
        'targets': [4,5], // column index (start from 0)
        'orderable': false, // set orderable false for selected columns
     }],
      buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });
  });
  
</script>

@endsection
