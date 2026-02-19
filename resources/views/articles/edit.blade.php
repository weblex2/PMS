@extends('layouts.pms')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-header" style="font-size: 1.75rem;">Artikel bearbeiten</h1>
        <a href="{{ route('articles.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Zurueck
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-success" style="border-color: #ff6b6b; color: #ff6b6b; background: rgba(255,107,107,0.2);">
            <strong>Fehler!</strong> Bitte ueberpruefen Sie Ihre Eingaben.
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('articles.update', $article->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="article_number" class="form-label">Artikelnummer *</label>
                            <input type="text" class="form-control" id="article_number" name="article_number" value="{{ old('article_number', $article->article_number) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label">Name *</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $article->name) }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Beschreibung</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $article->description) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category" class="form-label">Kategorie</label>
                            <input type="text" class="form-control" id="category" name="category" value="{{ old('category', $article->category) }}">
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Artikel speichern
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Preise Card -->
    @php
        $allPrices = $article->prices()->orderBy('valid_from', 'desc')->get();
    @endphp

    <div class="card" style="margin-top: 20px;">
        <div class="d-flex justify-content-between align-items-center mb-3" style="border-bottom: 1px solid #dee2e6; padding-bottom: 15px;">
            <h3 style="margin: 0; color: #28a745;"><i class="fa fa-tags"></i> Preise</h3>
            <button type="button" class="btn btn-success btn-sm" onclick="addNewPrice()">
                <i class="fas fa-plus"></i> Neuer Preis
            </button>
        </div>

        @if($allPrices->count() > 0)
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa;">
                    <th style="padding: 12px 15px; text-align: left; font-weight: 600; border-bottom: 2px solid #dee2e6;">Preis (EUR)</th>
                    <th style="padding: 12px 15px; text-align: left; font-weight: 600; border-bottom: 2px solid #dee2e6;">Typ</th>
                    <th style="padding: 12px 15px; text-align: left; font-weight: 600; border-bottom: 2px solid #dee2e6;">Gültig ab</th>
                    <th style="padding: 12px 15px; text-align: left; font-weight: 600; border-bottom: 2px solid #dee2e6;">Gültig bis</th>
                    <th style="padding: 12px 15px; text-align: center; font-weight: 600; border-bottom: 2px solid #dee2e6; width: 120px;">Aktionen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allPrices as $price)
                <tr class="price-row" style="border-bottom: 1px solid #dee2e6;" id="price-row-{{ $price->id }}">
                    <td style="padding: 10px 15px; font-weight: bold; color: #276749;" class="display-mode">{{ number_format($price->price, 2, ',', '.') }}</td>
                    <td style="padding: 10px 15px;" class="display-mode">
                        @if($price->price_type == 'selling')
                            <span style="background: #c6f6d5; color: #276749; padding: 4px 10px; border-radius: 20px; font-size: 12px;">Verkauf</span>
                        @elseif($price->price_type == 'purchase')
                            <span style="background: #bee3f8; color: #2b6cb0; padding: 4px 10px; border-radius: 20px; font-size: 12px;">Einkauf</span>
                        @else
                            <span style="background: #e2e8f0; color: #4a5568; padding: 4px 10px; border-radius: 20px; font-size: 12px;">Standard</span>
                        @endif
                    </td>
                    <td style="padding: 10px 15px;" class="display-mode">{{ $price->valid_from->format('d.m.Y') }}</td>
                    <td style="padding: 10px 15px;" class="display-mode">{{ $price->valid_until ? $price->valid_until->format('d.m.Y') : 'unbegrenzt' }}</td>
                    <td style="padding: 10px 15px; text-align: center;" class="display-mode">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="editPrice({{ $price->id }})">
                            <i class="fas fa-pencil"></i>
                        </button>
                    </td>
                    
                    <!-- Edit Mode -->
                    <td style="padding: 10px 15px; display: none;" class="edit-mode">
                        <input type="hidden" name="prices[{{ $price->id }}][id]" value="{{ $price->id }}">
                        <input type="number" step="0.01" min="0" name="prices[{{ $price->id }}][price]" value="{{ $price->price }}" class="form-control" style="font-weight: bold; color: #276749; width: 100px;" required>
                    </td>
                    <td style="padding: 10px 15px; display: none;" class="edit-mode">
                        <select name="prices[{{ $price->id }}][price_type]" class="form-control" style="width: 120px;">
                            <option value="selling" {{ $price->price_type == 'selling' ? 'selected' : '' }}>Verkauf</option>
                            <option value="purchase" {{ $price->price_type == 'purchase' ? 'selected' : '' }}>Einkauf</option>
                            <option value="standard" {{ $price->price_type == 'standard' || !$price->price_type ? 'selected' : '' }}>Standard</option>
                        </select>
                    </td>
                    <td style="padding: 10px 15px; display: none;" class="edit-mode">
                        <input type="date" name="prices[{{ $price->id }}][valid_from]" value="{{ $price->valid_from->format('Y-m-d') }}" class="form-control" style="width: 140px;" required>
                    </td>
                    <td style="padding: 10px 15px; display: none;" class="edit-mode">
                        <input type="date" name="prices[{{ $price->id }}][valid_until]" value="{{ $price->valid_until ? $price->valid_until->format('Y-m-d') : '' }}" class="form-control" style="width: 140px;">
                    </td>
                    <td style="padding: 10px 15px; text-align: center; display: none;" class="edit-mode">
                        <button type="button" class="btn btn-primary btn-sm" onclick="savePrice({{ $price->id }})">
                            <i class="fas fa-check"></i>
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="cancelEdit({{ $price->id }})">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state" style="padding: 40px; text-align: center; color: #718096;">
            <i class="fa fa-tag" style="font-size: 48px; margin-bottom: 15px;"></i>
            <h3>Keine Preise vorhanden</h3>
            <p>Erstellen Sie Ihren ersten Preis.</p>
        </div>
        @endif

        <!-- Hidden form for saving -->
        <form action="{{ route('articles.prices.bulkUpdate', $article->id) }}" method="POST" id="priceEditForm" style="display: none;">
            @csrf
            @method('PUT')
            <div id="priceEditData"></div>
        </form>
    </div>
