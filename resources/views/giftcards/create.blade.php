<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear GiftCard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 12px 0 6px;
        }
        input, select, textarea, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background: #3490dc;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #2779bd;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Crear GiftCard</h2>

    @if ($errors->any())
        <div class="error">
            <strong>Errores:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('giftcards.store') }}" method="POST">
        @csrf

        <label for="id_category">Categoría</label>
        <select name="id_category" id="id_category" required>
            <option value="">Seleccionar categoría</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        <label for="title">Título</label>
        <input type="text" name="title" id="title" required>

        <label for="description">Descripción</label>
        <textarea name="description" id="description"></textarea>

        <label for="amount">Monto</label>
        <input type="number" name="amount" id="amount" required>

        <label for="price">Precio</label>
        <input type="number" name="price" id="price" step="0.01" required>

        <label for="image">URL de imagen</label>
        <input type="url" name="image" id="image">

        <label for="stock">Stock</label>
        <input type="number" name="stock" id="stock" required>

        <button type="submit">Guardar GiftCard</button>
    </form>
</div>
</body>
</html>
