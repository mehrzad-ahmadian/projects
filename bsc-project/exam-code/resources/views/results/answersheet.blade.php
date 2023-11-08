@extends('layouts.app')

@section('breadcrumbs')
	<li><span>خانه</span></li>
	<li><span>نتایج آزمون‌ها</span></li>
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

		<hr style="margin: 20px 0;">
		<div class="uk-overflow-container" style="padding: 0 10px;">

			@if (\Carbon\Carbon::now() < $exam->end_time)

				<div class="alert alert-danger">
					کاربر گرامی، هنوز زمان آزمون به پایان نرسیده است
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
	                                    	disabled
	                                    	<?php if ($question->multiple_choice_correct_answer == 1): ?>checked<?php endif ?>>
	                                    <label for="exam_question<?= $question->id ?>_choice1" class="inline-label">{{ $question->choice_one }}</label>
	                                </p>
	                                <p>
	                                    <input 
	                                    	id="exam_question<?= $question->id ?>_choice2" 
	                                    	type="radio" 
	                                    	name="exam_question[<?= $question->id ?>]" 
	                                    	value="2"
	                                    	disabled
	                                    	<?php if ($question->multiple_choice_correct_answer == 2): ?>checked<?php endif ?>>
	                                    <label for="exam_question<?= $question->id ?>_choice2" class="inline-label">{{ $question->choice_two }}</label>
	                                </p>
	                                <p>
	                                    <input 
	                                    	id="exam_question<?= $question->id ?>_choice3" 
	                                    	type="radio" 
	                                    	name="exam_question[<?= $question->id ?>]" 
	                                    	value="3"
	                                    	disabled
	                                    	<?php if ($question->multiple_choice_correct_answer == 3): ?>checked<?php endif ?>>
	                                    <label for="exam_question<?= $question->id ?>_choice3" class="inline-label">{{ $question->choice_three }}</label>
	                                </p>
	                                <p>
	                                    <input 
	                                    	id="exam_question<?= $question->id ?>_choice4" 
	                                    	type="radio" 
	                                    	name="exam_question[<?= $question->id ?>]" 
	                                    	value="4"
	                                    	disabled
	                                    	<?php if ($question->multiple_choice_correct_answer == 4): ?>checked<?php endif ?>>
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
	                                    	disabled
	                                    	<?php if ($question->true_false_correct_answer == true): ?>checked<?php endif ?>>
	                                    <label for="exam_question<?= $question->id ?>_true" class="inline-label">بلی</label>
	                                </p>
	                                <p>
	                                    <input
	                                    	id="exam_question<?= $question->id ?>_false"  
	                                    	type="radio"
	                                    	name="exam_question[<?= $question->id ?>]" 
	                                    	value="false"
	                                    	disabled
	                                    	<?php if ($question->true_false_correct_answer == false): ?>checked<?php endif ?>>
	                                    <label for="exam_question<?= $question->id ?>_false" class="inline-label">خیر</label>
	                                </p>
								</div>
								<?php break ?>

							<?php case 'text': ?>
								<label>{{ $questionCounter }}- {{ $question->title }}</label>
								<div class="uk-width-medium-1">
									<div class="uk-form-row md-card">
										<textarea class="md-input" rows="3" name="exam_question[<?= $question->id ?>]" disabled><?= $question->text_correct_answer ?></textarea>
									</div>
								</div>
								<?php break ?>

					<?php endswitch ?>

					<hr style="margin: 20px 0px; ">
					<?php $questionCounter++ ?>

				@endforeach
			@endif

        </div>
	<form>
@endsection