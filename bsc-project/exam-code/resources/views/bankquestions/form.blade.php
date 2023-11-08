@extends('layouts.app')

@section('breadcrumbs')
	<li><span>خانه</span></li>
	<li><span>بانک سوال‌ها</span></li>
@endsection

@section('content')
	<form method="post">
		{{ csrf_field() }}
		<h3 class="heading_a">اطلاعات سوال</h3>
		<hr style="margin: 20px 0 25px 0;">
		<div class="uk-grid" data-uk-grid-margin>

			<div class="uk-width-medium-1">
				<div class="uk-form-row md-card">
					<label>* عنوان سوال</label>
					<input type="text" name="title" value="{{ old('title') }}{{ (isset($record) && !old('title')) ? $record->title : '' }}" class="md-input " />
				</div>
			</div>

			<div class="uk-width-medium-1-2">
	        	<div class="md-card">
	        		<label>* نوع سوال</label>
	                <div class="md-input-wrapper md-input-filled">
	                	<select name="type" id="type" class="md-input" data-uk-tooltip="{pos:'top'}">
	                    	<option value="multiple_choice" {{ (old('type') == 'multiple_choice' || (isset($record) && !old('type') && $record->type == 'multiple_choice')) ? 'selected="selected"' : '' }}>چهار گزینه‌ای</option>
	                    	<option value="true_false" {{ (old('type') == 'true_false' || (isset($record) && !old('type') && $record->type == 'true_false')) ? 'selected="selected"' : '' }}>بلی / خیر</option>
	                    	<option value="text" {{ (old('type') == 'text' || (isset($record) && !old('type') && $record->type == 'text')) ? 'selected="selected"' : '' }}>متنی</option>
	                	</select>
	                	<span class="md-input-bar "></span>
	                </div>
	            </div>
	        </div>

	        <div class="uk-width-medium-1-2">
	        	<div class="md-card">
	        		<label>درجه سختی سوال</label>
	                <div class="md-input-wrapper md-input-filled">
	                	<select name="difficulty_level_id" id="difficulty_level_id" class="md-input" data-uk-tooltip="{pos:'top'}">
	                		<option value="">- انتخاب نمایید -</option>
		                    @foreach ($difficultyLevels as $i => $difficultyLevel)
		                    	<option value="{{ $i }}" {{ (old('difficulty_level_id') == $i || (isset($record) && !old('difficulty_level_id') && $record->difficulty_level_id == $i)) ? 'selected="selected"' : '' }}>{{ $difficultyLevel }}</option>
		                    @endforeach
	                	</select>
	                	<span class="md-input-bar "></span>
	                </div>
	            </div>
	        </div>

			<div class="uk-width-medium-1">
				<hr style="width: 100%; margin: 10px 0;">
				<h3 class="heading_a">اطلاعات سوال چهار گزینه‌ای</h3>
			</div>

			<div class="uk-width-medium-1-2">
				<div class="uk-form-row md-card">
					<label>* گزینه اول</label>
					<input type="text" name="choice_one" value="{{ old('choice_one') }}{{ (isset($record) && !old('choice_one')) ? $record->choice_one : '' }}" class="md-input " />
				</div>
			</div>

			<div class="uk-width-medium-1-2">
				<div class="uk-form-row md-card">
					<label>* گزینه دوم</label>
					<input type="text" name="choice_two" value="{{ old('choice_two') }}{{ (isset($record) && !old('choice_two')) ? $record->choice_two : '' }}" class="md-input " />
				</div>
			</div>

			<div class="uk-width-medium-1-2">
				<div class="uk-form-row md-card">
					<label>* گزینه سوم</label>
					<input type="text" name="choice_three" value="{{ old('choice_three') }}{{ (isset($record) && !old('choice_three')) ? $record->choice_three : '' }}" class="md-input " />
				</div>
			</div>

			<div class="uk-width-medium-1-2">
				<div class="uk-form-row md-card">
					<label>* گزینه چهارم</label>
					<input type="text" name="choice_four" value="{{ old('choice_four') }}{{ (isset($record) && !old('choice_four')) ? $record->choice_four : '' }}" class="md-input " />
				</div>
			</div>

			<div class="uk-width-medium-1-2">
	        	<div class="md-card">
	        		<label>* پاسخ صحیح سوال چهار گزینه‌ای</label>
	                <div class="md-input-wrapper md-input-filled">
	                	<select name="multiple_choice_correct_answer" id="multiple_choice_correct_answer" class="md-input" data-uk-tooltip="{pos:'top'}">
	                		<option value="">- انتخاب نمایید -</option>
	                    	<option value="1" {{ (old('multiple_choice_correct_answer') == 1 || (isset($record) && !old('multiple_choice_correct_answer') && $record->multiple_choice_correct_answer == 1)) ? 'selected="selected"' : '' }}>گزینه اول</option>
	                    	<option value="2" {{ (old('multiple_choice_correct_answer') == 2 || (isset($record) && !old('multiple_choice_correct_answer') && $record->multiple_choice_correct_answer == 2)) ? 'selected="selected"' : '' }}>گزینه دوم</option>
	                    	<option value="3" {{ (old('multiple_choice_correct_answer') == 3 || (isset($record) && !old('multiple_choice_correct_answer') && $record->multiple_choice_correct_answer == 3)) ? 'selected="selected"' : '' }}>گزینه سوم</option>
	                    	<option value="4" {{ (old('multiple_choice_correct_answer') == 4 || (isset($record) && !old('multiple_choice_correct_answer') && $record->multiple_choice_correct_answer == 4)) ? 'selected="selected"' : '' }}>گزینه چهارم</option>
	                	</select>
	                	<span class="md-input-bar "></span>
	                </div>
	            </div>
	        </div>


			<div class="uk-width-medium-1">
				<hr style="width: 100%; margin: 10px 0;">
				<h3 class="heading_a">اطلاعات سوال بلی / خیر</h3>
			</div>

			<div class="uk-width-medium-1-2">
	        	<div class="md-card">
	        		<label>* پاسخ صحیح سوال بلی / خیر</label>
	                <div class="md-input-wrapper md-input-filled">
	                	<select name="true_false_correct_answer" id="true_false_correct_answer" class="md-input" data-uk-tooltip="{pos:'top'}">
	                    	<option value="">- انتخاب نمایید -</option>
	                    	<option value="true" {{ (old('true_false_correct_answer') == 'true' || (isset($record) && !old('true_false_correct_answer') && $record->true_false_correct_answer == 'true')) ? 'selected="selected"' : '' }}>بلی</option>
	                    	<option value="false" {{ (old('true_false_correct_answer') == 'false' || (isset($record) && !old('true_false_correct_answer') && $record->true_false_correct_answer == 'false')) ? 'selected="selected"' : '' }}>خیر</option>
	                	</select>
	                	<span class="md-input-bar "></span>
	                </div>
	            </div>
	        </div>


			<div class="uk-width-medium-1">
				<hr style="width: 100%; margin: 10px 0;">
				<h3 class="heading_a">اطلاعات سوال متنی</h3>
			</div>

			<div class="uk-width-medium-1">
				<div class="uk-form-row md-card">
					<label>* پاسخ صحیح سوال متنی</label>
					<textarea class="md-input" rows="3" name="text_correct_answer" value="{{ old('text_correct_answer') }}{{ (isset($record) && !old('text_correct_answer')) ? $record->text_correct_answer : '' }}">{{ old('text_correct_answer') }}{{ (isset($record) && !old('text_correct_answer')) ? $record->text_correct_answer : '' }}</textarea>
				</div>
			</div>	        

        </div>
		<hr style="margin: 25px 0 15px 0">
		<div class="uk-grid">
			<div class="uk-width-1-7">
				<button type="submit" href="#" class="md-btn md-btn-success">ذخیره</button>
				<a href="{{ URL::to('bankquestions') }}"><button type="button" class="md-btn md-btn-default" style="margin-right: 10px;">انصراف</button></a>
			</div>
		</div>
	<form>
@endsection