<?php

namespace App\Http\Controllers;

use App\DTOs\Product\StoreProductDTO;
use App\DTOs\Product\UpdateProductDTO;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Product\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly ProductRepositoryInterface $productRepository,
    ) {}

    public function index(Request $request): View
    {
        $products = $this->productService->list($request->query('q'));
        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        return view('products.create');
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->productService->store(StoreProductDTO::fromRequest($request));
        return redirect()->route('products.index')->with('success', __('products.created'));
    }

    public function edit(int $id): View
    {
        $product = $this->productRepository->findById($id);
        return view('products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, int $id): RedirectResponse
    {
        $this->productService->update($id, UpdateProductDTO::fromRequest($request));
        return redirect()->route('products.index')->with('success', __('products.updated'));
    }

    public function destroy(int $id): RedirectResponse
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('products.index')->with('error', __('products.unauthorized'));
        }

        try {
            $this->productService->delete($id);
            return redirect()->route('products.index')->with('success', __('products.deleted'));
        } catch (\RuntimeException $e) {
            return redirect()->route('products.index')->with('error', $e->getMessage());
        }
    }
}
