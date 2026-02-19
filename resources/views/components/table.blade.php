<div class="table-container">
    @if($items->count() > 0)
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        @foreach($columns as $column)
                            <th class="{{ $column['class'] ?? '' }}">
                                @if(isset($column['sortable']))
                                    <a href="{{ route($route . '.index', ['sort' => $column['key'], 'order' => $sortKey === $column['key'] && $sortOrder === 'asc' ? 'desc' : 'asc']) }}" class="sortable-header">
                                        {{ $column['label'] }}
                                        @if($sortKey === $column['key'])
                                            <span class="sort-icon">{{ $sortOrder === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </a>
                                @else
                                    {{ $column['label'] }}
                                @endif
                            </th>
                        @endforeach
                        @if($actions)
                            <th class="text-right">Aktionen</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            @foreach($columns as $column)
                                <td class="{{ $column['class'] ?? '' }}">
                                    @if(isset($column['slot']))
                                        {{ $column['slot']($item) }}
                                    @elseif(isset($column['callback']))
                                        {{ $column['callback']($item) }}
                                    @else
                                        {{ $item->{$column['key']} ?? '' }}
                                    @endif
                                </td>
                            @endforeach
                            @if($actions)
                                <td class="text-right action-buttons">
                                    @if($actions['edit'] ?? true)
                                        <a href="{{ route($route . '.edit', $item->id) }}" class="btn btn-sm btn-icon btn-edit" title="Bearbeiten">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        </a>
                                    @endif
                                    @if($actions['delete'] ?? true)
                                        <form action="{{ route($route . '.destroy', $item->id) }}" method="POST" class="inline-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-delete" title="Löschen" onclick="return confirm('Wirklich löschen?');">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                    @if(isset($actions['custom']))
                                        @foreach($actions['custom'] as $action)
                                            <a href="{{ $action['url']($item) }}" class="btn btn-sm btn-icon {{ $action['class'] ?? '' }}" title="{{ $action['title'] }}">
                                                {{ $action['icon'] ?? '' }}
                                            </a>
                                        @endforeach
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($items instanceof \Illuminate\Contracts\Pagination\Paginator)
            <div class="pagination-wrapper">
                {{ $items->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </div>
            <p class="empty-message">{{ $emptyMessage ?? 'Keine Einträge gefunden.' }}</p>
            @if($createRoute)
                <a href="{{ route($createRoute) }}" class="btn btn-primary">
                    {{ $createLabel ?? 'Neuer Eintrag' }}
                </a>
            @endif
        </div>
    @endif
</div>
