    @props(['class' => ''])

    <div>
        <table class="table
            table-bordered table-hover align-middle text-nowrap {{ $class }}">
            {{ $head }}
            {{ $body }}
        </table>
    </div>
