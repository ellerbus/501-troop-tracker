<x-card :label="'Troop Breakdown by Costume'">
  <div class="container">
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
      @forelse($total_troops_by_costume as $costume)
      <div class="col">
        <span class="badge bg-dark d-flex justify-content-between align-items-center w-100 px-2 py-2">
          <span>
            {{ $costume->club_costume->fullCostumeName() }}
          </span>
          <span class="text-end fw-bold">
            @if($costume->troop_count > 0)
            <x-number-format :value="$costume->troop_count"
                             :prefix="'#'" />
            @else
            <span class="text-muted">
              N/A
            </span>
            @endif
          </span>
        </span>
      </div>
      @empty
      <span class="badge bg-dark d-inline-flex align-items-center gap-2">
        No Troops ... Yet!
      </span>
      @endforelse
    </div>
  </div>
</x-card>