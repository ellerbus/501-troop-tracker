<div id="trooper-approval-{{ $trooper->id }}"
     class="card h-100 shadow-sm">
  <div class="card-header text-uppercase">
    {{ $trooper->name }}
  </div>
  <div class="card-body">
    <dl class="row mb-0">
      <dt class="col-4">Email:</dt>
      <dd class="col-8">{{ $trooper->email }}</dd>
      <dt class="col-4">Phone:</dt>
      <dd class="col-8">{{ $trooper->phone ?? 'n/a' }}</dd>
      <dt class="col-4">Role:</dt>
      <dd class="col-8">{{ $trooper->membership_role }}</dd>
    </dl>
    <hr />
    <x-table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Organization</th>
        </tr>
      </thead>
      @foreach($trooper->organizations as $organization)
      <tr>
        <td>{{ $organization->name }}</td>
        <td>{{ $organization->identifier }}</td>
      </tr>
      <thead>
        <tr>
          <th>ID</th>
          <th>Organization</th>
        </tr>
      </thead>
      @foreach($trooper->organizations as $organization)
      <tr>
        <td>{{ $organization->name }}</td>
        <td>{{ $organization->identifier }}</td>
      </tr>
      @endforeach
      @endforeach
    </x-table>

  </div>
  <div class="card-footer d-flex justify-content-between">
    @if($trooper->isActive())
    <div class="w-100">
      <x-message type="success"
                 icon="fa-brands fa-empire"
                 class="w-100">
        Let the Trooping begin!
      </x-message>
    </div>
    @elseif($trooper->isDenied())
    <div class="w-100">
      <x-message type="danger">
        Denied Trooper Status
      </x-message>
    </div>
    @else
    <button class="btn btn-danger btn-sm"
            type="button"
            hx-post="{{ route('admin.troopers.deny-htmx', ['trooper' => $trooper]) }}"
            hx-swap="outerHTML"
            hx-select="#trooper-approval-{{ $trooper->id }}"
            hx-target="#trooper-approval-{{ $trooper->id }}"
            hx-indicator="#transmission-bar-approvals">
      Deny
    </button>
    <button class="btn btn-success btn-sm"
            type="button"
            hx-post="{{ route('admin.troopers.approve-htmx', ['trooper' => $trooper]) }}"
            hx-swap="outerHTML"
            hx-select="#trooper-approval-{{ $trooper->id }}"
            hx-target="#trooper-approval-{{ $trooper->id }}"
            hx-indicator="#transmission-bar-approvals">
      Approve
    </button>
    @endif
  </div>
</div>