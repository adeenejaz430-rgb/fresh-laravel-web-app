@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="max-w-4xl mx-auto pt-4">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Product</h1>

        <a
            href="{{ route('admin.products.index') }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
        >
            ✕
            <span class="ml-2">Back to list</span>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        {{-- ⬇️ important for file upload --}}
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">
                {{-- Basic info --}}
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Product Name *
                            </label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $product->name) }}"
                                required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"
                            >
                            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Category *
                            </label>
                            <select
                                name="category"
                                required
                                class="mt-1 block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md"
                            >
                                <option value="">Select a category</option>
                                @foreach($categories as $cat)
                                    <option
                                        value="{{ $cat->slug }}"
                                        {{ (old('category', $product->category) == $cat->slug) ? 'selected' : '' }}
                                    >
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Price ($) *
                            </label>
                            <input
                                type="number"
                                name="price"
                                step="0.01"
                                min="0"
                                value="{{ old('price', $product->price) }}"
                                required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"
                            >
                            @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Stock Quantity *
                            </label>
                            <input
                                type="number"
                                name="quantity"
                                min="0"
                                value="{{ old('quantity', $product->quantity) }}"
                                required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"
                            >
                            @error('quantity') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Description *
                    </label>
                    <textarea
                        name="description"
                        rows="4"
                        required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"
                    >{{ old('description', $product->description) }}</textarea>
                    @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Featured --}}
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input
                            id="featured"
                            name="featured"
                            type="checkbox"
                            value="1"
                            {{ old('featured', $product->featured) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                        >
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="featured" class="font-medium text-gray-700">
                            Featured Product
                        </label>
                        <p class="text-gray-500">
                            Featured products are displayed prominently on the homepage.
                        </p>
                    </div>
                </div>

                {{-- Images --}}
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Images</h2>

                    {{-- Current main image --}}
                    @if($product->image)
                        <div class="mb-3">
                            <p class="text-sm text-gray-700 mb-1">Current Main Image:</p>
                            <div class="relative inline-block group">
                                <img
                                    src="{{ asset('storage/'.$product->image) }}"
                                    alt="Current main image"
                                    class="h-24 rounded border"
                                >
                                <button
                                    type="button"
                                    onclick="markMainImageForDeletion(this)"
                                    class="absolute -top-1 -right-1 opacity-0 group-hover:opacity-100 transition-opacity bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs shadow-lg delete-main-image-btn"
                                    title="Remove main image (will be cleared on save)"
                                >
                                    ×
                                </button>
                                <div class="absolute inset-0 bg-red-500 bg-opacity-50 rounded border-2 border-red-500 hidden deletion-overlay-main">
                                    <span class="absolute inset-0 flex items-center justify-center text-white font-bold text-xs">Will Delete</span>
                                </div>
                            </div>
                            {{-- Hidden input for main image deletion --}}
                            <input type="hidden" name="delete_main_image" id="deleteMainImageInput" value="0">
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Replace Main Image (optional)
                        </label>
                        <input
                            type="file"
                            name="image"
                            accept="image/*"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"
                        >
                        <p class="text-xs text-gray-500 mt-1">
                            Leave empty to keep current image.
                        </p>
                        @error('image') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Current gallery --}}
                    @php
                        use Illuminate\Support\Facades\Storage;
                        $validImages = [];
                        $brokenImages = [];
                        if (is_array($product->images)) {
                            foreach ($product->images as $img) {
                                if (!empty($img) && Storage::disk('public')->exists($img)) {
                                    $validImages[] = $img;
                                } else {
                                    $brokenImages[] = $img;
                                }
                            }
                        }
                    @endphp
                    @if(count($validImages) > 0)
                        <div class="mb-3">
                            <p class="text-sm text-gray-700 mb-1">Current Gallery Images:</p>
                            <div class="flex flex-wrap gap-2" id="galleryContainer">
                                @foreach($validImages as $index => $img)
                                    <div class="relative group gallery-item" data-image-path="{{ $img }}">
                                        <img
                                            src="{{ asset('storage/'.$img) }}"
                                            alt="Gallery image"
                                            class="h-16 w-16 object-cover rounded border gallery-image"
                                            onerror="this.style.display='none';"
                                        >
                                        <button
                                            type="button"
                                            onclick="markImageForDeletion({{ json_encode($img) }}, this)"
                                            class="absolute -top-1 -right-1 opacity-0 group-hover:opacity-100 transition-opacity bg-red-500 hover:bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs shadow-lg delete-image-btn"
                                            title="Mark for deletion (will be removed on save)"
                                        >
                                            ×
                                        </button>
                                        <div class="absolute inset-0 bg-red-500 bg-opacity-50 rounded border-2 border-red-500 hidden deletion-overlay">
                                            <span class="absolute inset-0 flex items-center justify-center text-white font-bold text-xs">Will Delete</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            {{-- Hidden inputs for images marked for deletion --}}
                            <div id="deleteImagesContainer"></div>
                            @if(count($brokenImages) > 0)
                                <p class="text-xs text-yellow-600 mt-1">
                                    {{ count($brokenImages) }} broken image(s) will be automatically removed on next update.
                                </p>
                            @endif
                        </div>
                    @elseif(count($brokenImages) > 0)
                        <div class="mb-3">
                            <p class="text-xs text-yellow-600">
                                All gallery images are broken/missing. They will be automatically removed on next update.
                            </p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Replace Gallery Images (optional)
                        </label>
                        <input
                            type="file"
                            name="gallery[]"
                            accept="image/*"
                            multiple
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"
                        >
                        <p class="text-xs text-gray-500 mt-1">
                            Leave empty to keep current gallery.
                        </p>
                        @error('gallery') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        @error('gallery.*') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <button
                    type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                >
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Track which images are marked for deletion
    const imagesToDelete = new Set();

    function markImageForDeletion(imagePath, button) {
        const container = button.closest('.gallery-item');
        const overlay = container.querySelector('.deletion-overlay');
        const deleteContainer = document.getElementById('deleteImagesContainer');
        
        if (imagesToDelete.has(imagePath)) {
            // Unmark for deletion
            imagesToDelete.delete(imagePath);
            overlay.classList.add('hidden');
            button.classList.remove('bg-red-700');
            button.classList.add('bg-red-500');
            
            // Remove hidden input
            const input = deleteContainer.querySelector(`input[value="${imagePath}"]`);
            if (input) {
                input.remove();
            }
        } else {
            // Mark for deletion
            imagesToDelete.add(imagePath);
            overlay.classList.remove('hidden');
            button.classList.remove('bg-red-500');
            button.classList.add('bg-red-700');
            
            // Add hidden input
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_images[]';
            input.value = imagePath;
            deleteContainer.appendChild(input);
        }
    }

    // Handle main image deletion
    let mainImageMarkedForDeletion = false;

    function markMainImageForDeletion(button) {
        const overlay = button.parentElement.querySelector('.deletion-overlay-main');
        const deleteInput = document.getElementById('deleteMainImageInput');
        
        if (mainImageMarkedForDeletion) {
            // Unmark for deletion
            mainImageMarkedForDeletion = false;
            overlay.classList.add('hidden');
            button.classList.remove('bg-red-700');
            button.classList.add('bg-red-500');
            if (deleteInput) {
                deleteInput.value = '0';
            }
        } else {
            // Mark for deletion
            mainImageMarkedForDeletion = true;
            overlay.classList.remove('hidden');
            button.classList.remove('bg-red-500');
            button.classList.add('bg-red-700');
            if (deleteInput) {
                deleteInput.value = '1';
            }
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure delete container exists
        if (!document.getElementById('deleteImagesContainer')) {
            const container = document.createElement('div');
            container.id = 'deleteImagesContainer';
            const galleryContainer = document.getElementById('galleryContainer');
            if (galleryContainer && galleryContainer.parentNode) {
                galleryContainer.parentNode.insertBefore(container, galleryContainer.nextSibling);
            }
        }
    });
</script>
@endsection
