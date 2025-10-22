@extends('layouts.app')

{{-- Aset CSS dari kode Anda, ditambah jQuery UI untuk jaga-jaga --}}
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
{{-- Aset JS dari kode Anda --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@section('content')
<div class="container">
    {{-- Sidebar disesuaikan untuk Dosen --}}
    <div class="sidebar">
        <a href="#">Dashboard</a>
        <a href="{{ route('dosen.bimbingan') }}" class="active">Bimbingan Mahasiswa</a>
        <a href="#">Jadwal Sidang</a>
        {{-- Sesuaikan route ini dengan route Dosen Anda --}}
    </div>

    <div class="main">
        {{-- Header sama persis dengan kode Anda --}}
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
            <h2>Daftar Mahasiswa Bimbingan</h2>
            
            {{-- Search Bar sama persis dengan kode Anda --}}
            <div class="search-bar mb-3">
                <input type="text" id="search" placeholder="Cari Mahasiswa, NIM, atau Periode..." class="form-control" onkeyup="searchTable()">
            </div>

            {{-- 
              ! ASUMSI DATA:
              Saya berasumsi Anda mengirimkan variabel $bimbingan dari controller.
              - $bimbingan adalah collection.
              - Setiap $item dalam $bimbingan memiliki relasi ->mahasiswa (untuk nama & NIM).
              - $item->periode (string, misal: "2024/2025 Ganjil").
              - $item->progres (array, misal: ['ACC BAB 1', 'ACC BAB 2']).
              - $item->nilai (string/int, misal: "A-" atau 85).
            --}}

            {{-- Definisikan langkah-langkah progres di sini --}}
            @php
                $langkahBimbingan = [
                    'ACC Judul',
                    'ACC BAB 1',
                    'ACC BAB 2',
                    'ACC BAB 3',
                    'ACC BAB 4',
                    'ACC BAB 5',
                    'Seminar Proposal',
                    'Sidang Akhir'
                ];
                $totalLangkah = count($langkahBimbingan);
            @endphp

            <table id="bimbinganTable" class="">
                <thead class="table-primary text-center align-middle ">
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Periode</th>
                        <th>Progres Bimbingan</th>
                        <th>Nilai</th>
                        <th>Progres</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    {{-- Ganti $sidang menjadi $bimbingan (atau variabel yang Anda passing) --}}
                    @forelse($bimbingan as $item)
                    {{-- Ganti route ke route update bimbingan Anda --}}
                    <form action="{{ route('dosen.updateBimbingan', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <tr>
                            <td class="text-center align-middle">
                                {{-- Ganti class 'sidang-checkbox' ke 'mhs-checkbox' --}}
                                <input type="checkbox" class="mhs-checkbox" value="{{ $item->id }}">
                            </td>
                            {{-- Data Mahasiswa --}}
                            <td class="align-middle">{{ optional($item->mahasiswa)->nama ?? 'N/A' }}</td>
                            <td class="align-middle text-center">{{ optional($item->mahasiswa)->nim ?? 'N/A' }}</td>
                            <td class="align-middle text-center">{{ $item->periode ?? '-' }}</td>
                            
                            {{-- 1. Progres (Select2 Checklist) --}}
                            <td style="min-width: 250px;">
                                <select name="progres[]" class="form-control select2 progres-select" 
                                        multiple="multiple" data-mhs-id="{{ $item->id }}" 
                                        style="width: 100%;" data-total-langkah="{{ $totalLangkah }}">
                                    @foreach($langkahBimbingan as $langkah)
                                        {{-- $item->progres diasumsikan adalah array ['ACC BAB 1', 'ACC BAB 2'] --}}
                                        <option value="{{ $langkah }}" {{ (is_array($item->progres) && in_array($langkah, $item->progres)) ? 'selected' : '' }}>
                                            {{ $langkah }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            {{-- 2. Nilai --}}
                            <td class="align-middle" style="width: 100px;">
                                <input type="text" name="nilai" class="form-control nilai-input" 
                                       data-mhs-id="{{ $item->id }}" 
                                       value="{{ $item->nilai ?? '' }}" 
                                       style="width: 80px; text-align: center; margin: auto;">
                            </td>

                            {{-- 3. Progres Bar (%) --}}
                            <td class="align-middle" style="min-width: 150px;">
                                <div class="progress" style="height: 25px; font-size: 0.9rem;">
                                    <div class="progress-bar" role="progressbar" 
                                         id="progress-bar-{{ $item->id }}" 
                                         style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                        <span id="progress-text-{{ $item->id }}">0%</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Tombol Simpan per Baris --}}
                            <td class="text-center align-middle">
                                <button type="submit" class="btn btn-success btn-sm" title="Simpan baris ini">
                                    <i class="fa fa-save"></i>
                                </button>
                            </td>
                        </tr>
                    </form>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada mahasiswa bimbingan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Tombol simpan semua (bulk) --}}
            <div class="mt-3 text-end">
                <button id="saveSelected" class="btn btn-primary">
                    <i class="fa fa-save"></i> Simpan Semua yang Dipilih
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Style dari kode Anda */
.highlight-sync {
    background-color: #fff3cd !important;
    transition: background-color 0.8s ease;
}
table th, table td { vertical-align: middle !important; }

