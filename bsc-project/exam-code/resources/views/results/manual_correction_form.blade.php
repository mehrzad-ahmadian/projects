<style type="text/css">
	.question-box {
	    padding: 15px 20px;
	    border: 2px solid gainsboro;
	    border-radius: 6px;
	    margin-bottom: 20px;
	}

	.main-question-title {
	    color: #0159a2;
	    font-size: 15px;
	    font-weight: 700;
	    margin: 0 0 10px 0;
	    line-height: 28px;
	}

	.main-question-answer {
		color: #484848;
	    margin: 0 0 15px 0;
	    background-color: #e6f4ff;
	    padding: 12px 15px;
	    border: 2px dashed #c1d4e4;
	    border-radius: 6px;
	    line-height: 20px;
	}

	.answerer-name {
	    font-size: 12px;
	    font-weight: 700;
	    margin: 0 0 10px 0;
	    line-height: 20px;
	}

	.answer-box {
	    margin: 0 0 15px 0;
	    padding: 12px 15px;
	    border: 2px solid #e0e0e0;
	    border-radius: 6px;
	}

	.answer {
	    color: #484848;
	    margin: 0 0 12px 0 !important;
	    background-color: #f8f8f8;
	    padding: 12px 15px;
	    border: 2px dashed #dedede;
	    border-radius: 6px;
	    line-height: 20px;
	    max-height: 250px;
	    overflow: auto;
	}
</style>

@extends('layouts.app')

@section('breadcrumbs')
	<li><span>خانه</span></li>
	<li><span>نتایج آزمون‌ها</span></li>
@endsection

@section('content')
	<form method="post">
		{{ csrf_field() }}
		<h3 class="heading_a">{{ $exam->name }}</h3>
		<hr style="margin: 20px 0 25px 0;">
		<div class="uk-overflow-container">

			@foreach ($questions as $question)
				<?php
					$questionPoints = $exam->getQuestionPoints($question->id);
					$submissions = $exam->submissions;
				?>

				<div class="question-box uk-width-medium-1">
					<label class="main-question-title">- <?= $question->title; ?> (بارم: <?= substr($questionPoints , 0 , strpos($questionPoints , '.') + 2) ?> نمره)</label>

					<?php if (! empty($question->text_correct_answer)): ?>
						<p class="main-question-answer">پاسخ صحیح: <?= nl2br($question->text_correct_answer) ?></p>
					<?php endif ?>

					<?php foreach ($submissions as $submission): ?>
						<?php
							$user = $submission->user;
							$correction = $submission->correction;
							$submissions = json_decode($submission->submissions, true);
						?>

						<div class="answer-box">
							<label class="answerer-name">فرد: <?= ! is_null($user) ? $user->name . ' ' . $user->lname : '-' ?></label>

								<?php if (is_null($submissions)): ?>
									<p class="answer">بی پاسخ</p>
								<?php elseif (array_key_exists($question->id, $submissions)): ?>
									<?php if (empty($submissions[$question->id])): ?>
										<p class="answer">بی پاسخ</p>
									<?php else: ?>
										<p class="answer">پاسخ کاربر: <?= nl2br($submissions[$question->id]) ?></p>
									<?php endif ?>
								<?php endif ?>

								<?php 
									if (isset($correction)) {
										$manualCorrection = json_decode($correction->manual_correction, true);
										$mark = $manualCorrection[$question->id];
									}
								?>

								<input 
									name="submission<?= $submission->id ?>_<?= $question->id ?>"
									id=""
									value="<?php if (! is_null($correction)): ?>{{ isset($mark) ? $mark : null }}<?php endif ?>"
							    	type="number"
							    	step="0.1"
							    	min="0"	
							    	max="<?= $questionPoints ?>"
							    	placeholder="نمره ..." 
							    	class="form-control answer-point" 
							    	autocomplete="off"
							    	style="width: 30%">


						</div>
					<?php endforeach ?>
				</div>

			@endforeach

        </div>
		<hr style="margin: 25px 0 15px 0">
		<div class="uk-grid">
			<div class="uk-width-1-7">
				<button type="submit" href="#" class="md-btn md-btn-success">ذخیره</button>
				<a href="{{ URL::to('results') }}"><button type="button" class="md-btn md-btn-default" style="margin-right: 10px;">انصراف</button></a>
			</div>
		</div>
	<form>
@endsection