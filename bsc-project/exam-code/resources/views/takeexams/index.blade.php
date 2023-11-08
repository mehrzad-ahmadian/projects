@extends('layouts.app')

@section('breadcrumbs')
	<li><span>خانه</span></li>
	<li><span>آزمون‌های درحال برگذاری</span></li>
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
						<th class="uk-width-2-10">نام آزمون</th>
						<th class="uk-width-1-10">مهلت پاسخ دهی</th>
						<th class="uk-width-1-10 uk-text-center">دسته‌بندی</th>
						<th class="uk-width-1-10 uk-text-center">تعداد سوال‌ها</th>
						<th class="uk-width-1-10 uk-text-center"></th>
					</tr>
				</thead>
				<tbody>
					@foreach ($records as $record)
						<tr>
							<td>{{ $record->name }}</td>

							<td>{{ $record->duration }} دقیقه</td>

							<td class="uk-text-center">{{ isset($record->category) ? $record->category->name : '-' }}</td>

							<td class="uk-text-center">{{ $record->questions()->count() }}</td>

							<td class="uk-text-center">
								<a class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" href="{{ URL::to('takeexams') }}/generatesubmission/{{ $record->id }}">شروع آزمون</a>
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