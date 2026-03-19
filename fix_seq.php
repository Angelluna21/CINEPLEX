<?php
use Illuminate\Support\Facades\DB;

$maxId = DB::table('users')->max('id') ?? 0;
DB::statement("SELECT setval('users_id_seq', ?, true)", [$maxId]);

echo "Sequence updated to max id: " . $maxId . "\n";
