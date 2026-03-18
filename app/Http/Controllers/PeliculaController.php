<?php

namespace App\Http\Controllers;

use App\Models\Pelicula;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
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

    public function searchTmdb(Request $request)
    {
        $query = $request->get('query');
        $apiKey = env('TMDB_API_KEY');
        
        if (!$apiKey) {
            return response()->json(['error' => 'API Key no configurada'], 500);
        }

        try {
            $searchResponse = Http::get("https://api.themoviedb.org/3/search/movie", [
                'api_key' => $apiKey,
                'query' => $query,
                'language' => 'es-MX',
                'include_adult' => 'false'
            ]);

            if ($searchResponse->successful() && !empty($searchResponse->json('results'))) {
                $movie = $searchResponse->json('results')[0];
                
                $detailsResponse = Http::get("https://api.themoviedb.org/3/movie/" . $movie['id'], [
                    'api_key' => $apiKey,
                    'language' => 'es-MX'
                ]);

                $details = $detailsResponse->successful() ? $detailsResponse->json() : [];

                $posterUrl = $movie['poster_path'] ? "https://image.tmdb.org/t/p/w500" . $movie['poster_path'] : '';
                
                $generosTMDB = $details['genres'] ?? [];
                $nombresGeneros = array_column($generosTMDB, 'name');
                $generoPrincipal = !empty($nombresGeneros) ? $nombresGeneros[0] : '';

                return response()->json([
                    'titulo' => $details['title'] ?? $movie['title'],
                    'sinopsis' => $details['overview'] ?? $movie['overview'],
                    'duracion' => $details['runtime'] ?? '',
                    'imagen_url' => $posterUrl,
                    'genero' => $generoPrincipal
                ]);
            }
            
            return response()->json(['error' => 'No se encontraron resultados en TMDB'], 404);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al conectar con TMDB'], 500);
        }
    }

    private function downloadAndSavePoster($titulo, $existingUrl = null)
    {
        // 1. Si el usuario proporcionó una URL manualmente (y no es una URL local nuestra), intentamos descargarla
        if ($existingUrl && !str_starts_with($existingUrl, '/storage/')) {
            try {
                $response = Http::timeout(10)->get($existingUrl);
                if ($response->successful()) {
                    $imageContent = $response->body();
                    $extension = 'jpg'; // Por defecto, podrías intentar detectarlo de headers
                    
                    $filename = Str::slug($titulo) . '-local-' . time() . '.' . $extension;
                    Storage::disk('public')->put('portadas/' . $filename, $imageContent);
                    return '/storage/portadas/' . $filename;
                }
            } catch (\Exception $e) {
                \Log::warning("Error al descargar póster manual para '{$titulo}': " . $e->getMessage());
            }
        }

        // 2. Si no hay URL manual o falló la descarga, intentamos buscar en TMDB automáticamente
        $apiKey = env('TMDB_API_KEY');
        if ($apiKey) {
            try {
                $searchResponse = Http::get("https://api.themoviedb.org/3/search/movie", [
                    'api_key' => $apiKey,
                    'query' => $titulo,
                    'language' => 'es-MX',
                ]);

                if ($searchResponse->successful() && !empty($searchResponse->json('results'))) {
                    $posterPath = $searchResponse->json('results')[0]['poster_path'];
                    
                    if ($posterPath) {
                        $imageUrl = "https://image.tmdb.org/t/p/w500" . $posterPath;
                        $imageResponse = Http::get($imageUrl);
                        
                        if ($imageResponse->successful()) {
                            $filename = Str::slug($titulo) . '-' . time() . '.jpg';
                            Storage::disk('public')->put('portadas/' . $filename, $imageResponse->body());
                            return '/storage/portadas/' . $filename;
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::error("Error en fallback de TMDB para '{$titulo}': " . $e->getMessage());
            }
        }

        // 3. Fallback: Si ya era una URL local (/storage/...), devolverla intacta
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