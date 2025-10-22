@extends('layouts.app')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@section('content')
<div class="container">
    <div class="sidebar">
        <a href="{{ route('koordinator.listmahasiswa') }}" class="active">List Mahasiswa</a>
        <a href="{{ route('koordinator.ploting-pembimbing') }}">Ploting Pembimbing</a>
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
            <h2>List Mahasiswa</h2>
            <div class="search-bar mb-3">
                <input type="text" id="search" placeholder="Search Bar..." class="form-control" onkeyup="searchTable()">
            </div>

            <table id="mahasiswaTable" class="table table-bordered table-striped">
                <thead class="table-primary text-center align-middle">
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>SKS</th>
                        <th>IPK</th>
                        <th>Nilai KP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mahasiswa as $mhs)
                    <tr>
                        <td class="text-center align-middle">
                            <input type="checkbox" class="mahasiswa-checkbox" value="{{ $mhs->id }}">
                        </td>
                        <td>{{ $mhs->nama }}</td>
                        <td>{{ $mhs->nim }}</td>
                        <td>{{ $mhs->periode }}</td>
                        <td>{{ $mhs->status }}</td>
                        <td>{{ $mhs->sks }}</td>
                        <td>{{ $mhs->ipk }}</td>
                        <td>{{ $mhs->nilai_kp }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Tombol pindahkan ke ploting --}}
            <div class="mt-3 text-end">
                <button id="moveToPloting" class="btn btn-success">
                    <i class="fa fa-arrow-right"></i> Pindahkan ke Ploting
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Select all checkbox
    $('#selectAll').on('change', function() {
        $('.mahasiswa-checkbox').prop('checked', $(this).is(':checked'));
    });

    // Tombol pindahkan ke ploting
    $('#moveToPloting').on('click', function() {
        let selectedIds = [];
        $('.mahasiswa-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if(selectedIds.length === 0){
            alert('Pilih minimal 1 mahasiswa untuk dipindahkan.');
            return;
        }

        $.ajax({
            url: "{{ route('koordinator.mahasiswa.moveToPloting') }}",
            method: "POST",
            data: { _token: "{{ csrf_token() }}", ids: selectedIds },
success: function(res) {
                alert(res.message); // Tampilkan pesan dari controller
                // LANGSUNG PINDAH KE HALAMAN PLOTING
                window.location.href = "{{ route('koordinator.ploting-pembimbing') }}";
            },
            error: function(err){
                console.error(err);
                alert('Terjadi kesalahan saat memindahkan mahasiswa.');
            }
        });
    });

    // Pencarian tabel
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
