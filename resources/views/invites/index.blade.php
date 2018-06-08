@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <h3>
                Your Invite Codes
            </h3>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <p>Use invite codes to invite other users to Mattbox.</p>
            <p>You currently have <strong>{{ Auth::user()->invite_codes }}</strong> invite {{ ((Auth::user()->invite_codes == 1) ? 'code' : 'codes') }} remaining.</p>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Date Created</th>
                        <th>Used By</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @if (count($invites) > 0)
                        @foreach ($invites as $invite)
                            <tr>
                                @if ($invite->used === 0)
                                    <td class="invite-unused">
                                        <i class="far fa-times"></i>
                                    </td>
                                @else
                                    <td class="invite-used">
                                        <i class="far fa-check"></i>
                                    </td>
                                @endif
                                <td>{{ $invite->id }}</td>
                                <td>{{ $invite->date_created }}</td>
                                <td>{{ $invite->invitee_name }}</td>
                                <td>
                                    <input type="text" class="form-control" value="{{ route('invites.accept', [$invite->code]) }}" readonly>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">No invites found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    @if ($canCreate)
        <div class="row">
            <div class="col">
                <a href="{{ route('invites.create') }}" class="btn btn-primary form-control">
                    <i class="far fa-user-plus"></i>
                    Create New Invite
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
