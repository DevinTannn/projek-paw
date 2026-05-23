<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pelanggan Baru</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md my-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Tambah Pelanggan Baru</h2>

        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                <input type="text" name="name"
                    class="w-full px-3 py-2 border rounded-lg @error('name') border-red-500 @enderror"
                    value="{{ old('name') }}" required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email"
                    class="w-full px-3 py-2 border rounded-lg @error('email') border-red-500 @enderror"
                    value="{{ old('email') }}" required>
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">No. Handphone</label>
                <input type="text" name="phone" class="w-full px-3 py-2 border rounded-lg" value="{{ old('phone') }}">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Alamat</label>
                <textarea name="address" rows="3"
                    class="w-full px-3 py-2 border rounded-lg">{{ old('address') }}</textarea>
            </div>

            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('customers.index') }}" class="text-gray-600 hover:underline">Batal</a>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow">
                    Simpan Pelanggan
                </button>
            </div>
        </form>
    </div>

</body>

</html>