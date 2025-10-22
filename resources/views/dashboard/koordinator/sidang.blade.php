@extends('layouts.app')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



@section('content')
<div class="container">
    <div class="sidebar">
        <a href="{{ route('koordinator.listmahasiswa') }}">List Mahasiswa</a>
        <a href="{{ route('koordinator.ploting-pembimbing') }}">Ploting Pembimbing</a>
        <a href="{{ route('koordinator.sidang') }}" class="active">Sidang</a>
    </div>

    <div class="main">
        <div class="header">
            <div class="logo">
                <img src="{{ asset('img/LOGO.png') }}" alt="UBPLogo">
            </div>
            <div class="profile">
                <strong>{{ Auth::user()->name }}</strong>
                <small>{{ Auth::user()->email }}</small>
                <img src="{{ asset('img/R1_.jpg') }}" alt="Profile">
            </div>
        </div>

        <div class="content">
            <h2>Ploting Jadwal Sidang</h2>
            <div class="search-bar mb-3">
                <input type="text" id="search" placeholder="Search Bar..." class="form-control" onkeyup="searchTable()">
            </div>

            <div class="table-responsive-wrapper">
                    <table id="sidangTable" class="table table-bordered table-striped ">
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Judul TA</th>
                        <th>Tanggal Sidang</th>
                        <th>Ketua Sidang</th>
                        <th>Penguji 1</th>
                        <th>Penguji 2</th>
                        <th>Ruang Sidang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($sidang as $item)
                   <form action="{{ route('koordinator.updateSidang', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <tr>
                            <td class="text-center align-middle">
                                <input type="checkbox" class="sidang-checkbox" value="{{ $item->id }}">
                            </td>
                            <td>{{ optional($item->mahasiswa)->nama ?? '-' }}</td>
                            <td>{{ optional($item->mahasiswa)->nim ?? '-' }}</td>
                            <td>{{ $item->judul_ta ?? 'Belum Ada' }}</td>
                            <td>
                                <input
                                    type="date"
                                    name="tanggal_sidang"
                                    class="form-control tanggal-sidang"
                                    data-sidang-id="{{ $item->id }}"
                                    value="{{ $item->tanggal_sidang ?? '' }}"
                                    required>
                            </td>
                            <td>
                                <select name="ketua_id" data-sidang-id="{{ $item->id }}" class="form-control select2 ketua-select" style="width: 100%;" required>
                                    <option value="">-- Pilih Ketua Sidang --</option>
                                    @foreach($dosen as $d)
                                        <option value="{{ $d->id }}" {{ ($item->ketua_id ?? '') == $d->id ? 'selected' : '' }}>
                                            {{ $d->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="penguji_id" data-sidang-id="{{ $item->id }}" class="form-control select2 penguji-select" style="width: 100%;" required>
                                    <option value="">-- Pilih Penguji --</option>
                                    @foreach($dosen as $d)
                                        <option value="{{ $d->id }}" {{ ($item->penguji_id ?? '') == $d->id ? 'selected' : '' }}>
                                            {{ $d->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="penguji2_id" data-sidang-id="{{ $item->id }}" class="form-control select2 penguji2-select" style="width: 100%;" required>
                                    <option value="">-- Pilih Penguji --</option>
                                    @foreach($dosen as $d)
                                        <option value="{{ $d->id }}" {{ ($item->penguji2_id ?? '') == $d->id ? 'selected' : '' }}>
                                            {{ $d->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                @php $ruang_sidang = ['C.2.01', 'B.3.10', 'f.1.01', 'A.1.04']; @endphp
                                <select name="ruang_sidang" class="form-control ruang-sidang" data-sidang-id="{{ $item->id }}" required>
                                    <option value="">Pilih ruang-sidang</option>
                                    @foreach($ruang_sidang as $ruangan)
                                        <option value="{{ $ruangan }}" {{ ($item->ruang_sidang ?? '') == $ruangan ? 'selected' : '' }}>
                                            {{ $ruangan }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            {{-- Tombol Simpan --}}
                            <td class="text-center align-middle">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fa fa-save"></i> Simpan
                                </button>
                            </td>
                        </tr>
                    </form>
                    @endforeach
                </tbody>
            </table>

            {{-- Tombol simpan semua --}}
            <div class="mt-3 text-end">
                <button id="saveSelected" class="btn btn-primary">
                    <i class=""></i> Simpan Semua yang Dipilih
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.highlight-sync {
    background-color: #fff3cd !important;
    transition: background-color 0.8s ease;
}
table th, table td { vertical-align: middle !important; }
</style>

{{-- ===== Script Interaktif ===== --}}
<script>
$(document).ready(function() {
    // Inisialisasi Select2
    $('.select2').select2({
        placeholder: "-- Pilih --",
        allowClear: true
    });

    // Select All Checkbox
    $('#selectAll').on('change', function() {
        $('.sidang-checkbox').prop('checked', $(this).is(':checked'));
    });

    // Sinkronisasi otomatis ke yang dicentang
    function syncToChecked(selector, value) {
        $('.sidang-checkbox:checked').each(function() {
            let sidangId = $(this).val();
            let target = $(selector + '[data-sidang-id="'+sidangId+'"]');
            target.val(value).trigger('change.select2');
            let row = target.closest('tr');
            row.addClass('highlight-sync');
            setTimeout(() => row.removeClass('highlight-sync'), 800);
        });
    }

    // Ubah penguji => sinkron ke semua dicentang
        $('.tanggal-sidang').on('change', function() {
        let value = $(this).val();
        if ($('.sidang-checkbox:checked').length > 1) {
            syncToChecked('.tanggal-sidang', value);
        }
    });
        $('.ketua-select').on('change', function() {
        let value = $(this).val();
        if ($('.sidang-checkbox:checked').length > 1) {
            syncToChecked('.ketua-select', value);
        }
    });
    $('.penguji-select').on('change', function() {
        let value = $(this).val();
        if ($('.sidang-checkbox:checked').length > 1) {
            syncToChecked('.penguji-select', value);
        }
    });
    $('.penguji2-select').on('change', function() {
        let value = $(this).val();
        if ($('.sidang-checkbox:checked').length > 1) {
            syncToChecked('.penguji2-select', value);
        }
    });
    $('.ruang-sidang').on('change', function() {
        let value = $(this).val();
        if ($('.sidang-checkbox:checked').length > 1) {
            syncToChecked('.ruang-sidang', value);
        }
    });

    $('#saveSelected').on('click', function() {
        let selected = [];
        $('.sidang-checkbox:checked').each(function() {
            let id = $(this).val();
            selected.push({
                id: id,
                tanggal_sidang: $('input[data-sidang-id="'+id+'"]').val(),
                ketua_id: $('select.ketua-select[data-sidang-id="'+id+'"]').val(),
                penguji_id: $('select.penguji-select[data-sidang-id="'+id+'"]').val(),
                penguji2_id: $('select.penguji2-select[data-sidang-id="'+id+'"]').val(),
                ruang_sidang: $('select.ruang-sidang[data-sidang-id="'+id+'"]').val()
            });
        });

        if (selected.length === 0) {
            alert('Pilih minimal 1 mahasiswa untuk disimpan.');
            return;
        }

        $.ajax({
            url: "{{ route('koordinator.updateSidangBulk') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                data: selected
            },
            success: function(res) {
                alert('Berhasil menyimpan jadwal sidang!');
                location.reload();
            },
            error: function(err) {
                console.error(err);
                alert('Terjadi kesalahan saat menyimpan.');
            }
        });
    });

    // Fitur pencarian tabel
    window.searchTable = function() {
        let input = document.getElementById("search").value.toLowerCase();
        let rows = document.querySelectorAll("#sidangTable tbody tr");
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(input) ? "" : "none";
        });
    }
    $(function() {
    $(".tanggal-sidang").datepicker({
        dateFormat: "dd/mm/yy"
    });
});
$('select.select2').on('select2:open', function() {
    $('.select2-results__option').attr('title', function() {
        return $(this).text();
    });
});

});
</script>
@endsection