</div>

<script>
    let editMode = false;

    function editPrice(id) {
        if (editMode) return;
        
        editMode = true;
        const row = document.getElementById('price-row-' + id);
        
        // Hide display mode, show edit mode
        row.querySelectorAll('.display-mode').forEach(el => el.style.display = 'none');
        row.querySelectorAll('.edit-mode').forEach(el => el.style.display = '');
    }

    function cancelEdit(id) {
        editMode = false;
        const row = document.getElementById('price-row-' + id);
        
        // Show display mode, hide edit mode
        row.querySelectorAll('.display-mode').forEach(el => el.style.display = '');
        row.querySelectorAll('.edit-mode').forEach(el => el.style.display = 'none');
    }

    function savePrice(id) {
        const row = document.getElementById('price-row-' + id);
        
        // Get values from edit mode inputs
        const price = row.querySelector('input[name="prices[' + id + '][price]"]').value;
        const priceType = row.querySelector('select[name="prices[' + id + '][price_type]"]').value;
        const validFrom = row.querySelector('input[name="prices[' + id + '][valid_from]"]').value;
        const validUntil = row.querySelector('input[name="prices[' + id + '][valid_until]"]').value;
        
        // Build form data
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');
        formData.append('single_update', 'true');
        formData.append('price_id', id);
        formData.append('price', price);
        formData.append('price_type', priceType);
        formData.append('valid_from', validFrom);
        formData.append('valid_until', validUntil);
        
        // Submit via fetch
        fetch('{{ route('articles.prices.bulkUpdate', $article->id) }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Fehler beim Speichern: ' + (data.message || 'Unbekannter Fehler'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Fehler beim Speichern');
        });
    }

    function addNewPrice() {
        const tbody = document.querySelector('tbody');
        const newRow = document.createElement('tr');
        newRow.className = 'price-row new-price-row';
        newRow.style = 'border-bottom: 1px solid #dee2e6; background: #f0fff4;';
        newRow.innerHTML = `
            <td style="padding: 10px 15px;" colspan="5">
                <div style="display: grid; grid-template-columns: 100px 120px 140px 140px auto; gap: 10px; align-items: center;">
                    <input type="number" step="0.01" min="0" name="new_price" placeholder="0.00" class="form-control" style="font-weight: bold; color: #276749;" required>
                    <select name="new_price_type" class="form-control">
                        <option value="selling">Verkauf</option>
                        <option value="purchase">Einkauf</option>
                        <option value="standard">Standard</option>
                    </select>
                    <input type="date" name="new_valid_from" class="form-control" required>
                    <input type="date" name="new_valid_until" class="form-control">
                    <div>
                        <button type="button" class="btn btn-primary btn-sm" onclick="saveNewPrice()">
                            <i class="fas fa-check"></i> Speichern
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="cancelNewPrice()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </td>
        `;
        tbody.insertBefore(newRow, tbody.firstChild);
    }

    function cancelNewPrice() {
        document.querySelector('.new-price-row').remove();
    }

    function saveNewPrice() {
        const row = document.querySelector('.new-price-row');
        const price = row.querySelector('input[name="new_price"]').value;
        const priceType = row.querySelector('select[name="new_price_type"]').value;
        const validFrom = row.querySelector('input[name="new_valid_from"]').value;
        const validUntil = row.querySelector('input[name="new_valid_until"]').value;
        
        if (!price || !validFrom) {
            alert('Bitte Preis und Gültig ab ausfüllen');
            return;
        }
        
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');
        formData.append('create_new', 'true');
        formData.append('price', price);
        formData.append('price_type', priceType);
        formData.append('valid_from', validFrom);
        formData.append('valid_until', validUntil);
        
        fetch('{{ route('articles.prices.bulkUpdate', $article->id) }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Fehler beim Erstellen: ' + (data.message || 'Unbekannter Fehler'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Fehler beim Erstellen');
        });
    }
</script>
@endsection
