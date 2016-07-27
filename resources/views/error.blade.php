@if (count($errors) > 0)
    <div class="ui error message">
        <div class="ui container">
            <ul class="list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
