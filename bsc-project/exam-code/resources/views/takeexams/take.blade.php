@extends('layouts.app')

@section('breadcrumbs')
	<li><span>خانه</span></li>
	<li><span>آزمون‌های درحال برگذاری</span></li>
@endsection

@section('content')
	<style type="text/css">
		label {
			margin: 0 !important;
		}

		.inline-label {
			padding-right: 5px;
			vertical-align: middle;
		}

		p {
		    margin: 0 0 5px;
		}

		.uk-width-medium-1 {
			margin-top: 10px;
		}
	</style>


	<form method="post">
		{{ csrf_field() }}

		@if ($remainingTime > 0)
		<h3 class="heading_a">{{ $exam->name }} <span style="float: left;">زمان باقی مانده: <span id="countdown-timer"></span></span></h3>
		@else
		<h3 class="heading_a">{{ $exam->name }}</h3>
		@endif

		<hr style="margin: 20px 0;">
		<div class="uk-overflow-container" style="padding: 0 10px;">

			@if ($remainingTime < 0)

				<div class="alert alert-danger">
					کاربر گرامی، زمان پاسخ‌دهی شما در این آزمون به اتمام رسیده ‌است
				</div>

			@else

				@foreach ($questions as $question)

					<?php switch ($question->type): 
							case 'multiple_choice': ?>
								<label>{{ $questionCounter }}- {{ $question->title }}</label>
								<div class="uk-width-medium-1">
									<p>
	                                    <input 
	                                    	id="exam_question<?= $question->id ?>_choice1" 
	                                    	type="radio" 
	                                    	name="exam_question[<?= $question->id ?>]" 
	                                    	value="1"
	                                    	<?php if (! is_null($submissions) && isset($submissions[$question->id]) && $submissions[$question->id] == 1): ?>checked<?php endif ?>>
	                                    <label for="exam_question<?= $question->id ?>_choice1" class="inline-label">{{ $question->choice_one }}</label>
	                                </p>
	                                <p>
	                                    <input 
	                                    	id="exam_question<?= $question->id ?>_choice2" 
	                                    	type="radio" 
	                                    	name="exam_question[<?= $question->id ?>]" 
	                                    	value="2"
	                                    	<?php if (! is_null($submissions) && isset($submissions[$question->id]) && $submissions[$question->id] == 2): ?>checked<?php endif ?>>
	                                    <label for="exam_question<?= $question->id ?>_choice2" class="inline-label">{{ $question->choice_two }}</label>
	                                </p>
	                                <p>
	                                    <input 
	                                    	id="exam_question<?= $question->id ?>_choice3" 
	                                    	type="radio" 
	                                    	name="exam_question[<?= $question->id ?>]" 
	                                    	value="3"
	                                    	<?php if (! is_null($submissions) && isset($submissions[$question->id]) && $submissions[$question->id] == 3): ?>checked<?php endif ?>>
	                                    <label for="exam_question<?= $question->id ?>_choice3" class="inline-label">{{ $question->choice_three }}</label>
	                                </p>
	                                <p>
	                                    <input 
	                                    	id="exam_question<?= $question->id ?>_choice4" 
	                                    	type="radio" 
	                                    	name="exam_question[<?= $question->id ?>]" 
	                                    	value="4"
	                                    	<?php if (! is_null($submissions) && isset($submissions[$question->id]) && $submissions[$question->id] == 4): ?>checked<?php endif ?>>
	                                    <label for="exam_question<?= $question->id ?>_choice4" class="inline-label">{{ $question->choice_four }}</label>
	                                </p>
								</div>
								<?php break ?>

							<?php case 'true_false': ?>
								<label>{{ $questionCounter }}- {{ $question->title }}</label>
								<div class="uk-width-medium-1">
									<p>
	                                    <input
	                                    	id="exam_question<?= $question->id ?>_true"  
	                                    	type="radio" 
	                                    	name="exam_question[<?= $question->id ?>]" 
	                                    	value="true"
	                                    	<?php if (! is_null($submissions) && isset($submissions[$question->id]) && $submissions[$question->id] == 'true'): ?>checked<?php endif ?>>
	                                    <label for="exam_question<?= $question->id ?>_true" class="inline-label">بلی</label>
	                                </p>
	                                <p>
	                                    <input
	                                    	id="exam_question<?= $question->id ?>_false"  
	                                    	type="radio"
	                                    	name="exam_question[<?= $question->id ?>]" 
	                                    	value="false"
	                                    	<?php if (! is_null($submissions) && isset($submissions[$question->id]) && $submissions[$question->id] == 'false'): ?>checked<?php endif ?>>
	                                    <label for="exam_question<?= $question->id ?>_false" class="inline-label">خیر</label>
	                                </p>
								</div>
								<?php break ?>

							<?php case 'text': ?>
								<label>{{ $questionCounter }}- {{ $question->title }}</label>
								<div class="uk-width-medium-1">
									<div class="uk-form-row md-card">
										<textarea class="md-input" rows="3" name="exam_question[<?= $question->id ?>]" value="{{ old('text_correct_answer') }}{{ (isset($record) && !old('text_correct_answer')) ? $record->text_correct_answer : '' }}"><?php if (! is_null($submissions) && isset($submissions[$question->id])): ?><?= $submissions[$question->id]; ?><?php endif ?></textarea>
									</div>
								</div>
								<?php break ?>

					<?php endswitch ?>

					<hr style="margin: 20px 0px; ">
					<?php $questionCounter++ ?>

				@endforeach
			@endif

        </div>
		<hr style="margin: 25px 0 15px 0">
		<div class="uk-grid">
			<div class="uk-width-1-7">
				@if ($remainingTime > 0)
					<button id="submitButton" type="submit" href="#" class="md-btn md-btn-success">ذخیره</button>
				@endif
				<a href="{{ URL::to('takeexams') }}"><button type="button" class="md-btn md-btn-default" style="margin-right: 10px;">بازکشت</button></a>
			</div>
		</div>
	<form>

		<script>
		    setInterval(function(){
		    	$('#submitButton').click();
		    }, 60000);

		    setTimeout(function(){
		    	$('#submitButton').click();
		    }, <?= ($remainingTime*1000) ?>);
		</script>

		<script>
			//countdown timer
			function startTimer(duration, display) {
			    var timer = duration, minutes, seconds;
			    setInterval(function () {
			        minutes = parseInt(timer / 60, 10);
			        seconds = parseInt(timer % 60, 10);

			        minutes = minutes < 10 ? "0" + minutes : minutes;
			        seconds = seconds < 10 ? "0" + seconds : seconds;

			        display.textContent = minutes + ":" + seconds;

			        if (--timer < 0) {
			            timer = duration;
			        }
			    }, 1000);
			}

			window.onload = function () {
			    var fiveMinutes = 60 * 5,
		        display = document.querySelector('#countdown-timer');
			    startTimer({{ $remainingTime }}, display);
			};
		</script>
@endsection