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

			@if (count($corrections))
				<table class="uk-table uk-table-nowrap table_check">
					<thead>
						<tr>
							<th class="uk-width-3-10">فرد</th>
			                <th class="uk-width-1-10">تستی صحیح</th>
			                <th class="uk-width-1-10">تستی غلط</th>
			                <th class="uk-width-1-10">تستی بی‌‌پاسخ</th>
			                <th class="uk-width-1-10">نمره از</th>
			                <th class="uk-width-1-10">نمره تستی</th>
			                <th class="uk-width-1-10">نمره تشریحی</th>
			                <th class="uk-width-1-10">نمره نهایی</th>
			                <th class="uk-width-1-10">میانگین کل</th>
			                <th class="uk-width-1-10">رتبه</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($corrections as $correction)
							<?php 
								$submission = $correction->submission;
								$user = $submission->user;
								$average = substr($corrections->avg('total_score') , 0 , strpos($corrections->avg('total_score') , '.') + 3);

								$submissions = $exam->submissions;
				        		$submissionIds = [];
				        		foreach ($submissions as $submission) {
				        			$submissionIds[] = $submission->id;
				        		}
								$totalCorrectionCount = \App\Correction::whereIn('submission_id', $submissionIds)->count();
								$lowerRanks = \App\Correction::whereIn('submission_id', $submissionIds)->where('total_score', '<', $correction->total_score)->count();
								$rank = $totalCorrectionCount - $lowerRanks;
							?>

							<tr>
								<td>{{ $user->name }} {{ $user->lname }} (@if ($user->group == 'learner')
									    محصل
									@elseif ($user->group == 'instructor')
									    استاد
									@elseif ($user->id == '1')
									    ادمین کل
									@endif)
								</td>
								<td>{{ $correction->automatic_total_correct }}</td>
								<td>{{ $correction->automatic_total_uncorrect }}</td>
								<td>{{ $correction->automatic_total_unanswered }}</td>
								<td>{{ $exam->total_score }}</td>
								<td>{{ $correction->automatic_score }}</td>
								<td>{{ $correction->manual_score }}</td>
								<td>{{ $correction->total_score }}</td>
								<td>{{ $average }}</td>
								<td>{{ $rank }} از <?= count($submissions) ?></td>
							</tr>

						@endforeach
					</tbody>
				</table>
			@else
				هیچ نتیجه‌ای یافت نشد.
			@endif

        </div>
		<hr style="margin: 25px 0 15px 0">
		<div class="uk-grid">
			<div class="uk-width-1-7">
				<a href="{{ URL::to('results') }}"><button type="button" class="md-btn md-btn-default" style="margin-right: 10px;">انصراف</button></a>
			</div>
		</div>
	<form>
@endsection