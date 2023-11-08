@extends('layouts.app')

@section('breadcrumbs')
	<li><span>خانه</span></li>
	<li><span>آزمون‌ها</span></li>
@endsection

@section('content')
	<div class="uk-grid" style="margin-bottom: 25px;">
		<div class="uk-width-1-2">
			<a href="{{ URL::to('exams/new') }}" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" href="form.html">جدید</a>
		</div>

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
						<th class="uk-width-1-10 uk-text-center">کاربر</th>
						<th class="uk-width-2-10 uk-text-center">عملیات</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($records as $record)
						<tr>
							<td>{{ $record->name }}</td>

							<td class="uk-text-center">{{ isset($record->category) ? $record->category->name : '-' }}</td>

							<td class="uk-text-center">{{ $record->questions()->count() }}</td>

							<td class="uk-text-center">{{ isset($record->creator) ? $record->creator->name . ' ' . $record->creator->lname : '-' }}</td>

							<td class="uk-text-center">
								<a href="{{ URL::to('exams/edit') }}/{{ $record->id }}" class="md-btn md-btn-flat md-btn-wave waves-effect waves-button" style="padding: 0; min-width: 40px;"><i class="md-icon material-icons">&#xE254;</i></a>

								<a href="{{ URL::to('questions') }}/{{ $record->id }}" class="md-btn md-btn-flat md-btn-wave waves-effect waves-button" style="padding: 0; min-width: 40px; margin: 0;"><i class="md-icon material-icons">add_box</i></a>

								<a href="{{ URL::to('exams') }}/import/{{ $record->id }}" class="md-btn md-btn-flat md-btn-wave waves-effect waves-button" style="padding: 0; min-width: 40px; margin: 0;"><i class="md-icon material-icons">archive</i></a>

								<form action="{{ URL::to('exams') }}/{{ $record->id }}" method="POST" id="deleteForm{{ $record->id }}" style="display: inline;">
						            {{ csrf_field() }}
						            {{ method_field('DELETE') }}

						            <button type="button" class="md-btn md-btn-flat md-btn-wave waves-effect waves-button" style="padding: 0; min-width: 40px;" onclick="UIkit.modal.confirm('آیا از حذف این مورد اطمینان دارید؟', function(){ $('#deleteForm{{ $record->id }}').submit(); });"><i class="md-icon material-icons">delete</i></button>
						        </form>
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