public function run(): void
{
    $salas = \App\Models\Sala::all();
    // Definimos las letras del abecedario para las filas
    $letras = range('A', 'Z');

    foreach ($salas as $sala) {
        $capacidad = $sala->capacidad; // Usamos tu campo real de la DB
        $asientosPorFila = 10; 
        $asientosCreados = 0;
        $filaIndex = 0;

        while ($asientosCreados < $capacidad) {
            $filaActual = $letras[$filaIndex];
            
            for ($num = 1; $num <= $asientosPorFila; $num++) {
                if ($asientosCreados >= $capacidad) break;

                \App\Models\Asiento::create([
                    'sala_id' => $sala->id,
                    'fila'    => $filaActual,
                    'numero'  => $num,
                    'tipo'    => ($filaIndex >= 5) ? 'VIP' : 'Tradicional', // Filas F en adelante son VIP
                ]);
                $asientosCreados++;
            }
            $filaIndex++;
        }
    }
}