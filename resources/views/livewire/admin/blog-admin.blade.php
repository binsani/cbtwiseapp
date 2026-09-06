<div class="flex flex-col lg:flex-row min-h-screen bg-slate-50/50 -mt-8 -mx-4 sm:-mx-6 lg:-mx-8">
    
    <!-- Sidebar Navigation -->
    <x-admin-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 p-6 sm:p-10 font-sans space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 font-heading">Manage Blog</h1>
                <p class="text-slate-500 text-sm mt-1">Publish study tips, official news, and guidelines.</p>
            </div>
            
            <div class="flex items-center gap-3">
                @if($activeTab === 'posts')
                    <button wire:click="openPostModal()" class="px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-extrabold rounded-2xl transition-all shadow-md">
                        + New Post
                    </button>
                @else
                    <button wire:click="openCategoryModal()" class="px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-extrabold rounded-2xl transition-all shadow-md">
                        + New Category
                    </button>
                @endif
            </div>
        </div>

        <!-- Alert messages -->
        @if (session()->has('message'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl text-sm font-bold shadow-sm">
                {{ session('message') }}
            </div>
        @endif

        <!-- Tab toggles and Search -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-3xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-2 bg-slate-50 p-1 rounded-2xl border border-slate-100">
                <button wire:click="$set('activeTab', 'posts')" class="px-4 py-2.5 rounded-xl text-xs font-black transition-all {{ $activeTab === 'posts' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-950' }}">
                    Blog Posts
                </button>
                <button wire:click="$set('activeTab', 'categories')" class="px-4 py-2.5 rounded-xl text-xs font-black transition-all {{ $activeTab === 'categories' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-950' }}">
                    Categories
                </button>
            </div>

            <div class="relative w-full sm:w-80">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">🔍</span>
                <input 
                    type="text" 
                    wire:model.live="search"
                    placeholder="Search records..." 
                    class="w-full pl-9 pr-4 py-2.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/10 focus:border-emerald-500 text-xs"
                >
            </div>
        </div>

        <!-- Content Tables -->
        @if($activeTab === 'posts')
            <div class="bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-55 border-b border-slate-100 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                            <th class="p-4 sm:p-6">Title</th>
                            <th class="p-4 sm:p-6">Category</th>
                            <th class="p-4 sm:p-6">Author</th>
                            <th class="p-4 sm:p-6">Status</th>
                            <th class="p-4 sm:p-6">Published At</th>
                            <th class="p-4 sm:p-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($posts as $post)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="p-4 sm:p-6">
                                    <div class="font-bold text-slate-900">{{ $post->title }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5 max-w-xs truncate">{{ $post->slug }}</div>
                                </td>
                                <td class="p-4 sm:p-6 font-semibold text-xs text-slate-500">
                                    {{ $post->category?->name ?? 'None' }}
                                </td>
                                <td class="p-4 sm:p-6 text-xs font-semibold text-slate-500">
                                    {{ $post->author?->name ?? 'Admin' }}
                                </td>
                                <td class="p-4 sm:p-6">
                                    @if($post->is_published)
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-[10px] font-black uppercase tracking-wider border border-emerald-100">
                                            Published
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-black uppercase tracking-wider border border-slate-200">
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 sm:p-6 text-xs font-semibold text-slate-500">
                                    {{ $post->published_at ? $post->published_at->format('M d, Y H:i') : 'N/A' }}
                                </td>
                                <td class="p-4 sm:p-6 text-right space-x-2">
                                    <button wire:click="openPostModal({{ $post->id }})" class="text-xs font-black text-emerald-600 hover:text-emerald-700">
                                        Edit
                                    </button>
                                    <button wire:click="deletePost({{ $post->id }})" wire:confirm="Are you sure you want to delete this post?" class="text-xs font-black text-red-600 hover:text-red-700">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-slate-400">No blog posts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-6 border-t border-slate-100">
                    {{ $posts->links() }}
                </div>
            </div>
        @else
            <!-- Categories Tab -->
            <div class="bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-55 border-b border-slate-100 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                            <th class="p-4 sm:p-6">Name</th>
                            <th class="p-4 sm:p-6">Slug</th>
                            <th class="p-4 sm:p-6">Description</th>
                            <th class="p-4 sm:p-6">Posts Count</th>
                            <th class="p-4 sm:p-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($categories as $cat)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="p-4 sm:p-6 font-bold text-slate-900">{{ $cat->name }}</td>
                                <td class="p-4 sm:p-6 text-xs font-semibold text-slate-400">{{ $cat->slug }}</td>
                                <td class="p-4 sm:p-6 text-xs text-slate-500">{{ $cat->description ?? 'N/A' }}</td>
                                <td class="p-4 sm:p-6 text-xs font-semibold text-slate-900">{{ $cat->posts_count }}</td>
                                <td class="p-4 sm:p-6 text-right space-x-2">
                                    <button wire:click="openCategoryModal({{ $cat->id }})" class="text-xs font-black text-emerald-600 hover:text-emerald-700">
                                        Edit
                                    </button>
                                    <button wire:click="deleteCategory({{ $cat->id }})" wire:confirm="Are you sure you want to delete this category?" class="text-xs font-black text-red-600 hover:text-red-700">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-slate-400">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Post CRUD Modal -->
        @if($showPostModal)
            <div class="fixed inset-0 z-50 bg-slate-950/40 backdrop-blur-sm flex items-center justify-center p-4">
                <div class="bg-white w-full max-w-3xl rounded-3xl shadow-xl overflow-hidden border border-slate-100 flex flex-col max-h-[90vh]">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-950 font-heading">
                            {{ $postId ? 'Edit Blog Post' : 'New Blog Post' }}
                        </h3>
                        <button wire:click="$set('showPostModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                    </div>
                    
                    <div class="p-6 overflow-y-auto space-y-4 flex-1">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Title</label>
                                <input type="text" wire:model.live="title" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs">
                                @error('title') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Slug (auto if empty)</label>
                                <input type="text" wire:model="slug" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Category</label>
                                <select wire:model="categoryId" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs">
                                    <option value="">-- Select Category --</option>
                                    @foreach(\App\Models\BlogCategory::all() as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('categoryId') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Featured Image URL</label>
                                <input type="text" wire:model="featuredImage" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs" placeholder="https://example.com/image.jpg">
                                @error('featuredImage') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Meta Description</label>
                            <input type="text" wire:model="metaDescription" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs" placeholder="SEO excerpt (max 160 characters)">
                            @error('metaDescription') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Excerpt</label>
                            <textarea wire:model="excerpt" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs resize-none" placeholder="Short description of the post"></textarea>
                            @error('excerpt') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Body (Markdown Supported)</label>
                            <textarea wire:model="body" rows="8" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs font-mono" placeholder="Write post body here..."></textarea>
                            @error('body') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <label class="flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer">
                                <input type="checkbox" wire:model.live="isPublished" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <span>Publish Immediately</span>
                            </label>
                            
                            @if($isPublished)
                                <div class="flex-1">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-0.5">Publish Date</label>
                                    <input type="datetime-local" wire:model="publishedAt" class="px-2 py-1 rounded-lg border border-slate-200 text-xs focus:outline-none">
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="p-6 border-t border-slate-100 flex justify-end gap-3">
                        <button wire:click="$set('showPostModal', false)" class="px-4 py-2.5 border border-slate-200 text-slate-700 text-xs font-extrabold rounded-xl hover:bg-slate-50">
                            Cancel
                        </button>
                        <button wire:click="savePost()" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl shadow-md">
                            Save Post
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Category CRUD Modal -->
        @if($showCategoryModal)
            <div class="fixed inset-0 z-50 bg-slate-950/40 backdrop-blur-sm flex items-center justify-center p-4">
                <div class="bg-white w-full max-w-md rounded-3xl shadow-xl overflow-hidden border border-slate-100">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-950 font-heading">
                            {{ $categoryIdField ? 'Edit Category' : 'New Category' }}
                        </h3>
                        <button wire:click="$set('showCategoryModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Category Name</label>
                            <input type="text" wire:model.live="categoryName" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs">
                            @error('categoryName') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Slug (auto if empty)</label>
                            <input type="text" wire:model="categorySlug" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Description</label>
                            <input type="text" wire:model="categoryDescription" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs">
                            @error('categoryDescription') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="p-6 border-t border-slate-100 flex justify-end gap-3">
                        <button wire:click="$set('showCategoryModal', false)" class="px-4 py-2.5 border border-slate-200 text-slate-700 text-xs font-extrabold rounded-xl hover:bg-slate-50">
                            Cancel
                        </button>
                        <button wire:click="saveCategory()" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl shadow-md">
                            Save Category
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </main>
</div>
