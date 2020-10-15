@extends('character.layout', ['isMyo' => $character->is_myo_slot])

@section('profile-title') Editing {{ $character->fullName }}'s Profile @endsection

@section('meta-img') {{ $character->image->thumbnailUrl }} @endsection

@section('profile-content')
@if(!$character->is_myo_slot && $character->category->masterlist_sub_id != 0 && $character->category->sublist->show_main == 0)
{!! breadcrumbs([($character->is_myo_slot ? 'MYO Slot Masterlist' : $character->category->sublist->name.' Masterlist') => ($character->is_myo_slot ? 'myos' : 'sublist/'.$character->category->sublist->key), $character->fullName => $character->url,  'Editing Profile' => $character->url . '/profile/edit']) !!}
@else
{!! breadcrumbs([($character->is_myo_slot ? 'MYO Slot Masterlist' : 'Character Masterlist') => ($character->is_myo_slot ? 'myos' : 'masterlist'), $character->fullName => $character->url,  'Editing Profile' => $character->url . '/profile/edit']) !!}
@endif

@include('character._header', ['character' => $character])

@if($character->user_id != Auth::user()->id)
    <div class="alert alert-warning">
        You are editing this character as a staff member.
    </div>
@endif

{!! Form::open(['url' => $character->url . '/profile/edit']) !!}
@if(!$character->is_myo_slot)
    <div class="form-group">
        {!! Form::label('name', 'Name') !!}
        {!! Form::text('name', $character->name, ['class' => 'form-control']) !!}
    </div>
@endif
<div class="form-group">
    {!! Form::label('text', 'Profile Content') !!}
    {!! Form::textarea('text', $character->profile->text, ['class' => 'wysiwyg form-control']) !!}
</div>

@if($character->user_id == Auth::user()->id)
    @if(!$character->is_myo_slot)
        <div class="form-group">
            {!! Form::label('is_gift_art_allowed', 'Allow Gift Art', ['class' => 'form-check-label mb-3']) !!} {!! add_help('This will place the character on the list of characters that can be drawn for gift art. This does not have any other functionality, but allow users looking for characters to draw to find your character easily.') !!}
            {!! Form::select('is_gift_art_allowed', [0 => 'No', 1 => 'Yes', 2 => 'Ask First'], $character->is_gift_art_allowed, ['class' => 'form-control user-select']) !!}
        </div>
    @endif
    @if($character->is_tradeable ||  $character->is_sellable)
        <div class="form-group disabled">
            {!! Form::checkbox('is_trading', 1, $character->is_trading, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
            {!! Form::label('is_trading', 'Up For Trade', ['class' => 'form-check-label ml-3']) !!} {!! add_help('This will place the character on the list of characters that are currently up for trade. This does not have any other functionality, but allow users looking for trades to find your character easily.') !!}
        </div>
    @else 
        <div class="alert alert-secondary">Cannot be set to "Up for Trade" as character cannot be traded or sold.</div>
    @endif
@endif
@if($character->user_id != Auth::user()->id)
    <div class="form-group">
        {!! Form::checkbox('alert_user', 1, true, ['class' => 'form-check-input', 'data-toggle' => 'toggle', 'data-onstyle' => 'danger']) !!}
        {!! Form::label('alert_user', 'Notify User', ['class' => 'form-check-label ml-3']) !!} {!! add_help('This will send a notification to the user that their character profile has been edited. A notification will not be sent if the character is not visible.') !!}
    </div>
@endif
<div class="text-right">
    {!! Form::submit('Edit Profile', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}

@endsection