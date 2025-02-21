@extends('layouts.app')

@section('subtitle', 'User')
@section('content_header_title', 'Data Management')
@section('content_header_subtitle', 'User')

@section('content_body')

    <x-adminlte-modal id="modalAddUser" title="New User" size="lg" theme="primary" icon="fas fa-list" v-centered scrollable>
        <form id="formUser" enctype="multipart/form-data">
            @csrf
            <div class="form-new-user">
                {{-- Input Title --}}
                <label for="name" class="form-label fw-light">Nama <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nama">
                </div>

                {{-- Input Email --}}
                <label for="email" class="form-label fw-light">Email <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                </div>

                {{-- Input Password --}}
                <label for="password" class="form-label fw-light">Password <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                </div>

                {{-- Input Role --}}
                <label for="role" class="form-label fw-light">Role <span class="text-danger">*</span></label>
                <x-adminlte-select name="role" id="role">
                    <option disabled selected>-- Pilih Role --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </x-adminlte-select>
            </div>

            <x-slot name="footerSlot">
                <button type="button" id="submitUser" class="btn btn-primary">Submit</button>
            </x-slot>
        </form>
    </x-adminlte-modal>

    {{-- Button to Create Book --}}
    <x-adminlte-button label="New User" icon="fas fa-list" data-toggle="modal" data-target="#modalAddUser"
        class="bg-primary my-2" />

    {{-- Modal Update Userr --}}
    <x-adminlte-modal id="modalUpdateUser" title="Update User" size="lg" theme="primary" icon="fas fa-list" v-centered
        scrollable>
        <form id="formUpdateUser" enctype="multipart/form-data">
            @csrf
            <div class="form-update-user">
                {{-- Input Name --}}
                <label for="nameUpdate" class="form-label fw-light">Nama <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                    <input type="hidden" name="userId" id="userId">
                    <input type="text" name="nameUpdate" id="nameUpdate" class="form-control" placeholder="Nama">
                </div>

                {{-- Input Email --}}
                <label for="emailUpdate" class="form-label fw-light">Email <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                    <input type="email" name="emailUpdate" id="emailUpdate" class="form-control" placeholder="Email">
                </div>

                {{-- Input Password --}}
                <label for="passwordUpdate" class="form-label fw-light">Password</label>
                <div class="input-group mb-3">
                    <input type="password" name="passwordUpdate" id="passwordUpdate" class="form-control"
                        placeholder="Password">
                </div>

                {{-- Input Role --}}
                <label for="roleUpdate" class="form-label fw-light">Role <span class="text-danger">*</span></label>
                <x-adminlte-select name="roleUpdate" id="roleUpdate">
                    <option disabled selected>-- Pilih Role --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </x-adminlte-select>
            </div>

            <x-slot name="footerSlot">
                <button type="button" id="btnUpdate" class="btn btn-primary">Save Changes</button>
            </x-slot>
        </form>
    </x-adminlte-modal>

    <table id="userTable" class="table table-striped table-hover display">
        <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">Nama Penerbit</th>
                <th scope="col">Role</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody id="dataUser">

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

    {{-- Data tables --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                fixedColumns: true,
                fixedHeader: true,
                responsive: true,
                ajax: {
                    url: "{{ route('getUsers') }}",
                    type: "GET"
                },
                columns: [{
                        data: "name",
                        name: "name"
                    },
                    {
                        data: "email",
                        name: "email"
                    },
                    {
                        data: "role",
                        name: "role"
                    },
                    {
                        render: function(data, type, row) {
                            return data = `
                                            <button class="btn btn-info m-1 btn-edit" 
                                                id="btnEdit"
                                                data-id="${row.id}"
                                                data-toggle="modal"
                                                data-target="#modalUpdateUser">
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

            // Create Publisher
            $('#submitUser').click(function() {
                if (!{{ auth()->user()->can('create users') ? 'true' : 'false' }}) {
                    Swal.fire({
                        title: "Ditolak",
                        text: "Kamu tidak memiliki akses.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                let formData = new FormData($('#formUser')[0]);

                $.ajax({
                    url: "{{ route('user.store') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        let modal = $('#modalAddUser');
                        if (modal.modal) {
                            modal.modal('hide');
                        } else {
                            modal.removeClass('show').attr('aria-hidden', 'true').css('display',
                                'none');
                        }

                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        $('#formUser')[0].reset();

                        $('#userTable').DataTable().ajax.reload(null, false);

                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Berhasil Menambah Penerbit!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
                    }
                });
            });

            // Menampilkan data berdasarkan ID
            $(document).on('click', '#btnEdit', function() {
                if (!{{ auth()->user()->can('update users') ? 'true' : 'false' }}) {
                    Swal.fire({
                        title: "Ditolak",
                        text: "Kamu tidak memiliki akses.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                $.ajax({
                    url: '{{ route('user.edit') }}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        console.log(response.data)
                        $('input[name="userId"]').val(response.data.id)
                        $('input[name="nameUpdate"]').val(response.data.name)
                        $('input[name="emailUpdate"]').val(response.data.email)
                        $('select[name="roleUpdate"]').val(response.data.roles[0].id)
                    }
                })
            });

            // Update Data
            $(document).on('click', '#btnUpdate', function() {
                if (!{{ auth()->user()->can('update users') ? 'true' : 'false' }}) {
                    Swal.fire({
                        title: "Ditolak",
                        text: "Kamu tidak memiliki akses.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                let formData = new FormData($('#formUpdateUser')[0]);
                formData.append('_method', 'PUT');

                $.ajax({
                    url: "{{ route('user.update') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        let modal = $('#modalUpdateUser');
                        if (modal.modal) {
                            modal.modal('hide');
                        } else {
                            modal.removeClass('show').attr('aria-hidden', 'true').css('display',
                                'none');
                        }

                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        $('#formUpdateUser')[0].reset();

                        $('#userTable').DataTable().ajax.reload(null, false);

                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Penerbit berhasil diperbarui!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
                    }
                })
            });

            $(document).on('click', '#btnDelete', function() {
                if (!{{ auth()->user()->can('delete users') ? 'true' : 'false' }}) {
                    Swal.fire({
                        title: "Ditolak",
                        text: "Kamu tidak memiliki akses.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                let userId = $(this).data('id');

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
                            url: `/admin/user/${userId}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                $('#userTable').DataTable().ajax.reload(null, false);

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
