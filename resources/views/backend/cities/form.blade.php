<div class="row">
    <div class="ibox">
        <div class="ibox-body">
            <div class="tab-content">
                @foreach ($languages as $lang)
                    <div class="form-group">
                        {{ Form::label('slug', 'Title '.$lang->locale) }}
                        {{ Form::text($lang->locale.'[title]', $contents[$lang->locale]->title, ['class' => $errors->has('slug') ? 'form-control is-invalid' : 'form-control']) }}
                        @if($errors->has('slug'))
                            <span class="invalid-feedback">{{ $errors->first('title') }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="form-group">
                {{ Form::label('slug', 'Slug') }}
                {{ Form::text('slug', $city->slug, ['class' => $errors->has('slug') ? 'form-control is-invalid' : 'form-control']) }}
                @if($errors->has('slug'))
                    <span class="invalid-feedback">
                {{ $errors->first('slug') }}
            </span>
                @endif
            </div>
            <div class="form-group">
                {{ Form::label('code', 'Code') }}
                {{ Form::text('code', $city->code, ['class' => $errors->has('code') ? 'form-control is-invalid' : 'form-control']) }}
                @if($errors->has('code'))
                    <span class="invalid-feedback">
                {{ $errors->first('code') }}
            </span>
                @endif
            </div>
            <div class="form-group">
                {{ Form::label('status', 'Status') }}
                {{ Form::select('status', [true => 'Yes', false => 'No'], $city->status, ['class' => $errors->has('status') ? 'form-control is-invalid' : 'form-control']) }}
                @if($errors->has('status'))
                    <span class="invalid-feedback">
                {{ $errors->first('status') }}
            </span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="ibox">
    <div class="ibox-footer pl-3">
        <button class="btn btn-success" type="submit"> Save</button>
        <a class="btn btn-warning" href="/admin/posts"> Cancel</a>
    </div>
</div>
