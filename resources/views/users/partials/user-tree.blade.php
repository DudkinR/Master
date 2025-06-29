
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

<li class="node" data-id="{{ $user->id }}">
    {{ $icons[$roleName] ?? '🔍' }} {{ $name }}
     <ul class="children list-unstyled" data-id="{{ $user->id }}">
        @forelse ($user->children as $child)
            @include('users.partials.user-tree', ['parentId'=> $user->id, 'userId' => $child->id])
        @empty
            @if($user->roles->first()->name ==="Unassigned" || $user->roles->isEmpty()|| $user->roles->first()->name === "Worker")
           
            @else 
               
            <li class="node unassigned">🔍 Немає підлеглих</li>
            @endif
        @endforelse
    </ul>
</li>
