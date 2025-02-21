@extends('layouts.app')

@section('subtitle', 'Category')
@section('content_header_title', 'Data Management')
@section('content_header_subtitle', 'Category')

@section('content_body')

    <x-adminlte-modal id="modalAddCategory" title="New Category" size="lg" theme="primary" icon="fas fa-list" v-centered
        scrollable>
        <form id="formCategory" enctype="multipart/form-data">
            @csrf
            <div class="form-new-category">
                {{-- Input Title --}}
                <label for="categoryName" class="form-label fw-light">Nama Kategori <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                    <input type="text" name="categoryName" id="categoryName" class="form-control" placeholder="Judul">
                </div>
            </div>

            <x-slot name="footerSlot">
                <button type="button" id="submitCategory" class="btn btn-primary">Submit</button>
            </x-slot>
        </form>
    </x-adminlte-modal>

    {{-- Button to Create Book --}}
    <x-adminlte-button label="New Category" icon="fas fa-list" data-toggle="modal" data-target="#modalAddCategory"
        class="bg-primary my-2" />

    <x-adminlte-modal id="modalUpdateCategory" title="Update Category" size="lg" theme="primary" icon="fas fa-list"
        v-centered scrollable>
        <form id="formUpdateCategory" enctype="multipart/form-data">
            @csrf
            <div class="form-update-category">
                {{-- Input Title --}}
                <label for="nameUpdate" class="form-label fw-light">Kategori <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                    <input type="hidden" name="categoryId" id="categoryId">
                    <input type="text" name="nameUpdate" id="nameUpdate" class="form-control" placeholder="Kategori">
                </div>
            </div>

            <x-slot name="footerSlot">
                <button type="button" id="btnUpdate" class="btn btn-primary">Save Changes</button>
            </x-slot>
        </form>
    </x-adminlte-modal>

    <table id="categoryTable" class="table table-striped table-hover display">
        <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">Nama Kategori</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody id="dataCategory">

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

            $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                fixedColumns: true,
                fixedHeader: true,
                responsive: true,
                ajax: {
                    url: "{{ route('getCategories') }}",
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
                                                data-target="#modalUpdateCategory">
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

            // Create Category
            $('#submitCategory').click(function() {

                if (!{{ auth()->user()->can('create categories') ? 'true' : 'false' }}) {
                    Swal.fire({
                        title: "Ditolak",
                        text: "Kamu tidak memiliki akses.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }
                let formData = new FormData($('#formCategory')[0]);

                $.ajax({
                    url: "{{ route('category.store') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        let modal = $('#modalAddCategory');
                        if (modal.modal) {
                            modal.modal('hide');
                        } else {
                            modal.removeClass('show').attr('aria-hidden', 'true').css('display',
                                'none');
                        }

                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        $('#formCategory')[0].reset();

                        $('#categoryTable').DataTable().ajax.reload(null, false);

                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Berhasil Menambah Kategori!',
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
                if (!{{ auth()->user()->can('update categories') ? 'true' : 'false' }}) {
                    Swal.fire({
                        title: "Ditolak",
                        text: "Kamu tidak memiliki akses.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                $.ajax({
                    url: '{{ route('category.edit') }}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        console.log(response.data)
                        $('input[name="categoryId"]').val(response.data.id)
                        $('input[name="nameUpdate"]').val(response.data.name)
                    }
                })
            });

            // Update Data
            $(document).on('click', '#btnUpdate', function() {
                if (!{{ auth()->user()->can('update categories') ? 'true' : 'false' }}) {
                    Swal.fire({
                        title: "Ditolak",
                        text: "Kamu tidak memiliki akses.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                let formData = new FormData($('#formUpdateCategory')[0]);
                formData.append('_method', 'PUT');

                $.ajax({
                    url: "{{ route('category.update') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        let modal = $('#modalUpdateCategory');
                        if (modal.modal) {
                            modal.modal('hide');
                        } else {
                            modal.removeClass('show').attr('aria-hidden', 'true').css('display',
                                'none');
                        }

                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        $('#formUpdateCategory')[0].reset();

                        $('#categoryTable').DataTable().ajax.reload(null, false);

                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Kategori berhasil diperbarui!',
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
                if (!{{ auth()->user()->can('delete categories') ? 'true' : 'false' }}) {
                    Swal.fire({
                        title: "Ditolak",
                        text: "Kamu tidak memiliki akses.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                let categoryId = $(this).data('id');

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
                            url: `/admin/category/${categoryId}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                // Refresh DataTable
                                $('#categoryTable').DataTable().ajax.reload(null, false);

                                // Tampilkan SweetAlert sukses
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
