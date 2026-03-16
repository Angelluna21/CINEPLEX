<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast; // <-- Importante
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SeatSelected implements ShouldBroadcast // <-- Agregamos esto
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $funcionId;
    public $asientoId;
    public $usuarioId;
    public $status; // 'seleccionado', 'disponible', 'bloqueado'

    public function __construct($funcionId, $asientoId, $usuarioId, $status)
    {
        $this->funcionId = $funcionId;
        $this->asientoId = $asientoId;
        $this->usuarioId = $usuarioId;
        $this->status = $status;
    }

    // El canal debe ser específico de la función para que no se mezclen las salas
    public function broadcastOn(): array
    {
        return [
            new Channel('funcion.' . $this->funcionId),
        ];
    }

    // El nombre con el que el JavaScript escuchará el cambio
    public function broadcastAs()
    {
        return 'seat.updated';
    }
}