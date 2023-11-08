@extends('layouts.app')

@section('breadcrumbs')
	<li><span>خانه</span></li>
	<li><span>نتایج آزمون‌ها</span></li>
@endsection

@section('content')
	<div class="uk-grid" style="margin-bottom: 25px;">
		<div class="uk-width-1-2"></div>

		<div class="uk-width-1-2">
			<form method="POST">
				{{ csrf_field() }}
				<div style="float: left;">
					<label>جستجو</label>
					<input type="text" name="search_keyword" value="{{ request('search_keyword') }}" class="md-input" />
				</div>
			</form>
		</div>
	</div>
	<hr>
	<div class="uk-overflow-container">
		@if (count($records))
			<table class="uk-table uk-table-nowrap table_check">
				<thead>
					<tr>
						<th class="uk-width-2-10">نام</th>
						<th class="uk-width-1-10 uk-text-center">دسته‌بندی</th>
						<th class="uk-width-1-10 uk-text-center">تعداد سوال‌ها</th>
						<th class="uk-width-1-10 uk-text-center">تعداد شرکت کنندگان</th>
						<th class="uk-width-2-10 uk-text-center">عملیات</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($records as $record)
						<tr>
							<td>{{ $record->name }}</td>

							<td class="uk-text-center">{{ isset($record->category) ? $record->category->name : '-' }}</td>

							<td class="uk-text-center">{{ $record->questions()->count() }}</td>

							<td class="uk-text-center">{{ $record->submissions()->count() }}</td>

							<td class="uk-text-center">
								
								<?php if (\Auth::user()->group == 'instructor' || \Auth::user()->id == 1): ?>
									<?php if (\App\Exam::hasAnySubmission($record->id)): ?>
										<?php if (\App\Exam::hasAnyQuestionByType($record->id, ['multiple_choice', 'true_false'])): ?>
											<?php if (\App\Exam::hasAutomaticUncorrectedSubmission($record->id)): ?>

											<a class="md-btn md-btn-default md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="{{ URL::to('results/automaticcorrection') }}/{{ $record->id }}" data-uk-tooltip="{pos:'bottom'}" title="تصحیح اتوماتیک">
				                                <i class="fa fa-check" style="margin-left: 0;"></i>
				                            </a>

				                            <?php else: ?>

			                            	<a class="md-btn md-btn-success md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="{{ URL::to('results/automaticcorrection') }}/{{ $record->id }}" data-uk-tooltip="{pos:'bottom'}" title="تصحیح اتوماتیک">
				                                <i class="fa fa-check" style="margin-left: 0;"></i>
				                            </a>

		                            		<?php endif ?>
		                            	<?php endif ?>
		                            <?php endif ?>
	                            <?php endif ?>


	                            <?php if (\Auth::user()->group == 'instructor' || \Auth::user()->id == 1): ?>
		                            <?php if (\App\Exam::hasAnySubmission($record->id)): ?>
										<?php if (\App\Exam::hasAnyQuestionByType($record->id, ['text'])): ?>
											<?php if (\App\Exam::hasManualUncorrectedSubmission($record->id)): ?>

				                            <a class="md-btn md-btn-default md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="{{ URL::to('results/manualcorrection') }}/{{ $record->id }}" data-uk-tooltip="{pos:'bottom'}" title="تصحیح دستی">
				                                <i class="fa fa-pencil" style="margin-left: 0;"></i>
				                            </a>

				                            <?php else: ?>

				                            	<a class="md-btn md-btn-success md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="{{ URL::to('results/manualcorrection') }}/{{ $record->id }}" data-uk-tooltip="{pos:'bottom'}" title="تصحیح دستی">
				                                <i class="fa fa-pencil" style="margin-left: 0;"></i>
				                            </a>

		                            		<?php endif ?>
		                            	<?php endif ?>
		                            <?php endif ?>
	                            <?php endif ?>


	                            <?php if (\App\Exam::hasAnySubmission($record->id)): ?>
	                            	<?php if (( \App\Exam::hasAnyQuestionByType($record->id, ['multiple_choice', 'true_false']) && \App\Exam::hasAutomaticUncorrectedSubmission($record->id) ) || ( \App\Exam::hasAnyQuestionByType($record->id, ['text']) && \App\Exam::hasManualUncorrectedSubmission($record->id) )): ?>

		                            	<a class="md-btn md-btn-default md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="javascript:void(0)" data-uk-tooltip="{pos:'bottom'}" title="مشاهده نتایج" disabled="disabled">
			                                <i class="fa fa-graduation-cap" style="margin-left: 0;"></i>
			                            </a>

	                            	<?php else: ?>

	                            		<a class="md-btn md-btn-success md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="{{ URL::to('results/allresults') }}/{{ $record->id }}" data-uk-tooltip="{pos:'bottom'}" title="مشاهده نتایج">
		                                <i class="fa fa-graduation-cap" style="margin-left: 0;"></i>
		                            </a>

                            		<?php endif ?>
                        		<?php endif ?>


								<?php if (\Carbon\Carbon::now() > $record->end_time): ?>
										<a class="md-btn md-btn-success md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="{{ URL::to('results/answersheet') }}/{{ $record->id }}" data-uk-tooltip="{pos:'bottom'}" title="پاسخ نامه">
		                                <i class="fa fa-book" style="margin-left: 0;"></i>
								<?php endif ?>

							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@else
			هیچ موردی یافت نشد.
		@endif
	</div>
@endsection