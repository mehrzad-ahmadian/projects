@extends('layouts.app')

@section('breadcrumbs')
	<li><span>خانه</span></li>
	<li><span>کاربران</span></li>
@endsection

@section('content')
	<form method="post">
		{{ csrf_field() }}
		<h3 class="heading_a">اطلاعات کاربر</h3>
		<hr style="margin: 20px 0 25px 0;">
		<div class="uk-grid" data-uk-grid-margin>
			<div class="uk-width-medium-1-2">
				<div class="uk-form-row md-card">
					<label>نام</label>
					<input type="text" name="name" value="{{ old('name') }}{{ (isset($record) && !old('name')) ? $record->name : '' }}" class="md-input" />
				</div>
			</div>
			<div class="uk-width-medium-1-2">
				<div class="uk-form-row md-card">
					<label>نام خانوادگی</label>
					<input type="text" name="lname" value="{{ old('lname') }}{{ (isset($record) && !old('lname')) ? $record->lname : '' }}" class="md-input" />
				</div>
			</div>
			<div class="uk-width-medium-1-2">
				<div class="uk-form-row md-card">
					<label>ایمیل</label>
					<input type="text" name="email" value="{{ old('email') }}{{ (isset($record) && !old('email')) ? $record->email : '' }}" class="md-input" />
				</div>
			</div>
			<div class="uk-width-medium-1-2">
	        	<div class="md-card">
	        		<label>نوع پنل</label>
	                <div class="md-input-wrapper md-input-filled">
	                	<select name="group" id="group" class="md-input" data-uk-tooltip="{pos:'top'}">
	                    	<option value="learner" {{ (old('group') == 'learner' || (isset($record) && !old('group') && $record->group == 'learner')) ? 'selected="selected"' : '' }}>محصلی</option>
	                    	<option value="instructor" {{ (old('group') == 'instructor' || (isset($record) && !old('group') && $record->group == 'instructor')) ? 'selected="selected"' : '' }}>استادی</option>
	                	</select>
	                	<span class="md-input-bar "></span>
	                </div>
	            </div>
	        </div>
			<div class="uk-width-medium-1-2">
				<div class="uk-form-row md-card">
					<label>گذرواژه</label>
					<input type="password" name="password" class="md-input" />
				</div>
			</div>
			<div class="uk-width-medium-1-2">
				<div class="uk-form-row md-card">
					<label>تکرار گذرواژه</label>
					<input type="password" name="password_confirmation" class="md-input" />
				</div>
			</div>
		</div>
		<hr style="margin: 25px 0 15px 0">
		<div class="uk-grid">
			<div class="uk-width-1-7">
				<button type="submit" href="#" class="md-btn md-btn-success">ذخیره</button>
				<a href="{{ URL::to('users') }}"><button type="button" class="md-btn md-btn-default" style="margin-right: 10px;">انصراف</button></a>
			</div>
		</div>
	<form>
@endsection