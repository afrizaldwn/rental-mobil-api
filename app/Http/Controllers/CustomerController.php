<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private function rules($id = null): array
    {
        $suffix = $id ? ",$id" : '';

        return [
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|max:100|unique:customers,email' . $suffix,
            'phone'                 => 'required|string|max:20',
            'address'               => 'required|string',
            'gender'                => 'required|in:male,female',
            'birth_date'            => 'required|date_format:Y-m-d',
            'id_card_number'        => 'required|digits:16|unique:customers,id_card_number' . $suffix,
            'driver_license_number' => 'required|string|max:20|unique:customers,driver_license_number' . $suffix,
            'city'                  => 'required|string|max:50',
            'status'                => 'in:active,inactive',
        ];
    }

    private function notFound(Request $request)
    {
        return ResponseFormatter::make($request, [
            'status' => 'error', 'message' => 'Customer tidak ditemukan',
        ], 404);
    }

    public function index(Request $request)
    {
        return ResponseFormatter::make($request, [
            'status' => 'success',
            'data'   => Customer::orderBy('id')->get(),
        ]);
    }

    public function show(Request $request, $id)
    {
        $customer = Customer::with('rentals')->find($id);
        if (!$customer) return $this->notFound($request);

        return ResponseFormatter::make($request, ['status' => 'success', 'data' => $customer]);
    }

    public function store(Request $request)
    {
        $v = app('validator')->make($request->all(), $this->rules());
        if ($v->fails()) {
            return ResponseFormatter::make($request, [
                'status' => 'error', 'errors' => $v->errors()->toArray(),
            ], 422);
        }

        $customer = Customer::create($request->only((new Customer)->getFillable()));

        return ResponseFormatter::make($request, [
            'status' => 'success', 'message' => 'Customer berhasil dibuat', 'data' => $customer,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);
        if (!$customer) return $this->notFound($request);

        $rules = collect($this->rules($id))->map(fn ($r) => 'sometimes|' . $r)->all();
        $v = app('validator')->make($request->all(), $rules);
        if ($v->fails()) {
            return ResponseFormatter::make($request, [
                'status' => 'error', 'errors' => $v->errors()->toArray(),
            ], 422);
        }

        $customer->update($request->only($customer->getFillable()));

        return ResponseFormatter::make($request, [
            'status' => 'success', 'message' => 'Customer berhasil diupdate', 'data' => $customer->fresh(),
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $customer = Customer::find($id);
        if (!$customer) return $this->notFound($request);

        $customer->delete();

        return ResponseFormatter::make($request, [
            'status' => 'success', 'message' => 'Customer berhasil dihapus',
        ]);
    }
}