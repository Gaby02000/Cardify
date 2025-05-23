<?php

namespace App\Http\Controllers;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Category;
use App\Models\GiftCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class GiftCardController extends Controller
{

    public function index(Request $request)
    {
        $query = Giftcard::with('category');

        // Filtro por categoría
        if ($category = $request->input('category')) {
            $query->where('id_category', $category); 
        }

        if ($search = $request->input('search')) {
            $driver = DB::getDriverName();

            $query->where(function ($q) use ($search, $driver) {
                if ($driver === 'pgsql') {
                    $q->whereRaw('title ILIKE ?', ["%{$search}%"])
                    ->orWhereRaw('description ILIKE ?', ["%{$search}%"]);
                } else {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
                }
            });
        }

        // Ordenamiento
        if ($request->has('sort')) {
            $sortField = $request->input('sort');
            $direction = $request->input('direction') === 'desc' ? 'desc' : 'asc';

            if (in_array($sortField, ['price', 'stock', 'amount'])) {
                $query->orderBy($sortField, $direction);
            }
        }

        $giftcards = $query->paginate(10);

        if ($request->ajax()) {
            return view('_table', compact('giftcards'))->render();
        }

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

        // if ($request->hasFile('image')) {
        //     $image = $request->file('image');
        //     $filename = time() . '_' . $image->getClientOriginalName(); 
        //     $image->move(public_path('images/giftcards'), $filename); 
        //     $data['image'] = 'images/giftcards/' . $filename;
        // }

        if ($request->hasFile('image')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            $data['image'] = $uploadedFileUrl;
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
        // $giftcard->update($request->all());

        $data = $request->all();

        if ($request->hasFile('image')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            $data['image'] = $uploadedFileUrl;
        }

        $giftcard->update($data);

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
