<div>
    <select name="" id="">
        @foreach ($lists as $list)
            <option value="{{ $list->value }}">{{ $list->key }}</option>
        @endforeach
    </select>
</div>
