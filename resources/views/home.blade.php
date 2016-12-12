@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-center text-uppercase">
                    <b>Lista de inscritos na 3ª fase</b>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="active">
                                    <th class="text-center text-uppercase" width="5%">Posição</th>
                                    <th class="text-center text-uppercase" width="5%">Inscritos</th>
                                    <th class="text-center text-uppercase" width="5%">Tipo</th>
                                    <th class="text-center text-uppercase" width="45%">Título</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($atividades as $atividade)
                                <tr class="bg-green">
                                    <th class="text-center">{{ $loop->iteration }}</th>
                                    <th class="text-center">{{ $atividade['subscribers'] }}</th>
                                    <th class="text-center">{{ $atividade['type'] }}</th>
                                    <th class="text-center">
                                        <a href="{{ $atividade['link'] }}" target="_blank">
                                            {{ $atividade['title'] }}
                                        </a>
                                    </th>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection