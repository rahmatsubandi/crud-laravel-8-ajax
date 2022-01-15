<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>CRUD Laravel 8 AJAX</title>

  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/css/bootstrap.min.css' />
  <link rel='stylesheet'
    href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css' />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.10.25/datatables.min.css" />
</head>
{{-- add modal start --}}
<div class="modal fade" id="addEmployeModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" id="add_employe_form" enctype="multipart/form-data">
        @csrf
        <div class="modal-body p-4 bg-light">
          <div class="row">
            <div class="col-lg">
              <label for="fname">First Name</label>
              <input type="text" name="fname" class="form-control" placeholder="First Name" required>
            </div>
            <div class="col-lg">
              <label for="lname">Last Name</label>
              <input type="text" name="lname" class="form-control" placeholder="Last Name" required>
            </div>
          </div>
          <div class="my-2">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Email" required>
          </div>
          <div class="my-2">
            <label for="phone">Phone</label>
            <input type="tel" name="phone" class="form-control" placeholder="Phone" required>
          </div>
          <div class="my-2">
            <label for="post">Post</label>
            <input type="text" name="post" class="form-control" placeholder="Post" required>
          </div>
          <div class="my-2">
            <label for="avatar">Select Avatar</label>
            <input type="file" name="avatar" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="add_employe_btn" class="btn btn-primary">Add Data</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- add modal end --}}

{{-- edit modal start --}}
<div class="modal fade" id="editEmployeModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" id="edit_employe_form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="emp_id" id="emp_id">
        <input type="hidden" name="emp_avatar" id="emp_avatar">
        <div class="modal-body p-4 bg-light">
          <div class="row">
            <div class="col-lg">
              <label for="fname">First Name</label>
              <input type="text" name="fname" id="fname" class="form-control" placeholder="First Name" required>
            </div>
            <div class="col-lg">
              <label for="lname">Last Name</label>
              <input type="text" name="lname" id="lname" class="form-control" placeholder="Last Name" required>
            </div>
          </div>
          <div class="my-2">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
          </div>
          <div class="my-2">
            <label for="phone">Phone</label>
            <input type="tel" name="phone" id="phone" class="form-control" placeholder="Phone" required>
          </div>
          <div class="my-2">
            <label for="post">Post</label>
            <input type="text" name="post" id="post" class="form-control" placeholder="Post" required>
          </div>
          <div class="my-2">
            <label for="avatar">Select Avatar</label>
            <input type="file" name="avatar" class="form-control">
          </div>
          <div class="mt-2" id="avatar"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="edit_employe_btn" class="btn btn-info text-light">Update Data</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- edit modal end --}}

<body>
  <div class="container">
    <div class="row my-5">
      <div class="col-lg-12">
        <div class="card shadow-sm">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Manage Data</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeModal"><i
                class="bi-plus-circle"></i></button>
          </div>
          <div class="card-body" id="show_all_employes">
            <h1 class="text-center text-secondary my-5">Loading...</h1>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/js/bootstrap.bundle.min.js'></script>
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/datatables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    $(function() {
      // add data ajax request
      $("#add_employe_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#add_employe_btn").text("Adding...");
        $.ajax({
          url: '{{ route('store') }}',
          method: "POST",
          data: fd,
          cache: false,
          contentType: false,
          processData: false,
          dataType: "json",
          success: function(response) {
            if (response.status == 200) {
              Swal.fire(
                'Data Added!',
                'Data has been added successfully!',
                'success'
              )
              fetchAllEmployes();
            }
            $("#add_employe_btn").text("Add Data");
            $("#add_employe_form")[0].reset();
            $("#addEmployeModal").modal("hide");
          }
        });
      });
      
    // edit employe ajax request
    $(document).on('click', '.editIcon', function(e) {
      e.preventDefault();
      let id = $(this).attr('id');
      $.ajax({
        url: '{{ route('edit') }}',
        method: "GET",
        data: {
          id: id,
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          $("#fname").val(response.first_name);
          $("#lname").val(response.last_name);
          $("#email").val(response.email);
          $("#phone").val(response.phone);
          $("#post").val(response.post);
          $("#avatar").html(`<img src="storage/images/${response.avatar}" width="100" class="img-fluid img-thumbnail">`);
          $("#emp_id").val(response.id);
          $("#emp_avatar").val(response.avatar);
        }
      });
    });

    // update employe ajax request
    $("#edit_employe_form").submit(function(e) {
      e.preventDefault();
      const fd = new FormData(this);
      $("#edit_employe_btn").text("Updating...");
      $.ajax({
        url: '{{ route('update') }}',
        method: "POST",
        data: fd,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(response){
          if (response.status == 200) {
            Swal.fire(
              'Data Updated!',
              'Data has been updated successfully!',
              'success'
            )
            fetchAllEmployes();
          }
          $("#edit_employe_btn").text("Update Data");
          $("#edit_employe_form")[0].reset();
          $("#editEmployeModal").modal("hide");
        }
      });
    });

    // delete employe ajax request
    $(document).on('click', '.deleteIcon', function(e) {
      e.preventDefault();
      let id = $(this).attr('id');
      let csrf = '{{ csrf_token() }}';
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: '{{ route('delete') }}',
            method: "DELETE",
            data: {
              id: id,
              _token: csrf
            },
            success: function(response) {
              console.log(response);
              Swal.fire(
                'Deleted!',
                'Data has been deleted successfully!',
                'success'
              )
              fetchAllEmployes();
            }
          });
        }
      })
    });

    // fetch all employes ajax request
    fetchAllEmployes();

    function fetchAllEmployes() {
      $.ajax({
        url: '{{ route('fetchAll') }}',
        method: "GET",
        success: function(response) {
          $("#show_all_employes").html(response);
          $("table").DataTable({
            order: [0, 'desc']
          });
        }
      });
    }
  });
  </script>
</body>

</html>