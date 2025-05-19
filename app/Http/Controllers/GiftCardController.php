<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\GiftCard;
use Illuminate\Http\Request;

class GiftCardController extends Controller
{
    public function index(Request $request)
    {
        $query = GiftCard::with('category'); 

        if (!empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if (!empty($request->category)) {
            $query->where('id_category', $request->category); 
        }

        $giftcards = $query->paginate(10);
        $categories = Category::all();

        return view('index', compact('giftcards', 'categories'));
    }

  
    public function create()
    {
        $categories = Category::all();
        return view('giftcards.create', compact('categories'));
    }
    // Guardar los datos
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
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName(); 
            $image->move(public_path('images/giftcards'), $filename); 
            $data['image'] = 'images/giftcards/' . $filename;
        }

        GiftCard::create($data);

        return redirect()->route('giftcards.index')->with('success', 'GiftCard creada con éxito.');
    }
    public function edit($id)
    {
        $giftcard = Giftcard::findOrFail($id);
        $categories = Category::all(); 
        return view('giftcards.edit', compact('giftcard', 'categories'));
    }

    // Actualizar los datos 
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stock' => 'required|integer|min:0',
            'id_category' => 'required|exists:categories,id',
        ]);

        $giftcard = Giftcard::findOrFail($id);
        $giftcard->update($request->all());

        return redirect()->route('giftcards.show', $giftcard->id)->with('success', 'Giftcard actualizada correctamente.');
    }
    // Eliminar la giftcard
     public function destroy($id)
    {
        $giftcard = Giftcard::findOrFail($id);
        $giftcard->delete();

        return redirect()->route('giftcards.index')
                         ->with('success', 'Giftcard eliminada correctamente.');
    }
    // Mostrar los detalles
    public function show($id)
    {
        $giftcard = GiftCard::findOrFail($id);
        return view('giftcards.show', compact('giftcard'));
    }
}
