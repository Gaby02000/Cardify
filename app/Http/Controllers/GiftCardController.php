<?php

namespace App\Http\Controllers;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
use App\Models\GiftCard;
use Illuminate\Http\Request;

class GiftCardController extends Controller
{
    public function index(Request $request)
    {
        $query = Giftcard::with('category');

        // Filtro por categoría
        if ($category = $request->input('category')) {
            $query->where('id_category', $category); 
        }

        // Filtro por búsqueda
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'stock' => 'required|integer',
        ]);

        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');

            $cloudName = config('cloudinary.cloud.cloud_name');
            $apiKey = config('cloudinary.cloud.api_key');

            // Log::info('Cloudinary config:', [
            //     'cloud_name' => config('cloudinary.cloud.cloud_name'),
            //     'api_key' => config('cloudinary.cloud.api_key'),
            // ]);

            $apiSecret = config('cloudinary.cloud.api_secret');
            $folder = 'giftcards';
            $slugTitle = \Illuminate\Support\Str::slug($request->title);
            $timestamp = time();
            $publicId = "{$slugTitle}_{$timestamp}";

            $paramsToSign = "folder={$folder}&public_id={$publicId}&timestamp={$timestamp}{$apiSecret}";
            $signature = hash('sha256', $paramsToSign);

            $response = \Illuminate\Support\Facades\Http::asMultipart()->post("https://api.cloudinary.com/v1_1/{$cloudName}/image/upload", [
                [
                    'name'     => 'file',
                    'contents' => fopen($uploadedFile->getRealPath(), 'r'),
                    'filename' => $uploadedFile->getClientOriginalName(),
                ],
                ['name' => 'api_key', 'contents' => $apiKey],
                ['name' => 'timestamp', 'contents' => $timestamp],
                ['name' => 'folder', 'contents' => $folder],
                ['name' => 'public_id', 'contents' => $publicId],
                ['name' => 'signature', 'contents' => $signature],
            ]);

            // Ejemplo:
            // Log::info('Firmando parámetros:', ['firma' => $signature]);
            // Log::info('Respuesta Cloudinary:', ['body' => $response->body()]);


            if (!$response->successful()) {
                return back()->withErrors(['image' => 'Error al subir la imagen a Cloudinary.']);
            }

            $result = $response->json();
            $data['image'] = $result['secure_url'];
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
