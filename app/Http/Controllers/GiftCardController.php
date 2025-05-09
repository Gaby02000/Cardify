<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\GiftCard;
use Illuminate\Http\Request;

class GiftCardController extends Controller
{
    public function index()
    {
        $giftcards = GiftCard::all();
        return view('index', compact('giftcards'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('giftcards.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_category' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stock' => 'required|integer',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('giftcards', 'public');
        }

        GiftCard::create($data);

        return redirect()->route('giftcards.index')->with('success', 'GiftCard creada con éxito.');
    }
    
    public function show($id)
    {
        $giftcard = GiftCard::findOrFail($id);
        return view('giftcards.show', compact('giftcard'));
    }
}
