@extends('adminlte::page')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Create Business Document</h3>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.csmlbusi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="client_name">Client Name</label>
                <input type="text" class="form-control" name="client_name" required>
            </div>
            <div class="form-group">
                <label for="business_type">Business Type</label>
                <select class="form-control" name="business_type" required>
                    <option value="tender">Tender</option>
                    <option value="quotation">Quotation</option>
                    <option value="prequalification">Prequalification</option>
                </select>
            </div>
            <div class="form-group">
                <label for="year">Year</label>
                <input type="number" class="form-control" name="year" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" class="form-control" name="amount" required>
            </div>
            <div class="form-group">
                <label for="expiry_date">Expiry Date</label>
                <input type="date" class="form-control" name="expiry_date" required>
            </div>
            
            <div class="form-group">
                <label for="bid_status">Bid Status</label>
                <select class="form-control" name="bid_status" required>
                    <option value="Progress">Progress</option>
                    <option value="won">Won</option>
                    <option value="lost">Lost</option>
                </select>
            </div>
            <div class="form-group">
        <label for="files">Upload Documents</label>
        <input type="file" name="files[]" class="form-control" multiple>
    </div>
            <button type="submit" class="btn btn-primary">Create Business Document</button>
        </form>
    </div>
</div>
@endsection
@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>
    <strong>
        Developed By: <a href="{{ config('app.company_url', 'mailto:sayahsamson@gmail.com') }}">
            {{ config('app.company_name', 'Samson Saya') }}
        </a>
    </strong>
@stop