@php
    $data = $this->getData();
@endphp
<x-filament::page>
    <x-filament::card>
        <div class='title'>
            {{ $data['name'] }}
        </div>
        <div class="content">
            <ul>
                <li>Email : {{ $data['email'] }}</li>
                <li>Roles : {{ $data['roles'][0]['name'] }}</li>
            </ul>
        </div>
    </x-filament::card>
</x-filament::page>

<style>
    .title{
        font-size:30px;
    }
    .content{
        margin:30px;
    }
    .content ul{
        list-style-type: circle;
    }
    .content li{
        padding:5px;
        font-size: 20px;
        border-bottom-style: dot;
        border-bottom-color: red;
    }
</style>
