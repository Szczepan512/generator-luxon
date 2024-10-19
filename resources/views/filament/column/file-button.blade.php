<div>
    @if(json_decode($getState()))
        <?php
        $states = json_decode($getState(), 1);
        ksort($states);
            ?>
        <ul style="display:flex; gap:10px; justify-content:center;">

            @foreach ($states as $key => $state)
                <li><x-filament::link :href="$state" target="_blank">
                        <span style="text-transform: uppercase">{{$key}}</span>
                    </x-filament::link>
            @endforeach
        </ul>
    @endif
</div>