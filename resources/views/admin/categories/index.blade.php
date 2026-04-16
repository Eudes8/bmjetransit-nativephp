@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Formulaire ajout --}}
        <div>
            <div class="bg-white rounded-xl border p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Nouvelle categorie</h3>
                <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nom</label>
                        <input type="text" name="nom" value="{{ old('nom') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                        @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Description</label>
                        <textarea name="description" rows="2"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Icone Font Awesome</label>
                        <input type="text" name="icone" value="{{ old('icone') }}" placeholder="fa-laptop"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <p class="text-xs text-gray-400 mt-1">Classe FA sans le prefixe fa-solid (ex: fa-laptop)</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Categorie parent (optionnel)</label>
                        <select name="parent_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">Aucune (categorie racine)</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-bmje-600 text-white py-2 rounded-lg text-sm hover:bg-bmje-700">Creer</button>
                </form>
            </div>
        </div>

        {{-- Liste --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-semibold text-gray-800">{{ $categories->count() }} categories</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($categories as $cat)
                        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-bmje-50 text-bmje-600 rounded-lg flex items-center justify-center">
                                    @if($cat->icone)
                                        <i class="fa-solid {{ $cat->icone }}"></i>
                                    @else
                                        <i class="fa-solid fa-folder"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $cat->nom }}</p>
                                    <p class="text-xs text-gray-400">{{ $cat->produits_count }} produit(s) @if($cat->description) - {{ Str::limit($cat->description, 60) }} @endif</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $cat->actif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $cat->actif ? 'Actif' : 'Inactif' }}
                                </span>
                                <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" class="inline"
                                      onsubmit="return confirm('Supprimer cette categorie ?')">
                                    @csrf @method('DELETE')
                                    <button class="text-gray-400 hover:text-red-500"><i class="fa-solid fa-trash text-xs"></i></button>
                                </form>
                            </div>
                        </div>
                        @foreach($cat->sousCategories as $sous)
                            <div class="px-6 py-3 pl-16 flex items-center justify-between hover:bg-gray-50 bg-gray-50/50">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-turn-up fa-rotate-90 text-gray-300 text-xs"></i>
                                    <span class="text-sm text-gray-700">{{ $sous->nom }}</span>
                                </div>
                                <form method="POST" action="{{ route('admin.categories.destroy', $sous) }}" class="inline"
                                      onsubmit="return confirm('Supprimer ?')">
                                    @csrf @method('DELETE')
                                    <button class="text-gray-400 hover:text-red-500"><i class="fa-solid fa-trash text-xs"></i></button>
                                </form>
                            </div>
                        @endforeach
                    @empty
                        <div class="px-6 py-8 text-center text-gray-400">Aucune categorie.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
