<!-- Troop Breakdown -->
<div class="card mb-4">
  <div class="card-header">Troop Breakdown</div>
  <div class="card-body">
    <div class="row text-center">
      @forelse($total_troops_club as $troop)
      <div class="col">
        {{ $troop->name }}
        <br>
        <strong>
          <x-number-format :value="$troop->troop_count" />
        </strong>
      </div>
      @empty
      <div class="col">
        No Troops Yet
      </div>
      @endforelse
    </div>
  </div>
</div>