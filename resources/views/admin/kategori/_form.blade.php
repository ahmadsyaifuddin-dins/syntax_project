<div class="grid grid-cols-1 gap-6">
    <div>
        <x-forms.label value="Nama Kategori" required />
        <x-forms.input type="text" name="nama_kategori"
            value="{{ old('nama_kategori', $kategori->nama_kategori ?? '') }}" placeholder="Misal: Fiksi, Teknologi, dsb."
            required />
        @error('nama_kategori')
            <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <x-forms.label value="Deskripsi (Opsional)" />
        <x-forms.textarea name="deskripsi" rows="4"
            placeholder="Penjelasan singkat mengenai kategori ini...">{{ old('deskripsi', $kategori->deskripsi ?? '') }}</x-forms.textarea>
        @error('deskripsi')
            <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
        @enderror
    </div>
</div>
