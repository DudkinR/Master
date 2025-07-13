
@php
   use App\Models\User;

    $user = User::with(['roles', 'children'])->firstWhere('id', $userId);


    if (!$user) {
        echo '<li class="node unassigned">🔍 Користувача не знайдено</li>';
        return;
    }

    $name = $user->name ?? 'Unknown User';
    
    $roleName = optional($user->roles->first())->name ?? 'Unassigned';
    $icons = [
        'Boss' => '👑',
        'Master' => '👷',
        'Brigadir' => '🛠️',
        'Worker' => '🔧',
        'Unassigned' => '🔍',
    ];
    $ulex=0;
    @endphp
    @php
        if($user->roles->first()->name === "Master") $class_name ='master';
        elseif($user->roles->first()->name === "Boss") $class_name ='boss';
        elseif($user->roles->first()->name === "Brigadir") $class_name ='brigadier';
        else $class_name = 'worker';
    @endphp
                
<li class="node {{$class_name}}" data-id="{{ $user->id }}">
    
     <ul class="children list-unstyled" data-id="{{ $user->id }}">
        {{ $icons[$roleName] ?? '🔍' }} {{ $name }} 
    <br>
   <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-outline-secondary">
    <b>
        {{ $user->position }}
    </b>
    </a>
        @forelse ($user->children as $child)
            @include('users.partials.user-tree', ['parentId'=> $user->id, 'userId' => $child->id])
        @empty
            @if($user->roles->first()->name ==="Unassigned" || $user->roles->isEmpty()|| $user->roles->first()->name === "Worker")
            
            @else 
               <li class="unassigned"></li>
      
            @endif
        @endforelse
    </ul>
</li>
