@php
    $isEdit = isset($buku);
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="md:col-span-2">
        <x-forms.label value="Judul Buku" required />
        <x-forms.input type="text" name="judul" value="{{ old('judul', $buku->judul ?? '') }}"
            placeholder="Masukkan judul buku..." required />
        @error('judul')
            <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <x-forms.label value="Kategori Buku" required />
        <x-forms.dropdown name="kategori_id" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach ($kategori as $kat)
                <option value="{{ $kat->id }}"
                    {{ old('kategori_id', $buku->kategori_id ?? '') == $kat->id ? 'selected' : '' }}>
                    {{ $kat->nama_kategori }}
                </option>
            @endforeach
        </x-forms.dropdown>
        @error('kategori_id')
            <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <x-forms.label value="Penulis" required />
        <x-forms.input type="text" name="penulis" value="{{ old('penulis', $buku->penulis ?? '') }}" required />
        @error('penulis')
            <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <x-forms.label value="Penerbit" required />
        <x-forms.input type="text" name="penerbit" value="{{ old('penerbit', $buku->penerbit ?? '') }}" required />
        @error('penerbit')
            <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <x-forms.label value="Tahun Terbit" required />
        <x-forms.input type="number" name="tahun_terbit"
            value="{{ old('tahun_terbit', $buku->tahun_terbit ?? date('Y')) }}" min="1900"
            max="{{ date('Y') + 1 }}" required />
        @error('tahun_terbit')
            <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <x-forms.label value="Stok Buku" required />
        <x-forms.input type="number" name="stok" value="{{ old('stok', $buku->stok ?? 0) }}" min="0"
            required />
        @error('stok')
            <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <x-forms.label value="Harga Denda (Per Hari)" required />
        <x-forms.input-currency name="harga_denda" value="{{ old('harga_denda', $buku->harga_denda ?? 0) }}"
            required />
        @error('harga_denda')
            <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <x-forms.label value="Cover Buku" />
        <x-forms.upload-gambar name="cover_gambar" :imageUrl="$isEdit && $buku->cover_gambar ? asset('uploads/images/' . $buku->cover_gambar) : null" />
        @error('cover_gambar')
            <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <x-forms.label value="File Digital Buku (Opsional)" />
        <x-forms.upload-file name="file_buku" :currentFile="$isEdit ? $buku->file_buku : null" />
        @error('file_buku')
            <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
        @enderror
    </div>

    <div class="md:col-span-2">
        <x-forms.label value="Sinopsis" />
        <x-forms.textarea name="sinopsis"
            rows="4">{{ old('sinopsis', $buku->sinopsis ?? '') }}</x-forms.textarea>
        @error('sinopsis')
            <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
        @enderror
    </div>
</div>
