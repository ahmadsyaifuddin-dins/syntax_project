@props(['name', 'imageUrl' => null, 'required' => false])

<div x-data="{
    photoPreview: '{{ $imageUrl }}',
    updatePhotoPreview(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (e) => {
            this.photoPreview = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}" class="w-full">

    <input type="file" name="{{ $name }}" x-ref="photo" @change="updatePhotoPreview" accept="image/*"
        class="hidden" {{ $required ? 'required' : '' }}>

    <div class="mt-1">
        <div x-show="!photoPreview" @click="$refs.photo.click()"
            class="border-2 border-dashed border-gray-300 rounded-md p-6 flex flex-col justify-center items-center h-48 bg-gray-50 cursor-pointer hover:bg-gray-100 transition">
            <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
            <span class="text-sm text-gray-500 font-medium">Klik untuk memilih gambar</span>
            <span class="text-xs text-gray-400 mt-1">Maks. 2MB (JPG, PNG)</span>
        </div>

        <div x-show="photoPreview" style="display: none;" class="relative border rounded-md p-2 h-48 bg-gray-50 group">
            <img :src="photoPreview" class="w-full h-full object-contain rounded">

            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded cursor-pointer"
                @click="$refs.photo.click()">
                <span class="text-white text-sm font-semibold flex items-center gap-2">
                    <i class="fa-solid fa-pen"></i> Ganti Gambar
                </span>
            </div>
        </div>
    </div>
</div>