/* Style baru untuk Progress Bar */
.progress {
    background-color: #e9ecef;
    border-radius: .25rem;
}
.progress-bar {
    background-color: #0d6efd;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    transition: width 0.6s ease;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    /* Agar checklist Select2 lebih rapi */
    background-color: #0d6efd;
    color: white;
    border-color: #0a58ca;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: white;
}
</style>

{{-- ===== Script Interaktif (Disesuaikan) ===== --}}
<script>
$(document).ready(function() {
    // Inisialisasi Select2
    $('.select2').select2({
        placeholder: "-- Pilih Progres --",
        allowClear: true,
        closeOnSelect: false // Biarkan terbuka untuk checklist
    });

    // Select All Checkbox
    $('#selectAll').on('change', function() {
        // Ganti ke '.mhs-checkbox'
        $('.mhs-checkbox').prop('checked', $(this).is(':checked'));
    });

    // --- Fungsi Baru: Update Progress Bar ---
    function updateProgressBar(selectElement) {
        let selectedCount = $(selectElement).val() ? $(selectElement).val().length : 0;
        let totalSteps = $(selectElement).data('total-langkah');
        let percentage = (selectedCount / totalSteps) * 100;
        let mhsId = $(selectElement).data('mhs-id');

        let bar = $('#progress-bar-' + mhsId);
        let text = $('#progress-text-' + mhsId);

        bar.css('width', percentage + '%').attr('aria-valuenow', percentage);
        text.text(Math.round(percentage) + '%');

        // Ganti warna bar berdasarkan progres
        if (percentage < 30) bar.css('background-color', '#dc3545'); // Merah
        else if (percentage < 70) bar.css('background-color', '#ffc107'); // Kuning
        else if (percentage < 100) bar.css('background-color', '#0d6efd'); // Biru
        else bar.css('background-color', '#198754'); // Hijau
    }

    // Update progress bar saat halaman dimuat
    $('.progres-select').each(function() {
        updateProgressBar(this);
    });

    // --- Sinkronisasi Otomatis (Diadaptasi) ---
    function syncToChecked(selector, value, isSelect2 = false) {
        $('.mhs-checkbox:checked').each(function() {
            let mhsId = $(this).val();
            let target = $(selector + '[data-mhs-id="'+mhsId+'"]');
            
            target.val(value);
            if (isSelect2) {
                target.trigger('change.select2');
            }
            
            // Highlight baris
            let row = target.closest('tr');
            row.addClass('highlight-sync');
            setTimeout(() => row.removeClass('highlight-sync'), 800);
        });
    }

    // Event listener untuk progres
    $('.progres-select').on('change', function() {
        updateProgressBar(this); // Update bar
        
        let value = $(this).val();
        if ($('.mhs-checkbox:checked').length > 1) {
            syncToChecked('.progres-select', value, true);
        }
    });

    // Event listener untuk nilai
    $('.nilai-input').on('input', function() {
        let value = $(this).val();
        if ($('.mhs-checkbox:checked').length > 1) {
            syncToChecked('.nilai-input', value, false);
        }
    });

    // --- Simpan Bulk (Diadaptasi) ---
    $('#saveSelected').on('click', function() {
        let selectedData = [];
        $('.mhs-checkbox:checked').each(function() {
            let id = $(this).val();
            selectedData.push({
                id: id,
                progres: $('select.progres-select[data-mhs-id="'+id+'"]').val(), // Ini akan jadi array
                nilai: $('input.nilai-input[data-mhs-id="'+id+'"]').val()
            });
        });

        if (selectedData.length === 0) {
            alert('Pilih minimal 1 mahasiswa untuk disimpan.');
            return;
        }

        // Ganti URL ke route bulk update bimbingan Anda
        $.ajax({
            url: "{{ route('dosen.updateBimbinganBulk') }}", 
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                data: selectedData
            },
            success: function(res) {
                alert('Berhasil menyimpan data bimbingan!');
                location.reload();
            },
            error: function(err) {
                console.error(err);
                alert('Terjadi kesalahan saat menyimpan.');
            }
        });
    });

    // --- Fitur Pencarian (Sama persis) ---
    window.searchTable = function() {
        let input = document.getElementById("search").value.toLowerCase();
        let rows = document.querySelectorAll("#bimbinganTable tbody tr");
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(input) ? "" : "none";
        });
    }
});
</script>
@endsection