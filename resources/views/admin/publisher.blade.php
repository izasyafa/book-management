@extends('layouts.app')

@section('subtitle', 'Penerbit')
@section('content_header_title', 'Data Management')
@section('content_header_subtitle', 'Penerbit')

@section('content_body')

    <x-adminlte-modal id="modalAddPublisher" title="New Publisher" size="lg" theme="primary" icon="fas fa-list" v-centered
        scrollable>
        <form id="formPublisher" enctype="multipart/form-data">
            @csrf
            <div class="form-new-publisher">
                {{-- Input Title --}}
                <label for="publisherName" class="form-label fw-light">Nama Penerbit <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                    <input type="text" name="publisherName" id="publisherName" class="form-control" placeholder="Penerbit">
                </div>
            </div>

            <x-slot name="footerSlot">
                <button type="button" id="submitPublisher" class="btn btn-primary">Submit</button>
            </x-slot>
        </form>
    </x-adminlte-modal>

    {{-- Button to Create Book --}}
    <x-adminlte-button label="New Publisher" icon="fas fa-list" data-toggle="modal" data-target="#modalAddPublisher"
        class="bg-primary my-2" />

    <x-adminlte-modal id="modalUpdatePublisher" title="Update Publisher" size="lg" theme="primary" icon="fas fa-list"
        v-centered scrollable>
        <form id="formUpdatePublisher" enctype="multipart/form-data">
            @csrf
            <div class="form-update-publisher">
                {{-- Input Title --}}
                <label for="nameUpdate" class="form-label fw-light">Penerbit <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                    <input type="hidden" name="publisherId" id="publisherId">
                    <input type="text" name="nameUpdate" id="nameUpdate" class="form-control" placeholder="Penerbit">
                </div>
            </div>

            <x-slot name="footerSlot">
                <button type="button" id="btnUpdate" class="btn btn-primary">Save Changes</button>
            </x-slot>
        </form>
    </x-adminlte-modal>

    <table id="publisherTable" class="table table-striped table-hover display">
        <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">Nama Penerbit</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody id="dataPublisher">

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

            $('#publisherTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                fixedColumns: true,
                fixedHeader: true,
                responsive: true,
                ajax: {
                    url: "{{ route('getPublishers') }}",
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
                                                data-target="#modalUpdatePublisher">
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
            $('#submitPublisher').click(function() {
                if (!{{ auth()->user()->can('create publishers') ? 'true' : 'false' }}) {
                    Swal.fire({
                        title: "Ditolak",
                        text: "Kamu tidak memiliki akses.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                let formData = new FormData($('#formPublisher')[0]);

                $.ajax({
                    url: "{{ route('publisher.store') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        let modal = $('#modalAddPublisher');
                        if (modal.modal) {
                            modal.modal('hide');
                        } else {
                            modal.removeClass('show').attr('aria-hidden', 'true').css('display',
                                'none');
                        }

                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        $('#formPublisher')[0].reset();

                        $('#publisherTable').DataTable().ajax.reload(null, false);

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
                if (!{{ auth()->user()->can('update publishers') ? 'true' : 'false' }}) {
                    Swal.fire({
                        title: "Ditolak",
                        text: "Kamu tidak memiliki akses.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                $.ajax({
                    url: '{{ route('publisher.edit') }}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        console.log(response.data)
                        $('input[name="publisherId"]').val(response.data.id)
                        $('input[name="nameUpdate"]').val(response.data.name)
                    }
                })
            });

            // Update Data
            $(document).on('click', '#btnUpdate', function() {
                if (!{{ auth()->user()->can('update publishers') ? 'true' : 'false' }}) {
                    Swal.fire({
                        title: "Ditolak",
                        text: "Kamu tidak memiliki akses.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                let formData = new FormData($('#formUpdatePublisher')[0]);
                formData.append('_method', 'PUT');

                $.ajax({
                    url: "{{ route('publisher.update') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        let modal = $('#modalUpdatePublisher');
                        if (modal.modal) {
                            modal.modal('hide');
                        } else {
                            modal.removeClass('show').attr('aria-hidden', 'true').css('display',
                                'none');
                        }

                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        $('#formUpdatePublisher')[0].reset();

                        $('#publisherTable').DataTable().ajax.reload(null, false);

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
                if (!{{ auth()->user()->can('delete publishers') ? 'true' : 'false' }}) {
                    Swal.fire({
                        title: "Ditolak",
                        text: "Kamu tidak memiliki akses.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                let publisherId = $(this).data('id');

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
                            url: `/admin/publisher/${publisherId}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                // Refresh DataTable
                                $('#publisherTable').DataTable().ajax.reload(null, false);

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
