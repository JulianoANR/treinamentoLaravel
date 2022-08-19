@extends('layouts.panel')
@section('title', $event->name)
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">Informações gerais</div>
                <div class="card-body">
                    <ul class="list-group text-center">
                        <li class="list-group-item">
                            <span class="font-weight-bold mb-1">Palestrante: </span>
                            <span>{{ $event->speaker_name }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="font-weight-bold mb-1">Início: </span>
                            {{ $event->start_date_formatted }}
                        </li>
                        <li class="list-group-item">
                            <span class="font-weight-bold mb-1">Fim: </span>
                            {{ $event->end_date_formatted }}
                        </li>
                        <li class="list-group-item">
                            <span class="font-weight-bold mb-1">Público-alvo: </span>
                            <span>{{ $event->target_audience }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header bg-primary text-white">Participantes</div>
        <div class="card-body">
            <form method="POST" action="{{ route('organization.events.subscriptions.store', '$event->id') }}">
                @csrf
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <select class="form-control" name="user_id">
                            <option value="">Selecione</option>
                            @foreach ($allParticipantsUser as $user)
                                <option value="$user->id">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-lg-2">
                        <button type="submit" class="btn btn-success">Incluir</button>
                    </div>
                </div>
            </form>
            <table class="table bg-white mt-3" aria-label="tabela participantes">
                <thead>
                    <th>Nome</th>
                    <th class="text-right">Ações</th>
                </thead>
                <tbody>
                    @foreach ($event->users as $user)
                        <tr>
                            <td>
                                {{ $user->name }}
                            <td class="text-right">
                                <div class="d-flex align-items-centes justify-contentend">
                                    @if ($eventStartDateHasPassed)
                                        <form
                                            action="{{ route('organization.events.presences', [
                                                'event' => $event->id,
                                                'user' => $user->id,
                                            ]) }}"
                                            method="post">
                                            @csrf
                                            <button
                                                class="btn btn-sm mr-2 {{ $user->pivot->present ? 'btn-danger' : 'btn-success' }}">
                                                {{ $user->pivot->present ? 'Remover presença' : 'Assinar presença' }}
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if (!$eventEndDateHasPassed)
                                        <form
                                            action="{{ route('organization.events.subscription.destroy', [
                                                'event' => $event->id,
                                                'user' => $user->id,
                                            ]) }}"
                                            method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Remover inscrição</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
