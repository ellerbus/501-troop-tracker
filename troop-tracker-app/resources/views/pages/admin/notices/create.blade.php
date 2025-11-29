@extends('layouts.base')

@section('content')

<x-transmission-bar :id="'notice'" />

<x-slim-container>

  <x-card :label="'Create Notice'">
    <form method="POST"
          novalidate="novalidate">
      @csrf

      <x-input-container>
        <x-label>
          Organization:
        </x-label>
        <x-input-picker :property="'organization_id'"
                        :route="'pickers.organization'"
                        :params="['moderated_only' => true]"
                        :text="$notice->organization->name ?? 'Everyone'"
                        :value="$notice->organization_id ?? -1" />
      </x-input-container>

      <x-input-container>
        <x-label>
          Name:
        </x-label>
        <x-input-text :property="'name'"
                      :value="$notice->name" />
      </x-input-container>

      <x-submit-container>
        <x-submit-button>
          Create
        </x-submit-button>
        <x-link-button-cancel :url="route('admin.notices.list', ['organization_id'=>$notice->organization_id])" />
      </x-submit-container>

    </form>
  </x-card>

</x-slim-container>

<x-modal-picker />

@endsection