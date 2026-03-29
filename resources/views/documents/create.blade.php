<x-app-layout>
    @section('title', ' - Ajouter un document')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ajouter un document
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ url('/documents') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="parcours">Parcours :</label>
                        <select name="parcours_id" id="parcours" class="border rounded p-2 w-full" required>
                            <option value="">Selectionner un parcours</option>
                            @foreach($parcours as $p)
                                <option value="{{ $p->id }}" @selected(old('parcours_id') == $p->id)>{{ $p->nom }}</option>
                            @endforeach
                        </select>
                        @error('parcours_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="niveau">Annee :</label>
                        <select
                            name="niveau_id"
                            id="niveau"
                            class="border rounded p-2 w-full"
                            data-selected="{{ old('niveau_id') }}"
                            required
                        >
                            <option value="">Selectionner une annee</option>
                            @foreach($annees as $a)
                                <option value="{{ $a->id }}" data-parcours-id="{{ $a->parcours_id }}">
                                    {{ $a->nom }} ({{ $a->parcours ? $a->parcours->nom : 'Parcours non defini' }})
                                </option>
                            @endforeach
                        </select>
                        @error('niveau_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="titre">Titre :</label>
                        <input type="text" name="titre" id="titre" class="border rounded p-2 w-full" required>
                    </div>

                    <div class="mb-4">
                        <label for="description">Description :</label>
                        <input type="text" name="description" id="description" class="border rounded p-2 w-full" required>
                    </div>

                    <div class="mb-4">
                        <label for="fichier" class="form-label">Fichier</label>
                        <input type="file" class="border rounded p-2 w-full" id="fichier" name="fichier" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg,.webp" required>
                        @error('fichier')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <p class="text-muted mb-4">
                        Taille maximale : 5 Mo. Si le fichier depasse 500 Ko, une compression lossless est tentee automatiquement.
                    </p>

                    <button type="submit" class="bg-blue-500 text-white px-12 py-3 rounded">
                        Ajouter
                    </button>

                    <button>
                        <a href="{{ route('documents.index') }}" class="bg-gray-500 text-white px-12 py-3 rounded">Annuler</a>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const parcoursSelect = document.getElementById('parcours');
            const niveauSelect = document.getElementById('niveau');

            if (!parcoursSelect || !niveauSelect) {
                return;
            }

            const defaultPlaceholder = 'Selectionner une annee';
            const emptyPlaceholder = 'Aucune annee pour ce parcours';
            const originalOptions = Array.from(niveauSelect.querySelectorAll('option[data-parcours-id]'))
                .map((option) => option.cloneNode(true));

            const oldNiveau = niveauSelect.dataset.selected || '';

            function renderNiveaux(useOldValue) {
                const selectedParcours = parcoursSelect.value;
                const previousValue = useOldValue ? oldNiveau : '';

                niveauSelect.innerHTML = '';

                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = defaultPlaceholder;
                niveauSelect.appendChild(placeholder);

                if (!selectedParcours) {
                    niveauSelect.disabled = true;
                    return;
                }

                const matchedOptions = originalOptions.filter(
                    (option) => option.dataset.parcoursId === selectedParcours
                );

                if (!matchedOptions.length) {
                    placeholder.textContent = emptyPlaceholder;
                    niveauSelect.disabled = true;
                    return;
                }

                matchedOptions.forEach((option) => {
                    niveauSelect.appendChild(option.cloneNode(true));
                });

                niveauSelect.disabled = false;

                if (previousValue && matchedOptions.some((option) => option.value === previousValue)) {
                    niveauSelect.value = previousValue;
                }
            }

            parcoursSelect.addEventListener('change', function () {
                renderNiveaux(false);
            });

            renderNiveaux(true);
        })();
    </script>
</x-app-layout>
