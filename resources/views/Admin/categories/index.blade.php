@extends('admin.layouts.app')

@section('title', 'Categories Management')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4 md:mb-0">
            Categories Management
        </h1>

        <button
            type="button"
            @click="$dispatch('open-add-category')"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
        >
            <span class="mr-2">＋</span>
            Add New Category
        </button>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="{{ route('admin.categories.index') }}">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    🔍
                </div>
                <input
                    type="text"
                    name="q"
                    value="{{ $search }}"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    placeholder="Search categories..."
                />
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Slug
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Products
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($categories as $category)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $category->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $category->slug }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $category->products_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <button
                                    type="button"
                                    class="text-blue-600 hover:text-blue-900"
                                    @click="$dispatch('open-edit-category', {
                                        id: {{ $category->id }},
                                        name: '{{ addslashes($category->name) }}',
                                        slug: '{{ addslashes($category->slug) }}'
                                    })"
                                >
                                    Edit
                                </button>

                                <form
                                    action="{{ route('admin.categories.destroy', $category) }}"
                                    method="POST"
                                    onsubmit="return confirm('Delete this category?')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="text-red-600 hover:text-red-900 {{ $category->products_count > 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $category->products_count > 0 ? 'disabled' : '' }}
                                    >
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                            No categories found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4">
            {{ $categories->links() }}
        </div>
    </div>
</div>

{{-- Add & Edit modals via Alpine --}}
<div
    x-data="{
        showAdd: false,
        showEdit: false,
        editId: null,
        editName: '',
        editSlug: '',
    }"
    @open-add-category.window="showAdd = true"
    @open-edit-category.window="
        showEdit = true;
        editId = $event.detail.id;
        editName = $event.detail.name;
        editSlug = $event.detail.slug;
    "
>
    {{-- Add modal --}}
    <div
        x-show="showAdd"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500 bg-opacity-75"
    >
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6" @click.away="showAdd = false">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Category</h3>

            <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text" name="name" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Slug
                    </label>
                    <input
                        type="text" name="slug"
                        placeholder="auto-generated if empty"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button"
                            class="px-4 py-2 rounded-md border border-gray-300 text-sm"
                            @click="showAdd = false">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-md bg-blue-600 text-white text-sm">
                        Add
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit modal --}}
    <div
        x-show="showEdit"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500 bg-opacity-75"
    >
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6" @click.away="showEdit = false">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Category</h3>

            <form
                :action="`{{ url('/admin/categories') }}/${editId}`"
                method="POST"
                class="space-y-4"
            >
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text" name="name" required
                        x-model="editName"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Slug
                    </label>
                    <input
                        type="text" name="slug"
                        x-model="editSlug"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button"
                            class="px-4 py-2 rounded-md border border-gray-300 text-sm"
                            @click="showEdit = false">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-md bg-blue-600 text-white text-sm">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
