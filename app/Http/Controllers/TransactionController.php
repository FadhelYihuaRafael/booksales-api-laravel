<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Read all transactions (Admin only).
     * Mengambil data dari relasi Model: customer dan book.
     */
    public function index()
    {
        $transactions = Transaction::with(['customer', 'book.author'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Get all transactions successfully',
            'data'    => $transactions
        ], 200);
    }

    /**
     * Create a new transaction (Customer only).
     * Mengatur data dengan relasi foreignId customer_id dan book_id.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id'      => 'required|exists:books,id',
            'quantity'     => 'nullable|integer|min:1',
            'total_amount' => 'nullable|numeric|min:0',
            'total_price'  => 'nullable|numeric|min:0',
            'order_number' => 'nullable|string|max:255|unique:transactions,order_number',
            'status'       => 'nullable|string|in:pending,paid,completed,cancelled',
            'customer_id'  => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Ambil ID customer dari user yang sedang login atau dari request
        $customerId = $request->customer_id ?? $request->user()->id;
        $quantity = $request->input('quantity', 1);
        $totalAmount = $request->total_amount ?? $request->total_price ?? 0;
        $orderNumber = $request->order_number ?? ('TRX-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5)));
        $status = $request->input('status', 'pending');

        $transaction = Transaction::create([
            'order_number' => $orderNumber,
            'customer_id'  => $customerId,
            'book_id'      => $request->book_id,
            'quantity'     => $quantity,
            'total_amount' => $totalAmount,
            'status'       => $status,
        ]);

        // Mengambil data dari relasi Model
        $transaction->load(['customer', 'book.author']);

        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully',
            'data'    => $transaction
        ], 201);
    }

    /**
     * Display the specified transaction (Customer only).
     * Mengambil data dari relasi Model.
     */
    public function show(Request $request, $id)
    {
        $transaction = Transaction::with(['customer', 'book.author'])->find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Get transaction detail successfully',
            'data'    => $transaction
        ], 200);
    }

    /**
     * Update the specified transaction (Customer only).
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'book_id'      => 'sometimes|required|exists:books,id',
            'quantity'     => 'nullable|integer|min:1',
            'total_amount' => 'nullable|numeric|min:0',
            'total_price'  => 'nullable|numeric|min:0',
            'status'       => 'nullable|string|in:pending,paid,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        if (isset($data['total_price']) && !isset($data['total_amount'])) {
            $data['total_amount'] = $data['total_price'];
            unset($data['total_price']);
        }

        $transaction->update($data);

        // Mengambil data dari relasi Model
        $transaction->load(['customer', 'book.author']);

        return response()->json([
            'success' => true,
            'message' => 'Transaction updated successfully',
            'data'    => $transaction
        ], 200);
    }

    /**
     * Remove the specified transaction (Admin only).
     */
    public function destroy($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }

        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaction deleted successfully'
        ], 200);
    }
}

