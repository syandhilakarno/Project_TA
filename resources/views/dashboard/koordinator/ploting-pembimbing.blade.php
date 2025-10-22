@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@section('content')
<div class="container">
    <div class="sidebar">
        <a href="{{ route('koordinator.listmahasiswa') }}">List Mahasiswa</a>
        <a href="{{ route('koordinator.ploting-pembimbing') }}" class="active">Ploting Pembimbing</a>
        <a href="{{ route('koordinator.sidang') }}">Sidang</a>
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
            <h2>Ploting Pembimbing</h2>
            <div class="search-bar">
                <input type="text" id="search" placeholder="Search Bar..." onkeyup="searchTable()">
            </div>

            <table id="mahasiswaTable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Periode</th>
                        <th>Dosen Pembimbing</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                  @forelse($mahasiswa as $mhs)
                  <form action="{{ route('koordinator.updatePloting', $mhs->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <tr>
                          <td><input type="checkbox" class="mahasiswa-checkbox" value="{{ $mhs->id }}"></td>
                          
                          <td>{{ optional($mhs->user)->name ?? 'Nama Tidak Ditemukan' }}</td>
                          
                          <td>{{ $mhs->nim }}</td>
                          <td>{{ $mhs->periode }}</td>
                          <td>
                              <select name="dosen_id" data-mhs-id="{{ $mhs->id }}" class="select2" style="width: 100%;" required>
                                  <option value="">-- Pilih Dosen --</option>
                                  @foreach($dosen as $dsn)
                                  
                                  <option value="{{ $dsn->id }}" {{ $mhs->dosen_id == $dsn->id ? 'selected' : '' }}>
                                      {{ $dsn->name }}
                                  </option>

                                  @endforeach
                              </select>
                          </td>
                          <td>
                              <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                          </td>
                      </tr>
                  </form>
                  @empty <tr>
                          <td colspan="6" class="text-center">Belum ada mahasiswa yang dipindahkan ke ploting.</td>
                      </tr>
                  @endforelse
              </tbody>
            </table>

            <div class="mt-3">
                <button id="saveSelected" class="btn btn-success">Simpan Semua yang Dipilih</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Sedikit gaya agar highlight kelihatan */
.highlight-sync {
    background-color: #fff3cd !important; /* kuning lembut */
    transition: background-color 0.8s ease;
}
</style>

<script>
$(document).ready(function() {
    // Inisialisasi Select2
    $('.select2').select2({
        placeholder: "-- Pilih Dosen --",
        allowClear: true
    });

    // Checkbox "Select All"
    $('#selectAll').on('change', function() {
        $('.mahasiswa-checkbox').prop('checked', $(this).is(':checked'));
    });

    // Saat ganti dosen salah satu mahasiswa
    $('.select2').on('change', function() {
        let selectedDosen = $(this).val();
        let selectedText = $(this).find('option:selected').text();
        let checkedBoxes = $('.mahasiswa-checkbox:checked');

        if (checkedBoxes.length > 1) {
            checkedBoxes.each(function() {
                let mhsId = $(this).val();
                let select = $('select[data-mhs-id="'+mhsId+'"]');
                select.val(selectedDosen).trigger('change.select2');

                // Highlight baris yang ikut tersinkron
                let row = select.closest('tr');
                row.addClass('highlight-sync');
                setTimeout(() => row.removeClass('highlight-sync'), 800);
            });

            // Notifikasi kecil alert('Dosen "' + selectedText + '" diterapkan ke semua mahasiswa yang dicentang.');
        }
    });

    // Tombol "Simpan Semua yang Dipilih"
    $('#saveSelected').on('click', function() {
        let selectedMahasiswa = [];
        let dosenAssignments = [];

        $('.mahasiswa-checkbox:checked').each(function() {
            let mhsId = $(this).val();
            let dosenId = $('select[data-mhs-id="'+mhsId+'"]').val();
            if(dosenId) {
                selectedMahasiswa.push(mhsId);
                dosenAssignments.push({id: mhsId, dosen_id: dosenId});
            }
        });

        if(selectedMahasiswa.length == 0) {
            alert('Pilih minimal 1 mahasiswa dan dosen.');
            return;
        }


        $.ajax({
            url: "{{ route('koordinator.updatePlotingBulk') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                assignments: dosenAssignments
            },
            success: function(res) {
                alert('Berhasil update pembimbing!');
                location.reload();
            },
            error: function(err) {
                console.log(err);
                alert('Terjadi kesalahan.');
            }
        });
    });

    // ✅ Fitur pencarian tabel
    window.searchTable = function() {
        let input = document.getElementById("search").value.toLowerCase();
        let rows = document.querySelectorAll("#mahasiswaTable tbody tr");
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(input) ? "" : "none";
        });
    }
});
</script>
@endsection