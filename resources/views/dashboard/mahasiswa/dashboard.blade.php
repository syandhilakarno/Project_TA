@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100 font-sans">

    <!-- Sidebar -->
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out z-30 lg:translate-x-0 lg:static lg:inset-0">
        <!-- Header Sidebar -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-blue-600">Mahasiswa</h2>
            <button @click="sidebarOpen = false" class="lg:hidden focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Menu Sidebar -->
        <nav class="mt-4 space-y-2">
            <a href="#"
                class="flex items-center px-5 py-2.5 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition rounded-md">
                Profil Mahasiswa
            </a>
            <a href="#"
                class="flex items-center px-5 py-2.5 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition rounded-md">
                Bimbingan
            </a>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Topbar -->
        <header class="flex items-center justify-between bg-white shadow px-6 py-4">
            <div class="flex items-center space-x-3">
                <!-- Toggle Button -->
                <button @click="sidebarOpen = true" class="text-gray-600 focus:outline-none lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="text-xl font-semibold text-gray-800">Dashboard Mahasiswa</h1>
            </div>
            <div>
                <span class="text-gray-700 font-medium">Halo, Mahasiswa 👋</span>
            </div>
        </header>

        <!-- Isi Konten -->
        <main class="flex-1 overflow-y-auto p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <div class="bg-white rounded-2xl shadow p-5 hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Status Bimbingan</h3>
                    <p class="text-gray-500 text-sm">Lihat progres dan catatan bimbingan Anda dengan dosen pembimbing.</p>
                </div>

                <div class="bg-white rounded-2xl shadow p-5 hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Profil Mahasiswa</h3>
                    <p class="text-gray-500 text-sm">Perbarui data diri, kontak, dan informasi akademik Anda.</p>
                </div>

                <div class="bg-white rounded-2xl shadow p-5 hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Pengumuman</h3>
                    <p class="text-gray-500 text-sm">Informasi terbaru seputar bimbingan dan jadwal sidang.</p>
                </div>

            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush