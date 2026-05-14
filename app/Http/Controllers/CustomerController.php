<?php

namespace App\Http\Controllers;

use App\DTOs\Customer\StoreCustomerDTO;
use App\DTOs\Customer\UpdateCustomerDTO;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Services\Customer\CustomerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct(
        private readonly CustomerService $customerService,
        private readonly CustomerRepositoryInterface $customerRepository,
    ) {}

    public function index(): View
    {
        $customers = $this->customerRepository->paginate();
        return view('customers.index', compact('customers'));
    }

    public function show(int $id): View|RedirectResponse
    {
        $customer = $this->customerRepository->findById($id);

        if (!$customer) {
            return redirect()->route('customers.index')->with('error', __('customers.not_found'));
        }

        $orders = $customer->orders()->latest()->get();
        return view('customers.show', compact('customer', 'orders'));
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $this->customerService->store(StoreCustomerDTO::fromRequest($request));
        return redirect()->route('customers.index')->with('success', __('customers.created'));
    }

    public function edit(int $id): View|RedirectResponse
    {
        $customer = $this->customerRepository->findById($id);

        if (!$customer) {
            return redirect()->route('customers.index')->with('error', __('customers.not_found'));
        }

        return view('customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, int $id): RedirectResponse
    {
        $this->customerService->update($id, UpdateCustomerDTO::fromRequest($request));
        return redirect()->route('customers.index')->with('success', __('customers.updated'));
    }

    public function destroy(int $id): RedirectResponse
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('customers.index')->with('error', __('customers.unauthorized'));
        }

        try {
            $this->customerService->delete($id);
            return redirect()->route('customers.index')->with('success', __('customers.deleted'));
        } catch (\RuntimeException $e) {
            return redirect()->route('customers.index')->with('error', $e->getMessage());
        }
    }
}
