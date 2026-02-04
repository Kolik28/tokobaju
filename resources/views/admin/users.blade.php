@extends('admin.layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div id="users" class="container mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6">
        <!-- Header -->
        <div class="mb-4 sm:mb-6">
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">Manajemen Pengguna</h2>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Kelola data pengguna sistem</p>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="text-sm sm:text-base">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.style.display='none'"
                    class="text-green-700 hover:text-green-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span class="text-sm sm:text-base">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.style.display='none'"
                    class="text-red-700 hover:text-red-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Search & Add Button -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 gap-3">
            <div class="w-full sm:w-auto flex-1 sm:max-w-md">
                <div class="relative">
                    <input type="text" id="searchUser" placeholder="Cari nama, role, atau WhatsApp..."
                        class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition"
                        onkeyup="searchUsers()">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
            <button onclick="openAddModal()"
                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2.5 rounded-lg flex items-center justify-center gap-2 text-sm sm:text-base font-medium transition shadow-sm hover:shadow-md">
                <i class="fas fa-plus"></i>
                <span>Tambah Admin</span>
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Tampilan Desktop (Table) -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Nama
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Role
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                WhatsApp
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Bergabung
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="userTableBody">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50 transition user-row">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 user-name">
                                                {{ $user->name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full capitalize user-role
                                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        <i class="fas fa-{{ $user->role === 'admin' ? 'user-shield' : 'user' }} mr-1.5"></i>
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center text-sm text-gray-900 user-whatsapp">
                                        <i class="fab fa-whatsapp text-green-500 mr-2"></i>
                                        {{ $user->whatsapp }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a target="_blank" rel="noopener noreferrer"
                                            href="https://wa.me/{{ $user->whatsapp }}"
                                            class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition shadow-sm hover:shadow">
                                            <i class="fab fa-whatsapp"></i>
                                            <span>Chat</span>
                                        </a>
                                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Yakin ingin menghapus {{ $user->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition shadow-sm hover:shadow">
                                                <i class="fas fa-trash"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fas fa-users text-5xl mb-4"></i>
                                        <p class="text-gray-500 font-medium">Tidak ada data pengguna</p>
                                        <p class="text-sm text-gray-400 mt-1">Tambahkan pengguna baru untuk memulai</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tampilan Tablet (Medium Devices) -->
            <div class="hidden md:block lg:hidden">
                <div class="divide-y divide-gray-200" id="userTabletList">
                    @forelse ($users as $user)
                        <div class="p-4 hover:bg-gray-50 transition user-row">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center flex-1 min-w-0">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div
                                            class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1 min-w-0">
                                        <div class="text-base font-medium text-gray-900 truncate user-name">
                                            {{ $user->name }}
                                        </div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-full capitalize user-role
                                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                                <i
                                                    class="fas fa-{{ $user->role === 'admin' ? 'user-shield' : 'user' }} mr-1"></i>
                                                {{ $user->role }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2 mb-3 ml-16">
                                <div class="flex items-center text-sm">
                                    <i class="fab fa-whatsapp text-green-500 w-5"></i>
                                    <span class="text-gray-900 ml-2 user-whatsapp">{{ $user->whatsapp }}</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-calendar text-gray-400 w-5"></i>
                                    <span class="text-gray-600 ml-2">{{ $user->created_at }}</span>
                                </div>
                            </div>

                            <div class="flex gap-2 ml-16">
                                <a target="_blank" rel="noopener noreferrer" href="https://wa.me/{{ $user->whatsapp }}"
                                    class="flex-1 text-center bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg text-sm font-medium transition shadow-sm hover:shadow">
                                    <i class="fab fa-whatsapp mr-1.5"></i> WhatsApp
                                </a>
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                                    class="flex-1"
                                    onsubmit="return confirm('Yakin ingin menghapus {{ $user->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm font-medium transition shadow-sm hover:shadow">
                                        <i class="fas fa-trash mr-1.5"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <i class="fas fa-users text-5xl mb-4 text-gray-300"></i>
                            <p class="text-gray-500 font-medium">Tidak ada data pengguna</p>
                            <p class="text-sm text-gray-400 mt-1">Tambahkan pengguna baru untuk memulai</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tampilan Mobile (Cards) -->
            <div class="md:hidden divide-y divide-gray-200" id="userMobileList">
                @forelse ($users as $user)
                    <div class="p-4 hover:bg-gray-50 transition user-row">
                        <!-- Header Card -->
                        <div class="flex items-center mb-3">
                            <div class="flex-shrink-0 h-12 w-12">
                                <div
                                    class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            </div>
                            <div class="ml-3 flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate user-name">
                                    {{ $user->name }}
                                </p>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full capitalize mt-1 user-role
                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    <i class="fas fa-{{ $user->role === 'admin' ? 'user-shield' : 'user' }} mr-1"></i>
                                    {{ $user->role }}
                                </span>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="space-y-2 mb-3 bg-gray-50 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500 flex items-center">
                                    <i class="fab fa-whatsapp mr-1.5 text-green-500"></i> WhatsApp
                                </span>
                                <span class="text-xs font-medium text-gray-900 user-whatsapp">{{ $user->whatsapp }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-calendar mr-1.5 text-gray-400"></i> Bergabung
                                </span>
                                <span class="text-xs font-medium text-gray-600">{{ $user->created_at }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <a target="_blank" rel="noopener noreferrer" href="https://wa.me/{{ $user->whatsapp }}"
                                class="flex-1 text-center bg-green-600 hover:bg-green-700 active:bg-green-800 text-white py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                                <i class="fab fa-whatsapp mr-1"></i> WhatsApp
                            </a>
                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="flex-1"
                                onsubmit="return confirm('Yakin ingin menghapus {{ $user->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 active:bg-red-800 text-white py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <i class="fas fa-users text-4xl mb-3 text-gray-300"></i>
                        <p class="text-gray-500 font-medium text-sm">Tidak ada data pengguna</p>
                        <p class="text-xs text-gray-400 mt-1">Tambahkan pengguna baru untuk memulai</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination (if needed) -->
        @if (method_exists($users, 'links'))
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <script>
        function searchUsers() {
            const searchValue = document.getElementById('searchUser').value.toLowerCase();
            const userRows = document.querySelectorAll('.user-row');

            userRows.forEach(row => {
                const name = row.querySelector('.user-name')?.textContent.toLowerCase() || '';
                const role = row.querySelector('.user-role')?.textContent.toLowerCase() || '';
                const whatsapp = row.querySelector('.user-whatsapp')?.textContent.toLowerCase() || '';

                const matchFound = name.includes(searchValue) ||
                    role.includes(searchValue) ||
                    whatsapp.includes(searchValue);

                row.style.display = matchFound ? '' : 'none';
            });
        }

        function openAddModal() {
            // Implementasi modal untuk tambah admin
            alert('Fitur tambah admin - implementasikan modal atau redirect ke form');
        }

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });
    </script>
@endsection
