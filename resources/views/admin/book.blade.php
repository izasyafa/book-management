@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Book')
@section('content_header_title', 'Data Management')
@section('content_header_subtitle', 'Book')

{{-- Content body: main page content --}}

@section('content_body')

    {{-- Button to Create Book --}}
    @can('create books')
        <x-adminlte-button label="New Book" icon="fas fa-book" data-toggle="modal" data-target="#modalAddBook"
            class="bg-primary my-2" />
    @endcan

    {{-- Button to Create Book --}}
    @can('create books')
        <x-adminlte-button label="Import CSV" icon="fas fa-book" data-toggle="modal" data-target="#modalImportBook"
            class="bg-primary my-2" />
    @endcan

    {{-- Modal New Book/Create Book --}}
    <x-adminlte-modal id="modalImportBook" title="New Book" size="lg" theme="primary" icon="fas fa-book" v-centered
        scrollable>
        <form id="formImportBook" enctype="multipart/form-data">
            @csrf
            <div class="form-import-book">

                {{-- Input Cover --}}
                <div class="mb-3">
                    <label for="importBook" class="form-label">Upload File CSV <span class="text-danger">*</span>
                    </label>
                    <input class="form-control" type="file" id="importBook" name="importBook">
                </div>

            </div>

            <x-slot name="footerSlot">
                <button type="button" id="submitImportBook" class="btn btn-primary">Submit</button>
            </x-slot>
        </form>
    </x-adminlte-modal>

    {{-- Modal New Book/Create Book --}}
    <x-adminlte-modal id="modalAddBook" title="New Book" size="lg" theme="primary" icon="fas fa-book" v-centered
        scrollable>
        <form id="formBook" enctype="multipart/form-data">
            @csrf
            <div class="form-new-book">
                {{-- Input Writer --}}
                <label for="writer" class="form-label fw-light">Penulis <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                    <input type="hidden" name="writer" id="writer" value="{{ $user->id }}">
                    <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                </div>

                {{-- Input Title --}}
                <label for="title" class="form-label fw-light">Judul <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                    <input type="text" name="title" id="title" class="form-control" placeholder="Judul">
                </div>

                {{-- Input Category --}}
                <label for="category" class="form-label fw-light">Kategori <span class="text-danger">*</span></label>
                <x-adminlte-select name="category" id="category">
                    <option disabled selected>-- Pilih Kategori --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </x-adminlte-select>

                {{-- Input Publisher --}}
                <label for="publisher" class="form-label fw-light">Penerbit <span class="text-danger">*</span></label>
                <x-adminlte-select name="publisher" id="publisher">
                    <option disabled selected>-- Pilih Penerbit --</option>
                    @foreach ($publishers as $publisher)
                        <option value="{{ $publisher->id }}">{{ $publisher->name }}</option>
                    @endforeach
                </x-adminlte-select>

                {{-- Input Cover --}}
                <div class="mb-3">
                    <label for="coverBook" class="form-label">Upload Cover Book <span class="text-danger">*</span>
                    </label>
                    <input class="form-control" type="file" id="coverBook" name="coverBook">
                </div>
            </div>

            <x-slot name="footerSlot">
                <button type="button" id="submitBook" class="btn btn-primary">Submit</button>
            </x-slot>
        </form>
    </x-adminlte-modal>


    {{-- Modal Update Book --}}
    <x-adminlte-modal id="modalUpdateBook" title="Update Book" size="lg" theme="primary" icon="fas fa-book" v-centered
        scrollable>
        <form id="formUpdateBook" enctype="multipart/form-data">
            @csrf
            <div class="form-update-book">
                {{-- Input Writer --}}
                <label for="writer" class="form-label fw-light">Penulis <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                    <input type="hidden" name="bookId" id="bookId">
                    <input type="hidden" name="writer" id="writer" value="{{ $user->id }}">
                    <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                </div>

                {{-- Input Title --}}
                <label for="titleUpdate" class="form-label fw-light">Judul <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                    <input type="text" name="titleUpdate" id="title" class="form-control" placeholder="Judul">
                </div>

                {{-- Input Category --}}
                <label for="categoryUpdate" class="form-label fw-light">Kategori <span
                        class="text-danger">*</span></label>
                <x-adminlte-select name="categoryUpdate" id="category">
                    <option disabled selected>-- Pilih Kategori --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </x-adminlte-select>

                {{-- Input Publisher --}}
                <label for="publisherUpdate" class="form-label fw-light">Penerbit <span
                        class="text-danger">*</span></label>
                <x-adminlte-select name="publisherUpdate" id="publisher">
                    <option disabled selected>-- Pilih Penerbit --</option>
                    @foreach ($publishers as $publisher)
                        <option value="{{ $publisher->id }}">{{ $publisher->name }}</option>
                    @endforeach
                </x-adminlte-select>

                <img id="previewCover" alt="" width="100" height="100" style="border-radius: 5px;">
                {{-- Input Cover --}}
                <div class="mb-3">
                    <label for="coverBookUpdate" class="form-label">Upload Cover Book
                    </label>
                    <input class="form-control" type="file" id="coverBookUpdate" name="coverBookUpdate">
                </div>
            </div>

            <x-slot name="footerSlot">
                <button type="button" id="btnUpdate" class="btn btn-primary">Save Changes</button>
            </x-slot>
        </form>
    </x-adminlte-modal>

    <div class="form-filter">
        <label for="categoryFilter">Filter:</label>
        <x-adminlte-select name="categoryFilter" id="categoryFilter">
            <option disabled selected>-- Pilih Kategori --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </x-adminlte-select>
    </div>

    <table id="bookTable" class="table table-striped table-hover display">
        <thead>
            <tr>
                <th scope="col">Judul</th>
                <th scope="col">Penulis</th>
                <th scope="col">Kategori</th>
                <th scope="col">Penerbit</th>
                <th scope="col">Cover</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody id="dataBook">

        </tbody>
    </table>

