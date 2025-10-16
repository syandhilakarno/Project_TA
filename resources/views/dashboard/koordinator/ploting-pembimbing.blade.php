@extends('layouts.app')

@section('content')
<div class="container">
<div class="sidebar">
    <a href="{{ route('koordinator.dashboard') }}">List Mahasiswa</a>
    <a href="{{ route('koordinator.ploting') }}" class="active">Ploting Pembimbing</a>
    <a href="{{ route('koordinator.sidang') }}">Sidang</a>
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
        <h2>Ploting Pembimbing</h2>
        <div class="search-bar">
            <input type="text" id="search" placeholder="Search Mahasiswa..." onkeyup="searchTable()">
        </div>

        <table id="mahasiswaTable">
            <thead>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <th>NIM</th>
                    <th>Periode</th>
                    <th>Dosen Pembimbing</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswa as $mhs)
                <tr>
                    <td>{{ $mhs->nama }}</td>
                    <td>{{ $mhs->nim }}</td>
                    <td>{{ $mhs->periode }}</td>
                    <td>
                        <form action="{{ route('koordinator.updatePloting', $mhs->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="dosen_id" required>
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dosen as $dsn)
                                    <option value="{{ $dsn->id }}" {{ $mhs->dosen_id == $dsn->id ? 'selected' : '' }}>
                                        {{ $dsn->nama }}
                                    </option>
                                @endforeach
                            </select>
                    </td>
                    <td>
                            <button type="submit" class="btn-ploting">Simpan</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>

<script>
function searchTable() {
    let input = document.getElementById("search").value.toLowerCase();
    let rows = document.querySelectorAll("#mahasiswaTable tbody tr");
    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}
</script>
@endsection