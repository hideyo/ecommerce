<ul class="nav nav-sidebar">

    <li>
        <a href="{!! URL::route('client.index', $client->id) !!}">
            Overview
        </a>
    </li>
    @if(isset($clientEdit))
    <li class="active">
    @else
    <li>
    @endif
        <a href="{{ URL::route('client.edit', $client->id) }}">
            <span class="visible-xs"><i class="entypo-gauge"></i></span>
            <span class="hidden-xs">Edit</span>
        </a>
    </li>

    @if(isset($clientActivate))

    <li class="active">
        <a href="{{ URL::route('client.activate', $client->id) }}">
            <span class="visible-xs"><i class="entypo-gauge"></i></span>
            <span class="hidden-xs">Activate</span>
        </a>
    </li>

    @endif

    @if(isset($clientDeActivate))

    <li class="active">
        <a href="{{ URL::route('client.deactivate', $client->id) }}">
            <span class="visible-xs"><i class="entypo-gauge"></i></span>
            <span class="hidden-xs">Deactivate</span>
        </a>
    </li>

    @endif

    <li>
        <a href="{!! URL::route('client.addresses.index', $client->id) !!}">
            <span class="visible-xs"><i class="entypo-gauge"></i></span>
            <span class="hidden-xs">Adressess</span>
        </a>
    </li>

    <li>
        <a href="{!! URL::route('client.order.index', $client->id) !!}">
            <span class="visible-xs"><i class="entypo-gauge"></i></span>
            <span class="hidden-xs">Orders ({!! $client->Orders()->count() !!})</span>
        </a>
    </li>
</ul>