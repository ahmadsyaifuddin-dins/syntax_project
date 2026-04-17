@props(['name', 'value' => '', 'placeholder' => '0', 'required' => false])

<div x-data="{
    rawValue: '{{ $value }}',
    formattedValue: '{{ $value ? number_format($value, 0, ',', '.') : '' }}',

    formatCurrency(e) {
        // Hapus semua karakter selain angka
        let input = e.target.value.replace(/[^0-9]/g, '');

        if (!input) {
            this.rawValue = '';
            this.formattedValue = '';
            return;
        }

        // Simpan nilai asli ke hidden input
        this.rawValue = input;

        // Format untuk tampilan (menggunakan standar Indonesia)
        this.formattedValue = new Intl.NumberFormat('id-ID').format(input);
    }
}">
    <input type="hidden" name="{{ $name }}" x-model="rawValue">

    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="text-gray-500 sm:text-sm font-medium">Rp</span>
        </div>
        <input type="text" x-model="formattedValue" @input="formatCurrency" placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full']) }}>
    </div>
</div>
