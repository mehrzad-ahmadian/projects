@extends('layouts.app')

@section('breadcrumbs')
	<li><span>خانه</span></li>
	<li><span>آزمون‌ها</span></li>
@endsection

@section('content')
	<form method="post">
		{{ csrf_field() }}
		<h3 class="heading_a">اطلاعات آزمون</h3>
		<hr style="margin: 20px 0 25px 0;">
		<div class="uk-grid" data-uk-grid-margin>

			<div class="uk-width-medium-1-2">
				<div class="uk-form-row md-card">
					<label>* نام</label>
					<input type="text" name="name" value="{{ old('name') }}{{ (isset($record) && !old('name')) ? $record->name : '' }}" class="md-input " />
				</div>
			</div>

			<div class="uk-width-medium-1-2">
	        	<div class="md-card">
	        		<label>دسته‌بندی آزمون</label>
	                <div class="md-input-wrapper md-input-filled">
	                	<select name="category_id" id="category_id" class="md-input" data-uk-tooltip="{pos:'top'}">
	                		<option value="">- انتخاب نمایید -</option>
		                    @foreach ($categories as $i => $category)
		                    	<option value="{{ $i }}" {{ (old('category_id') == $i || (isset($record) && !old('category_id') && $record->category_id == $i)) ? 'selected="selected"' : '' }}>{{ $category }}</option>
		                    @endforeach
	                	</select>
	                	<span class="md-input-bar "></span>
	                </div>
	            </div>
	        </div>

	        <div class="uk-width-medium-1-2">
				<div class="uk-form-row md-card">
					<label>* نمره کل</label>
					<input type="number" min="1" name="total_score" value="{{ old('total_score') }}{{ (isset($record) && !old('total_score')) ? $record->total_score : '' }}" class="md-input" />
				</div>
			</div>

			<div class="uk-width-medium-1-2">
				<div class="uk-form-row md-card">
					<label>* مهلت پاسخ‌دهی (دقیقه)</label>
					<input type="number" min="1" name="duration" value="{{ old('duration') }}{{ (isset($record) && !old('duration')) ? $record->duration : '' }}" class="md-input" />
				</div>
			</div>

			<div class="uk-width-medium-1-2">
				<div class="uk-form-row md-card">
					<label>* زمان شورع</label>
					<input type="text" data-MdDateTimePicker="true" data-targetselector="#start_time" data-trigger="click" data-enabletimepicker="true" class="md-input label-fixed" id="start_time" name="start_time" value="{{ old('start_time') }}{{ (isset($record) && !old('start_time')) ? $record->start_time : '' }}" autocomplete="off" />
				</div>
			</div>

			<div class="uk-width-medium-1-2">
				<div class="uk-form-row md-card">
					<label>* زمان پایان</label>
					<input type="text" data-MdDateTimePicker="true" data-targetselector="#end_time" data-trigger="click" data-enabletimepicker="true" class="md-input label-fixed" id="end_time" name="end_time" value="{{ old('end_time') }}{{ (isset($record) && !old('end_time')) ? $record->end_time : '' }}" autocomplete="off" />
				</div>
			</div>

        </div>
		<hr style="margin: 25px 0 15px 0">
		<div class="uk-grid">
			<div class="uk-width-1-7">
				<button type="submit" href="#" class="md-btn md-btn-success">ذخیره</button>
				<a href="{{ URL::to('exams') }}"><button type="button" class="md-btn md-btn-default" style="margin-right: 10px;">انصراف</button></a>
			</div>
		</div>
	<form>
@endsection