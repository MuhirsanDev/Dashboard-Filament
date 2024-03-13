@php
    $data = $this->getData();
@endphp
<x-filament::page>
    <x-filament::card>
        <div class="container mx-auto">
            <h1 class='p-2'>{{ $data->title }}</h1>
            <hr>
            <div class='mt-4 p-2 rounded-2xl'>
                    {!! $data->content !!}
            </div>
        </div>
    </x-filament::card>
</x-filament::page>
