@extends('layouts.app')
@section('content')

<style>
.filter-box {
    justify-content: space-between;
    gap: 15px;
    border: 1px solid #d6d9e9;
    border-radius: 12px;
    padding: 15px;
    grid-template-columns: repeat(5, minmax(0, 1fr));
		display: flex;
		width: 100%;
    
}
.filter-box input, .filter-box select {
    height: 42px;
		border-radius:12px;
}
.table thead {
    background: #f1f6fb;
}
.pdf-icon {
    color: red !important;
    font-size: 50px !important;
}
/* DOCUMENT ICONS */
.doc-icon {
    font-size: 26px;
    margin: 0 6px;
    display: inline-block;
    transition: transform 0.2s ease, opacity 0.2s ease;
}

.doc-icon:hover {
    transform: scale(1.2);
    opacity: 0.85;
}

.doc-icon.pdf {
    color: #d32f2f;
}

.doc-icon.excel {
    color: #2e7d32;
}
/* OUTER LIGHT BLUE BAR */
.pagination-container {
    background: #cfe7f5;
    padding: 18px;
    border-radius: 12px;
}

/* DARK BLUE STRIP */
.pagination-box {
    /* background: #0b4a6f; */
    padding: 12px 16px;
    border-radius: 8px;
    display: flex;
    justify-content: center;
}

/* RESET LARAVEL NAV */
.pagination-box nav {
    background: transparent !important;
    padding: 0 !important;
}

/* PAGINATION LIST */
.pagination {
    margin: 0;
    display: flex;
    align-items: center;
}

/* BUTTON STYLE */
.pagination .page-item .page-link {
    background-color: #ffffff;
    color: #0b4a6f !important;
    border-radius: 6px;
    margin: 0 6px;
    min-width: 44px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    font-weight: 600;
    transition: all 0.2s ease;
}

/* ACTIVE PAGE */
.pagination .page-item.active .page-link {
    background-color: #0b4a6f;
    color: #ffffff !important;
}

/* HOVER EFFECT */
.pagination .page-item .page-link:hover {
    background-color: #e3f2fd;
}

/* PREV/NEXT ICON SIZE */
.pagination .page-link svg,
.pagination .page-link i {
    width: 18px;
    height: 18px;
    font-size: 18px;
}

.w-full {
		width: 100%;
}
.card-box {
    background-color: #fff;
    box-shadow: 0 5px 25px #0000001a;
    border-radius: 16px;
    padding: 1em;
    margin-top: 2rem;

}
</style>

<div class="">

    <!-- FILTER BAR -->
    <form method="GET" action="{{ route('publication_front_page') }}">
    <div class="filter-box">

        <div class="col-md-3">
            <input type="text" name="search" class="form-control"
                   placeholder="Search"
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-2">
            @php
                $years = \App\Models\DigitalProjectLibrary::select('year')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year');
            @endphp

            <select name="year" class="form-control">
                <option value="">Select Year</option>
                @foreach($years as $yr)
                    <option value="{{ $yr }}"
                        {{ request('year') == $yr ? 'selected' : '' }}>
                        {{ $yr }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-5">
            @php $departments = App\Models\Department::get(); @endphp
            <select name="dept_id" class="form-control">
                <option value="">Select Department</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->dept_id }}"
                        {{ request('dept_id') == $dept->dept_id ? 'selected' : '' }}>
                        {{ $dept->dept_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100" style="background:#00446d">Search</button>
        </div>

    </div>
</form>
    <!-- TABLE -->
    <div class="card">
        <div class="card-body w-full">
            <div class="card-box">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
												<th width="120">Year</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($dept_list as $item)
                        <tr>
                            <td>
                                <i class="fa fa-file-text-o" style="font-size:20px;color:#0b4a6f;"></i>
                                {{ $item->study_name }}
                            </td>

                            <td>{{ $item->year ?? '-' }}</td>


                            <td class="text-center">
                            @if($item->upload_file)
                                <a href="{{ route('get_the_publication_document', 
                                    [Crypt::encrypt($item->rand_val), $item->upload_file]) }}"
                                target="_blank"
                                class="doc-icon pdf"
                                title="View PDF">
                                    <i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i>

                                </a>
                            @endif

   
                        </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <!-- PAGINATION -->
            @if($dept_list->hasPages())
                <div class="pagination-container mt-4">
                    <div class="pagination-box">
                        {{ $dept_list->onEachSide(1)->links() }}
                    </div>
                </div>
            @endif


</div>
        </div>
    </div>
</div>

@endsection
