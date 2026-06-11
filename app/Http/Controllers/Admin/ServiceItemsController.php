<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service\Service;
use App\Models\Service\ServiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ServiceItemsController extends Controller
{
    public function index(Service $service)
    {
        abort_unless(Gate::allows('service_access'), 403);

        $items = $service->items()->get();
        return view('admin.service-items.index', compact('service', 'items'));
    }

    public function create(Service $service)
    {
        abort_unless(Gate::allows('service_create'), 403);
        return view('admin.service-items.create', compact('service'));
    }

    public function store(Request $request, Service $service)
    {
        abort_unless(Gate::allows('service_create'), 403);

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'addon_price' => 'nullable|numeric|min:0',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['addon_price'] = $request->filled('addon_price') ? $data['addon_price'] : null;

        $service->items()->create($data);
        return redirect()->route('admin.services.items.index', $service->id)
            ->with('success', 'تم إضافة العنصر بنجاح.');
    }

    public function edit(Service $service, ServiceItem $item)
    {
        abort_unless(Gate::allows('service_edit'), 403);
        return view('admin.service-items.edit', compact('service', 'item'));
    }

    public function update(Request $request, Service $service, ServiceItem $item)
    {
        abort_unless(Gate::allows('service_edit'), 403);

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'addon_price' => 'nullable|numeric|min:0',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $data['is_active']   = $request->boolean('is_active', true);
        $data['sort_order']  = (int) ($data['sort_order'] ?? 0);
        $data['addon_price'] = $request->filled('addon_price') ? $data['addon_price'] : null;

        $item->update($data);
        return redirect()->route('admin.services.items.index', $service->id)
            ->with('success', 'تم تحديث العنصر بنجاح.');
    }

    public function destroy(Service $service, ServiceItem $item)
    {
        abort_unless(Gate::allows('service_delete'), 403);

        $item->delete();
        return redirect()->route('admin.services.items.index', $service->id)
            ->with('success', 'تم حذف العنصر.');
    }
}
