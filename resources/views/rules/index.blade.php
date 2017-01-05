@extends('layouts.app')

@section('content')
<form>
    <h3>Trigger</h3>
    When
    <select name="action_thing">
        @foreach($modules as $module)
            @foreach($module->things as $thing)
                @if($thing->type==2)
                    <option
                    value="{{ $thing->id }}"
                    >{{ $thing->name }}</option>
                @endif
            @endforeach
        @endforeach
    </select>
    value
    <select name="action_match">
        <option value="=">Is equals to</option>
        <option value="<">Is less than</option>
        <option value=">">Is greater than</option>
    </select>
    <input type="number" name="abc" name="action_value" >

    <h3>Action</h3>
    <select>
        <option>turn on</option>
        <option>turn off</option>
    </select>
    <select>
        @foreach($modules as $module)
            @foreach($module->things as $thing)
                @if($thing->type==1)
                    <option
                            value="{{ $thing->id }}"
                    >{{ $thing->name }}</option>
                @endif
            @endforeach
        @endforeach
    </select>
    <button type="submit" class="btn btn-default">Submit</button>
</form>
@endsection
