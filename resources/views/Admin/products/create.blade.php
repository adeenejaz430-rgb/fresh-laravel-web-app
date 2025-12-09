@extends('admin.layouts.app')

@section('title', 'Add New Product')

@section('content')
<div class="max-w-4xl mx-auto pt-4">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Add New Product</h1>

        <a
            href="{{ route('admin.products.index') }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
        >
            ✕
            <span class="ml-2">Cancel</span>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        {{-- ⬇️ important for file upload --}}
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf

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
                                value="{{ old('name') }}"
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
                                    <option value="{{ $cat->slug }}" {{ old('category') == $cat->slug ? 'selected' : '' }}>
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
                                value="{{ old('price', 0) }}"
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
                                value="{{ old('quantity', 0) }}"
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
                    >{{ old('description') }}</textarea>
                    @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Featured --}}
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-2">Product Options</h2>
                    <div class="flex items-center">
                        <input
                            id="featured"
                            name="featured"
                            type="checkbox"
                            value="1"
                            {{ old('featured') ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                        >
                        <label for="featured" class="ml-2 block text-sm text-gray-700">
                            Featured product (will appear on homepage)
                        </label>
                    </div>
                </div>

                {{-- Images --}}
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-2">Product Images</h2>

                    <label class="block text-sm font-medium text-gray-700">
                        Main Image
                    </label>
                    <input
                        type="file"
                        name="image"
                        accept="image/*"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 mb-3"
                    >
                    @error('image') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror

                    <label class="block text-sm font-medium text-gray-700">
                        Gallery Images (you can select multiple)
                    </label>
                    <input
                        type="file"
                        name="gallery[]"
                        accept="image/*"
                        multiple
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"
                    >
                    @error('gallery') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    @error('gallery.*') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <button
                    type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                >
                    Save Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
