@extends('layouts.app')

@section('subtitle', 'Role')
@section('content_header_title', 'Data Management')
@section('content_header_subtitle', 'Role')

@section('content_body')

    <x-adminlte-modal id="modalAddRole" title="New Role" size="lg" theme="primary" icon="fas fa-list" v-centered scrollable>
        <form id="formRole" enctype="multipart/form-data">
            @csrf
            <div class="form-new-role">
                {{-- Input Title --}}
                <label for="roleName" class="form-label fw-light">Nama Role <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                    <input type="text" name="roleName" id="roleName" class="form-control" placeholder="Role">
                </div>
            </div>

            <x-slot name="footerSlot">
                <button type="button" id="submitRole" class="btn btn-primary">Submit</button>
            </x-slot>
        </form>
    </x-adminlte-modal>

    {{-- Button to Create Book --}}
    <x-adminlte-button label="New Role" icon="fas fa-list" data-toggle="modal" data-target="#modalAddRole"
        class="bg-primary my-2" />

    {{-- Modal Update --}}
    <x-adminlte-modal id="modalUpdateRole" title="Update Role" size="lg" theme="primary" icon="fas fa-list" v-centered
        scrollable>
        <form id="formUpdateRole" enctype="multipart/form-data">
            @csrf
            <div class="form-update-role">
                {{-- Input Title --}}
                <label for="nameUpdate" class="form-label fw-light">Nama Role <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                    <input type="hidden" name="roleId" id="roleId">
                    <input type="text" name="nameUpdate" id="nameUpdate" class="form-control" placeholder="Nama Role">
                </div>
            </div>

            <div>
                <label for="permissionsEdit">Edit Permissions</label>
                <div id="permissionsList"></div>
            </div>

            <x-slot name="footerSlot">
                <button type="button" id="btnUpdate" class="btn btn-primary">Save Changes</button>
            </x-slot>
        </form>
    </x-adminlte-modal>

    <table id="roleTable" class="table table-striped table-hover display">
        <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">Nama Role</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody id="dataRole">

        </tbody>
    </table>

@stop

@push('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@push('js')
    {{-- Jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Data tables --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#roleTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                fixedColumns: true,
                fixedHeader: true,
                responsive: true,
                ajax: {
                    url: "{{ route('getRoles') }}",
                    type: "GET"
                },
                columns: [{
                        data: "id",
                        name: "id"
                    },
                    {
                        data: "name",
                        name: "name"
                    },
                    {
                        render: function(data, type, row) {
                            return data = `
                                            <button class="btn btn-info m-1 btn-edit" 
                                                id="btnEdit"
                                                data-id="${row.id}"
                                                data-toggle="modal"
                                                data-target="#modalUpdateRole">
                                                <i class="fas fa-pen"></i>
                                            </button>

                                            <button class="btn btn-danger m-1" id="btnDelete" data-id="${row.id}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        `
                        }
                    }
                ],
            });

            // Create Role
            $('#submitRole').click(function() {
                if (!{{ auth()->user()->can('create roles') ? 'true' : 'false' }}) {
                    Swal.fire({
                        title: "Ditolak",
                        text: "Kamu tidak memiliki akses.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                let formData = new FormData($('#formRole')[0]);

                $.ajax({
                    url: "{{ route('role.store') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#modalAddRole').modal('hide');
                        $('#formRole')[0].reset();
                        $('#roleTable').DataTable().ajax.reload(null, false);

                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Berhasil Menambah Role!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan: ' + xhr.responseJSON.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Menampilkan data berdasarkan ID
            $(document).on('click', '#btnEdit', function() {
                if (!{{ auth()->user()->can('update roles') ? 'true' : 'false' }}) {
                    Swal.fire({
                        title: "Ditolak",
                        text: "Kamu tidak memiliki akses.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                let roleId = $(this).data('id');

                $.ajax({
                    url: '{{ route('role.edit') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": roleId
                    },
                    success: function(response) {
                        $('input[name="roleId"]').val(response.data.id);
                        $('input[name="nameUpdate"]').val(response.data.name);

                        $('#permissionsList').empty();

                        response.permissions.forEach(permission => {
                            let checked = response.rolePermissions.includes(permission
                                .id) ? 'checked' : '';

                            let permissionHtml = `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="${permission.id}" ${checked}>
                        <label class="form-check-label">${permission.name}</label>
                    </div>
                `;

                            $('#permissionsList').append(permissionHtml);
                        });

                        $('#modalUpdateRole').modal('show');
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan: ' + xhr.responseJSON.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Update Data
            $(document).on('click', '#btnUpdate', function() {
                if (!{{ auth()->user()->can('update roles') ? 'true' : 'false' }}) {
                    Swal.fire({
                        title: "Ditolak",
                        text: "Kamu tidak memiliki akses.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                let formData = new FormData();
                formData.append("_token", "{{ csrf_token() }}");
                formData.append("roleId", $('input[name="roleId"]').val());
                formData.append("nameUpdate", $('input[name="nameUpdate"]').val());

                // Append permissions yang dipilih
                $('input[name="permissions[]"]:checked').each(function() {
                    formData.append("permissions[]", $(this).val());
                });

                $.ajax({
                    url: "{{ route('role.update') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        let modal = $('#modalUpdateRole');
                        if (modal.modal) {
                            modal.modal('hide');
                        } else {
                            modal.removeClass('show').attr('aria-hidden', 'true').css('display',
                                'none');
                        }

                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        $('#formUpdateRole')[0].reset();

                        $('#roleTable').DataTable().ajax.reload(null, false);

                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Role berhasil diperbarui!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan: ' + xhr.responseJSON.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Delete Data
            $(document).on('click', '#btnDelete', function() {
                if (!{{ auth()->user()->can('delete roles') ? 'true' : 'false' }}) {
                    Swal.fire({
                        title: "Ditolak",
                        text: "Kamu tidak memiliki akses.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                let roleId = $(this).data('id');

                Swal.fire({
                    title: "Apakah yakin?",
                    text: "Data yang dihapus tidak bisa dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, hapus!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/role/${roleId}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                $('#roleTable').DataTable().ajax.reload(null, false);

                                Swal.fire({
                                    title: "Dihapus!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: "Gagal!",
                                    text: "Terjadi kesalahan saat menghapus data.",
                                    icon: "error",
                                    confirmButtonText: "OK"
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
