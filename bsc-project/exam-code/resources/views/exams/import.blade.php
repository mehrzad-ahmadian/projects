@extends('layouts.app')

@section('breadcrumbs')
	<li><span>خانه</span></li>
	<li><span>آزمون‌ها</span></li>
@endsection

@section('content')
	<form method="post">
		{{ csrf_field() }}
		<h3 class="heading_a">افزودن سوال از بانک</h3>
		<hr style="margin: 20px 0 25px 0;">

        <div class="uk-overflow-container">
			@if (count($bankQuestions))
				<table class="uk-table uk-table-nowrap table_check">
					<thead>
						<tr>
							<th class="uk-width-7-10"> عنوان سوال</th>
							<th class="uk-width-3-10">درجه سختی</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($bankQuestions as $bankQuestion)
							<tr>
								<td>
									<input type="checkbox" name="bankquestionIds[]" value="{{ $bankQuestion->id }}">
									 {{ $bankQuestion->title }}
								</td>

								<td>{{ isset($bankQuestion->difficulty_level) ? $bankQuestion->difficulty_level->name : '-' }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				هیچ سوالی در بانک یافت نگردید.
			@endif
		</div>

		<div class="uk-grid" style="margin-top: 20px;">
			<div class="uk-width-1-7">
				<button type="submit" href="#" class="md-btn md-btn-success">افزودن</button>
				<a href="{{ URL::to('exams') }}"><button type="button" class="md-btn md-btn-default" style="margin-right: 10px;">انصراف</button></a>
			</div>
		</div>
	<form>




@endsection