@stop

{{-- Push extra CSS --}}

@push('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

{{-- Push extra scripts --}}

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {

            // Konfigurasi Data tables
            var table = $('#bookTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                fixedColumns: true,
                fixedHeader: true,
                responsive: true,
                columnDefs: [{
                        width: "200px",
                        targets: 0
                    },
                    {
                        width: "150px",
                        targets: 1
                    },
                    {
                        width: "100px",
                        targets: 2
                    },
                    {
                        width: "120px",
                        targets: 3
                    },
                    {
                        width: "80px",
                        targets: 4
                    }
                ],
                ajax: {
                    url: "{{ route('getBooks') }}",
                    type: "GET",
                    data: function(d) {
                        d.category = $('#categoryFilter').val();
                    }
                },
                columns: [{
                        data: "title",
                        name: "title"
                    },
                    {
                        data: "user.name",
                        name: "user.name"
                    },
                    {
                        data: "category.name",
                        name: "category.name"
                    },
                    {
                        data: "publisher.name",
                        name: "publisher.name"
                    },
                    {
                        data: "cover_book",
                        name: "cover_book",
                    },
                    {
                        render: function(data, type, row) {
                            return data = `
                                            @can('update books')
                                            <button class="btn btn-info m-1 btn-edit" 
                                                id="btnEdit"
                                                data-id="${row.id}"
                                                data-toggle="modal"
                                                data-target="#modalUpdateBook">
                                                <i class="fas fa-pen"></i>
                                                </button>
                                                @endcan
                                            @can('delete books')
                                            <button class="btn btn-danger m-1" id="btnDelete" data-id="${row.id}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endcan
                                        `
                        }
                    }
                ],
            });

            $('#categoryFilter').change(function() {
                table.draw();
            });

            // Konfigurasi Import
            $('#submitImportBook').click(function(e) {
                e.preventDefault();

                let formData = new FormData();
                formData.append('importBook', $('#importBook')[0].files[0]);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    url: "{{ route('import.book') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#submitImportBook').prop('disabled', true).text('Importing...');
                    },
                    success: function(response) {
                        $('#submitImportBook').prop('disabled', false).text('Submit');
                        Swal.fire({
                            title: "Berhasil!",
                            text: response.message,
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        $('#submitImportBook').prop('disabled', false).text('Submit');
                        console.log(xhr.responseText); // Debugging untuk lihat error
                        Swal.fire({
                            title: "Terjadi Kesalahan!",
                            text: xhr.responseText,
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                });
            });

            // Create Data
            $('#submitBook').click(function() {
                if (!{{ auth()->user()->can('create books') ? 'true' : 'false' }}) {
                    Swal.fire({
                        title: "Ditolak",
                        text: "Kamu tidak memiliki akses.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                let formData = new FormData($('#formBook')[0]);

                $.ajax({
                    url: "{{ route('book.store') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        let modal = $('#modalAddBook');
                        if (modal.modal) {
                            modal.modal('hide');
                        } else {
                            modal.removeClass('show').attr('aria-hidden', 'true').css('display',
                                'none');
                        }

                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        $('#formBook')[0].reset();

                        $('#bookTable').DataTable().ajax.reload(null, false);

                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Berhasil Menambah Buku!',
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
                if (!{{ auth()->user()->can('update books') ? 'true' : 'false' }}) {
                    Swal.fire({
                        title: "Ditolak",
                        text: "Kamu tidak memiliki akses.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    }).then(() => {
                        if ($('#modalUpdateBook').hasClass('show')) {
                            $('#modalUpdateBook').modal('hide');
                        }
                    });
                    return;
                }

                $.ajax({
                    url: '{{ route('book.edit') }}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        console.log(response.data)
                        $('input[name="bookId"]').val(response.data.id)
                        $('input[name="writer"]').val(response.data.user.name)
                        $('input[name="titleUpdate"]').val(response.data.title)
                        $('select[name="categoryUpdate"]').val(response.data.category.id)
                        $('select[name="publisherUpdate"]').val(response.data.publisher.id)
                        if (response.data.cover_book) {
                            let imageUrl =
                                `{{ asset('storage/') }}/${response.data.cover_book}`;
                            $('#previewCover').attr('src', imageUrl);
                        }
                    }
                })
            })

            // Update Data
            $(document).on('click', '#btnUpdate', function() {
                if (!{{ auth()->user()->can('update books') ? 'true' : 'false' }}) {
                    Swal.fire({
                        title: "Ditolak",
                        text: "Kamu tidak memiliki akses.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    }).then(() => {
                        if ($('#modalUpdateBook').hasClass('show')) {
                            $('#modalUpdateBook').modal('hide');
                        }
                    });
                    return;
                }

                let formData = new FormData($('#formUpdateBook')[0]);
                formData.append('_method', 'PUT');

                $.ajax({
                    url: "{{ route('book.update') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        let modal = $('#modalUpdateBook');
                        if (modal.modal) {
                            modal.modal('hide');
                        } else {
                            modal.removeClass('show').attr('aria-hidden', 'true').css('display',
                                'none');
                        }

                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        $('#formUpdateBook')[0].reset();

                        $('#bookTable').DataTable().ajax.reload(null, false);

                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Buku berhasil diperbarui!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
                    }
                })
            });

            // Delete Data
            $(document).on('click', '#btnDelete', function() {

                if (!{{ auth()->user()->can('delete books') ? 'true' : 'false' }}) {
                    Swal.fire({
                        title: "Ditolak",
                        text: "Kamu tidak memiliki akses.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                let bookId = $(this).data('id');

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
                            url: `/admin/book/${bookId}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                // Refresh DataTable
                                $('#bookTable').DataTable().ajax.reload(null, false);

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
