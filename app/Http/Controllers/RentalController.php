<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Rental;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    private function rules(): array
    {
        return [
            'customer_id'  => 'required|integer|exists:customers,id',
            'car_brand'    => 'required|string|max:50',
            'car_model'    => 'required|string|max:50',
            'plate_number' => 'required|string|max:15',
            'rental_date'  => 'required|date_format:Y-m-d',
            'return_date'  => 'required|date_format:Y-m-d',
            'total_price'  => 'required|integer|min:0',
            'status'       => 'in:booked,ongoing,returned,cancelled',
        ];
    }

    private function notFound(Request $request)
    {
        return ResponseFormatter::make($request, [
            'status' => 'error', 'message' => 'Rental tidak ditemukan',
        ], 404);
    }

    // GET /rentals  atau  GET /rentals?customer_id=1
    public function index(Request $request)
    {
        $query = Rental::orderBy('id');

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->query('customer_id'));
        }

        return ResponseFormatter::make($request, ['status' => 'success', 'data' => $query->get()]);
    }

    public function show(Request $request, $id)
    {
        $rental = Rental::with('customer')->find($id);
        if (!$rental) return $this->notFound($request);

        return ResponseFormatter::make($request, ['status' => 'success', 'data' => $rental]);
    }

    public function store(Request $request)
    {
        $v = app('validator')->make($request->all(), $this->rules());
        if ($v->fails()) {
            return ResponseFormatter::make($request, [
                'status' => 'error', 'errors' => $v->errors()->toArray(),
            ], 422);
        }

        $rental = Rental::create($request->only((new Rental)->getFillable()));

        return ResponseFormatter::make($request, [
            'status' => 'success', 'message' => 'Rental berhasil dibuat', 'data' => $rental,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $rental = Rental::find($id);
        if (!$rental) return $this->notFound($request);

        $rules = collect($this->rules())->map(fn ($r) => 'sometimes|' . $r)->all();
        $v = app('validator')->make($request->all(), $rules);
        if ($v->fails()) {
            return ResponseFormatter::make($request, [
                'status' => 'error', 'errors' => $v->errors()->toArray(),
            ], 422);
        }

        $rental->update($request->only($rental->getFillable()));

        return ResponseFormatter::make($request, [
            'status' => 'success', 'message' => 'Rental berhasil diupdate', 'data' => $rental->fresh(),
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $rental = Rental::find($id);
        if (!$rental) return $this->notFound($request);

        $rental->delete();

        return ResponseFormatter::make($request, [
            'status' => 'success', 'message' => 'Rental berhasil dihapus',
        ]);
    }
}