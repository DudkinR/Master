@extends('layouts.app')
@section('title', 'Magazine')
@section('content')
<div class="container">
     <h3 class="mb-4">Журнал оперативного обліку завдань</h3>


                <hr>
                <h6>Хронологія дій:</h6>
                <table class="table table-sm table-bordered table-hover text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Дата</th>
                            <th>Час</th>
                            <th>Подія</th>
                            <th>Працівник</th>   
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentLogs->sortBy('created_at') as $log)                        
                            <tr>
                                <td>
                                    @php
                                    if (!isset($lastdate) || $lastdate!== \Carbon\Carbon::parse($log->created_at)->format('d.m.Y')) {
                                        $lastdate = \Carbon\Carbon::parse($log->created_at)->format('d.m.Y');
                                        echo $lastdate;
                                    }
                                    @endphp
                                </td>
                                <td>
                                    @php
                                    if (!isset($lasttime) || $lasttime!== \Carbon\Carbon::parse($log->created_at)->format('H:i:s')) {
                                        $lasttime = \Carbon\Carbon::parse($log->created_at)->format('H:i:s');
                                        echo $lasttime;

                                    }
                                    @endphp
                                </td>
                                <td>{{ ucfirst($log->comment) }}</td>
                                <td class="text-right">
                                    {{ $log->user->position ?? '—' }} -
                                    {{ $log->user->name ?? '—' }}


                                </td>
                              
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
 

@endsection

@section('scripts')
<script>

</script>
@endsection
