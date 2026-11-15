<?php

namespace App\Http\Controllers\DataManagement;

use App\Http\Controllers\Controller;
use App\Models\DataManagement\OrderManagement\Order;
use App\Models\DataManagement\OrderManagement\OrderShipment;
use App\Models\DataManagement\OrderManagement\OrderCost;
use App\Models\MasterData\Administration\Customer;
use App\Models\MasterData\Administration\GoodsType;
use App\Models\MasterData\Administration\GoodsCategory;
use App\Models\MasterData\Administration\PackagingType;
use App\Models\MasterData\Vehicle\VehicleType;
use App\Actions\DataManagement\OrderManagement\CreateOrder;
use App\Actions\DataManagement\OrderManagement\UpdateOrder;
use App\Actions\DataManagement\OrderManagement\DeleteOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('customer')
            ->withCount('shipments')
            ->latest('po_date')
            ->paginate(15);

        $statCounts = [
            'total'    => Order::count(),
            'pending'  => Order::where('approval_status', 'pending')->count(),
            'approved' => Order::where('approval_status', 'approved')->count(),
            'rejected' => Order::where('approval_status', 'rejected')->count(),
        ];

        return view('data-management.orders.index', compact('orders', 'statCounts'));
    }

    public function create()
    {
        return view('data-management.orders.create', [
            'customers'       => Customer::where('is_active', true)->with(['primaryPic', 'pics', 'customerType', 'businessType'])->orderBy('name')->get(),
            'goodsTypes'      => GoodsType::where('is_active', true)->orderBy('name')->get(),
            'goodsCategories' => GoodsCategory::where('is_active', true)->orderBy('name')->get(),
            'packagingTypes'  => PackagingType::where('is_active', true)->orderBy('name')->get(),
            'vehicleTypes'    => VehicleType::where('is_active', true)->orderBy('name')->get(),
            'nextPoNumber'    => $this->generatePoNumber(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'po_number' => 'required|string|max:50|unique:bd01_01_orders,po_number',
            'po_date'   => 'required|date',
        ]);

        DB::transaction(function () use ($request) {
            $order = (new CreateOrder)->execute([
                'po_number'       => $request->po_number,
                'po_date'         => $request->po_date,
                'customer_id'     => $request->customer_id,
                'customer_name'   => $this->snapCustomer($request->customer_id, 'name'),
                'customer_address'=> $request->customer_address,
                'pic_id'          => $request->pic_id,
                'pic_name'        => $request->pic_name,
                'pic_phone'       => $request->pic_phone,
                'payment_method'  => $request->payment_method,
                'payment_due_date'=> $request->payment_due_date,
                'special_notes'   => $request->special_notes,
                'submission_date' => $request->submission_date,
                'approval_status' => $request->approval_status ?? 'pending',
                'approval_notes'  => $request->approval_notes,
                'approval_date'   => $request->approval_date,
            ]);

            $this->syncShipments($order, $request->shipments ?? []);
            $this->syncCosts($order, $request->costs ?? []);
            $this->updateOrderTotal($order);
        });

        return redirect()->route('data-management.orders.index')
            ->with('success', 'Pesanan berhasil dibuat.');
    }

    public function show(Order $order)
    {
        $order->load(['customer.customerType', 'customer.businessType', 'customer.pics', 'shipments.goodsType', 'shipments.goodsCategory', 'shipments.packagingType', 'shipments.vehicleType', 'costs', 'approvedBy', 'createdBy']);
        return view('data-management.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load(['shipments', 'costs']);
        return view('data-management.orders.edit', [
            'order'           => $order,
            'customers'       => Customer::where('is_active', true)->with(['primaryPic', 'pics', 'customerType', 'businessType'])->orderBy('name')->get(),
            'goodsTypes'      => GoodsType::where('is_active', true)->orderBy('name')->get(),
            'goodsCategories' => GoodsCategory::where('is_active', true)->orderBy('name')->get(),
            'packagingTypes'  => PackagingType::where('is_active', true)->orderBy('name')->get(),
            'vehicleTypes'    => VehicleType::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'po_number' => 'required|string|max:50|unique:bd01_01_orders,po_number,'.$order->id,
            'po_date'   => 'required|date',
        ]);

        DB::transaction(function () use ($request, $order) {
            (new UpdateOrder)->execute($order, [
                'po_number'       => $request->po_number,
                'po_date'         => $request->po_date,
                'customer_id'     => $request->customer_id,
                'customer_name'   => $this->snapCustomer($request->customer_id, 'name'),
                'customer_address'=> $request->customer_address,
                'pic_id'          => $request->pic_id,
                'pic_name'        => $request->pic_name,
                'pic_phone'       => $request->pic_phone,
                'payment_method'  => $request->payment_method,
                'payment_due_date'=> $request->payment_due_date,
                'special_notes'   => $request->special_notes,
                'submission_date' => $request->submission_date,
                'approval_status' => $request->approval_status ?? $order->approval_status,
                'approval_notes'  => $request->approval_notes,
                'approval_date'   => $request->approval_date,
            ]);

            $this->syncShipments($order, $request->shipments ?? []);
            $this->syncCosts($order, $request->costs ?? []);
            $this->updateOrderTotal($order);
        });

        return redirect()->route('data-management.orders.index', $order)
            ->with('success', 'Pesanan berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        (new DeleteOrder)->execute($order);
        return redirect()->route('data-management.orders.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }

    public function updateApproval(Request $request, Order $order)
    {
        $request->validate(['approval_status' => 'required|in:pending,approved,rejected']);
        $order->update([
            'approval_status' => $request->approval_status,
            'approval_notes'  => $request->approval_notes,
            'approval_date'   => now()->toDateString(),
            'approved_by'     => auth()->id(),
        ]);
        return back()->with('success', 'Status persetujuan diperbarui.');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function generatePoNumber(): string
    {
        $year  = now()->year;
        $count = Order::whereYear('created_at', $year)->count() + 1;
        return sprintf('PO/LOG/%d/%03d', $year, $count);
    }

    private function snapCustomer(?int $id, string $field): ?string
    {
        if (!$id) return null;
        return Customer::find($id)?->$field;
    }

    private function syncShipments(Order $order, array $rows): void
    {
        $order->shipments()->delete();
        foreach ($rows as $i => $row) {
            if (empty(array_filter($row))) continue;
            $vtId   = $row['vehicle_type_id'] ?? null;
            $vtName = $vtId ? VehicleType::find($vtId)?->name : null;
            $gtId   = $row['goods_type_id'] ?? null;
            $gcId   = $row['goods_category_id'] ?? null;
            $ptId   = $row['packaging_type_id'] ?? null;
            OrderShipment::create([
                'order_id'            => $order->id,
                'goods_type_id'       => $gtId,
                'goods_type_name'     => $gtId ? GoodsType::find($gtId)?->name : null,
                'goods_category_id'   => $gcId,
                'goods_category_name' => $gcId ? GoodsCategory::find($gcId)?->name : null,
                'packaging_type_id'   => $ptId,
                'packaging_type_name' => $ptId ? PackagingType::find($ptId)?->name : null,
                'loading_address'     => $row['loading_address'] ?? null,
                'unloading_address'   => $row['unloading_address'] ?? null,
                'loading_date'        => $row['loading_date'] ?: null,
                'unloading_date'      => $row['unloading_date'] ?: null,
                'vehicle_type_id'     => $vtId,
                'vehicle_type_name'   => $vtName,
                'vehicle_capacity'    => $row['vehicle_capacity'] ?? null,
                'vehicle_quantity'    => $row['vehicle_quantity'] ?? 1,
                'total_volume'        => $row['total_volume'] ?? null,
                'measurement_unit'    => $row['measurement_unit'] ?? 'Ton',
                'sort_order'          => $i,
            ]);
        }
    }

    private function syncCosts(Order $order, array $rows): void
    {
        $order->costs()->delete();
        foreach ($rows as $i => $row) {
            if (empty(array_filter($row))) continue;
            $qty   = (float)($row['cost_qty'] ?? 0);
            $price = (float)($row['cost_unit_price'] ?? 0);
            $ppn   = (float)($row['ppn_percent'] ?? 0);
            $sub   = $qty * $price;
            $ppnAmt = $sub * $ppn / 100;
            OrderCost::create([
                'order_id'        => $order->id,
                'cost_description'=> $row['cost_description'] ?? null,
                'cost_qty'        => $qty,
                'cost_unit_price' => $price,
                'cost_subtotal'   => $sub,
                'ppn_percent'     => $ppn,
                'ppn_amount'      => $ppnAmt,
                'total_amount'    => $sub + $ppnAmt,
                'sort_order'      => $i,
            ]);
        }
    }

    private function updateOrderTotal(Order $order): void
    {
        $order->refresh();
        $order->update(['total_amount' => $order->costs->sum('total_amount')]);
    }
}
