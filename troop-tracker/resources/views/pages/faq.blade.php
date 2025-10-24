@extends('layouts.base')
@section('current_page', 'faq')

@section('content')
@foreach ($videos as $key => $label)
<h3>{{ $label }}</h3>
<p>
  <iframe width="100%"
          height="315"
          src="https://www.youtube.com/embed/{{ $key }}"
          title="YouTube video player"
          frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen></iframe>
</p>
@endforeach

<h3>Troop Tracker Manual</h3>
<p>
  <a href="https://github.com/MattDrennan/501-troop-tracker/blob/master/manual/troop_tracker_manual.pdf"
     target="_blank">Click here to view PDF manual.</a>
</p>

<h3>I cannot login / I forgot my password</h3>
<p>
  The Troop Tracker has been integrated with the boards. You must use your <b>{{ config('forum.name') }}</b> boards
  username and passwordto login to Troop Tracker. To recover your password, use password recovery on the
  <b>{{ config('forum.name') }}</b> forum. If you
  continue to have issues logging into your account, your <b>{{ config('forum.name') }}</b> forum username may not
  match the Troop Tracker
  records. Contact the <b>{{ config('forum.name') }}</b> Webmaster or post a help thread on the forums to get this
  corrected.
</p>

<h3>I am missing troop data / My troop data is incorrect</h3>
<p>
  Please refer to your squad leader to get this corrected.
</p>

<h3>I am now a member of another club and need access to their costumes.</h3>
<p>
  Please refer to your squad / club leader to get added to the roster.
</p>

<h3>My costumes are not showing on my profile / I am missing a costume on my profile</h3>
<p>
  The troop tracker automatically scrapes several different club databases for your costume data. If your costume data
  is not showing, make sure your ID numbers and forum usernames are accurate. If the aforementioned information is
  correct, then refer to your squad / club leadership, as this data is missing on their end.
</p>

<h3>How do I know I confirmed a troop?</h3>
<p>
  The troop will be listed on your troop tracker profile, or under your stats on the troop tracker page. When you
  confirm a troop, your status will change from "Going" to "Attended".
</p>

<h3>I need a costume added to the troop tracker.</h3>
<p>
  Please notify your squad leader, or e-mail the Garrison Web Master directly. See below for e-mail.
</p>

<h3>I need information on joining the 501st Legion.</h3>
<p>
  <a href="https://databank.501st.com/databank/Join_Us"
     target="_blank">Click here to learn how to join.</a>
</p>

<h3>Contact Garrison Web Master</h3>
<p>
  If you have read and reviewed all the material above and are still experiencing issues, or have noticed a bug on the
  website, please <a href="mailto:{{ config('forum.webmaster') }}">send an e-mail here</a>.
</p>
@endsection