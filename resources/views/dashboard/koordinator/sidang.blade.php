@extends('layouts.app')

@section('content')
<div class="container">
    <div class="sidebar">
        <a href="{{ route('koordinator.dashboard') }}">List Mahasiswa</a>
        <a href="{{ route('koordinator.ploting') }}">Ploting Pembimbing</a>
        <a href="{{ route('koordinator.sidang') }}" class="active">Sidang</a>
    </div>

    <div class="main">
        <div class="header">
            <div class="logo">
                <img src="{{ asset('public/img/LOGO.png') }}" alt="UBPLogo">
            </div>
            <div class="profile">
                <strong>{{ Auth::user()->name }}</strong>
                <small>{{ Auth::user()->email }}</small>
                <img src="{{ asset('public/img/R1_.jpg') }}" alt="Profile">
            </div>
        </div>

        <div class="content">
            <h2>Daftar Sidang Mahasiswa</h2>
            <div class="search-bar">
                <input type="text" id="search" placeholder="Cari Mahasiswa..." onkeyup="searchTable()">
            </div>

            <table id="sidangTable">
                <thead>
                    <tr>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Judul TA</th>
                        <th>Tanggal Sidang</th>
                        <th>Penguji</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sidang as $item)
                        <tr>
                            <td>{{ $item->mahasiswa->nama ?? '-' }}</td>
                            <td>{{ $item->mahasiswa->nim ?? '-' }}</td>
                            <td>{{ $item->judul_ta ?? 'Belum Ada' }}</td>
                            <td>{{ $item->tanggal_sidang ?? 'Belum Dijadwalkan' }}</td>
                            <td>{{ $item->penguji ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;">Belum ada data sidang</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function searchTable() {
    let input = document.getElementById("search").value.toLowerCase();
    let rows = document.querySelectorAll("#sidangTable tbody tr");
    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}
</script>
@endsection
