<?php

namespace App\Http\Controllers;

use App\Models\Pelicula;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PeliculaController extends Controller
{
    public function index()
    {
        $peliculas = Pelicula::all(); 
        return view('admin.peliculas.index', compact('peliculas'));
    }

    public function create()
    {
        $generos = Genre::all(); 
        return view('admin.peliculas.create', compact('generos'));
    }

    private function downloadAndSavePoster($query, $existingUrl = null)
    {
        // 1. Si el usuario proporcionó una URL manualmente (y no es una URL local nuestra), priorizarla
        if ($existingUrl && !str_starts_with($existingUrl, '/storage/')) {
            try {
                $imageContent = Http::get($existingUrl)->body();
                $filename = Str::slug($query) . '-manual-' . time() . '.jpg';
                Storage::disk('public')->put('portadas/' . $filename, $imageContent);
                return '/storage/portadas/' . $filename;
            } catch (\Exception $e) {
                // Fallback a intentar con TMDB si falla la descarga manual
            }
        }

        // 2. Si no hay URL manual, usar TMDB
        $apiKey = env('TMDB_API_KEY');
        if ($apiKey) {
            try {
                $response = Http::get("https://api.themoviedb.org/3/search/movie", [
                    'api_key' => $apiKey,
                    'query' => $query,
                    'language' => 'es-MX',
                    'include_adult' => 'false'
                ]);

                if ($response->successful() && !empty($response->json('results'))) {
                    $posterPath = $response->json('results')[0]['poster_path'];
                    
                    if ($posterPath) {
                        $imageUrl = "https://image.tmdb.org/t/p/w500" . $posterPath;
                        $imageContent = Http::get($imageUrl)->body();
                        
                        $filename = Str::slug($query) . '-' . time() . '.jpg';
                        Storage::disk('public')->put('portadas/' . $filename, $imageContent);
                        
                        return '/storage/portadas/' . $filename;
                    }
                }
            } catch (\Exception $e) {
                // Ignore TMDB error
            }
        }

        // 3. Si era una URL local que ya existía, devolverla tal cual para no perderla
        if ($existingUrl && str_starts_with($existingUrl, '/storage/')) {
            return $existingUrl;
        }

        return null;
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => ['required', 'string', 'max:255', 'unique:peliculas,titulo'],
            'genero' => ['required', 'string'],
            'clasificacion' => ['required', 'string'],
            'duracion' => ['required', 'integer', 'min:1'],
            'idioma' => ['required', 'string'],
            'formato' => ['required', 'string'],
            'estatus' => ['required', 'string'],
            'sinopsis' => ['required', 'string', 'min:10'],
            'imagen_url' => ['nullable'],
        ], [
            'titulo.unique' => '¡Esta película ya se encuentra registrada en la cartelera!',
        ]);

        $data = $request->all();
        $data['imagen_url'] = $this->downloadAndSavePoster($request->titulo, $request->imagen_url);

        Pelicula::create($data);

        return redirect()->route('peliculas.index')->with('success', '¡La película "' . $request->titulo . '" se guardó con éxito!');
    }

    public function edit(Pelicula $pelicula)
    {
        $generos = Genre::all();
        return view('admin.peliculas.edit', compact('pelicula', 'generos'));
    }

    public function update(Request $request, Pelicula $pelicula)
    {
        $request->validate([
            'titulo' => ['required', 'string', 'max:255', 'unique:peliculas,titulo,' . $pelicula->id],
            'genero' => ['required', 'string'],
            'clasificacion' => ['required', 'string'],
            'duracion' => ['required', 'integer'],
            'idioma' => ['required', 'string'],
            'formato' => ['required', 'string'],
            'estatus' => ['required', 'string'],
            'sinopsis' => ['required', 'string'],
            'imagen_url' => ['nullable'],
        ]);

        $data = $request->all();
        
        if ($request->titulo !== $pelicula->titulo || $request->filled('imagen_url') && $request->imagen_url !== $pelicula->imagen_url) {
            $nuevaPortada = $this->downloadAndSavePoster($request->titulo, $request->imagen_url);
            if ($nuevaPortada) {
                $data['imagen_url'] = $nuevaPortada;
            }
        } else {
            $data['imagen_url'] = $pelicula->imagen_url;
        }

        $pelicula->update($data);

        return redirect()->route('peliculas.index')->with('success', 'La película se ha actualizado correctamente.');
    }

    public function destroy(Pelicula $pelicula)
    {
        $pelicula->delete();
        return redirect()->route('peliculas.index')->with('success', 'Película eliminada correctamente.');
    }
}