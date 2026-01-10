<?php

namespace App\Http\Controllers;

use App\Models\KbArticle;
use App\Models\KbCategory;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class KnowledgebaseController extends Controller
{
    protected $tabs = [
        'articles' => 'Articles',
        'categories' => 'Categories',
    ];

    /**
     * Display knowledgebase articles.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $categorySlug = $request->get('category');

        $categories = KbCategory::active()
            ->ordered()
            ->withCount(['articles' => function ($query) {
                $query->where('status', 'published');
            }])
            ->get();

        $popularArticles = KbArticle::with('category')
            ->published()
            ->orderByDesc('views')
            ->limit(5)
            ->get();

        // If category is selected, show articles
        $selectedCategory = null;
        $articles = collect();
        
        if ($categorySlug) {
            $selectedCategory = KbCategory::where('slug', $categorySlug)->first();
            if ($selectedCategory) {
                $articlesQuery = $selectedCategory->articles()->published();
                if ($search) {
                    $articlesQuery->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                          ->orWhere('content', 'like', "%{$search}%");
                    });
                }
                $perPage = (int) setting('pagination_size', 15);
                $articles = $articlesQuery->orderByDesc('published_at')->paginate($perPage);
            }
        } elseif ($search) {
            $perPage = (int) setting('pagination_size', 15);
            $articles = KbArticle::with('category')
                ->published()
                ->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
                })
                ->orderByDesc('published_at')
                ->paginate($perPage);
        }

        return view('knowledgebase.index', compact(
            'categories',
            'popularArticles',
            'selectedCategory',
            'articles',
            'search'
        ));
    }

    /**
     * Display a single article.
     */
    public function showArticle(KbArticle $article)
    {
        if (!$article->isPublished()) {
            abort(404);
        }

        $article->incrementViews();
        $article->load('category');

        $relatedArticles = KbArticle::where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->published()
            ->limit(3)
            ->get();

        return view('knowledgebase.article', compact('article', 'relatedArticles'));
    }

    /**
     * Display knowledgebase settings with tabs.
     */
    public function settings(Request $request)
    {
        $user = auth()->user();
        
        // Check if user has any manage permission for knowledgebase
        $canManage = $user->hasAnyPermissionMatching(['knowledgebase_articles.', 'knowledgebase_categories.']) 
            || $user->hasPermission('knowledgebase_manage');
        
        if (!$canManage) {
            abort(403, 'You do not have permission to access Knowledgebase Settings.');
        }
        
        $tab = $request->get('tab', 'articles');
        $search = $request->get('search');
        $status = $request->get('status');

        $data = $this->getTabData($tab, $search, $status);
        $categories = KbCategory::active()->ordered()->get();

        return view('knowledgebase.settings', [
            'currentTab' => $tab,
            'tabs' => $this->tabs,
            'items' => $data,
            'categories' => $categories,
        ]);
    }

    /**
     * Get data based on current tab.
     */
    private function getTabData(string $tab, ?string $search, ?string $status)
    {
        $query = match ($tab) {
            'articles' => KbArticle::with('category'),
            'categories' => KbCategory::withCount('articles'),
            default => KbArticle::with('category'),
        };

        if ($search) {
            $searchColumn = $tab === 'articles' ? 'title' : 'name';
            $query->where($searchColumn, 'like', "%{$search}%");
        }

        if ($status) {
            $query->where('status', $status);
        }

        $orderColumn = $tab === 'articles' ? 'updated_at' : 'sort_order';
        $orderDirection = $tab === 'articles' ? 'desc' : 'asc';

        $perPage = (int) setting('pagination_size', 15);
        return $query->orderBy($orderColumn, $orderDirection)->paginate($perPage)->withQueryString();
    }

    // ==================== ARTICLES ====================

    public function storeArticle(Request $request)
    {
        if (!auth()->user()->hasPermission('knowledgebase_articles.create')) {
            abort(403, 'You do not have permission to create articles.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:kb_categories,id',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
        ]);

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $article = KbArticle::create($validated);
        
        // Log activity
        ActivityLogService::logCreate($article, 'knowledgebase', "Article created: {$article->title}");

        return redirect()->route('knowledgebase.settings', ['tab' => 'articles'])
            ->with('success', 'Article created successfully.');
    }

    public function editArticle(KbArticle $article)
    {
        if (!auth()->user()->hasPermission('knowledgebase_articles.edit')) {
            abort(403, 'You do not have permission to edit articles.');
        }
        
        $categories = KbCategory::active()->ordered()->get();
        
        return view('knowledgebase.articles.edit', [
            'article' => $article,
            'categories' => $categories,
        ]);
    }

    public function updateArticle(Request $request, KbArticle $article)
    {
        if (!auth()->user()->hasPermission('knowledgebase_articles.edit')) {
            abort(403, 'You do not have permission to edit articles.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:kb_categories,id',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
        ]);

        // Set published_at if publishing for the first time
        if ($validated['status'] === 'published' && !$article->published_at) {
            $validated['published_at'] = now();
        }

        $oldValues = $article->only(['title', 'status', 'category_id']);
        $article->update($validated);
        
        // Log activity
        ActivityLogService::logUpdate($article, 'knowledgebase', $oldValues, "Article updated: {$article->title}");

        return redirect()->route('knowledgebase.settings', ['tab' => 'articles'])
            ->with('success', 'Article updated successfully.');
    }

    public function destroyArticle(KbArticle $article)
    {
        if (!auth()->user()->hasPermission('knowledgebase_articles.delete')) {
            abort(403, 'You do not have permission to delete articles.');
        }
        
        // Log activity before delete
        ActivityLogService::logDelete($article, 'knowledgebase', "Article deleted: {$article->title}");
        
        $article->delete();

        return redirect()->route('knowledgebase.settings', ['tab' => 'articles'])
            ->with('success', 'Article deleted successfully.');
    }

    // ==================== CATEGORIES ====================

    public function storeCategory(Request $request)
    {
        if (!auth()->user()->hasPermission('knowledgebase_categories.create')) {
            abort(403, 'You do not have permission to create categories.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $category = KbCategory::create($validated);
        
        // Log activity
        ActivityLogService::logCreate($category, 'knowledgebase', "Category created: {$category->name}");

        return redirect()->route('knowledgebase.settings', ['tab' => 'categories'])
            ->with('success', 'Category created successfully.');
    }

    public function updateCategory(Request $request, KbCategory $category)
    {
        if (!auth()->user()->hasPermission('knowledgebase_categories.edit')) {
            abort(403, 'You do not have permission to edit categories.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $oldValues = $category->only(['name', 'status']);
        $category->update($validated);
        
        // Log activity
        ActivityLogService::logUpdate($category, 'knowledgebase', $oldValues, "Category updated: {$category->name}");

        return redirect()->route('knowledgebase.settings', ['tab' => 'categories'])
            ->with('success', 'Category updated successfully.');
    }

    public function destroyCategory(KbCategory $category)
    {
        if (!auth()->user()->hasPermission('knowledgebase_categories.delete')) {
            abort(403, 'You do not have permission to delete categories.');
        }
        
        if ($category->articles()->count() > 0) {
            return redirect()->route('knowledgebase.settings', ['tab' => 'categories'])
                ->with('error', 'Cannot delete category with articles. Please move or delete articles first.');
        }

        // Log activity before delete
        ActivityLogService::logDelete($category, 'knowledgebase', "Category deleted: {$category->name}");
        
        $category->delete();

        return redirect()->route('knowledgebase.settings', ['tab' => 'categories'])
            ->with('success', 'Category deleted successfully.');
    }
}
