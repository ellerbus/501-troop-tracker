<x-transmission-bar :id="'notifications'" />

<div id="notifications-form-container">

  <form method="POST"
        hx-trigger="submit"
        hx-post="{{ route('account.notifications-htmx') }}"
        hx-swap="outerHTML"
        hx-select="#notifications-form-container"
        hx-target="#notifications-form-container"
        hx-indicator="#transmission-bar-notifications"
        novalidate="novalidate">
    @csrf

    <h3>Website</h3>

    <x-input-container class="ps-5">
      <!-- efast -->
      <x-input-checkbox :property="'instant_notification'"
                        :label="'Instant Event Notification'"
                        :value="1"
                        :checked="$instant_notification" />
    </x-input-container>

    <x-input-container class="ps-5">
      <!-- econfirm -->
      <x-input-checkbox :property="'attendance_notification'"
                        :label="'Confirm Attendance Notification'"
                        :value="1"
                        :checked="$attendance_notification" />
    </x-input-container>

    <x-input-container class="ps-5">
      <!-- ecommandnotify -->
      <x-input-checkbox :property="'command_staff_notification'"
                        :label="'Command Staff Notifications'"
                        :value="1"
                        :checked="$command_staff_notification" />
    </x-input-container>

    <h3>Squads / Clubs</h3>
    <p>
      <i>Note: Events are categorized by 501st squad territory. To receive event notifications for a particular area,
        ensure you subscribed to the appropriate squad(s). Club notifications are used in command staff e-mails, to send
        command staff information on trooper milestones based on squad or club.</i>
    </p>

    @foreach ($clubs as $club)
    <x-input-container class="ps-5">
      <x-input-checkbox :property="'clubs.' . $club->id . '.notification'"
                        :label="$club->name"
                        :value="1"
                        :checked="$club->selected" />
      @foreach ($club->squads as $squad)
      <x-input-container class="ps-5">
        <x-input-checkbox :property="'squads.' . $squad->id . '.notification'"
                          :label="$squad->name"
                          :value="1"
                          :checked="$squad->selected"
                          data-club-id="$club->id" />
      </x-input-container>
      @endforeach
    </x-input-container>
    @endforeach

    <x-submit-container>
      <x-submit-button>
        Save
      </x-submit-button>
    </x-submit-container>
  </form>
</div>