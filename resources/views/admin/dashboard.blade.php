@extends('layouts.app')

@section('title', 'Panel Administrativo - Cineplex')

@section('header-actions')
<div class="text-white flex items-center gap-4">
    <span class="font-roboto text-sm text-gray-300">
        <i class="bi bi-person-circle mr-1"></i> Empleado
    </span>
    <a href="/" class="text-red-400 hover:text-red-300 font-roboto text-sm transition flex items-center gap-1">
        <i class="bi bi-box-arrow-right"></i> Salir
    </a>
</div>
@endsection

@section('sidebar')
<nav class="flex flex-col gap-2 font-roboto h-full">
    <a href="/admin" class="flex items-center gap-3 text-white bg-gray-800/50 border border-gray-700 px-4 py-3 rounded-md hover:bg-gray-700 transition">
        <i class="bi bi-house-door text-cinecyan"></i> Inicio
    </a>

    <h3 class="text-gray-500 text-xs font-montserrat mt-6 mb-2 uppercase tracking-wider font-bold">Catálogos</h3>

    <a href="/admin/peliculas" class="flex items-center gap-3 text-gray-400 px-4 py-2 hover:bg-gray-800 hover:text-white rounded transition">
        <i class="bi bi-film"></i> Películas
    </a>
    <a href="#" class="flex items-center gap-3 text-gray-400 px-4 py-2 hover:bg-gray-800 hover:text-white rounded transition">
        <i class="bi bi-geo-alt"></i> Sucursales
    </a>
    <a href="#" class="flex items-center gap-3 text-gray-400 px-4 py-2 hover:bg-gray-800 hover:text-white rounded transition">
        <i class="bi bi-list-ul"></i> Salas
    </a>

    <h3 class="text-gray-500 text-xs font-montserrat mt-4 mb-2 uppercase tracking-wider font-bold">Operaciones</h3>
    <a href="#" class="flex items-center gap-3 text-gray-400 px-4 py-2 hover:bg-gray-800 hover:text-white rounded transition">
        <i class="bi bi-calendar-date text-cinemagenta"></i> Programar Funciones
    </a>

    <h3 class="text-gray-500 text-xs font-montserrat mt-4 mb-2 uppercase tracking-wider font-bold">Gerencia</h3>
    <a href="#" class="flex items-center gap-3 text-gray-400 px-4 py-2 hover:bg-gray-800 hover:text-white rounded transition">
        <i class="bi bi-person-gear"></i> Mantener Usuarios
    </a>
</nav>
@endsection

@section('content')
<div class="bg-cinecard p-8 rounded-lg shadow-lg border border-gray-800">
    <h2 class="text-3xl font-montserrat font-bold text-white mb-2">Resumen del Sistema</h2>
    <p class="text-gray-400 font-roboto mb-8 text-lg">Selecciona un módulo en el menú lateral para gestionar los registros.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#0B0F19] p-6 rounded-lg border-l-4 border-cinecyan shadow flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm font-roboto uppercase tracking-wide">Películas</p>
                <p class="text-white text-4xl font-montserrat font-bold mt-1">3</p>
            </div>
            <i class="bi bi-film text-4xl text-gray-700"></i>
        </div>

        <div class="bg-[#0B0F19] p-6 rounded-lg border-l-4 border-cinemagenta shadow flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm font-roboto uppercase tracking-wide">Sucursales</p>
                <p class="text-white text-4xl font-montserrat font-bold mt-1">4</p>
            </div>
            <i class="bi bi-geo-alt text-4xl text-gray-700"></i>
        </div>

        <div class="bg-[#0B0F19] p-6 rounded-lg border-l-4 border-green-500 shadow flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm font-roboto uppercase tracking-wide">Salas</p>
                <p class="text-white text-4xl font-montserrat font-bold mt-1">12</p>
            </div>
            <i class="bi bi-list-ul text-4xl text-gray-700"></i>
        </div>
    </div>
</div>
@endsection