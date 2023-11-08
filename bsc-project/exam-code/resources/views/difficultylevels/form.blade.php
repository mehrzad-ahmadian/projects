@extends('layouts.app')

@section('breadcrumbs')
	<li><span>خانه</span></li>
	<li><span>درجه‌های سختی سوال‌ها</span></li>
@endsection

@section('content')
	<form method="post">
		{{ csrf_field() }}
		<h3 class="heading_a">اطلاعات درجه‌ سختی سوال‌ها</h3>
		<hr style="margin: 20px 0 25px 0;">
		<div class="uk-grid" data-uk-grid-margin>
			<div class="uk-width-medium-1-2">
				<div class="uk-form-row md-card">
					<label>* نام</label>
					<input type="text" name="name" value="{{ old('name') }}{{ (isset($record) && !old('name')) ? $record->name : '' }}" class="md-input" />
				</div>
			</div>
		</div>
		<hr style="margin: 25px 0 15px 0">
		<div class="uk-grid">
			<div class="uk-width-1-7">
				<button type="submit" href="#" class="md-btn md-btn-success">ذخیره</button>
				<a href="{{ URL::to('difficultylevels') }}"><button type="button" class="md-btn md-btn-default" style="margin-right: 10px;">انصراف</button></a>
			</div>
		</div>
	<form>
@endsection