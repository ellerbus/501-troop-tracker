<div id="profile-form-container">

  <form method="POST"
        hx-trigger="submit"
        hx-post="{{ route('admin.troopers.profile-htmx', ['trooper'=>$trooper]) }}"
        hx-swap="outerHTML"
        hx-select="#profile-form-container"
        hx-target="#profile-form-container"
        hx-indicator="#transmission-bar-trooper"
        novalidate="novalidate">
    @csrf

    <x-input-container>
      <x-label>
        Display Name:
      </x-label>
      <x-input-text :property="'name'"
                    :value="$trooper->name" />
    </x-input-container>

    <x-input-container>
      <x-label>
        Email:
      </x-label>
      <x-input-text :property="'email'"
                    :value="$trooper->email" />
    </x-input-container>

    <x-input-container>
      <x-label>
        Phone (Optional):
      </x-label>
      <x-input-text :property="'phone'"
                    :value="$trooper->phone" />
    </x-input-container>

    <x-input-container>
      <x-label>
        Status:
      </x-label>
      <x-input-select :property="'membership_status'"
                      :options="\App\Enums\MembershipStatus::toArray()"
                      :value="$trooper->membership_status->value" />
    </x-input-container>

    <x-input-container>
      <x-label>
        Role:
      </x-label>
      <x-input-select :property="'membership_role'"
                      :options="\App\Enums\MembershipRole::toArray()"
                      :value="$trooper->membership_role->value" />
      <x-input-help>
        If selected as {{ \App\Enums\MembershipRole::Admin->name }}, they have full control within the Command Staff.
        If selected as {{ \App\Enums\MembershipRole::Moderator->name }}, they have full control over their
        assigned organizations as {{ \App\Enums\MembershipRole::Moderator->name }}. A {{ \App\Enums\MembershipRole::Member->name }},
        can sign up for events provided they are {{ \App\Enums\MembershipStatus::Active->name }},
      </x-input-help>
    </x-input-container>

    <x-submit-container>
      <x-submit-button>
        Save
      </x-submit-button>
      <x-link-button-cancel :url="route('admin.troopers.display')" />
    </x-submit-container>
  </form>
</div>