@php
    $user = $users->find($userId);
@endphp

<li class="node" data-id="{{ $user->id }}">
    @php
        $roleName = $user->roles->first()->name ?? null;
        $icons = [
            'Boss' => '👑',
            'Master' => '🛠️',
            'Brigadir' => '👷',
            'Worker' => '🔧',
        ];
    @endphp

    {{ $icons[$roleName] ?? '🔍' }} {{ $user->name }}

    <ul class="children list-unstyled" data-id="{{ $user->id }}">
        @forelse ($user->children as $child)
            @include('users.partials.user-tree', ['userId' => $child->id, 'users' => $users])
        @empty
          <!-- No children for this user -->
        @endforelse
    </ul>
</li>
