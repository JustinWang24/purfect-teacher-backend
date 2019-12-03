<p class="text-center">
    <button class="btn btn-default" v-on:click="showEdit({{ $id }})">
        @php
$a = str_split($name, 3);
        echo implode('<br>',$a);
        @endphp
    </button>
</p>